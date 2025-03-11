<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once ROOT.'/utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header(sprintf("Location: %s/auth/login.html",ROOT)); 
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$user_name = $user ? htmlspecialchars($user['name']) : "User"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./tickets/dashboard.css">
</head>
<body class="body">

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Welcome, <?php echo $user_name; ?>!</h1>
        <p class="dashboard-subtext">Manage your tickets efficiently.</p>
    </div>

    <div class="dashboard-actions">
        <a href="create" class="dashboard-btn">Create a Ticket</a>
        <a href="view" class="dashboard-btn">View Tickets</a>
    </div>

    <a id="logout" href="logout" class="logout-btn">Logout</a>
    <script>
    document.getElementById("logout").addEventListener("click", function(event) {
        if (!confirm("Are you sure you want to log out?")) {
            event.preventDefault(); 
        }
    });
    </script>
</div>
</body>
</html>
