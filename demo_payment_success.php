<?php
session_start();

$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : "ORD".rand(1000,9999);
$total = isset($_POST['total']) ? $_POST['total'] : 0;
$payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : "Unknown";

/* Generate Demo Transaction ID */
$txn_id = "TXN".rand(100000,999999);

/* Payment Date */
$date = date("d M Y, h:i A");

// Store success data temporarily for the invoice page
$_SESSION['invoice_order_id'] = $order_id;
$_SESSION['invoice_total'] = $total;
$_SESSION['invoice_payment_type'] = $payment_type;
$_SESSION['invoice_txn'] = $txn_id;
$_SESSION['invoice_date'] = $date;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Success - DESIAROMA</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background: #0a0a0a;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    overflow: hidden;
}

.success-container {
    background: #111;
    width: 500px;
    padding: 50px 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 15px 40px rgba(0, 255, 136, 0.15);
    border: 1px solid rgba(0, 255, 136, 0.3);
    position: relative;
    z-index: 10;
    animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transform: translateY(50px);
    opacity: 0;
}

@keyframes slideUp {
    to { transform: translateY(0); opacity: 1; }
}

/* --- ANIMATED CHECKMARK --- */
.checkmark-circle {
    width: 100px;
    height: 100px;
    position: relative;
    display: inline-block;
    vertical-align: top;
    border-radius: 50%;
    margin-bottom: 25px;
}
.checkmark-circle .background {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #00ff88;
    position: absolute;
    box-shadow: 0 0 30px rgba(0,255,136,0.6);
}
.checkmark-circle .checkmark {
    border-radius: 5px;
}
.checkmark-circle .checkmark.draw:after {
    animation-delay: 100ms;
    animation-duration: 1s;
    animation-timing-function: ease;
    animation-name: checkmark;
    transform: scaleX(-1) rotate(135deg);
    animation-fill-mode: forwards;
}
.checkmark-circle .checkmark:after {
    opacity: 1;
    height: 50px;
    width: 25px;
    transform-origin: left top;
    border-right: 6px solid #111;
    border-top: 6px solid #111;
    border-radius: 2.5px !important;
    content: '';
    left: 20px;
    top: 52px;
    position: absolute;
}

@keyframes checkmark {
    0% { height: 0; width: 0; opacity: 1; }
    20% { height: 0; width: 25px; opacity: 1; }
    40% { height: 50px; width: 25px; opacity: 1; }
    100% { height: 50px; width: 25px; opacity: 1; }
}

h1 {
    color: #00ff88;
    margin: 0 0 10px 0;
    font-size: 32px;
}

p.subtitle {
    color: #ccc;
    font-size: 15px;
    margin-bottom: 30px;
}

.details-box {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 20px;
    text-align: left;
    margin-bottom: 30px;
}

.row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}
.row:last-child { margin-bottom: 0; }
.label { color: #aaa; }
.value { color: #fff; font-weight: 600; }
.value.amount { color: #ffd000; font-size: 18px; }

.btn-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.btn {
    display: inline-block;
    padding: 15px 30px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 16px;
    text-decoration: none;
    transition: 0.3s;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: #ffd000;
    color: #000;
    box-shadow: 0 5px 15px rgba(255, 208, 0, 0.3);
}
.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 208, 0, 0.5);
    background: #ffdf4d;
}

.btn-secondary {
    background: transparent;
    color: #ffd000;
    border: 2px solid #ffd000;
}
.btn-secondary:hover {
    background: rgba(255,208,0,0.1);
}

/* Simple Confetti Effect overlay */
.confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #f00;
    animation: fall 3s linear infinite;
    z-index: 0;
}
@keyframes fall {
    to { transform: translateY(100vh) rotate(720deg); }
}

</style>
</head>
<body>

<!-- Confetti Canvas (Simple JS array) -->
<div id="confetti-container"></div>

<div class="success-container">
    <div class="checkmark-circle">
        <div class="background"></div>
        <div class="checkmark draw"></div>
    </div>

    <h1>Payment Successful!</h1>
    <p class="subtitle">Thank you for your purchase. Your order is confirmed.</p>

    <div class="details-box">
        <div class="row">
            <span class="label">Transaction ID</span>
            <span class="value"><?php echo $txn_id; ?></span>
        </div>
        <div class="row">
            <span class="label">Order ID</span>
            <span class="value"><?php echo $order_id; ?></span>
        </div>
        <div class="row">
            <span class="label">Payment Method</span>
            <span class="value"><?php echo $payment_type; ?></span>
        </div>
        <div class="row">
            <span class="label">Date</span>
            <span class="value"><?php echo $date; ?></span>
        </div>
        <div class="row" style="margin-top:10px; padding-top:10px; border-top:1px solid rgba(255,255,255,0.1);">
            <span class="label">Amount Paid</span>
            <span class="value amount">₹<?php echo number_format($total, 2); ?></span>
        </div>
    </div>

    <div class="btn-group">
        <a href="invoice.php" target="_blank" class="btn btn-primary">Download Invoice</a>
        <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    </div>
</div>

<script>
    // Confetti Generator
    const colors = ['#00ff88', '#ffd000', '#ff0055', '#00d4ff'];
    const container = document.getElementById('confetti-container');
    
    for (let i = 0; i < 50; i++) {
        let conf = document.createElement('div');
        conf.classList.add('confetti');
        conf.style.left = Math.random() * 100 + 'vw';
        conf.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        conf.style.animationDuration = (Math.random() * 3 + 2) + 's';
        conf.style.animationDelay = Math.random() * 2 + 's';
        container.appendChild(conf);
    }
</script>

</body>
</html>