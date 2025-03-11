<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once ROOT.'/utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: login");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='error-message'>Invalid request.</div>";
    exit();
}

$ticket_id = intval($_GET['id']); 

$query = "SELECT t.* FROM tickets t 
          LEFT JOIN assignments a ON t.id = a.ticket_id 
          WHERE t.id = ? AND (t.created_by = ? OR a.assigned_to = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $ticket_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='error-message'>You are not authorized to update this ticket.</div>";
    exit();
}

$ticket = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = trim($_POST['status']);
    
    $allowed_statuses = ['pending', 'inprogress', 'onhold', 'completed'];
    if (!in_array($new_status, $allowed_statuses)) {
        echo "<div class='error-message'>Invalid status selection.</div>";
        exit();
    }

    $update_query = "UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_status, $ticket_id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows >= 0) { 
        header("Location: view");
        exit();
    } else {
        echo "<div class='error-message'>Error updating status.</div>";
    }
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ticket Status</title>
    <link rel="stylesheet" href="./tickets/status.css">
    <link rel="stylesheet" href="../utils/navbar.css">
</head>
<body class="body-container">

<?php require_once ROOT.'/utils/navbar2.html'; ?>
    <div class="form-container">
        <h2 class="form-heading">Update Ticket Status</h2>
        <p class="ticket-info"><strong>Ticket:</strong> <?php echo htmlspecialchars($ticket['name']); ?></p>
        <p class="ticket-info"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
        <form method="POST" class="status-form">
            <label for="status" class="form-label">Current Status: <?php echo htmlspecialchars($ticket['status']); ?></label>
            <select name="status" id="status" class="form-select" required>
                <option value="pending" <?php echo ($ticket['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="inprogress" <?php echo ($ticket['status'] == 'inprogress') ? 'selected' : ''; ?>>In Progress</option>
                <option value="onhold" <?php echo ($ticket['status'] == 'onhold') ? 'selected' : ''; ?>>On Hold</option>
                <option value="completed" <?php echo ($ticket['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
            </select>
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
</body>
</html>
