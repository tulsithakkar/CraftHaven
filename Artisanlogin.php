<!-- Tulsi Thakkar-->
<?php include('contect.php'); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Artisans login</title>
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
    <form action="" class="register-form"  method="post">
            <h3> Artisan Login</h3>
            <label for="ArtisanName" >Artisan UserName</label>
            <input type="text" id="ArtisanName" placeholder="your Name" 
            class="box" required="required" name="Artisan_LName">
            <label for="ArtisanPassword" >password</label>
            <input type="password" id="ArtisanPassword" 
            placeholder="your password" class="box" required="required" name="Artisan_LPassword">
            <i id="togglepassword" class="fa fa-eye password-icon"></i>
            <p>Don't have an account<a href="http://localhost/CraftHaven_Project/ArtisanRegister.php"> Register now</a></p>
            <input type="submit" class="btn" value="Login now" name="Artisan_Login">
        
        </form>  
    </section>
 
<?php include 'footer.php'; ?>

    <!-- Register Artisan-->

    <!-- Custom JavaScript file -->
    <script src="js/ArtisansScript.js"></script>
</body>
</html>

<!-- login php code -->

<?php 
// Start the session
session_start();

if(isset($_POST['Artisan_Login'])) {
    $Artisan_LName = $_POST['Artisan_LName'];
    $Artisan_LPassword = $_POST['Artisan_LPassword'];

    // Prepare the SQL statement
    $stmt = $con->prepare("SELECT * FROM `artisans1` WHERE Name = ?");
    $stmt->bind_param("s", $Artisan_LName);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows_count = $result->num_rows;

    if($rows_count > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['Artisan_Password'];

        // Verify the password
        if (password_verify($Artisan_LPassword, $hashed_password)) {
            // Store the username in the session
            $_SESSION['Artisan_LName'] = $Artisan_LName;
            echo "<script>window.open('ArtisanProfile.php','_self')</script>";
        } else {
            echo "<script>alert('Invalid Username or Password')</script>";
        }
    } else {
        echo "<script>alert('Invalid Username or Password')</script>";
    }

    // Close the statement
    $stmt->close();
}
?>












