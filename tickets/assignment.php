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

$ticket_id = $_GET['id'];

$ticket_query = "SELECT id, name, description FROM tickets WHERE id = ? AND created_by = ?";
$stmt = $conn->prepare($ticket_query);
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='error-message'>You are not authorized to assign this ticket.</div>";
    exit();
}

$ticket = $result->fetch_assoc();
$stmt->close();

$user_query = "SELECT id, name, email FROM users WHERE id != ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Ticket</title>
    <link rel="stylesheet" href="./tickets/assignment.css">
    <link rel="stylesheet" href="../utils/navbar.css">
</head>
<body class="body">
<?php require_once ROOT.'/utils/navbar2.html'; ?>
    
<div class="container">
    <h2 class="heading">Assign Ticket</h2>

    <form action="assignment_handler" method="POST" class="assign-form">
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
                <?php while ($user = $user_result->fetch_assoc()): ?>
                    <option value="<?= $user['id']; ?>">
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
