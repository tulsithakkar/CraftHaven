<!-- Tulsi Thakkar-->
<?php include('contect.php'); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisans Register</title>
    <!-- Font Awesome CDN link -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Custom style -->
    <link href="css/ArtisansStyle.css" rel="stylesheet">
</head>
<body>
    <!-- Header section starts -->
    <header class="header">
    <div class="logo">
        <h1><i class="fas fa-hand-sparkles"></i>CraftHaven</h1>
        </div>
        <div class="icons">
            <div><a href="index.php"><i class="fas fa-home"></i></a> </div>
        </div>
    </header> 


    <section class="home">
    <form action="" class="register-form"  method="post" enctype="multipart/form-data">
            <h3>Register yourself as Artisan</h3>

            <label for="ArtisanName" >Artisan UserName</label>
            <input type="text" id="ArtisanName" placeholder="your Name" class="box"
             required="required" name="Artisan_Name">
            
            <label for="ArtisanBio" >Artisan Bio</label>
            <input type="text" id="ArtisanBio" placeholder="your Bio" 
            class="box" required="required" name="Artisan_Bio">
            
            <label for="Artisanemail" >Artisan Email</label>
            <input type="email" id="Artisanemail" placeholder="your Email" 
            class="box" required="required" Name="Artisan_email">
            
            <label for="ArtisanPassword" >password</label>
            <input type="password" id="ArtisanPassword" placeholder="your password" 
            class="box" required="required" name="Artisan_Password">
            <i id="togglepassword" class="fa fa-eye password"></i>
            
            <label for="ArtisanConformPassword" >conform password</label>
            <input type="password" id="ArtisanConformPassword" 
            placeholder="conform password" class="box" required="required" name="Artisan_conformPassword">
            <i id="togglepassword1" class="fa fa-eye password-i"></i>

            <label for="ArtisanProfile" >Select Your Profile Picture/Logo:</label>
            <input type="file" id="ArtisanProfile" placeholder="Your Profile Picture/Logo" 
            class="box" required="required" name="Artisan_profile">
            
            
            <p>already have an account<a href="http://localhost/CraftHaven_Project/Artisanlogin.php"> Login now</a></p>
            <input type="submit" class="btn" 
            value="Register now" name="Artisan_Register">
        
        </form>  
    </section>

   
<?php include 'footer.php'; ?>
    <!-- Register Artisan-->
   

    <!-- Custom JavaScript file -->
    <script src="js/ArtisansScript.js"></script>
</body>
</html>

<!-- Register Arstisan form code -->
<?php 
if(isset($_POST['Artisan_Register']))
{
    //image remain

    $Artisan_Name = $_POST['Artisan_Name'];
    $Artisan_Bio = $_POST['Artisan_Bio'];
    $Artisan_email = $_POST['Artisan_email'];
    $Artisan_Password = $_POST['Artisan_Password'];
    $hash_password = password_hash($Artisan_Password,PASSWORD_DEFAULT);
    $Artisan_conformPassword = $_POST['Artisan_conformPassword'];
    $Artisan_profile = $_FILES['Artisan_profile']['name'];
    $Artisan_profile_temp = $_FILES['Artisan_profile']['tmp_name'];

    //select query
    $select_query = "select * from `artisans1` where Name = '$Artisan_Name' or ContactInfo = '$Artisan_email' ";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);

    if($rows_count > 0)
    {
        echo "<script>alert('Username or Email already exist')</script>";
    }
    else  if($Artisan_Password != $Artisan_conformPassword )
    {
        echo "<script>alert('password and conform password doesnot match')</script>"; 
    }
    else 
    {
         //insert query
        move_uploaded_file($Artisan_profile_temp,"./user_images/$Artisan_profile" );
        $insert_query = "insert into `artisans1` (Name,Bio,ContactInfo,ProfilePicture,Artisan_Password) 
        values('$Artisan_Name','$Artisan_Bio','$Artisan_email','$Artisan_profile','$hash_password')";
        $sql_execute= mysqli_query($con,$insert_query); 
        if($sql_execute)
        {
            echo "<script>window.open('Artisanlogin.php','_self')</script>";
        }
        else
        {
                die(mysqli_error($con));
        }
    }
}
?>

