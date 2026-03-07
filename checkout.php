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

    // 🔹 CHANGED: Redirect to demo payment page instead of Razorpay
    if($payment_method == "ONLINE"){
        $_SESSION['demo_order_id'] = $order_id;
        $_SESSION['demo_total'] = $total_price;

        header("Location: demo_payment.php"); // <-- new demo payment page
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - DESIAROMA</title>
    <link rel="stylesheet" href="style.css">
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

<!-- Display Shipping Address -->
<?php if($user): ?>
<div style="border:1px solid #c39f77; padding:20px; margin-bottom:20px; border-radius:8px; background:#fff8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.05); color:#333;">
    <h3 style="color:#b35400;">Shipping Address</h3>
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

<h3>Select Payment Method</h3>

<div style="display:flex; flex-direction:column; gap:15px; margin-top:15px;">

    <!-- Cash on Delivery Card -->
    <label style="border:1px solid #c39f77; border-radius:8px; padding:15px; background:#fff8f0; cursor:pointer; transition:0.2s; display:flex; align-items:center;">
        <input type="radio" name="payment_method" value="COD" required style="margin-right:15px; transform:scale(1.2);">
        <span style="font-weight:500; color:#333;">Cash on Delivery</span>
    </label>

    <!-- Online Payment Card -->
    <label style="border:1px solid #c39f77; border-radius:8px; padding:15px; background:#fff8f0; cursor:pointer; transition:0.2s; display:flex; align-items:center;">
        <input type="radio" name="payment_method" value="ONLINE" required style="margin-right:15px; transform:scale(1.2);">
        <span style="font-weight:500; color:#333;">Pay Online (UPI / Card / NetBanking)</span>
    </label>

</div>

<style>
    /* Hover effect for payment cards */
    label:hover {
        background:#fdf1e6;
        border-color:#b35400;
    }
</style>

<br><br>

<button class="checkout-btn" name="place_order">
    Confirm Order
</button>

</form>

<?php endif; ?>

</div>
</section>

</body>
</html>