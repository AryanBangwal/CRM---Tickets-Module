<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
<?php
    require_once '../utils/connection.php';
    
    $email = $_REQUEST['nemail'];
    $password = $_REQUEST['npass'];
    $query = "SELECT password,email FROM users WHERE email = '$email';";
    $results = mysqli_query($conn, $query);
    if ($results->num_rows > 0) 
    {
        $data = $results->fetch_assoc();
    } 
    else
    {
        echo "0 results";
    }
        if ($data['email'] === $email && password_verify($password,$data['password'])) 
        {
            $user_exists = true;
            ?>
            <script>alert("Login successful")</script>
            <?php
        }
        else
        {
            echo "wrong credentials";
            die();
        }

?>    
</body>
</html>
