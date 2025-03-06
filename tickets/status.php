<?php
session_start();
require_once '../utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: ../auth/login.html"); 
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "<div class='error-message'>Invalid request.</div>";
    exit();
}

$ticket_id = $_GET['id'];


$query = "SELECT * FROM tickets t JOIN assignments a ON t.id = a.ticket_id WHERE t.id = ? AND a.assigned_to = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='error-message'>You are not authorized to update this ticket.</div>";
    exit();
}

$ticket = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];
    $update_query = "UPDATE tickets SET status = '$new_status' WHERE id = '$ticket_id'";
   
    $update_result = mysqli_query($conn, $update_query);
    
    if ($update_result) {
        $fetch_query = "SELECT * FROM tickets WHERE id = $ticket_id";
        $fetch_result = mysqli_query($conn, $fetch_query);
        $ticket = mysqli_fetch_assoc($fetch_result);

        header("Location: view.php");
        exit();
    } else {
        echo "<div class='error-message'>Error updating status.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ticket Status</title>
    <link rel="stylesheet" href="status.css">
    <link rel="stylesheet" href="../utils/navbar.css">
</head>
<body class="body-container">
    
<?php require_once '../utils/navbar2.html';?>
    <div class="form-container">
        <h2 class="form-heading">Update Ticket Status</h2>
        <p class="ticket-info"><strong>Ticket:</strong> <?php echo htmlspecialchars($ticket['name']); ?></p>
        <p class="ticket-info"><strong>Description:</strong> <?php echo htmlspecialchars($ticket['description']); ?></p>
        <form method="POST" class="status-form">
        <label for="status" class="form-label">
            Status: <?php echo $ticket['status'] ?? ''; ?>
        </label>

            <select name="status" id="status" class="form-select" required>
                <option value="pending" <?php echo ($ticket['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="inprogress" <?php echo ($ticket['status'] == 'inprogress') ? 'selected' : ''; ?>>In progress</option>
                <option value="onhold" <?php echo ($ticket['status'] == 'onhold') ? 'selected' : ''; ?>>On hold</option>
                <option value="completed" <?php echo ($ticket['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
            </select>
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
</body>
</html>