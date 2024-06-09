<!-- Tulsi Thakkar-->
<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'crafthaven_project');
if (!$con) {
    die(mysqli_error($con));
}

// PayPal configuration
define('PAYPAL_ID', 'sb-47rqio31099448@business.example.com'); 
define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE
define('PAYPAL_RETURN_URL', 'http://localhost/CraftHaven_Project/checkOut.php'); 
define('PAYPAL_CANCEL_URL', 'http://localhost/CraftHaven_Project/checkOut.php'); 
define('PAYPAL_CURRENCY', 'USD');
define('PAYPAL_URL', (PAYPAL_SANDBOX == true) ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr");

$orderSuccess = false;
$orderDetails = [];

if (isset($_POST['Logout'])) {
    $_SESSION['username'] = '';
    $_SESSION['userlogin'] = false;
    $_SESSION['UserID'] = '';
    $_SESSION['email'] = '';
    session_unset();
    session_destroy();
    echo "<script>window.open('index.php','_self')</script>";
    exit();
}

if (isset($_POST['placeorder'])) {
    $userID = $_SESSION['UserID'];
    $totalAmount = $_POST['amount'];
    $shippingAddress = $_POST['Useraddress'];
    $shippingCity = $_POST['UserCity'];
    $shippingState = $_POST['Userstate'];
    $shippingZip = $_POST['Userzip'];
    $shippingCountry = $_POST['Usercountry'];
    $shippingMethod = $_POST['shipping-method'];
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert into order table
    $orderQuery = "INSERT INTO orders (UserID, OrderDate, TotalAmount, shippingAddress, shippingCity, shippingState, shippingZip, shippingCountry, shippingMethod) VALUES (?, now(), ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($orderQuery);
    $stmt->bind_param('idsssiss', $userID, $totalAmount, $shippingAddress, $shippingCity, $shippingState, $shippingZip, $shippingCountry, $shippingMethod);
    $stmt->execute();
    $orderID = $stmt->insert_id; // Get the auto-generated OrderID
    $stmt->close();

    // Insert Order Details into OrderDetails Table
    $cartQuery = "SELECT * FROM cartdetails WHERE UserID = ?";
    $cartStmt = $con->prepare($cartQuery);
    $cartStmt->bind_param('i', $userID);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();
    
    while ($row = $cartResult->fetch_assoc()) {
        $productID = $row['ProductID'];
        $quantity = $row['Quantity'];
        $price = $row['Price'];
        $orderDetailQuery = "INSERT INTO orderdetails1 (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($orderDetailQuery);
        $stmt->bind_param('iiid', $orderID, $productID, $quantity, $price);
        $stmt->execute();
        $stmt->close();
    }
    $cartStmt->close();

    // Clear Cart (optional)
    $clearCartQuery = "DELETE FROM cartdetails WHERE UserID = ?";
    $clearCartStmt = $con->prepare($clearCartQuery);
    $clearCartStmt->bind_param('i', $userID);
    $clearCartStmt->execute();
    $clearCartStmt->close();

    // Fetch product names
    $query = "SELECT p.Title FROM orderdetails1 od JOIN products1 p ON od.ProductID = p.ProductID WHERE od.OrderID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $productNames = '';
    while ($row = $result->fetch_assoc()) {
        $productNames .= $row['Title'] . ', ';
    }
    $productNames = rtrim($productNames, ', ');
    $stmt->close();

    $orderDate = date('Y-m-d H:i:s'); // Example order date, replace with actual

    $orderSuccess = true;
    $_SESSION['orderDetails'] = [
        'orderDate' => $orderDate,
        'totalAmount' => $totalAmount,
        'shippingAddress' => $shippingAddress,
        'shippingCity' => $shippingCity,
        'shippingState' => $shippingState,
        'shippingZip' => $shippingZip,
        'shippingCountry' => $shippingCountry,
        'shippingMethod' => $shippingMethod,
        'productNames' => $productNames
    ];

    if ($_POST['payment-method'] == 'paypal') {
        // Redirect to PayPal with necessary parameters
        $queryString = http_build_query([
            'business' => PAYPAL_ID,
            'item_name' => $productNames,
            'amount' => $totalAmount,
            'currency_code' => PAYPAL_CURRENCY,
            'return' => PAYPAL_RETURN_URL,
            'cancel_return' => PAYPAL_CANCEL_URL,
            'notify_url' => 'http://localhost/CraftHaven_Project/ipn.php',
            'cmd' => '_xclick'
        ]);
        header('Location: ' . PAYPAL_URL . '?' . $queryString);
        $_SESSION['payment_success'] = true; // Set session variable to indicate successful payment
        exit();
    }


}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!--font awesome cdn link-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- custom style-->
    <link href="css/checkout.css" rel="stylesheet">
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
    <section class="checkout-container">
        <div class="order-summary">
            <h1 class="heading">Your <span>Order Summary</span></h1>
            <div class="order-items">
                <div class="item-grid">
                    <?php
                    if (isset($_SESSION['UserID'])) {
                        $userID = $_SESSION['UserID'];
                        $query = "SELECT c.*, p.Images, p.Title
                                  FROM `products1` p
                                  JOIN `cartdetails` c ON p.ProductID = c.ProductID
                                  WHERE c.UserID = $userID";
                        $result = mysqli_query($con, $query);
                        $num_rows = mysqli_num_rows($result);
                        $totalPrice = 0;
                        if ($num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $CartID = $row['CartDetailID'];
                                $Quantity_cart = $row['Quantity'];
                                $Price_cart = $row['Price'];
                                $Title_cart = $row['Title'];
                                $Images_cart = $row['Images'];
                                $totalPrice += $Quantity_cart * $Price_cart;
                                echo "
                                <div class='item'>
                                    <img src='user_images/$Images_cart' alt='Product Image'>
                                    <div class='item-details'>
                                        <h3>$Title_cart</h3>
                                        <p>Quantity: $Quantity_cart</p>
                                        <p>Price: $$Price_cart</p>
                                    </div>
                                </div>";
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="total-price">
                <p>Subtotal: $<span id="subtotal"><?php echo $totalPrice; ?></span></p>
                <hr class="price-separator">
                <p>Shipping: $<span id="shipping-cost">10.00</span></p>
                <hr class="price-separator">
                <?php $total = $totalPrice + 10.00; ?>
                <p>Total: $<span id="total-price"><?php echo $total; ?></span></p>
            </div>
        </div>

        <div class="vertical-section">
            <div class="billing-info">
                <h1 class="heading">Billing <span>Information</span></h1>
                <form id="checkout-form" action="#" method="post">
                    <div class="form-group">
                        <label for="UserName">Full Name</label>
                        <input type="text" id="UserName" placeholder="Your Name" class="box" required name="UserName">
                    </div>
                    <div class="form-group">
                        <label for="UserEmail">Email Address</label>
                        <input type="email" id="UserEmail" placeholder="Your Email" class="box" required name="UserEmail">
                    </div>
                    <div class="form-group">
                        <label for="Useraddress">Address</label>
                        <input type="text" id="Useraddress" 
                        placeholder="Your Address" class="box" required name="Useraddress">
                    </div>
                    <div class="form-group">
                        <label for="UserCity">City</label>
                        <input type="text" id="UserCity" placeholder="Your City"
                         class="box" required name="UserCity">
                    </div>
                    <div class="form-group">
                        <label for="Userstate">State</label>
                        <input type="text" id="Userstate" placeholder="Your State" 
                        class="box" required name="Userstate">
                    </div>
                    <div class="form-group">
                        <label for="Userzip">Zip/Postal Code</label>
                        <input type="number" id="Userzip" 
                        placeholder="Your Zip" class="box" required name="Userzip">
                    </div>
                    <div class="form-group">
                        <label for="Usercountry">Country</label>
                        <select id="Usercountry" name="Usercountry" required>
                            <option value="">Select Country</option>
                            <option value="IN">India</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="UK">United Kingdom</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shipping-method">Shipping Method</label>
                        <select id="shipping-method" name="shipping-method" required>
                            <option value="standard">Standard - $10.00 (3-5 days)</option>
                            <option value="express">Express - $20.00 (1-2 days)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment-method">Payment Method</label>
                        <select id="payment-method" name="payment-method" required>
                            <option value="credit-card">Credit/Debit Card</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div id="credit-card-info" class="payment-method-details">
                        <div class="form-group">
                            <label for="card-number">Card Number</label>
                            <input type="text" id="card-number" 
                            placeholder="Card Number" class="box"  name="card-number">
                        </div>
                        <div class="form-group">
                            <label for="card-expiry">Expiry Date</label>
                            <input type="text" id="card-expiry"
                             placeholder="MM/YY" class="box" name="card-expiry">
                        </div>
                        <div class="form-group">
                            <label for="card-cvc">CVC</label>
                            <input type="text" id="card-cvc" placeholder="CVC"
                             class="box"  name="card-cvc">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="terms-conditions" required>
                        <label for="terms-conditions">
                            I agree to the <a href="#">terms and conditions</a></label>
                    </div>
                    <input type="hidden" name="amount"
                     value="<?php echo isset($total) ? $total : '0'; ?>">
                    <button type="submit" class="btn" name="placeorder">Place Order</button>
                </form>
            </div>
            <div class="confirmation">
                <h1 class="heading">Order <span>Confirmation</span></h1>
                <div class="confirmation_content">
                <h3><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></h3>
                <p><i class='fas fa-envelope'></i>
                <?php echo isset( $_SESSION['email']) ?  $_SESSION['email'] : 'abc@gamil.com'; ?>
                <p>Products: <?php echo isset($_SESSION['orderDetails']['productNames']) ? $_SESSION['orderDetails']['productNames'] : ''; ?></p>
                <p>Total Price: <?php echo isset($_SESSION['orderDetails']['totalAmount']) ? $_SESSION['orderDetails']['totalAmount'] : ''; ?></p>
                <p>Shipping Address: <?php echo isset($_SESSION['orderDetails']['shippingAddress']) ? $_SESSION['orderDetails']['shippingAddress'] : ''; ?>, 
                <?php echo isset($_SESSION['orderDetails']['shippingCity']) ? $_SESSION['orderDetails']['shippingCity'] : ''; ?>,
                <?php echo isset($_SESSION['orderDetails']['shippingZip']) ? $_SESSION['orderDetails']['shippingZip'] : ''; ?>,
                <?php echo isset($_SESSION['orderDetails']['shippingState']) ? $_SESSION['orderDetails']['shippingState'] : ''; ?>
                <?php echo isset($_SESSION['orderDetails']['shippingCountry']) ? $_SESSION['orderDetails']['shippingCountry'] : ''; ?>
                </p>
                <p>Shipping Method: <?php echo isset($_SESSION['orderDetails']['shippingMethod']) ? $_SESSION['orderDetails']['shippingMethod'] : ''; ?></p>
                <p>Order Date-Time: <?php echo isset($_SESSION['orderDetails']['orderDate']) ? $_SESSION['orderDetails']['orderDate'] : ''; ?></p>

            
            
            
            
            
            <!--</p>
                   <p>Products: <?php echo $orderDetails['productNames']; ?></p>
                    
                    
                    <p>Total Price: <?php echo $orderDetails['totalAmount']; ?></p>

                    <p>Shipping Address: <?php echo $orderDetails['shippingAddress'] . ', ' . 
                    $orderDetails['shippingCity'] . ',- ' . 
                    $orderDetails['shippingZip'] . ', ' . 
                    $orderDetails['shippingState'] . ' ' . 
                    $orderDetails['shippingCountry']; ?></p>

                    <p>Shipping Method: <?php echo $orderDetails['shippingMethod']; ?></p>                   
                    <p>Order Date-Time: <?php echo $orderDetails['orderDate']; ?></p> -->
                <p>Thank you for your purchase! Your order has been successfully placed.</p>
                <button onclick="window.print()" class="btn">Print Receipt</button>
                <button onclick="window.location.href='index.php'" class="btn">Return to Marketplace</button>
                </div>
            </div>
        </div>
    </section>
</section>

<?php include 'footer.php'; ?>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (<?php echo json_encode($orderSuccess); ?>) {
        document.querySelector('.confirmation').style.display = 'block';
        }
        if (<?php echo isset($_SESSION['payment_success']) ? json_encode($_SESSION['payment_success']) : 'false'; ?>) {

            document.querySelector('.confirmation').style.display = 'block';
            // Clear the session variable after displaying the confirmation
            <?php unset($_SESSION['payment_success']); ?>
        }



        let profileForm = document.querySelector('.Profile');
        document.querySelector('#Profile-btn').onclick = () => {
            profileForm.classList.toggle('active');
        };
        document.querySelector('#home-btn').onclick = () => {
            window.location.href = 'index.php';
        };
        document.querySelector('#payment-method').onchange = () => {
            let selectedMethod = document.querySelector('#payment-method').value;
            document.querySelector('#credit-card-info').style.display = 
            selectedMethod === 'credit-card' ? 'block' : 'none';
            if (selectedMethod === 'credit-card') {
                document.querySelector('#checkout-form').action = '#';
            } else {
                // Set PayPal form action back to its original value
                document.querySelector('#checkout-form').action = 
                '#';
            }
        };
      
        
    });
</script>

</body>
</html>
