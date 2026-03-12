<?php
session_start();
include 'config.php';

// Require login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$order_id = intval($_GET['id'] ?? 0);

if($order_id == 0){
    header("Location: orders.php");
    exit();
}

// Fetch order — make sure it belongs to this user
$order_q = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id' LIMIT 1");
if(mysqli_num_rows($order_q) == 0){
    die("<p style='color:red; text-align:center; margin-top:50px;'>Order not found or access denied.</p>");
}
$order = mysqli_fetch_assoc($order_q);

// Fetch order items with product names
$items_q = mysqli_query($conn, "
    SELECT oi.*, p.product_name, p.image 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = '$order_id'
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order #<?php echo $order_id; ?> – DESIAROMA</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
body { background: #080808; font-family: 'Inter', sans-serif; color: #ccc; }

.order-page {
    max-width: 760px;
    margin: 110px auto 60px;
    padding: 0 20px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #888;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 24px;
    transition: color 0.2s;
}
.back-link:hover { color: #ffd700; }

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 10px;
}

.order-title {
    font-family: 'Cinzel', serif;
    font-size: 26px;
    color: #fff;
    letter-spacing: 1px;
}

.order-title span { color: #ffd700; }

.status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.5px;
}
.status-badge.pending    { background: rgba(255,152,0,0.15); color: #ff9800; border: 1px solid rgba(255,152,0,0.3); }
.status-badge.confirmed  { background: rgba(76,217,100,0.15); color: #4cd964; border: 1px solid rgba(76,217,100,0.3); }
.status-badge.completed  { background: rgba(76,217,100,0.15); color: #4cd964; border: 1px solid rgba(76,217,100,0.3); }
.status-badge.cancelled  { background: rgba(255,59,48,0.15); color: #ff3b3b; border: 1px solid rgba(255,59,48,0.3); }

.info-card {
    background: #141414;
    border: 1px solid rgba(255,215,0,0.12);
    border-radius: 12px;
    padding: 24px 28px;
    margin-bottom: 20px;
}

.info-card-title {
    font-family: 'Cinzel', serif;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #ffd700;
    padding-bottom: 14px;
    margin-bottom: 16px;
    border-bottom: 1px solid rgba(255,215,0,0.1);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 9px 0;
    font-size: 14px;
    border-bottom: 1px solid #1c1c1c;
}
.info-row:last-child { border-bottom: none; }
.info-row .label { color: #666; }
.info-row .value { color: #eee; font-weight: 500; text-align: right; }

/* Items */
.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #1c1c1c;
    gap: 14px;
}
.item-row:last-child { border-bottom: none; }

.item-img {
    width: 54px;
    height: 54px;
    border-radius: 8px;
    object-fit: cover;
    background: #222;
    flex-shrink: 0;
}

.item-name { color: #ddd; font-size: 14px; font-weight: 500; }
.item-qty  { color: #666; font-size: 12px; margin-top: 3px; }
.item-price { color: #fff; font-weight: 600; font-size: 15px; flex-shrink: 0; }

.grand-row {
    display: flex;
    justify-content: space-between;
    padding-top: 16px;
    border-top: 1px solid rgba(255,215,0,0.2);
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin-top: 6px;
}
.grand-row span:last-child { color: #ffd700; }

.btn-invoice {
    display: inline-block;
    margin-top: 24px;
    background: linear-gradient(135deg, #ffd700, #c8a800);
    color: #000;
    font-family: 'Cinzel', serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    padding: 13px 30px;
    border-radius: 8px;
    text-decoration: none;
    text-transform: uppercase;
    transition: 0.3s;
}
.btn-invoice:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(255,215,0,0.3);
}
</style>
</head>
<body>

<header>
    <div class="logo">DESIAROMA</div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="orders.php">My Orders</a></li>
        </ul>
    </nav>
</header>

<div class="order-page">

    <a href="orders.php" class="back-link">← Back to My Orders</a>

    <div class="order-header">
        <div class="order-title">Order <span>#<?php echo $order_id; ?></span></div>
        <?php 
            $status_class = strtolower($order['order_status'] ?? $order['status'] ?? 'pending');
            $status_label = ucfirst($order['order_status'] ?? $order['status'] ?? 'Pending');
        ?>
        <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span>
    </div>

    <!-- Order Info -->
    <div class="info-card">
        <div class="info-card-title">Order Details</div>
        <div class="info-row">
            <span class="label">Order Date</span>
            <span class="value"><?php echo date('d M Y, h:i A', strtotime($order['order_date'] ?? $order['created_at'])); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Payment Method</span>
            <span class="value"><?php echo strtoupper($order['payment_method'] ?? 'N/A'); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Payment Status</span>
            <span class="value"><?php echo ucfirst($order['payment_status'] ?? 'N/A'); ?></span>
        </div>
    </div>

    <!-- Items -->
    <div class="info-card">
        <div class="info-card-title">Items Ordered</div>
        <?php while($item = mysqli_fetch_assoc($items_q)): ?>
        <div class="item-row">
            <div style="display:flex; align-items:center; gap:14px; flex:1;">
                <div>
                    <div class="item-name"><?php echo htmlspecialchars($item['product_name'] ?? 'Product'); ?></div>
                    <div class="item-qty">Qty: <?php echo $item['quantity']; ?> &nbsp;·&nbsp; ₹<?php echo number_format($item['price'], 2); ?> each</div>
                </div>
            </div>
            <div class="item-price">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
        </div>
        <?php endwhile; ?>

        <div class="grand-row">
            <span>Grand Total</span>
            <span>₹<?php echo number_format($order['total_amount'] ?? $order['total_price'], 2); ?></span>
        </div>
    </div>

    <a href="invoice.php?order_id=<?php echo $order_id; ?>" class="btn-invoice">🧾 Download Invoice</a>

</div>

</body>
</html>
