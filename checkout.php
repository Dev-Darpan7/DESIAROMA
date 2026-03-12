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
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout – DESIAROMA</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ======= CHECKOUT PAGE ======= */
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: #080808;
    font-family: 'Inter', sans-serif;
    color: #ccc;
}

.checkout-page {
    min-height: 100vh;
    padding: 110px 20px 60px;
}

.checkout-heading {
    text-align: center;
    font-family: 'Cinzel', serif;
    font-size: 34px;
    color: #fff;
    letter-spacing: 3px;
    margin-bottom: 8px;
}

.checkout-sub {
    text-align: center;
    color: #666;
    font-size: 14px;
    margin-bottom: 40px;
    letter-spacing: 1px;
}

.checkout-grid {
    max-width: 960px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 24px;
    align-items: start;
}

/* ---- CARD ---- */
.co-card {
    background: #141414;
    border: 1px solid rgba(255,215,0,0.12);
    border-radius: 14px;
    padding: 28px 30px;
    margin-bottom: 20px;
}

.co-card:last-child { margin-bottom: 0; }

.co-card-title {
    font-family: 'Cinzel', serif;
    font-size: 13px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #ffd700;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,215,0,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
}

.co-card-title .step-badge {
    background: rgba(255,215,0,0.15);
    color: #ffd700;
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ---- SHIPPING INFO ---- */
.ship-name {
    font-size: 17px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 10px;
}

.ship-detail {
    font-size: 14px;
    color: #888;
    line-height: 1.8;
}

.ship-edit-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 14px;
    color: #ffd700;
    font-size: 12px;
    text-decoration: none;
    border: 1px solid rgba(255,215,0,0.25);
    padding: 5px 12px;
    border-radius: 20px;
    transition: 0.2s;
}

.ship-edit-link:hover {
    background: rgba(255,215,0,0.08);
}

/* ---- PAYMENT OPTIONS ---- */
.pay-option {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: 0.25s;
    color: #bbb;
    font-size: 14px;
    font-weight: 500;
}

.pay-option:hover,
.pay-option:has(input:checked) {
    border-color: rgba(255,215,0,0.4);
    background: rgba(255,215,0,0.04);
    color: #fff;
}

.pay-option input[type="radio"] {
    accent-color: #ffd700;
    width: 17px;
    height: 17px;
    flex-shrink: 0;
}

.pay-icon { font-size: 20px; }

/* ---- CONFIRM BUTTON ---- */
.btn-place-order {
    width: 100%;
    padding: 16px;
    margin-top: 22px;
    background: linear-gradient(135deg, #ffd700 0%, #c8a800 100%);
    color: #000;
    font-family: 'Cinzel', serif;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 2px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
    text-transform: uppercase;
}

.btn-place-order:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255,215,0,0.3);
}

/* ---- ORDER SUMMARY ---- */
.order-line {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 14px 0;
    border-bottom: 1px solid #1e1e1e;
    gap: 10px;
}

.order-line:last-of-type { border-bottom: none; }

.ol-name {
    font-size: 14px;
    color: #ddd;
    font-weight: 500;
    flex: 1;
}

.ol-qty {
    font-size: 12px;
    color: #666;
    margin-top: 3px;
}

.ol-price {
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    flex-shrink: 0;
}

.divider {
    border: none;
    border-top: 1px solid #1e1e1e;
    margin: 16px 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #777;
    margin-bottom: 10px;
}

.summary-row.free-ship span:last-child { color: #4cd964; }

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    padding-top: 14px;
    border-top: 1px solid rgba(255,215,0,0.2);
}

.summary-total span:last-child { color: #ffd700; }

/* ---- SUCCESS PAGE ---- */
.success-wrap {
    max-width: 500px;
    margin: 0 auto;
    text-align: center;
    grid-column: 1/-1;
}

.success-wrap .checkmark {
    font-size: 60px;
    margin-bottom: 20px;
    display: block;
}

.success-wrap h2 {
    font-family: 'Cinzel', serif;
    color: #4cd964;
    font-size: 28px;
    margin-bottom: 12px;
}

.success-wrap p {
    color: #777;
    font-size: 15px;
    margin-bottom: 30px;
}

.btn-continue {
    display: inline-block;
    background: linear-gradient(135deg, #ffd700, #c8a800);
    color: #000;
    padding: 13px 36px;
    border-radius: 30px;
    font-family: 'Cinzel', serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-decoration: none;
    transition: 0.3s;
}

.btn-continue:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,215,0,0.3);
}

@media (max-width: 750px) {
    .checkout-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<header>
    <div class="logo">DESIAROMA</div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="cart.php">← Cart</a></li>
        </ul>
    </nav>
</header>

<div class="checkout-page">

    <?php if(isset($success)): ?>

    <div class="checkout-grid">
        <div class="success-wrap">
            <span class="checkmark">✅</span>
            <h2>Order Placed Successfully!</h2>
            <p>Thank you for shopping at DESIAROMA. Your luxury fragrance is being prepared and will be on its way soon.</p>
            <a href="index.php" class="btn-continue">Continue Shopping</a>
        </div>
    </div>

    <?php else: ?>

    <h1 class="checkout-heading">CHECKOUT</h1>
    <p class="checkout-sub">Complete your order below</p>

    <div class="checkout-grid">

        <!-- LEFT: Shipping + Payment -->
        <div class="checkout-left-col">

            <!-- Shipping -->
            <div class="co-card">
                <div class="co-card-title">
                    <span class="step-badge">1</span>
                    Shipping Details
                </div>
                <?php if($user): ?>
                <div class="ship-name"><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></div>
                <div class="ship-detail">
                    <?php echo htmlspecialchars($user['address'] ?? '—'); ?><br>
                    <?php
                        $loc = array_filter([
                            htmlspecialchars($user['city'] ?? ''),
                            htmlspecialchars($user['state'] ?? ''),
                            htmlspecialchars($user['pincode'] ?? '')
                        ]);
                        echo implode(', ', $loc);
                    ?><br>
                    📞 <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?>
                </div>
                <a href="profile.php" class="ship-edit-link">✎ Edit Address</a>
                <?php else: ?>
                <p style="color:#777; font-size:14px;">No address on file. Please update your profile.</p>
                <?php endif; ?>
            </div>

            <!-- Payment -->
            <div class="co-card">
                <div class="co-card-title">
                    <span class="step-badge">2</span>
                    Payment Method
                </div>
                <form method="POST">
                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="COD" required>
                        <span class="pay-icon">💵</span>
                        <div>
                            <div style="color:#fff; font-weight:500;">Cash on Delivery</div>
                            <div style="font-size:12px; color:#666; margin-top:2px;">Pay when your order arrives</div>
                        </div>
                    </label>
                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="ONLINE" checked>
                        <span class="pay-icon">💳</span>
                        <div>
                            <div style="color:#fff; font-weight:500;">Pay Online</div>
                            <div style="font-size:12px; color:#666; margin-top:2px;">UPI · Card · NetBanking · Wallet</div>
                        </div>
                    </label>
                    <button class="btn-place-order" name="place_order">
                        Confirm &amp; Place Order →
                    </button>
                </form>
            </div>

        </div>

        <!-- RIGHT: Order Summary -->
        <div class="co-card" style="position: sticky; top: 100px;">
            <div class="co-card-title">
                <span class="step-badge">3</span>
                Order Summary
            </div>
            <div class="order-items">
                <?php foreach($products as $p): ?>
                <div class="order-line">
                    <div>
                        <div class="ol-name"><?php echo htmlspecialchars($p['product_name']); ?></div>
                        <div class="ol-qty">Qty: <?php echo $p['qty']; ?></div>
                    </div>
                    <div class="ol-price">₹<?php echo number_format($p['subtotal'], 2); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <hr class="divider">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₹<?php echo number_format($total_price, 2); ?></span>
            </div>
            <div class="summary-row free-ship">
                <span>Shipping</span>
                <span>Free ✓</span>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <span>₹<?php echo number_format($total_price, 2); ?></span>
            </div>
        </div>

    </div><!-- /.checkout-grid -->

    <?php endif; ?>

</div><!-- /.checkout-page -->

</body>
</body>
</html>