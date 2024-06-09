<!-- Tulsi Thakkar-->
<?php
session_start(); // Start the session at the beginning of the PHP script

// Check if the artisan's username is stored in the session
$artisanName = isset($_SESSION['Artisan_LName']) ? $_SESSION['Artisan_LName'] : 'Guest';
$con = mysqli_connect('localhost','root','','crafthaven_project');
if($con){


  //deflaut information for profile
  $bio = 'Guest bio';
  $contactInfo = 'abc@gmail.com';
  $profilePic = 'images/pottery1.jpg'; // Default profile picture
  
  //check for profile
  if ($artisanName !== 'Guest') {
      $stmt = $con->prepare("SELECT * FROM `artisans1` WHERE Name = ?");
      $stmt->bind_param("s", $artisanName);
      $stmt->execute();
      $result = $stmt->get_result();
  
      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $bio = $row['Bio'];
          $id= $row['ArtisanID'];
          $contactInfo = $row['ContactInfo'];
          $profilePic = "user_images/" . $row['ProfilePicture'];
      }
  }
  

  //Edit profile
  if(isset($_POST['Edit_profilebtn']))
  {
    $newBio = $_POST['Artisan_Bio'];
        $newContactInfo = $_POST['Artisan_email'];
        $profilePicUpdated = false;

        // Check if a new profile picture is uploaded
        if($_FILES['Artisan_profile']['name'] != '') {
            $profile = $_FILES['Artisan_profile']['name'];
            $Artisan_profile_temp = $_FILES['Artisan_profile']['tmp_name'];
             move_uploaded_file($Artisan_profile_temp,"./user_images/$profile" );
            $profilePicUpdated = true;
        }

        if ($profilePicUpdated) {
          $stmt = $con->prepare("UPDATE `artisans1` SET `Bio`=?, `ContactInfo`=?, `ProfilePicture`=? WHERE `ArtisanID`=?");
          $stmt->bind_param("sssi", $newBio, $newContactInfo, $profile, $id);
        }
        else {
          $stmt = $con->prepare("UPDATE `artisans1` SET `Bio`=?, `ContactInfo`=? WHERE `ArtisanID`=?");
          $stmt->bind_param("ssi", $newBio, $newContactInfo, $id);
        }

        $stmt->execute();
        echo "<script>alert('Profile is updated')</script>";
        $stmt = $con->prepare("SELECT * FROM `artisans1` WHERE Name = ?");
        $stmt->bind_param("s", $artisanName);
        $stmt->execute();
        $result = $stmt->get_result();
  
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bio = $row['Bio'];
            $id= $row['ArtisanID'];
            $contactInfo = $row['ContactInfo'];
            $profilePic = "user_images/" . $row['ProfilePicture'];
        }

  }


  //Add product
  if(isset($_POST['Product_Addbtn']))
  {
    $ArtisanID = $id;
    $Title= $_POST['Product_Title'];
    $Description=$_POST['Product_Description'];
    $Price=$_POST['Product_price'];
    $Availability=$_POST['Product_Availability'];
    $Images=  $_FILES['Product_image']['name'];
    $Images_temp = $_FILES['Product_image']['tmp_name'];
    $Category=$_POST['Product_Category'];



    move_uploaded_file($Images_temp,"./user_images/$Images" );

    $stmt = $con->prepare("INSERT INTO `products1` (ArtisanID, Title, Description, Price, Availability, Images, Category) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdiss", $ArtisanID, $Title, $Description, $Price, $Availability, $Images, $Category);

    if ($stmt->execute()) {
      
      echo "<script>alert('Product added successfully')</script>";
    } else {
      echo "<script>alert('Error: " . $stmt->error . "')</script>";
      die(mysqli_error($con));
    }

  }

  //Fetch Product
   $stmt = $con->prepare("SELECT * FROM `products1` WHERE `ArtisanID`=?");
   $stmt->bind_param("i", $id);
   $stmt->execute();
   $products = $stmt->get_result();


   //Fetch orders
   $stmt_order = $con->prepare("SELECT 
    p.Title, od.Quantity, p.Price, p.Category,u.Username, o.OrderDate, od.OrderDetailID,od.OrderID FROM 
    products1 p JOIN orderdetails1 od ON p.ProductID = od.ProductID JOIN 
    orders o ON od.OrderID = o.OrderID JOIN users1 u ON o.UserID = u.UserID
    WHERE 
    p.ArtisanID = ?");
    $stmt_order->bind_param("i", $id);
    $stmt_order->execute();
    $orderss = $stmt_order->get_result();


    //delete order details
    if(isset($_POST['Delete_orderbtn']))
  {
    if ($_POST['confirm'] !== 1) {
      $OrderDetailID = $_POST['OrderDetailID'];
      $OrderID = $_POST['OrderID'];
      $stmt = $con->prepare("DELETE FROM `orderdetails1` WHERE `OrderDetailID`=?");
      $stmt->bind_param("i", $OrderDetailID);
      if ($stmt->execute()) {
          echo "<script>alert('Order details deleted successfully');</script>";
      } else {
          echo "<script>alert('Failed to delete product: " . $stmt->error . "');</script>";
      }
      $stmt_order = $con->prepare("SELECT 
      p.Title, od.Quantity, p.Price, p.Category,u.Username, o.OrderDate, od.OrderDetailID,od.OrderID FROM 
      products1 p JOIN orderdetails1 od ON p.ProductID = od.ProductID JOIN 
      orders o ON od.OrderID = o.OrderID JOIN users1 u ON o.UserID = u.UserID
      WHERE 
      p.ArtisanID = ?");
      $stmt_order->bind_param("i", $id);
      $stmt_order->execute();
      $orderss = $stmt_order->get_result();

    } 
  }

    





 







   //Fetch products using product if
   if(isset($_POST['update_Productbtn'])) {

    $productid = $_POST['Product_ID']; 
    $stmt = $con->prepare("SELECT * FROM `products1` WHERE `ProductID`=?");
    $stmt->bind_param("i", $productid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $Title = $row['Title'];
        $Description =$row['Description'];
        $Price  = $row['Price'];
        $Availability = $row['Availability'];
        $Images = $row['Images'];
        $Category = $row['Category'];      
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('Product_updateform').style.display = 'block';
        });
      </script>";
    
      } else {
        echo "<script>console.log('No product found for Product ID: " . $productid . "');</script>";
    }
  }

  //update form update button click
  if(isset($_POST['Product_updatebtn']))
  {
    $productid_update=$_POST['Product_Updateid'];
    $newProduct_Title = $_POST['Product_Title'];
    $newProduct_Description= $_POST['Product_Description'];
    $newProduct_price = $_POST['Product_price'];
    $newProduct_Availability = $_POST['Product_Availability'];
    $newProduct_Category = $_POST['Product_Category'];
    $productPicUpdated = false;

    // Check if a new product picture is uploaded
    if($_FILES['Product_image']['name'] != '') {
        $newProduct_image = $_FILES['Product_image']['name'];
        $newProduct_image_temp = $_FILES['Product_image']['tmp_name'];
         move_uploaded_file($newProduct_image_temp,"./user_images/$newProduct_image" );
         $productPicUpdated = true;
    }

    error_log("Title: $newProduct_Title, Description: $newProduct_Description, Price: $newProduct_price, Availability: $newProduct_Availability, Category: $newProduct_Category, Image: $newProduct_image");
    if ($productPicUpdated) {
        $stmt = $con->prepare("UPDATE `products1` SET `Title`=?, `Description`=?, `Price`=?, `Availability`=?, `Images`=?, `Category`=? WHERE `ProductID`=?");
        $stmt->bind_param("ssdissi", $newProduct_Title, 
        $newProduct_Description, $newProduct_price, $newProduct_Availability,
         $newProduct_image, $newProduct_Category, $productid_update);
   
         
    } else {
        $stmt = $con->prepare("UPDATE `products1` SET `Title`=?, `Description`=?, `Price`=?, `Availability`=?, `Category`=? WHERE `ProductID`=?");
        $stmt->bind_param("ssdisi", $newProduct_Title, 
        $newProduct_Description, $newProduct_price, $newProduct_Availability,
         $newProduct_Category, $productid_update);
         echo "<script>console.log('no  ');</script>";
    }

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "<script>alert('Product details updated successfully');</script>";
        $stmt = $con->prepare("SELECT * FROM `products1` WHERE `ArtisanID`=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $products = $stmt->get_result();
    } else {
        echo "<script>alert('Failed to update product details: " . $stmt->error . "');</script>";
        error_log("SQL Error: " . $stmt->error);
    }
  
  }




  //update form cancel click
  if(isset($_POST['Product_cancelbtn']))
  {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('Product_updateform').style.display = 'none';
    });
  </script>";
  }
  //delete code products
  if(isset($_POST['Delete_Productbtn']))
  {
    if ($_POST['confirm'] !== 1) {
      $productid = $_POST['Product_ID'];
      $stmt = $con->prepare("DELETE FROM `products1` WHERE `ProductID`=?");
      $stmt->bind_param("i", $productid);
      if ($stmt->execute()) {
          echo "<script>alert('Product deleted successfully');</script>";
      } else {
          echo "<script>alert('Failed to delete product: " . $stmt->error . "');</script>";
      }
      $stmt = $con->prepare("SELECT * FROM `products1` WHERE `ArtisanID`=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $products = $stmt->get_result();

    } 
  }





}
else
{
    die(mysqli_error($con));
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
    <link href="css\ArtisansProfile.css" rel="stylesheet">
    <title>ArtisanProfile</title>
</head>
<body>
 
<header class="header">

    <div class="logo">
        <h1><i class="fas fa-hand-sparkles"></i>CraftHaven</h1>
        </div>
        <div class="icons">
             <div class="fas fa-home" id="home-btn"></div>  
             <div class="fas fa-user" id="Profile-btn"></div>  
        </div>

        <div class="Profile">
                      <img class="rounded" src="<?php echo $profilePic; ?>" alt="Profile Picture">
                      <h3><?php echo $artisanName; ?></h3>
                      <p><?php echo $bio; ?></p>
                      <p><i class='fas fa-envelope'></i><?php echo $contactInfo; ?></p>

                    <form action="" method="post">
                    <input type="submit" class="btn" value="Edit now" name="Edit_profile">
                    <input type="submit" class="btn"
                     onClick="return confirm('Are you sure you want to logout?')"
                     value="Logout" name="Logout">
                    </form>
        </div>

        <form action="" class="Edit-form"  method="post" enctype="multipart/form-data">
            
            <h3 class="heading"> Edit <span>Profile</span></h3>
            <label for="ArtisanName" >Artisan UserName</label>
            <input type="text" id="ArtisanName" value="<?php echo $artisanName; ?>" class="box"
             required="required" name="Artisan_Name" readonly>
            
            <label for="ArtisanBio" >Artisan Bio</label>
            <input type="text" id="ArtisanBio" value="<?php echo $bio; ?> "
            class="box" required="required" name="Artisan_Bio">
            
            <label for="Artisanemail" >Artisan Email</label>
            <input type="email" id="Artisanemail" value="<?php echo $contactInfo; ?> "
            class="box" required="required" Name="Artisan_email">
          

            <label for="ArtisanProfile" >Select Your Profile Picture/Logo:</label>
            <input type="file" id="ArtisanProfile" value= "<?php echo $profilePic; ?>"
            class="box" name="Artisan_profile">
            
            <input type="submit" class="btn" value="Edit now" name="Edit_profilebtn">
        
        </form> 
</header>  


<!-- section for product managment -->
<section class="home">

      <section class="productManagement">
      <h1 class="heading"> Your <span>Products</span></h1>
      <button id="addProductBtn" class="btn addbtn">Add Product</button>
        <!-- Product management table-->

       
          <table class="styled-table">
          <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Category</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <?php while($product = $products->fetch_assoc()) { ?>
          <tr data-id="<?php echo $product['ProductID']; ?>" data-title="<?php echo htmlspecialchars($product['Title']); ?>" data-description="<?php echo htmlspecialchars($product['Description']); ?>" data-price="<?php echo $product['Price']; ?>" data-availability="<?php echo $product['Availability']; ?>" data-category="<?php echo $product['Category']; ?>" data-image="<?php echo $product['Images']; ?>">
              <td><?php echo $product['Title']; ?></td>
              <td><?php echo $product['Description']; ?></td>
              <td><?php echo $product['Price']; ?></td>
              <td><?php echo $product['Availability']; ?></td>
              <td><?php echo $product['Category']; ?></td>
              <td><img src="user_images/<?php echo $product['Images']; ?>" width="50" height="50"></td>
              <td>

                  <form  action="" method="post">
                      <input type="hidden" name="Product_ID" value="<?php echo $product['ProductID']; ?>">
                      <input type="submit" name="update_Productbtn"
                       value="Update" class="btn">
                  </form>


                  <form  action="" method="post">
                      <input type="hidden" name="Product_ID" value="<?php echo $product['ProductID']; ?>">
                      <input type="submit" name="Delete_Productbtn" 
                      value="Delete" class="btn"
                       onClick="return confirm('are you sure you want to delete this
                        product?')">
                  </form>
              </td>
          </tr>
    <?php } ?>
</tbody>

    </table>         

      </section>

</section>

<!-- section for order managment -->
<section class="orderhome">

      <section class="orderManagement">
      <h1 class="heading"> Your <span>Orders and Sales</span></h1>


          <table class="styled-table">
          <thead>
            <tr>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Date and Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = $orderss->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $order['Username']; ?></td>
                    <td><?php echo $order['Title']; ?></td>
                    <td><?php echo $order['Category']; ?></td>
                    <td><?php echo $order['Quantity']; ?></td>
                    <td><?php echo $order['Price']; ?></td>
                    <td><?php echo $order['OrderDate']; ?></td>
                    <td>
                  <form  action="" method="post">
                      <input type="hidden" name="OrderDetailID" 
                      value="<?php echo $order['OrderDetailID']; ?>">
                      <input type="hidden" name="OrderID" 
                      value="<?php echo $order['OrderID']; ?>">
                      
                      <input type="submit" name="Delete_orderbtn" 
                      value="Delete" class="btn"
                       onClick="return confirm('are you sure you want to delete this
                        product?')">
                  </form>
              </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>         

      </section>

</section>












      <!--Add product popup form-->
     <section>
     <form class="Product_form" method="post" enctype="multipart/form-data" id="Product_form">

      <h1 class="heading"> Add <span>Products</span></h1>
        <label for="ProductTitle" >Product Title</label>
        <input type="text" id="ProductTitle" class="box"
        required="required" name="Product_Title">

        <label for="productDescription" >Product Description</label>
        <input type="text" id="productDescription" class="box"
        required="required" name="Product_Description">

        <label for="ProductPrice" >Product Price</label>
        <input type="number" id="ProductPrice" class="box"
        required="required" name="Product_price" step="0.01">

        <label for="ProductAvailability" >Product Availability</label>
        <input type="number" id="ProductAvailability" class="box"
        required="required" name="Product_Availability">
        
        <label for="ProductImage" >Select product Picture</label>
        <input type="file" id="ProductImage" required="required"
        class="box" name="Product_image">

        <label for="ProductCategory" >Product Category </label>
        <select id="ProductCategory" class="box" required="required" name="Product_Category">
          <option value="jewellery">jewellery</option>
          <option value="pottery"> pottery</option>
          <option value="paintings">paintings</option>
          <option value="Woodwork">Woodwork</option>
          <option value="Papercraft">Papercraft</option>
        </select>

        <input type="submit" class="btn" value="Add" name="Product_Addbtn">
        <input type="submit" class="btn" value="Cancel" id="cancelAddbtn">
      </form>
     </section>
    <!--Update product popup form-->
     <section>
    <form class="Product_form" method="post" enctype="multipart/form-data" 
    id="Product_updateform">
        <h1 class="heading"> Update <span>Products</span></h1>

        <label for="ProductTitle">Product Title</label>
        <input type="text" id="ProductTitle" class="box" required="required"
         name="Product_Title" value="<?php echo $Title; ?>">

        <label for="productDescription">Product Description</label>
        <input type="text" id="productDescription" class="box" required="required" 
        name="Product_Description" value="<?php echo $Description; ?>" >

        <label for="ProductPrice">Product Price</label>
        <input type="number" id="ProductPrice" class="box" required="required"
         name="Product_price" step="0.01" value="<?php echo $Price; ?>">

        <label for="ProductAvailability">Product Availability</label>
        <input type="number" id="ProductAvailability" class="box"
         required="required" name="Product_Availability" value="<?php echo $Availability; ?>">
        
        <label for="ProductImage">Select product Picture</label>
        <input type="file" id="ProductImage" class="box"
        value="<?php echo $Images; ?>" name="Product_image">


        <input type="hidden" id="Productid_update" class="box" value="<?php echo $productid; ?>" 
        name="Product_Updateid">
        <label for="ProductCategory">Product Category</label>
        <select id="ProductCategory" class="box" required="required"
         name="Product_Category" value="<?php echo $Category; ?>">
            <option value="jewellery">jewellery</option>
            <option value="pottery">pottery</option>
            <option value="paintings">paintings</option>
            <option value="Woodwork">Woodwork</option>
            <option value="Papercraft">Papercraft</option>
        </select>

        <input type="hidden" name="Product_ID" value="">
        <input type="submit" class="btn" value="Update" name="Product_updatebtn">
        <input type="submit" class="btn" value="Cancel" name="Product_cancelbtn">
    </form>
  </section>





  <?php include 'footer.php'; ?>
  <!--custome js file-->
  <script src="js\ArtisansProfile.js"></script>
</body>
</html>


<?php


if(isset($_POST['Edit_profile']))
{
    echo "<script>
    let profile = document.querySelector('.Profile');
    let editform = document.querySelector('.Edit-form');
    profile.classList.remove('active');
    editform.classList.toggle('active');
  </script>";
}

if(isset($_POST['Logout']))
{
  if ($_POST['confirm'] !== 1) {
    $_SESSION['Artisan_LName'] = '';

    // Destroy the session if needed
    session_destroy();
  
    // Redirect to login page
    echo "<script>window.open('Artisanlogin.php','_self')</script>";
  }

 
}



?>