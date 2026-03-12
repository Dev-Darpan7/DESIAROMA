<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login to view invoice.");
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['invoice_order_id']) ? intval($_SESSION['invoice_order_id']) : 0);

if ($order_id == 0) {
    die("Order not found.");
}

/* Fetch Order */
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'");
if(mysqli_num_rows($order_query) == 0){
    die("Order not found or access denied.");
}
$order = mysqli_fetch_assoc($order_query);

/* Fetch User */
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

/* Fetch Items */
$items_query = mysqli_query($conn, "
    SELECT oi.*, p.product_name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = '$order_id'
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice #<?php echo $order_id; ?> - DESIAROMA</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background: #e9ecef;
    margin: 0;
    padding: 40px 20px;
    color: #333;
}

.invoice-container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 50px;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.logo {
    font-family: 'Cinzel', serif;
    font-size: 32px;
    color: #b89000;
    margin: 0;
}

.invoice-title {
    font-size: 36px;
    color: #222;
    margin: 0;
    text-align: right;
    font-weight: 700;
}

.invoice-details {
    text-align: right;
    color: #777;
    font-size: 14px;
    margin-top: 5px;
}

.billing-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
}

.billing-col {
    width: 45%;
}

.billing-col h3 {
    margin-top: 0;
    font-size: 14px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.billing-col p {
    margin: 5px 0;
    font-size: 15px;
    line-height: 1.5;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

th {
    background: #f8f9fa;
    color: #333;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #ddd;
}

td {
    padding: 15px 12px;
    border-bottom: 1px solid #eee;
    color: #555;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.total-section {
    width: 50%;
    float: right;
    background: #fdfdfd;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #eee;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 15px;
    color: #666;
}

.total-row.grand-total {
    font-size: 22px;
    font-weight: 700;
    color: #222;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid #eee;
}

.clear { clear: both; }

.footer-notes {
    margin-top: 50px;
    text-align: center;
    color: #888;
    font-size: 13px;
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
}

/* Print Button */
.no-print {
    text-align: center;
    margin-bottom: 20px;
}

.btn-print {
    background: #b89000;
    color: #fff;
    border: none;
    padding: 12px 30px;
    font-size: 16px;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 4px 10px rgba(184, 144, 0, 0.3);
    transition: 0.3s;
}

.btn-print:hover {
    background: #967500;
    transform: translateY(-2px);
}

/* PRINT MEDIA QUERY */
@media print {
    body {
        background: #fff;
        padding: 0;
    }
    .invoice-container {
        box-shadow: none;
        padding: 0;
    }
    .no-print {
        display: none;
    }
}
</style>
</head>
<body>

<div class="no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Print Invoice</button>
</div>

<div class="invoice-container">

    <div class="header">
        <div>
            <h1 class="logo">DESIAROMA</h1>
            <p style="color:#777; font-size:13px; margin:5px 0;">Luxury Perfumes & Fragrances</p>
        </div>
        <div>
            <h1 class="invoice-title">INVOICE</h1>
            <div class="invoice-details">
                <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
                <p><strong>Date:</strong> <?php echo date("d M Y", strtotime($order['order_date'])); ?></p>
                <p><strong>Status:</strong> <?php echo $order['payment_status']; ?></p>
            </div>
        </div>
    </div>

    <div class="billing-info">
        <div class="billing-col">
            <h3>Billed To</h3>
            <p><strong><?php echo htmlspecialchars($user['name']); ?></strong></p>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
            <p><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
        </div>

        <div class="billing-col">
            <h3>Shipping Address</h3>
            <p><?php echo nl2br(htmlspecialchars($user['address'] ?? 'Address not provided')); ?></p>
            <?php if(!empty($user['city'])): ?>
                <p><?php echo htmlspecialchars($user['city'] . ", " . $user['state'] . " - " . $user['pincode']); ?></p>
            <?php elseif(!empty($user['pincode'])): ?>
                <p>Pincode: <?php echo htmlspecialchars($user['pincode']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Description</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $subtotal = 0;
            while($item = mysqli_fetch_assoc($items_query)): 
                $amount = $item['price'] * $item['quantity'];
                $subtotal += $amount;
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                <td class="text-center"><?php echo $item['quantity']; ?></td>
                <td class="text-right">₹<?php echo number_format($item['price'], 2); ?></td>
                <td class="text-right">₹<?php echo number_format($amount, 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span>Subtotal</span>
            <span>₹<?php echo number_format($subtotal, 2); ?></span>
        </div>
        <div class="total-row">
            <span>Shipping</span>
            <span>Free</span>
        </div>
        <div class="total-row">
            <span>Tax (Included)</span>
            <span>₹0.00</span>
        </div>
        <div class="total-row grand-total">
            <span>Total</span>
            <span>₹<?php echo number_format($order['total_amount'], 2); ?></span>
        </div>
    </div>
    
    <div class="clear"></div>

    <div class="footer-notes">
        <p><strong>Thank you for your business!</strong></p>
        <p>If you have any questions about this invoice, please contact support at support@desiaroma.com</p>
    </div>

</div>

</body>
</html>
