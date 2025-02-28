<?php
session_start();
require_once '../utils/connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='../auth/login.html';</script>";
    exit();
}

// Fetch tickets created by the logged-in user
$user_id = $_SESSION['user_id'];
$ticket_query = "SELECT id, name FROM tickets WHERE created_by = $user_id";
$ticket_result = mysqli_query($conn, $ticket_query);

// Fetch all users to assign tickets
$user_query = "SELECT id, name FROM users WHERE id != $user_id";
$user_result = mysqli_query($conn, $user_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Ticket</title>
    <link rel="stylesheet" href="assignment.css">
</head>
<body class="body">
    <div class="container">
        <h2 class="heading">Assign Ticket</h2>

        <form action="assignment_handler.php" method="POST" class="assign-form">
            <div class="form-group">
                <label for="ticket_id" class="form-label">Select Ticket:</label>
                <select name="ticket_id" id="ticket_id" class="form-select" required>
                    <option value="">-- Select Ticket --</option>
                    <?php while ($ticket = mysqli_fetch_assoc($ticket_result)): ?>
                        <option value="<?= $ticket['id']; ?>"><?= htmlspecialchars($ticket['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="assigned_to" class="form-label">Assign to:</label>
                <select name="assigned_to" id="assigned_to" class="form-select" required>
                    <option value="">-- Select User --</option>
                    <?php while ($user = mysqli_fetch_assoc($user_result)): ?>
                        <option value="<?= $user['id']; ?>"><?= htmlspecialchars($user['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="submit-btn">Assign</button>
        </form>
    </div>
</body>
</html>
