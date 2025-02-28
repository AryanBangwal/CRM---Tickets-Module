<?php
session_start();
if (!isset($_SESSION['user_id'])) 
{
    header("Location: ../auth/login.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body class="body">

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Welcome, <?php echo $_SESSION['user_email'];  ?>!</h1> <!-- need to display name instead if email -->
        <p class="dashboard-subtext">Manage your tickets efficiently.</p>
    </div>

    <div class="dashboard-actions">
        <a href="create.php" class="dashboard-btn">Create a Ticket</a>
        <a href="view.php" class="dashboard-btn">View Tickets</a>
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>
</body>
</html>
