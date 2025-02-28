<?php
session_start();
require_once '../utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch tickets where the user is the creator
$query = "SELECT * FROM tickets WHERE created_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch assigned tickets
$assigned_query = "SELECT t.* FROM tickets t 
                   JOIN assignments a ON t.id = a.ticket_id 
                   WHERE a.assigned_to = ? 
                   ORDER BY t.created_at DESC";
$assigned_stmt = $conn->prepare($assigned_query);
$assigned_stmt->bind_param("i", $user_id);
$assigned_stmt->execute();
$assigned_result = $assigned_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <link rel="stylesheet" href="view.css">
</head>
<body class="body">

    <div class="container">
        <h2 class="heading">My Tickets</h2>

        <a href="create.php" class="create-ticket-btn">Create New Ticket</a>

        <h3 class="sub-heading">Tickets Created by Me</h3>
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ticket = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['description']); ?></td>
                        <td><?php echo ucfirst($ticket['status']); ?></td>
                        <td><?php echo date("Y-m-d", strtotime($ticket['created_at'])); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $ticket['id']; ?>" class="edit-btn">Edit</a>
                            <a href="assignment.php?id=<?php echo $ticket['id']; ?>" class="assign-btn">Assign</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 class="sub-heading">Tickets Assigned to Me</h3>
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ticket = mysqli_fetch_assoc($assigned_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['description']); ?></td>
                        <td><?php echo ucfirst($ticket['status']); ?></td>
                        <td><?php echo date("Y-m-d", strtotime($ticket['created_at'])); ?></td>
                        <td>
                            <a href="status.php?id=<?php echo $ticket['id']; ?>" class="status-btn">Update Status</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

</body>
</html>
