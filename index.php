<!-- Tulsi Thakkar-->

<?php 
session_start();


$con = mysqli_connect('localhost', 'root', '', 'crafthaven_project');
if (!$con) {
    die(mysqli_error($con));
}

$searchResults = [];
$searchQuery = '';

if (isset($_POST['search_query'])) {
    $searchQuery = mysqli_real_escape_string($con, $_POST['search_query']);
    $searchQuery = trim($searchQuery);

    if (!empty($searchQuery)) {
        $searchQuery = '%' . $searchQuery . '%';
        $query = "SELECT * FROM `products1` WHERE `Title` LIKE ? OR `Category` LIKE ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--font awesome cdn link-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- custome style-->
    <link href="css/style.css" rel="stylesheet">

    <!-- swiper link
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>-->

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <title>CraftHaven</title>
    <style>
        .search-results {
            display: none;
        }
        .search-results.active {
            display: block;
        }
    </style>
</head>
<body>

    <!-- header section starts-->
        <header class="header">

        <div class="logo">
        <h1><i class="fas fa-hand-sparkles"></i>CraftHaven</h1>
        </div>
      

        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="#products">Products</a>
            <a href="#categories">categories</a>
            <a href="http://localhost/CraftHaven_Project/ArtisanRegister.php">Register as Artisan</a>

        </nav>

        <div class="icons">
             <div class="fas fa-bars" id="menu-btn"></div>   
             <div class="fas fa-search" id="search-btn"></div>  
             <div class="fas fa-shopping-cart" id="cart-btn"></div>  
             <div class="fas fa-user" id="login-btn"></div>  
        
            </div>

                <!-- search form code -->

            <form action="" class="search-form" method="post">
                <input type="search" id="search-box" name="search_query"
                 placeholder="search Product name or Category here..">
                <label for="search-box" class="fas fa-search"></label>    
            </form>

            <!-- Cart code -->
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






        <!-- Registration code -->
          <form action="" class="login-form1" method="post" >
            <h3>Register now</h3>
            <input type="text" placeholder="your username" class="box" name="username" required="required">
            <input type="email" placeholder="your email" class="box" name="regemail" required="required">
            <input type="password" placeholder="your password" class="box" name="regpass" required="required">
            <p>forgot your password <a href="#">click here</a></p>
            <p>already have an account <a href="#" id="loginlink">login now</a></p>
                <input type="submit" class="btn" value="Register now" name="userReg">
        </form>
        <!-- Login code -->
        <form action=""  class="login-form" method="post">
            <h3>Login now</h3>
            <input type="text" placeholder="your email" class="box" name="username" required="required">
            <input type="password" placeholder="your password" class="box" name="userpassword" required="required">
            <p>forgot your password <a href="#">click here</a></p>
            <p>don't have an account <a href="#" id="Reglink">create new</a></p>
            <input type="submit" class="btn" value="login now" name="userlogin">
        </form>    



      
        <!-- Profile code -->
        <div class="Profile">

                      <h3><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
                    </h3>
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
     <!-- header section ends-->


     <!-- home section starts-->
<section class="home" id="home">
 <div class="content">

    <h3>Welcome to CraftHaven:<br>The Local Artisan Marketplace</h3>
    <p>Explore Exclusive Handcrafted Treasures
    Discover the beauty of authentic, handmade crafts.
    <br>Support their artistry and
     bring unique, one-of-a-kind items into your home.
    </p>

      <a href='ProductDetails.php?id=1' class='btn'>Shop Now and Celebrate Craftsmanship!</a></diV>";

 </div>
</section>

     <!--home section ends-->

     <!--craftss section starts -->

        <section class="crafts" id="products">
            <h1 class="heading"> our <span>Products</span></h1>
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
                                $ProductID = $row["ProductID"];
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
                                <a href='ProductDetails.php?id=$ProductID' class='btn'>Shop Now</a>
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

 <!-- products categoies starts -->

 <section class="crafts" id="categories">
            <h1 class="heading"> Products <span>categories</span></h1>
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
                                $ProductID = $row["ProductID"];
                    			$Images = $row["Images"];
                     			$Category =  $row["Category"];

                                 if($row["Availability"]==1)
                                 {
                                     $Availability = "yes"; 
                                 }
                                 else
                                 {
                                     $Availability = "No"; 
                                 }

                                echo"
                                <diV class='swiper-slide box'>
                                <img src='user_images/$Images' alt='image'><br><br>
                                <h3>$Category</h3>
                                <br>
                                <P>Lorem ipsum dolor sit amet consectetur, adipisicing elit</P><br>
                                <p>Availabal : $Availability </p><br>
                                <a href='ProductDetails.php?id=$ProductID' class='btn'>Shop Now</a>
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
 <!-- products categiie ends-->

 <!--Artisan Profiles starts-->

 <section class="crafts" id="Artisan Profile">
            <h1 class="heading"> Our <span>Artisan Profiles</span></h1>
            <div class="swiper crafts-slider">
                <div class="swiper-wrapper">
                <?php
			$con = mysqli_connect('localhost','root','','crafthaven_project');
			if($con){

     				$select_query = "select * from `artisans1`";
                 		$result = mysqli_query($con, $select_query);
                 		//$row = mysqli_fetch_assoc($result)
                 		while( $row = mysqli_fetch_assoc($result))
                 		{
                    			$ProfilePicture = $row["ProfilePicture"];
                                $ArtisanID = $row["ArtisanID"];
                     			$Name =  $row["Name"];
                     			$Bio =  $row["Bio"] ;
                     			$ContactInfo =  $row["ContactInfo"];

                     			echo "
                     			<diV class='swiper-slide box'>
                     			<img class='rounded' src='user_images/$ProfilePicture' alt=''><br><br>
                     			<h3>$Name</h3><br>
                     			<p> $Bio</p><br>
                                <p><i class='fas fa-envelope'></i> $ContactInfo</p><br>
                     			<br>
                                 <a href='ArtisanProfile_User.php?id=$ArtisanID' class='btn'>
                                 Read More</a>
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



<!--Artisan Profiles ends-->

<!-- Search Results Section -->
<section class="search-results" id="search-results">
        <h1 class="heading"> Search <span>Results</span></h1>
        <div class="products">
            <?php if (!empty($searchResults)): ?>
                <?php foreach ($searchResults as $product): ?>
                    <div class="product-box">
                        <img src="user_images/<?php echo htmlspecialchars($product['Images']); ?>" alt="image">
                        <h3><?php echo htmlspecialchars($product['Title']); ?></h3>
                        <p><?php echo htmlspecialchars($product['Description']); ?></p>
                        <p class="price">Price: <?php echo htmlspecialchars($product['Price']); ?> rs</p>
                        <a href="ProductDetails.php?id=<?php echo htmlspecialchars($product['ProductID']); ?>" class="btn">Shop Now</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No results found for "<?php echo htmlspecialchars($_POST['search_query']); ?>"</p>
            <?php endif; ?>
        </div>
        <button onclick="hideSearchResults() " class="btn">Go Back</button>
    </section>


   



<!-- customer reviews -->
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
                                <img  src='user_images/$Images' alt=''>
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

<!-- Customer reviews ends-->

<!-- blogs starts-->
<section class="crafts" id="blogs">
            <h1 class="heading"> Our <span>Blogs</span></h1>
            <div class="swiper crafts-slider">
                <div class="swiper-wrapper">
                <diV class="swiper-slide box">
                    <img src="images/blog1.jpg" alt="">
                    <div class="content">
                        <div class="icons">
                            <P> <i class="fas fa-user"></i> Rajiv singh</P>
                            <P><i class="fas fa-calendar"></i> Jul 27,2018</P>
                            
                        </div>
                    </div>
                    <p>Craft beer maker Bira 91 forays into merchandising</p>
                    <a href="https://www.forbesindia.com/article/special/craft-beer-maker-bira-91-forays-into-merchandising/50849/1" class="btn">Read more</a>
                </diV>

                <diV class="swiper-slide box">
                    <img src="images/blog2.jpg" alt="">
                    <div class="content">
                        <div class="icons">
                            <p><i class="fas fa-user"></i> Mansvini Kaushik</p>
                            <P><i class="fas fa-calendar"></i> Jun 30,2022 </P>
                            
                        </div>
                    </div>
                    <p>Dance, art and craft classes went virtual in the pandemic</p>
                    <a href="https://www.forbesindia.com/article/2022-edtech-special/dance-art-and-craft-classes-went-virtual-in-the-pandemic-can-they-survive-as-kids-go-back-to-school/77729/1" class="btn">Read more</a>
                </diV>

                <diV class="swiper-slide box">
                    <img src="images/blog3.jpg" alt="">
                    <div class="content">
                        <div class="icons">
                            <p><i class="fas fa-user"></i> AFPRelaxnews</p>
                            <P><i class="fas fa-calendar"></i> May 28,2024  </P>
                        </div>
                    </div>
                    <p>Spain unveils 'lost Caravaggio' art that nearly sold for a song</p>
                    <a href="https://www.forbesindia.com/article/lifes/spain-unveils-lost-caravaggio-art-that-nearly-sold-for-a-song/93220/1" class="btn">Read more</a>
                </diV>
                
              </div>
            </div>
        </section>

<!-- blogs ends-->

<!-- footer starts-->

<section class="footer">
        <div class="box-container">
            <div class="box">
                <h3><i class="fas fa-hand-sparkles"></i>CraftHaven</h3>
                <p>The Local Artisan Marketplace</p>
                <diV class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </diV>
            </div>

            <div class="box">
                <h3>Contact info</h3>
                <a href="#" class="links"><i class="fas fa-phone"></i>+123-456-7890</a>
                <a href="#" class="links"><i class="fas fa-phone"></i>+111-222-2224</a>
                <a href="#" class="links"><i class="fas fa-envelope"></i>CraftHaven@gmail.com</a>
                <a href="#" class="links"><i class="fas fa-map-marker-alt"></i>mumbai,india - 400104</a>
            </div>
            <div class="box">
                <h3>Quick Links</h3>
                <a href="#" class="links"><i class="fas fa-arrow-right"></i>About Us</a>
                <a href="#" class="links"><i class="fas fa-arrow-right"></i> FAQs</a>
                <a href="#" class="links"><i class="fas fa-arrow-right"></i>Terms of Service</a>
                <a href="#" class="links"><i class="fas fa-arrow-right"></i>Privacy Policy</a>
            </div>
        </div>

        <diV class="credit">created by <span>Tulsi Thakkar</span>  | all rights reserved</diV>

</section>


<!-- footer ends-->
  <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <!--custome js file-->
    <script src="js\script.js"></script>
    <script>
        function hideSearchResults() {
            document.getElementById('search-results').classList.remove('active');
        }

        <?php if (!empty($searchResults) || isset($_POST['search_query'])): ?>
            document.getElementById('search-results').classList.add('active');
        <?php endif; ?>
    </script>
  
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
                console.log('Username: " . $_SESSION['username'] . "');
                console.log('Email: " . $_SESSION['email'] . "');
                console.log('UserID: " . $_SESSION['UserID'] . "');
                console.log('Userlogn: " . $_SESSION['userlogin'] . "');
                </script>";
               
            
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

echo "<script> var userLoginSession = " . 
(isset($_SESSION['userlogin']) && $_SESSION['userlogin'] ? 'true' : 'false') . "
;</script>";






?>