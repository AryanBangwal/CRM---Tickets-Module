<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = 'dashboard'; 
        }, 3000); 
    </script>
</head>
<body>
    <h2>🎉 Registration Successful! 🎉</h2>
    <p>Redirecting to your dashboard...</p>
</body>
</html>
