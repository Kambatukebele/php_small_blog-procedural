<?php
    require "functions.php"; 

    check_login(); 

    if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == "delete")
    {
        //delete your profile

        $id = $_SESSION['info']['$id'];
        $query = "DELETE FROM users WHERE id = '$id' LIMIT 1";

        $result = mysqli_query($con, $query);

        if(file_exists($_SESSION['info']['image']))
        {
            unlink($_SESSION['info']['image']);
        }

        $query = "DELETE FROM posts WHERE user_id = '$id'";
        $result = mysqli_query($con,$query);

        header("Location : logout.php");
        die;
    }

    elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username']))
    {
        //profile edit

        $image_added = false;
        if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0)
        {
            //file was uploaded

            $folder = "uploads/";
            if(!file_exists($folder))
            {
                mkdir($folder, 0777, true);
            }           

            $image = $folder . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $image);

            if(file_exists($_SESSION['info']['image']))
            {
                unlink($_SESSION['info']['image']);
            }

            $image_added = true;
        }
       $username = addslashes($_POST['username']);
       $email = addslashes($_POST['email']);
       $password = addslashes($_POST['password']); 
       $id = $_SESSION['info']['id']; 

       if($image_added == true)
       {
            $query = "UPDATE users SET username = '$username', email = '$email', password = '$password', image = '$image' WHERE id = '$id' LIMIT 1";

       }else{

            $query = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE id = '$id' LIMIT 1";
       }

       $result = mysqli_query($con, $query);

       $query = "SELECT * FROM users WHERE id = '$id' LIMIT 1";

       $result = mysqli_query($con, $query);

       if(mysqli_num_rows($result) > 0)
       {
            $row = mysqli_fetch_assoc($result);

            $_SESSION['info'] = $row;
       }

       header("Location: profile.php");
       die; 
     
    }



    elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['post']))
    {
        //post
        $image = "";
        if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0)
        {
            //file was uploaded

            $folder = "uploads/";
            if(!file_exists($folder))
            {
                mkdir($folder, 0777, true);
            }           

            $image = $folder . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $image);


            $image_added = true;
        }
        $post = addslashes($_POST['post']);
        $user_id = $_SESSION['info']['id'];
        $date = date('Y-m-d H:i:s');

        $query = "INSERT INTO posts (user_id, post, image, date) VALUES ('$user_id', '$post', '$image', '$date')";


        $result = mysqli_query($con, $query);

        header("Location: profile.php");
        die; 
     
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - My Website</title>
</head>
<body>

   <?php
        include "header.php"; 
   ?>
    <div style="margin: auto; max-width:600px; text-align:center">

        <?php if(!empty($_GET['action']) && $_GET['action'] == "edit"): ;?>

            <h2 style="text-align: center;">Edit profile</h2>
            <form action="" enctype="multipart/form-data" method="POST" style="margin: auto;padding: 10px;">
                <img src="<?php echo $_SESSION['info']['image'];?>" alt="" style="width: 100px;height: 100px;object-fit:cover; margin:auto; display:block; ">
                image: <input type="file" name="image">
                <input value="<?php echo $_SESSION['info']['username'];?>" type="text" name="username" placeholder="username" required>
                <input value="<?php echo $_SESSION['info']['email'];?>" type="email" name="email" placeholder="email" id="" required>
                <input value="<?php echo $_SESSION['info']['password'];?>" type="password" name="password" placeholder="password" id="" required>

                <button>Save</button>
                <a href="profile.php"><button type="button">Cancel</button></a>
            </form>
        <?php elseif(!empty($_GET['action']) && $_GET['action'] == "delete"): ;?>

            <h2 style="text-align: center;">Are you sure you want to delete your profile?</h2>
            <form action="" enctype="multipart/form-data" method="POST" style="margin: auto;padding: 10px;">
                <img src="<?php echo $_SESSION['info']['image'];?>" alt="" style="width: 100px;height: 100px;object-fit:cover; margin:auto; display:block; ">
                <div> <?php echo $_SESSION['info']['username'];?></div>
                <div> <?php echo $_SESSION['info']['email'];?></div>
                <input type="hidden" name="action" value="delete" id="">

                <button>Delete</button>
                <a href="profile.php"><button type="button">Cancel</button></a>
            </form>
        <?php else: ;?>
            <h2 style="text-align: center;">User Profile</h2>
            <br>
            <div style="margin:auto; max-width: 600px;">
                <div>
                    <td><img src="<?php echo $_SESSION['info']['image'];?>" alt="" style="width: 150px;height: 150px;object-fit:cover"></td>
                </div>
                <div>
                    <td><?php echo $_SESSION['info']['username']; ?></td>
                </div>
                <div>
                    <td><?php echo $_SESSION['info']['email']; ?></td>
                </div>
            </div>
            <a href="profile.php?action=edit"><button>Edit profile</button></a>
            <a href="profile.php?action=delete"><button>Delete profile</button></a>
            <br>
            <hr>
            <h5>Create a Post</h5>
            <form action="" method="POST" enctype="multipart/form-data" style="margin: auto;padding: 10px;">
                image: <input type="file" name="image" id=""><br>
                <textarea name="post" id="" cols="30" rows="8"></textarea><br>

                <button>Post</button>
            </form>

            <hr>

            <div>
                <?php
                    $id = $_SESSION['info']['id'];
                    $query = "SELECT * FROM posts WHERE user_id = '$id' ORDER BY id DESC";

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
        <?php endif;?>
    </div>
    <?php
        include "footer.php"; 
    ?>
</body>
</html>