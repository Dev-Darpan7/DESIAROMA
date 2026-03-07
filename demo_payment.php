<?php
session_start();
include 'config.php';

if(!isset($_SESSION['demo_order_id'], $_SESSION['demo_total'])){
    header("Location: checkout.php");
    exit();
}

$order_id = $_SESSION['demo_order_id'];
$total = $_SESSION['demo_total'];

/* YOUR UPI ID HERE */
$upi_id = "yourupi@upi";

/* UPI PAYMENT LINK */
$upi_link = "upi://pay?pa=".$upi_id."&pn=DESIAROMA&am=".$total."&cu=INR";

/* QR CODE GENERATOR */
$qr_code = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=".urlencode($upi_link);
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment - DESIAROMA</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f5f5f5;
}

/* HEADER */

header{
background:#000;
color:#ffd000;
padding:16px;
font-size:22px;
font-weight:bold;
text-align:center;
}

/* MAIN CONTAINER */

.payment-container{
width:1000px;
margin:40px auto;
display:flex;
background:white;
border-radius:10px;
box-shadow:0 8px 25px rgba(0,0,0,0.15);
overflow:hidden;
}

/* LEFT PANEL */

.left-panel{
width:30%;
background:#111;
color:#ffd000;
padding:30px;
}

.summary{
background:#ffd000;
color:#000;
padding:20px;
border-radius:8px;
margin-top:20px;
}

.summary h2{
margin:5px 0;
}

/* PAYMENT METHODS */

.middle-panel{
width:30%;
border-right:1px solid #eee;
padding:20px;
}

.payment-method{
display:flex;
justify-content:space-between;
align-items:center;
padding:16px;
border-radius:8px;
border:1px solid #ddd;
margin-bottom:12px;
cursor:pointer;
transition:0.25s;
font-size:16px;
}

.payment-method:hover{
background:#fff9d6;
border-color:#ffd000;
}

.payment-method.active{
background:#fff3c4;
border:2px solid #ffd000;
}

/* RIGHT PANEL */

.right-panel{
width:40%;
padding:25px;
min-height:380px;
}

/* SECTIONS */

.section{
display:none;
min-height:220px;
}

.section.active{
display:block;
}

/* QR BOX */

.qr-box{
border:1px dashed #ccc;
padding:25px;
text-align:center;
border-radius:8px;
margin-bottom:15px;
background:#fafafa;
}

input,select{
width:100%;
padding:11px;
margin-top:10px;
border:1px solid #ccc;
border-radius:6px;
font-size:14px;
}

/* PAY BUTTON */

.pay-btn{
background:#ffd000;
border:none;
padding:13px;
margin-top:20px;
width:100%;
border-radius:6px;
font-weight:bold;
cursor:pointer;
font-size:15px;
transition:0.2s;
}

.pay-btn:hover{
background:#f2c800;
transform:scale(1.02);
}

</style>

<script>

function showSection(type,id,element){

document.getElementById("payment_type").value = type;

document.querySelectorAll(".section").forEach(function(sec){
sec.classList.remove("active");
});

document.getElementById(id).classList.add("active");

document.querySelectorAll(".payment-method").forEach(function(card){
card.classList.remove("active");
});

element.classList.add("active");

}

window.onload = function(){
showSection('UPI','upi',document.getElementById('upiBtn'));
}

</script>

</head>

<body>

<header>DESIAROMA</header>

<form action="demo_payment_success.php" method="POST">

<div class="payment-container">

<!-- LEFT PANEL -->

<div class="left-panel">

<h3>DESIAROMA</h3>

<div class="summary">
Price Summary  
<h2>₹<?php echo number_format($total,2); ?></h2>
</div>

<br>

Order ID: <?php echo $order_id; ?>

</div>

<!-- PAYMENT METHODS -->

<div class="middle-panel">

<div class="payment-method" id="upiBtn" onclick="showSection('UPI','upi',this)">
<span>UPI</span>
</div>

<div class="payment-method" onclick="showSection('Card','card',this)">
<span>Cards</span>
</div>

<div class="payment-method" onclick="showSection('Netbanking','bank',this)">
<span>Netbanking</span>
</div>

<div class="payment-method" onclick="showSection('Wallet','wallet',this)">
<span>Wallet</span>
</div>

<input type="hidden" name="payment_type" id="payment_type">

<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
<input type="hidden" name="total" value="<?php echo $total; ?>">

</div>

<!-- PAYMENT DETAILS -->

<div class="right-panel">

<h3>Payment Details</h3>

<!-- UPI -->

<div id="upi" class="section">

<div class="qr-box">
<img src="images/Qr.jpg" width="170">><br><br>
Scan with any UPI App
</div>

Enter UPI ID
<input type="text" name="upi_id" placeholder="example@upi">

</div>

<!-- CARD -->

<div id="card" class="section">

Card Number
<input type="text" name="card_number" placeholder="1234 5678 9012 3456">

Expiry
<input type="text" name="expiry" placeholder="MM/YY">

CVV
<input type="password" name="cvv" placeholder="123">

</div>

<!-- BANK -->

<div id="bank" class="section">

Select Bank
<select name="bank">
<option>SBI</option>
<option>HDFC</option>
<option>ICICI</option>
<option>Axis Bank</option>
</select>

</div>

<!-- WALLET -->

<div id="wallet" class="section">

Select Wallet
<select name="wallet">
<option>Paytm</option>
<option>PhonePe</option>
<option>Amazon Pay</option>
</select>

</div>

<button class="pay-btn">Pay Now</button>

</div>

</div>

</form>

</body>
</html>