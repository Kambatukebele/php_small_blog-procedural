<?php
    require "functions.php"; 

    check_login(); 
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

    <div style="max-width: 600px;margin:auto">
        <?php
           
            $query = "SELECT * FROM posts ORDER BY id DESC";

            $result = mysqli_query($con, $query);
        
        ?>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <?php
                    $user_id = $row['user_id'];
                    $query = "SELECT username, image FROM users WHERE id = '$user_id' LIMIT 1";
                    $result2 = mysqli_query($con, $query);

                    $user_row = mysqli_fetch_assoc($result2); 
                ?>
                <div style="background-color:white;display:flex; border:solid thin #aaa; border-radius:10px; margin-bottom :10px;margin-top :10px;">
                    <div style="flex:1">
                        <img src="<?= $user_row['image']; ?>" style="border-radius:50%; margin: 10px;width:100px; height:100px; object-fit:cover;" alt="">
                        <br>
                        <?= $user_row['username']; ?>
                    </div>
                    <div style="flex:8">                                
                        <?php if(file_exists($row['image'])): ?>
                            <div style="">
                                <img src="<?= $row['image']; ?>" style="width:100%; height:200px; object-fit:cover;" alt="">
                            </div>
                        <?php endif; ?>
                        <div>
                            <?php echo $row['post']; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <?php
        include "footer.php"; 
    ?>
</body>
</html>