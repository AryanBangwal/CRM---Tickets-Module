<?php
session_start(); 
require_once '../utils/connection.php';

$email = $_REQUEST['nemail'];
$password = $_REQUEST['npass'];
$query = "SELECT id, email, password FROM users WHERE email = '$email';";
$results = mysqli_query($conn, $query);

if ($results->num_rows > 0) 
{
    $data = $results->fetch_assoc();

 
    if (password_verify($password, $data['password'])) 
    {
        $_SESSION['user_id'] = $data['id']; // Store user ID in session
        $_SESSION['user_email'] = $data['email']; 

        echo "<script>alert('Login successful'); window.location.href='dashboard.php';</script>";
        exit();
    } 
    else 
    {
        echo "<script>alert('Wrong credentials'); window.location.href='login.html';</script>";
        exit();
    }
} 
else 
{
    echo "<script>alert('User not found'); window.location.href='login.html';</script>";
    exit();
}
?>
 