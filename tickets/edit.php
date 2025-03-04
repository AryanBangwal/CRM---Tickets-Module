<?php
session_start();
require_once '../utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid ticket ID'); window.location.href='view.php';</script>";
    exit();
}
    
$ticket_id = $_GET['id'];

// Fetch ticket details
$query = "SELECT * FROM tickets WHERE id = $ticket_id";
$ticket_result = mysqli_query($conn, $query);
$ticket = mysqli_fetch_assoc($ticket_result);

if (!$ticket) {
    echo "<script>alert('Ticket not found or access denied'); window.location.href='view.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $status = mysqli_real_escape_string($conn, $status);

    $update_query = "UPDATE tickets SET name = '$name', description = '$description', status = '$status', updated_at = NOW() WHERE id = '$ticket_id'";
    // var_dump($update_query);
    // die();
    if ($conn->query($update_query) === TRUE) 
    {
        echo "<script>alert('Ticket updated successfully'); window.location.href='view.php';</script>";
    } 
    else 
    {
        echo "<script>alert('Error updating ticket: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ticket</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body class="body">

    <div class="container">
        <h2 class="heading">Edit Ticket</h2>

        <form method="POST" class="ticket-form">
            <div class="form-group">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($ticket['name']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-textarea" required><?php echo htmlspecialchars($ticket['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Status:</label>
                <select name="status" class="form-select">
                    <option value="pending" <?php echo ($ticket['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="in_progress" <?php echo ($ticket['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="onhold" <?php echo ($ticket['status'] == 'onhold') ? 'selected' : ''; ?>>on hold</option>
                    <option value="completed" <?php echo ($ticket['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <button type="submit" class="submit-btn">Update Ticket</button>
        </form>

        <a href="view.php" class="back-btn">Back to Tickets</a>
    </div>

</body>
</html>