<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* 🔒 REQUIRE LOGIN */
if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect_after_login'] = "checkout.php";
    header("Location: login.php");
    exit();
}

/* 🛒 CART EMPTY CHECK */
if(empty($_SESSION['cart'])){
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = 0;
$products = [];

/* 🧾 FETCH CART PRODUCTS */
$ids = array_keys($_SESSION['cart']);
$ids = array_map('intval',$ids);
$id_list = implode(',', $ids);

$query = "SELECT * FROM products WHERE id IN ($id_list)";
$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($result)){
    $qty = $_SESSION['cart'][$row['id']];
    $row['qty'] = $qty;
    $row['subtotal'] = $row['price'] * $qty;
    $total_price += $row['subtotal'];
    $products[] = $row;
}

/* 🏠 FETCH USER SHIPPING ADDRESS */
$address_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id' LIMIT 1");
$user = mysqli_fetch_assoc($address_query);

/* 📦 PLACE ORDER */
if(isset($_POST['place_order'])){

    $payment_method = $_POST['payment_method'];
    $payment_status = ($payment_method == "COD") ? "Pending" : "Initiated";

    $insert_order = "INSERT INTO orders
        (user_id,total_amount,total_price,order_status,order_date,payment_method,payment_status)
        VALUES
        ('$user_id','$total_price','$total_price','Pending',NOW(),'$payment_method','$payment_status')";

    if(!mysqli_query($conn,$insert_order)){
        die("Order insert failed: ".mysqli_error($conn));
    }

    $order_id = mysqli_insert_id($conn);

    foreach($products as $p){
        $pid   = $p['id'];
        $qty   = $p['qty'];
        $price = $p['price'];

        mysqli_query($conn,"INSERT INTO order_items(order_id,product_id,quantity,price)
                            VALUES('$order_id','$pid','$qty','$price')");
    }

    if($payment_method == "COD"){
        unset($_SESSION['cart']);
        $success = true;
    }

    if($payment_method == "ONLINE"){
        $_SESSION['demo_order_id'] = $order_id;
        $_SESSION['demo_total'] = $total_price;

        header("Location: demo_payment.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Checkout - DESIAROMA</title>
<link rel="stylesheet" href="style.css">
<style>

/* Make checkout container smaller */
.cart-box{
max-width:680px !important;
padding:25px !important;
}

/* Smaller title */
.cart-box h2{
font-size:28px !important;
margin-bottom:20px !important;
}

/* Compact table */
.cart-box table th,
.cart-box table td{
padding:8px 10px !important;
font-size:14px !important;
}

/* Smaller payment box */
.payment-box{
padding:18px !important;
margin-top:15px !important;
}

/* Smaller checkout button */
.checkout-btn{
padding:10px 22px !important;
font-size:14px !important;
}

/* Reduce spacing */
.cart-section{
padding:40px 15px !important;
}

</style>
</head>

<body>

<header>
<div class="logo">DESIAROMA</div>
</header>

<section class="cart-section">
<div class="cart-box">

<h2>Checkout</h2>

<?php if(isset($success)): ?>

<p style="color:lightgreen;font-size:20px;">
🎉 Your order has been placed successfully!
</p>

<br>

<a href="index.php" class="checkout-btn">Continue Shopping</a>

<?php else: ?>

<!-- Shipping Address -->
<?php if($user): ?>
<div class="payment-box">

<h3>Shipping Address</h3>

<p><strong><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></strong></p>

<p><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></p>

<p>
<?php echo htmlspecialchars($user['city'] ?? ''); ?>
<?php echo (!empty($user['city']) && !empty($user['state'])) ? ',' : ''; ?>
<?php echo htmlspecialchars($user['state'] ?? ''); ?>
<?php echo htmlspecialchars($user['pincode'] ?? ''); ?>
</p>

<p>Phone: <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>

</div>
<?php endif; ?>

<table>

<tr>
<th>Product</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
</tr>

<?php foreach($products as $p): ?>

<tr>
<td><?php echo htmlspecialchars($p['product_name']); ?></td>
<td>₹<?php echo number_format($p['price'],2); ?></td>
<td><?php echo $p['qty']; ?></td>
<td>₹<?php echo number_format($p['subtotal'],2); ?></td>
</tr>

<?php endforeach; ?>

<tr>
<th colspan="3">Grand Total</th>
<th>₹<?php echo number_format($total_price,2); ?></th>
</tr>

</table>

<br>

<form method="POST">

<div class="payment-box">

<h3>Select Payment Method</h3>

<label class="payment-option">
<input type="radio" name="payment_method" value="COD" required>
Cash on Delivery
</label>

<label class="payment-option">
<input type="radio" name="payment_method" value="ONLINE" required>
Pay Online (UPI / Card / NetBanking)
</label>

</div>

<br>

<button class="checkout-btn" name="place_order">
Confirm Order
</button>

</form>

<?php endif; ?>

</div>
</section>

</body>
</html>