<!-- Tulsi Thakkar-->
<?php
session_start();
$con = mysqli_connect('localhost','root','','crafthaven_project');
if($con)
    if (isset($_GET['searchname'])) {
        $searchname = mysqli_real_escape_string($con, $_GET['searchname']);
        // Fetch ArtisanID based on search name (assuming there's a table `artisans1` with `Name` and `ArtisanID` columns)
        $query = "SELECT * FROM artisans1 WHERE Name LIKE '%$searchname%'";
        $result = mysqli_query($con, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $ArtisanID = $row['ArtisanID'];
            $Name = $row['Name'];
            $ProfilePicture = $row['ProfilePicture'];
            $Bio = $row['Bio'];
            $ContactInfo = $row['ContactInfo'];
        } else {
            echo "<script>
            alert('No artisan found with the name: $searchname'); </script>";
            $ArtisanID=1;
            $query = "SELECT * FROM artisans1 WHERE ArtisanID = $ArtisanID";
            $result = mysqli_query($con, $query);
        
            if ($row = mysqli_fetch_assoc($result)) {
                $ProfilePicture = $row["ProfilePicture"];
                $Name = $row["Name"];
                $Bio = $row["Bio"];
                $ContactInfo = $row["ContactInfo"];
            }


           
        }
    } elseif (isset($_GET['id'])) {
        $ArtisanID = $_GET['id'];
        $query = "SELECT * FROM artisans1 WHERE ArtisanID = $ArtisanID";
        $result = mysqli_query($con, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $ProfilePicture = $row["ProfilePicture"];
            $Name = $row["Name"];
            $Bio = $row["Bio"];
            $ContactInfo = $row["ContactInfo"];
        } else {
            echo "Artisan not found.";
            exit();
        }
    } else {
        echo "Invalid Artisan ID.";
        exit();
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
    <title>Artisan Profiles Details</title>
    <!--font awesome cdn link-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- custom style-->
    <link href="css\ArtisansProfile_user.css" rel="stylesheet">

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
        <div class="fas fa-user" id="Profile-btn"></div> 
    </div>
    <form action="" class="search-form">
        <input type="search" id="search-box" name="searchname" placeholder="search Artisan Name here..">
        <label for="search-box" class="fas fa-search"></label>  
        
        
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
    <h1 class="heading">Artisan <span> Details</span></h1>
    <table class="styled-table">
        <tbody>
            <tr>
                <td>
                    <div class="profiles">
                       <img src='user_images/<?php echo $ProfilePicture ; ?>' alt="Artisan Image">
                    </div>
                </td>
                <td>
                    <div class="product_details">
                        <h3><?php echo $Name; ?></h3>
                        <p><?php echo $Bio ; ?></p>
                        <p><?php echo $ContactInfo ; ?></p>
                        <p>Follow Artisan on</p>
                        <diV class="share">
                            <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>
                            <a href="#" class="fab fa-instagram"></a>
                            <a href="#" class="fab fa-linkedin"></a>
                            <a href="#" class="fab fa-whatsapp"></a>
                        </diV>
                    </div>
                </td>
            </tr>

        </tbody>

    </table>
   
    </section>
</section>

<section class="crafts" id="products">

            <h1 class="heading"> <?php echo $Name; ?> 's <span>Products</span></h1>
            <div class="swiper crafts-slider">
                <div class="swiper-wrapper">

                <?php
			$con = mysqli_connect('localhost','root','','crafthaven_project');
			if($con){
     				$select_query = "select * from `products1` where ArtisanID = $ArtisanID";
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

<?php include 'footer.php'; ?>
<!-- footer ends-->
<!-- Swiper JS -->
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<!-- custom js file -->

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        let profileform = document.querySelector('.Profile');
        let searchForm = document.querySelector('.search-form');
        document.querySelector('#Profile-btn').onclick = () => 
        {
             profileform.classList.toggle('active');
            searchForm.classList.remove('active');
        }
        document.querySelector('#search-btn').onclick = () => {
            searchForm.classList.toggle('active');
            profileform.classList.remove('active');
        };
        document.querySelector('#home-btn').onclick = () => {
            window.location.href = 'index.php';
        };
        var swiper = new Swiper(".crafts-slider", {
    loop:true,
    spaceBetween: 20,
    autoplay:{
        delay:4500,
        disableOnInteraction:false,
    },
    breakpoints:{
        0:{
            slidesPerView: 1,
        },
        768:
        {
            slidesPerView: 2,   
        },
        1020:
        {
            slidesPerView: 3,  
        }
    }

    
  });
    });
</script>
</body>
</html>
