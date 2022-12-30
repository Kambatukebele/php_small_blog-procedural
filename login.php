<?php
    require "functions.php";

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
      
       $email = addslashes($_POST['email']);
       $password = addslashes($_POST['password']); 
      
       $query = "SELECT * FROM users WHERE email = '$email' && password = '$password' LIMIT 1";

       $result = mysqli_query($con, $query);

       if(mysqli_num_rows($result) > 0)
       {
            $row = mysqli_fetch_assoc($result);
           
            $_SESSION['info'] = $row;
            header("Location: profile.php");
            die; 

       }else{

            $error = "wrong email or password"; 
       }

       echo "<pre>";
       print_r($result);
       echo "</pre>";
    
    
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My Website</title>
</head>
<body>

   <?php
        include "header.php"; 
   ?>
    <div style="margin: auto; max-width:600px;">
    <?php
        if(!empty($error))
        {
            echo $error;
        }
    ?>
    <h2 style="text-align: center;">Login</h2>
        <form action="" method="POST" style="margin: auto;padding: 10px;">
            <input type="email" name="email" placeholder="email" id="" required>
            <input type="password" name="password" placeholder="password" id="" required>

            <button>Login</button>
        </form>
    </div>
    <?php
        include "footer.php"; 
    ?>
</body>
</html>