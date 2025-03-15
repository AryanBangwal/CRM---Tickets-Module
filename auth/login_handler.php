<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once ROOT.'/utils/connection.php';
$email = $_REQUEST['nemail'];
$password = $_REQUEST['npass'];

$query = "SELECT id, name, email, password FROM users WHERE email = '$email';";
$results = mysqli_query($conn, $query);

if ($results->num_rows > 0) 
{
    $data = $results->fetch_assoc();
    
    if (password_verify($password, $data['password'])) 
    {
        $_SESSION['user_id'] = $data['id']; 
        $_SESSION['user_email'] = $data['email'];
        $_SESSION['name'] = $data['name'];

        echo "<script>window.location.href='dashboard';</script>";
        exit();
    } 
    else 
    {
        echo "<script>alert('Wrong credentials'); window.location.href='login';</script>";
        exit();
    }
} 
else 
{
    echo "<script>alert('User not found'); window.location.href='login';</script>";
    exit();
}
?>
