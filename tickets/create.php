<?php
session_start();
require_once '../utils/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: ../auth/login.html"); 
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $created_by = $_SESSION['user_id'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $query = "INSERT INTO tickets (name, description, status, created_by, created_at, updated_at, completed_at, deleted_at)
              VALUES ('$name', '$description', '$status', '$created_by', '$created_at', '$updated_at', NULL, NULL)";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Ticket Created Successfully!'); window.location.href='view.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="stylesheet" href="tickets.css">
    <link rel="stylesheet" href="../utils/navbar.css">
</head>
<body class="body">
<?php require_once '../utils/navbar2.html';?>
    <div class="container">
        <h2 class="heading">Create New Ticket</h2>
        <form action="" method="post" class="ticket-form">
            <div class="form-group">
                <label for="name" class="form-label">Ticket Name:</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" name="description" class="form-textarea" required></textarea>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="pending">Pending</option>
                    <option value="inprogress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="onhold">On Hold</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Create Ticket</button>
        </form>
    </div>
</body>
</html>
