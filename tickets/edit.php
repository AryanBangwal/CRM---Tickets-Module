<!-- Note* Code Not working properly-->
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

$ticket_id = intval($_GET['id']);

// Fetch ticket details
$query = "SELECT * FROM tickets WHERE id = ? AND created_by = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    echo "<script>alert('Ticket not found or access denied'); window.location.href='view.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    $update_query = "UPDATE tickets SET name = ?, description = ?, status = ?, updated_at = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $name, $description, $status, $ticket_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Ticket updated successfully'); window.location.href='view.php';</script>";
    } else {
        echo "<script>alert('Error updating ticket');</script>";
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
                    <option value="open" <?php echo ($ticket['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
                    <option value="in_progress" <?php echo ($ticket['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="closed" <?php echo ($ticket['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Update Ticket</button>
        </form>

        <a href="view.php" class="back-btn">Back to Tickets</a>
    </div>

</body>
</html>
