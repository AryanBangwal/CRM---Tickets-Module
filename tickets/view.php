<?php
session_start();
require_once '../utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: ../auth/login.html"); 
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM tickets WHERE created_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$assigned_query = "SELECT t.* FROM tickets t 
                   JOIN assignments a ON t.id = a.ticket_id 
                   WHERE a.assigned_to = ? 
                   ORDER BY t.created_at DESC";
$assigned_stmt = $conn->prepare($assigned_query);
$assigned_stmt->bind_param("i", $user_id);
$assigned_stmt->execute();
$assigned_result = $assigned_stmt->get_result();

$user_query = "SELECT id, name,email FROM users WHERE id != $user_id";
$user_result = mysqli_query($conn, $user_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <link rel="stylesheet" href="view.css">
    <link rel="stylesheet" href="../utils/navbar.css">
</head>
<body class="body">
    <?php require_once '../utils/navbar.html';
    ?>
    <div class="container">
        <h3 class="sub-heading">Tickets Created by Me</h3>
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Assignee</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($ticket = mysqli_fetch_assoc($result)) { 
                        $assignee_id = $ticket['assignee_id'];
                        $assignee_query = "SELECT name FROM users WHERE id = ?";
                        $assignee_stmt = $conn->prepare($assignee_query);
                        $assignee_stmt->bind_param("i", $assignee_id);
                        $assignee_stmt->execute();
                        $assignee_result = $assignee_stmt->get_result();
                        $assignee = $assignee_result->fetch_assoc();
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                            <td><?php echo strlen(htmlspecialchars($ticket['description'])) > 100 ? substr(htmlspecialchars($ticket['description']), 0, 100) . '...' : htmlspecialchars($ticket['description']); ?></td>
                            <td><?php echo $assignee ? htmlspecialchars($assignee['name']) : 'Unassigned'; ?></td>
                            <td><?php echo ucfirst($ticket['status']); ?></td>
                            <td><?php echo date("Y-m-d", strtotime($ticket['created_at'])); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $ticket['id']; ?>" class="edit-btn">Edit</a>
                                <a href="assignment.php?id=<?php echo $ticket['id']; ?>" class="assign-btn">Assign</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6" class="no-data">No tickets created yet</td>
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
            <?php if ($assigned_result->num_rows > 0) 
            { ?>
                <?php while ($ticket = mysqli_fetch_assoc($assigned_result)) 
                { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                            <td><?php echo strlen(htmlspecialchars($ticket['description']))>100 ?(substr(htmlspecialchars($ticket['description']),0, 100)).'...': htmlspecialchars($ticket['description']); ?></td>
                        <td><?php echo ucfirst($ticket['status']); ?></td>
                        <td><?php echo date("Y-m-d", strtotime($ticket['created_at'])); ?></td>
                        <td>
                            <a href="status.php?id=<?php echo $ticket['id']; ?>" class="status-btn">Update Status</a>
                        </td>
                    </tr>
                <?php 
                } ?>
            <?php 
            } 
            else 
            { ?>
                <tr>
                    <td colspan="5" class="no-data">No tickets assigned yet</td>
                </tr>
            <?php 
            } ?>
            </tbody>
        </table>

    </div>

</body>
</html>
