<?php
    require "functions.php";

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
       $username = addslashes($_POST['username']);
       $email = addslashes($_POST['email']);
       $password = addslashes($_POST['password']); 
       $date = date('Y-m-d H:i:s'); 

       $query = "INSERT INTO users (username, email, password, date) VALUES ('$username', '$email', '$password', '$date')";

       $result = mysqli_query($con, $query);
    
       header("Location: login.php");
       die; 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
</head>
<body>

   <?php
        include "header.php"; 
   ?>
    <div style="margin: auto; max-width:600px;">
    <h2 style="text-align: center;">Signup</h2>
        <form action="" method="POST" style="margin: auto;padding: 10px;">
            <input type="text" name="username" placeholder="username" required>
            <input type="email" name="email" placeholder="email" id="" required>
            <input type="password" name="password" placeholder="password" id="" required>

            <button>Signup</button>
        </form>
    </div>
    <?php
        include "footer.php"; 
    ?>
</body>
</html>