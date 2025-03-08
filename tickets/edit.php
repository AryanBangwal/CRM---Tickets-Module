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
    echo "<script>alert('Invalid ticket ID'); window.location.href='view.php';</script>";
    exit();
}
    
$ticket_id = $_GET['id'];

$query = "SELECT * FROM tickets WHERE id = ? AND created_by= ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
$stmt->close();

if (!$ticket) {
    $_SESSION['error'] = "You are not authorized to view this ticket.";
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $status = mysqli_real_escape_string($conn, $status);

    $update_query = "UPDATE tickets SET name = '$name', description = '$description', status = '$status', updated_at = NOW() WHERE id = '$ticket_id'";

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
    <link rel="stylesheet" href="../utils/navbar.css">
</head>
<body class="body">
<?php require_once '../utils/navbar2.html';
    ?>
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
                    <option value="inprogress" <?php echo ($ticket['status'] == 'inprogress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="onhold" <?php echo ($ticket['status'] == 'onhold') ? 'selected' : ''; ?>>on hold</option>
                    <option value="completed" <?php echo ($ticket['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <button type="submit" class="submit-btn">Update Ticket</button>
        </form>
    </div>

</body>
</html>