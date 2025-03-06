<?php
session_start();
require_once '../utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: ../auth/login.html"); 
    exit();
}


$user_id = $_SESSION['user_id'];
$ticketid=$_GET['id'];
$ticket_query = "SELECT id, name, description FROM tickets WHERE id = $ticketid";
$ticket_result = mysqli_query($conn, $ticket_query);
$ticket = mysqli_fetch_assoc($ticket_result);

$user_query = "SELECT id, name,email FROM users WHERE id != $user_id";
$user_result = mysqli_query($conn, $user_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Ticket</title>
    <link rel="stylesheet" href="assignment.css">
    <link rel="stylesheet" href="../utils/navbar.css">
</head>
<body class="body">
<?php require_once '../utils/navbar2.html';
    ?>
    <div class="container">
        <h2 class="heading">Assign Ticket</h2>

        <form action="assignment_handler.php" method="POST" class="assign-form">

        <div class="form-group">
            <label class="form-label"><b>Ticket name: <?php echo htmlspecialchars($ticket['name']); ?></b></label>
            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Description:</label>
            <div class="ticket-description"><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></div>

        </div>

            <div class="form-group">
                <label for="assigned_to" class="form-label">Assign to:</label>
                <select name="assigned_to" id="assigned_to" class="form-select" required>
                    <option value="">-- Select User --</option>
                    <?php while ($user = mysqli_fetch_assoc($user_result)): ?>
                        <option value="<?= $user['id'] ; ?>">
                            <?= htmlspecialchars($user['name'] . ' - ' . $user['email']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="submit-btn">Assign</button>
        </form>
    </div>
</body>
</html>
