<?php
session_start();

$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : "ORD".rand(1000,9999);
$total = isset($_POST['total']) ? $_POST['total'] : 0;
$payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : "Unknown";

/* Generate Demo Transaction ID */
$txn_id = "TXN".rand(100000,999999);

/* Payment Date */
$date = date("d M Y, h:i A");
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Success</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
text-align:center;
padding:60px;
}

.box{
background:white;
width:450px;
margin:auto;
padding:40px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,0.2);
}

h1{
color:green;
margin-bottom:10px;
}

.success-icon{
font-size:50px;
color:green;
margin-bottom:10px;
}

p{
font-size:15px;
margin:8px 0;
}

.line{
height:1px;
background:#eee;
margin:20px 0;
}

.btn{
display:inline-block;
margin-top:20px;
padding:12px 25px;
background:#ffd000;
color:black;
text-decoration:none;
border-radius:6px;
font-weight:bold;
}

.btn:hover{
background:#f2c800;
}

</style>

</head>

<body>

<div class="box">

<div class="success-icon">✓</div>

<h1>Payment Successful</h1>

<p>Your payment has been processed successfully.</p>

<div class="line"></div>

<p><b>Transaction ID:</b> <?php echo $txn_id; ?></p>

<p><b>Order ID:</b> <?php echo $order_id; ?></p>

<p><b>Payment Method:</b> <?php echo $payment_type; ?></p>

<p><b>Amount Paid:</b> ₹<?php echo number_format($total,2); ?></p>

<p><b>Date:</b> <?php echo $date; ?></p>

<div class="line"></div>

<a href="index.php" class="btn">Continue Shopping</a>

</div>

</body>
</html>