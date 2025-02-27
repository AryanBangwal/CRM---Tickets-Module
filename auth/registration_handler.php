<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php require_once '../utils/connection.php';
    
        $name=$_REQUEST["fname"];
        $email=$_REQUEST["nemail"];
        $pass=$_REQUEST["npass"];
      
        // $query =sprintf( "INSERT INTO users (`name`,`email`,`password`, `role`) VALUES ('%s', '%s', '%s', '%s')", $name,$email,$pass, 'author');
         $query ="INSERT INTO users (`name`,`email`,`password`) VALUES ( '$name','$email','$pass')";
         
         $query2 = "SELECT * FROM users WHERE email = '$email'";
         $results = mysqli_query($conn, $query2);
         $rows = mysqli_num_rows($results);
                  
         if ($rows === 0) 
         {  // Meaning record does not exist with “some email”
            // Insert query
            if ($conn->query($query) === TRUE) 
            {
                echo "New record created successfully";
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
