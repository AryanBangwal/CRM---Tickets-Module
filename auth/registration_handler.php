<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php require_once '../utils/connection.php';
    
        $name = $_REQUEST["fname"];
        $email = $_REQUEST["nemail"];
        $pass = password_hash($_REQUEST["npass"], PASSWORD_DEFAULT); 
        if($name == "" || $email == "" || $pass == "")
        {
            echo "Cannot be empty";
            ?>
            <a href="registration.html">Try again</a>
            <?php
            die();
        }
        // $query =sprintf( "INSERT INTO users (`name`,`email`,`password`, `role`) VALUES ('%s', '%s', '%s', '%s')", $name,$email,$pass, 'author');
         $query ="INSERT INTO users (`name`,`email`,`password`) VALUES ('$name','$email','$pass')";
         
         $query2 = "SELECT * FROM users WHERE email = '$email'";
         $results = mysqli_query($conn, $query2);
         $rows = mysqli_num_rows($results);
                  
         if ($rows === 0) 
         {  
            if ($conn->query($query) === TRUE) 
            {
                echo "<script>window.location.href='../tickets/dashboard.php';</script>";
                exit();
            } 
            else 
            {
                echo "Error: " . $query . "<br>" . $conn->error;
            }
         }
        else
        {
            echo "Already present";
            ?>
            <a href="registration.html">Try again</a>
            <?php

        }
        // var_dump($_REQUEST);
        // die();
    ?>
</body>
</html>
