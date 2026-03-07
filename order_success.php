<?php
session_start();

if(!isset($_SESSION['last_order_id'])){
    header("Location: index.php");
    exit();
}

$order_id = $_SESSION['last_order_id'];
unset($_SESSION['last_order_id']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Confirmed - DESIAROMA</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f4f4;
}

/* HEADER */

header{
background:#000;
color:#ffd000;
padding:16px;
text-align:center;
font-size:22px;
font-weight:bold;
}

/* CENTER SECTION */

.success-container{
height:80vh;
display:flex;
justify-content:center;
align-items:center;
}

/* SUCCESS BOX */

.success-box{
background:white;
padding:40px;
width:420px;
text-align:center;
border-radius:10px;
box-shadow:0 8px 25px rgba(0,0,0,0.15);
}

.success-box h2{
color:#000;
margin-bottom:10px;
}

.success-icon{
font-size:50px;
color:#ffd000;
margin-bottom:10px;
}

.order-id{
background:#fff6cc;
padding:10px;
border-radius:6px;
margin:15px 0;
font-weight:bold;
}

/* BUTTON */

.btn{
background:#ffd000;
border:none;
padding:12px 20px;
font-weight:bold;
border-radius:6px;
cursor:pointer;
font-size:15px;
}

.btn:hover{
background:#f2c800;
}

</style>

</head>

<body>

<header>DESIAROMA</header>

<div class="success-container">

<div class="success-box">

<div class="success-icon">✔</div>

<h2>Order Confirmed</h2>

<p>Your order has been placed successfully.</p>

<div class="order-id">
Order ID: #<?php echo $order_id; ?>
</div>

<a href="index.php">
<button class="btn">Continue Shopping</button>
</a>

</div>

</div>

</body>
</html>