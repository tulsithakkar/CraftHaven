<!-- Tulsi Thakkar-->
<?php
session_start();
$con = mysqli_connect('localhost','root','','crafthaven_project');
if($con){
    if (isset($_GET['searchname'])) {
        $searchname = mysqli_real_escape_string($con, $_GET['searchname']);   
        $query = "SELECT p.*, a.Name
        FROM `products1` p
        JOIN `artisans1` a ON p.ArtisanID = a.ArtisanID
        WHERE Title LIKE '%$searchname%' or Category LIKE '%$searchname%' ";
        $result = mysqli_query($con, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $ArtisanID = $row["ArtisanID"];
            $Artisan_Name = $row["Name"];
            $Images = $row["Images"];
            $Title =  $row["Title"];
            $Description =  $row["Description"] ;
            $Price =  $row["Price"];
            if($row["Availability"]==1)
            {
                $Availability = "yes"; 
            }
            else
            {
                $Availability = "No"; 
            }
            $Category = $row["Category"];
        } else {
            echo "<script>alert('No product or category find such as : $searchname'); </script>";
            $product_id = 1;
            $query = "SELECT p.*, a.Name
              FROM `products1` p
              JOIN `artisans1` a ON p.ArtisanID = a.ArtisanID
              WHERE p.ProductID = $product_id";
    
    
            $result = mysqli_query($con, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                $ProductID= $row["ProductID"];
                $ArtisanID = $row["ArtisanID"];
                $Artisan_Name = $row["Name"];
                $Images = $row["Images"];
                $Title =  $row["Title"];
                $Description =  $row["Description"] ;
                $Price =  $row["Price"];
                if($row["Availability"]==1)
                {
                    $Availability = "yes"; 
                }
                else
                {
                    $Availability = "No"; 
                }
                $Category = $row["Category"];
            }            
        }
    } elseif (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $query = "SELECT p.*, a.Name
          FROM `products1` p
          JOIN `artisans1` a ON p.ArtisanID = a.ArtisanID
          WHERE p.ProductID = $product_id";


        $result = mysqli_query($con, $query);
        if ($row = mysqli_fetch_assoc($result)) {

            $ProductID= $row["ProductID"];
            $ArtisanID = $row["ArtisanID"];
            $Artisan_Name = $row["Name"];
            $Images = $row["Images"];
            $Title =  $row["Title"];
            $Description =  $row["Description"] ;
            $Price =  $row["Price"];
            if($row["Availability"]==1)
            {
                $Availability = "yes"; 
            }
            else
            {
                $Availability = "No"; 
            }
            $Category = $row["Category"];
        } else {
            echo "Product not found.";
            exit;
        }


    } else {
        echo "invalid product ID.";
        exit();
    }

    //update product form the cart
    if(isset($_POST['update_cartproduct']))
    {
        $cartid = $_POST['cartdetailid']; 
        $Quantity_cart= $_POST['Cartquantity']; 

        $stmt = $con->prepare("UPDATE `cartdetails` SET `Quantity`=? WHERE `CartDetailID`=?");
        $stmt->bind_param("ii", $Quantity_cart, $cartid);
        if ($stmt->execute()) {
            echo "<script>alert('Product quantity updated to the cart successfully');</script>";
        } else {
            echo "<script>alert('Failed to delete product: " . $stmt->error . "');</script>";
        }   
    }

    //delete product form the cart
    if(isset($_POST['Delete_cartProduct']))
    {
        if ($_POST['confirm'] !== 1) {
         
            $cartid = $_POST['cartdetailid'];
            $stmt = $con->prepare("DELETE FROM `cartdetails` WHERE `CartDetailID`=?");
            $stmt->bind_param("i", $cartid);
            if ($stmt->execute()) {
                echo "<script>alert('Product deleted from the cart successfully');</script>";
            } else {
                echo "<script>alert('Failed to delete product: " . $stmt->error . "');</script>";
            }      
          } 

    }



}
else
{
    die(mysqli_error($con));
}

if(isset($_POST['Logout']))
{
  if ($_POST['confirm'] !== 1) {
    $_SESSION['username'] = '';
    $_SESSION['userlogin'] = false;
    $_SESSION['UserID'] = '';
    $_SESSION['email'] = '';
    session_unset();
    // Destroy the session if needed
    session_destroy();
  
    // Redirect to login page
    echo "<script>window.open('index.php','_self')</script>";
  }

 
}

?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <!--font awesome cdn link-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- custom style-->
    <link href="css\ProductDetails.css" rel="stylesheet">

    <!-- swiper link -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
</head>
<body>

<!-- header section starts-->
<header class="header">
<div class="logo">
        <h1><i class="fas fa-hand-sparkles"></i>CraftHaven</h1>
        </div>
    <div class="icons">
        <div class="fas fa-search" id="search-btn"></div>  
        <div class="fas fa-home" id="home-btn" name="homepage"></div> 
        <div class="fas fa-shopping-cart" id="cart-btn"></div>  
        <div class="fas fa-user" id="Profile-btn"></div> 
    </div>
    <form action="" class="search-form">
        <input type="search" id="search-box" name="searchname" 
        placeholder="search product Name or category here..">
        <label for="search-box" class="fas fa-search"></label>        
    </form>
    <div class="shopping-cart">
    <?php
    $con = mysqli_connect('localhost','root','','crafthaven_project');
    if($con){
       if (isset($_SESSION['UserID'])) 
       {   
           $userID = $_SESSION['UserID'];
            // $select_query = "select * from `cartdetails`";
          $query = "SELECT c.*, p.Images,p.Title
           FROM `products1` p
           JOIN `cartdetails` c ON p.ProductID = c.ProductID
           WHERE c.UserID = $userID";
           $result = mysqli_query($con, $query );
           $num_rows = mysqli_num_rows($result);
           $totalPrice = 0;
            if($num_rows > 0)
            {
                
                while($row = mysqli_fetch_assoc($result))
                {
                    $CartID = $row['CartDetailID'];
                    
                    $Quantity_cart = $row['Quantity'];
                    $Price_cart =$row['Price'];
                    $Title_cart = $row['Title'];
                    $Images_cart = $row['Images'];
                    $totalPrice += $Quantity_cart * $Price_cart;
                   echo"
                  
                   <div class='box'>
                   <img src='user_images/$Images_cart' alt='' />
                    <div class='content'>
                       <h3> $Title_cart</h3>
                       <span class='price'>Price: $Price_cart</span><br>
                       <form action='' method='post' >
                       <div class='quantity-container'>
                                <span class='quantity'>qty:</span>
                                <input type='number' name='Cartquantity' 
                                value='$Quantity_cart'>
                                <input type='hidden' name='cartdetailid' 
                                value='$CartID'>
                            </div>
                        <div class='btn-groups' >
                        <input type='submit' class='btn' name='update_cartproduct'
                         value='update'>
                         <input type='submit' name='Delete_cartProduct' value='Delete' class='btn'>
                     </form>
                        </div>
                        </form>
                     
                   </div>   

             
                    </div>
                   ";
                }


            }
            else
            {
                //echo "<script>alert('Your cart is Empty')</script>";
                echo"Your cart is Empty";
            }
        
       }
     

}	
else
{
        die(mysqli_error($con));
}
?>   
    <div class="total">total: <?php echo $totalPrice; ?>/-</div>
    <div>
<?php 
    if($totalPrice!=0)
    {
     echo" <a href='checkOut.php' class='btn'>CheckOut</a>";  
    }
    else
    {
        echo" <a href='#' class='btn'>CheckOut</a>"; 
    }

?>

    </div>

</div>




    <form action="" class="login-form1" method="post" >
        <h3>Register now</h3>
        <input type="text" placeholder="your username" class="box" name="username" required="required">
        <input type="email" placeholder="your email" class="box" name="regemail" required="required">
        <input type="password" placeholder="your password" class="box" name="regpass" required="required">
        <p>forgot your password <a href="#">click here</a></p>
        <p>already have an account <a href="#" id="loginlink">login now</a></p>
            <input type="submit" class="btn" value="Register now" name="userReg">
        </form>
        <form action=""  class="login-form" method="post">
            <h3>Login now</h3>
            <input type="text" placeholder="your email" class="box" name="username" required="required">
            <input type="password" placeholder="your password" class="box" name="userpassword" required="required">
            <p>forgot your password <a href="#">click here</a></p>
            <p>don't have an account <a href="#" id="Reglink">create new</a></p>
            <input type="submit" class="btn" value="login now" name="userlogin">
        </form>  
    <div class="Profile">

        <h3><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></h3>
        <p><i class='fas fa-envelope'></i>
            <?php echo isset( $_SESSION['email']) ?  $_SESSION['email'] : 'abc@gamil.com'; ?>
        </p>

        <form action="" method="post">
            <input type="submit" class="btn"
                    onClick="return confirm('Are you sure you want to logout?')"
                    value="Logout" name="Logout">
        </form>
    </div>




</header>

<section class="home">
    <section class="information">
    <h1 class="heading">Product <span> Details</span></h1>
    <table class="styled-table">
        <tbody>
            <tr>
                <td>
                    <div class="profiles">
                         <img src='user_images/<?php echo $Images ; ?>' alt="Product Image">
                    </div>
                </td>
                <td>
                    <div class="product_details">
                        <h3><?php echo $Title ; ?></h3>
                        <p><?php echo $Description ; ?></p>
                        <p><strong>Artisan Name:</strong> <?php echo $Artisan_Name ; ?></p>
                        <p><strong>Category:</strong> <?php echo $Category ; ?></p>
                        <p><strong>Price:</strong> <?php echo $Price ; ?></p>
                        <p><strong>Availability:</strong> <?php echo $Availability ; ?></p>

                        <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    	</div>

                        <p>Share using..</p>
                        <diV class="share">
                            <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>
                            <a href="#" class="fab fa-instagram"></a>
                            <a href="#" class="fab fa-whatsapp"></a>
                        </diV>
                        <br>
                        <form action="" method="post" >
                        <input type="hidden" id="price" name="Price" class="QTY" value="<?php echo $Price ; ?>"
                        placeholder="Enter price" required="required">
                        <input type="number" id="quantity" name="quantity" class="QTY"
                        placeholder="Enter Quantity" required="required">
                        <br>
                        <div class="btn-groups" >
                        <input type="submit" class="btn" name="Addtocart" value="Add to Cart">
                        <input type="submit" class="btn" name="BuyNow" value="Buy Now">
                        </div>
                    </form>
                    
                </td>
            </tr>

        </tbody>

    </table>
   
    </section>
</section>

<section class="crafts" id="products">

            <h1 class="heading">More <span>Products</span></h1>
            <div class="swiper crafts-slider">
                <div class="swiper-wrapper">

                <?php
			$con = mysqli_connect('localhost','root','','crafthaven_project');
			if($con){
     				$select_query = "select * from `products1`";
                 		$result = mysqli_query($con, $select_query);
                 		//$row = mysqli_fetch_assoc($result)
                 		while( $row = mysqli_fetch_assoc($result))
                 		{
                                $ProductID1 = $row["ProductID"];
                    			$Images = $row["Images"];
                     			$Title =  $row["Title"];
                     			$Description =  $row["Description"] ;
                     			$Price =  $row["Price"];
                                echo"
                                <diV class='swiper-slide box'>
                                <img src='user_images/$Images' alt='image'><br><br>
                                <h3>$Title</h3>
                                <br>
                                <p>$Description </p><br>
                                <p class='price'>Price : $Price rs</p><br>
                                <a href='ProductDetails.php?id=$ProductID1' class='btn'>Shop Now</a>
                            </diV>";
                		 }
			}	
			else
			{
    				die(mysqli_error($con));
    				
			}
		?>   
           </div>
            </div>
        </section>



<!-- Customer reviews starts-->
<section class="crafts" id="Reviews">
            <h1 class="heading"> Customer <span>Reviews</span></h1>
            <div class="swiper crafts-slider">
                <div class="swiper-wrapper">

                <?php
			$con = mysqli_connect('localhost','root','','crafthaven_project');
			if($con){
     				$select_query = "select * from `products1`";
                 		$result = mysqli_query($con, $select_query);
                 		//$row = mysqli_fetch_assoc($result)
                 		while( $row = mysqli_fetch_assoc($result))
                 		{
                                $ProductID2 = $row["ProductID"];
                    			$Images = $row["Images"];
                     			$Title =  $row["Title"];
                                echo"
                                <diV class='swiper-slide box'>
                                <img class='rounded' src='user_images/$Images' alt=''>
                                <h3>$Title</h3>
                                <p> doloremque quae, cupiditate repellendus voluptatum.</p>
                                <div class='stars'>
                                      <i class='fas fa-star'></i>
                                      <i class='fas fa-star'></i>
                                      <i class='fas fa-star'></i>
                                      <i class='fas fa-star'></i>
                                      <i class=/fas fa-star-half-alt'></i>
                                </div>
                                
                                <a href='ProductDetails.php?id=$ProductID2' class='btn'>Shop Now</a>
                                    </diV>";
                		 }
			}	
			else
			{
    				die(mysqli_error($con));
    				
			}
		?>         
              </div>
            </div>
        </section>


<!-- header section ends-->
<?php include 'footer.php'; ?>
<!-- footer ends-->
<!-- Swiper JS -->
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<!-- custom js file -->

<script src="js\productdetails.js"></script>
</body>
</html>


<?php 
$con = mysqli_connect('localhost','root','','crafthaven_project');

if($con){

    //Register user 
    if(isset($_POST['userReg']))
    {
        $username = $_POST['username'];
        $regemail = $_POST['regemail'];
        $regpass = $_POST['regpass'];
        $hash_password = password_hash($regpass,PASSWORD_DEFAULT);
   
        //select query
        $select_query = "select * from `users1` where username = '$username' or Email = '$regemail' ";
        $result = mysqli_query($con, $select_query);
        $rows_count = mysqli_num_rows($result);

        if($rows_count > 0)
        {
            echo "<script>alert('Username or Email already exist')
            let login = document.querySelector('.login-form');
            let register = document.querySelector('.login-form1');
            let profile = document.querySelector('.Profile');
            register.classList.toggle('active');
            login.classList.remove('active');
            profile.classList.remove('active');
            
            </script>";
        }
        else 
        {
         //insert query    
            $insert_query = "insert into `users1` (Username,Email,Password,UserType) 
            values('$username','$regemail','$hash_password','Buyer')";
            $sql_execute= mysqli_query($con,$insert_query); 
            if($sql_execute)
            {
                echo "<script>
                        alert('Registration successful. Please log in.');
                        let login = document.querySelector('.login-form');
                        let register = document.querySelector('.login-form1');
                        register.classList.remove('active');
                        login.classList.toggle('active');
                      </script>";
            }
            else
            {
                die(mysqli_error($con));
            }
        }
    }


    //user login
    if(isset($_POST['userlogin']))
    {
        $username = $_POST['username'];
        $userpassword = $_POST['userpassword'];
        
        // Select query to fetch the hashed password from the database
        $select_query = "SELECT * FROM `users1` where Username = '$username'";
        $result = mysqli_query($con, $select_query);
        $rows_count = mysqli_num_rows($result);
    
        if($rows_count > 0)
        {
            $row = mysqli_fetch_assoc($result);
            $hashed_password = $row['Password'];
    
            // Verify the password
            if (password_verify($userpassword, $hashed_password)) {
                $_SESSION['username'] = $username;
                $_SESSION['userlogin'] = true;
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['email'] =$row['Email'];
                echo "<script>
                alert('Login sucessful');

                let login = document.querySelector('.login-form');
                let register = document.querySelector('.login-form1');
                let profile = document.querySelector('.Profile');
                register.classList.remove('active');
                login.classList.remove('active');
                </script>";
               
            
            } else {
                echo "<script>alert('Invalid Username or Password')
                let login = document.querySelector('.login-form');
                let register = document.querySelector('.login-form1');
                let profile = document.querySelector('.Profile');
                register.classList.remove('active');
                login.classList.toggle('active');
                profile.classList.remove('active');
                </script>";
            }
        }
        else
        {
            echo "<script>alert('Invalid Username or Password')</script>"; 
        }
    }

    //Add To cart
    if (isset($_POST['Addtocart'])) {
       
        if (isset($_SESSION['UserID'])) {
            if ($Availability == "yes") {
                $qty = $_POST['quantity'];
                $Price_add = $_POST['Price'];
                $userid = $_SESSION['UserID'];
               
                $stmt = $con->prepare("INSERT INTO cartdetails (UserID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $userid, $ProductID, $qty, $Price_add);
                if ($stmt->execute()) {
                    echo "<script>alert('Product added to cart successfully');</script>";
                } else {
                    echo "Error: " . $stmt->error;
                }
               $stmt->close();


            } else {
                echo "<script>alert('Product is unavailable');</script>";
            }
        }
        else
        {
            echo "<script>alert('Please log in to contioue..')
            let login = document.querySelector('.login-form');
            login.classList.toggle('active');
            ;</script>";
        }  
    }
}
else
{
    die(mysqli_error($con));
}
echo "<script> var userLoginSession = " . 
(isset($_SESSION['userlogin']) && $_SESSION['userlogin'] ? 'true' : 'false') . "
;</script>";


?>