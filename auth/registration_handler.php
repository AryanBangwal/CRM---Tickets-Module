<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
require_once ROOT.'/utils/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST["fname"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["nemail"]));
    $password = trim($_POST["npass"]); 
    if (empty($name) || empty($email) || empty($password)) {
        echo "Cannot be empty";
        echo '<a href="registration.html">Try again</a>';
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query_check_both ="SELECT id FROM users WHERE name= '$name' AND email = '$email'";
    $result_both = mysqli_query($conn, $query_check_both);
    if (mysqli_num_rows($result_both) > 0) {
        ?><script>alert("Name and email already exists.");window.location.href='registration.html'</script>
        <?php
        exit();
    }

    $query_check_email = "SELECT id FROM users WHERE email = '$email'";
    $result_email = mysqli_query($conn, $query_check_email);

    if (mysqli_num_rows($result_email) > 0) {
        ?><script>alert("Email already exists.");window.location.href='registration.html'</script>
        <?php
        exit();
    }

    $query_check_name = "SELECT id FROM users WHERE name = '$name'";
    $result_name = mysqli_query($conn, $query_check_name);

    if (mysqli_num_rows($result_name) > 0) {
       
        ?><script>alert("Name already exists.");window.location.href='registration.html'</script>
        <?php
        exit();
    }


    $query_insert = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
    if (mysqli_query($conn, $query_insert)) {

        $user_id = mysqli_insert_id($conn); 
    
        session_start();

        $_SESSION['user_id'] = $user_id; 
        $_SESSION['user_email'] = $email;
        $_SESSION['name'] = $name;
    
        header("Location: ./success.php");
        exit();
    }
    else {
        echo "Error: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>


</body>
</html>
