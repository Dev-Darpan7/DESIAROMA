<?php
session_start();

$order_id = $_POST['order_id'];
$total = $_POST['total'];
$payment_type = $_POST['payment_type'];
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
</style>

</head>

<body>

<div class="box">

<h1>Payment Successful</h1>

<p><b>Order ID:</b> <?php echo $order_id; ?></p>

<p><b>Payment Method:</b> <?php echo $payment_type; ?></p>

<p><b>Amount Paid:</b> ₹<?php echo number_format($total,2); ?></p>

<a href="index.php" class="btn">Continue Shopping</a>

</div>

</body>
</html>