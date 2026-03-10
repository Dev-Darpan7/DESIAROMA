<?php
session_start();
include 'config.php';

if(!isset($_SESSION['demo_order_id'], $_SESSION['demo_total'])){
    header("Location: checkout.php");
    exit();
}

$order_id = $_SESSION['demo_order_id'];
$total = $_SESSION['demo_total'];

$upi_id = "yourupi@upi";
$upi_link = "upi://pay?pa=".$upi_id."&pn=DESIAROMA&am=".$total."&cu=INR";
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

header{
background:#000;
color:#ffd000;
padding:16px;
font-size:22px;
font-weight:bold;
text-align:center;
}

.payment-container{
width:1000px;
height:520px;
margin:40px auto;
display:flex;
background:white;
border-radius:10px;
box-shadow:0 8px 25px rgba(0,0,0,0.15);
overflow:hidden;
}

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

.middle-panel{
width:30%;
border-right:1px solid #eee;
padding:20px;
}

.payment-method{
padding:14px;
border:1px solid #ddd;
border-radius:8px;
margin-bottom:12px;
cursor:pointer;
transition:0.2s;
}

.payment-method.active{
background:#fff3c4;
border:2px solid #ffd000;
}

.right-panel{
width:40%;
padding:25px;
overflow-y:auto;
}

.section{
display:none;
}

.section.active{
display:block;
}

input,select{
width:100%;
padding:10px;
margin-top:10px;
border:1px solid #ccc;
border-radius:6px;
}

.pay-btn{
background:#ffd000;
border:none;
padding:13px;
margin-top:20px;
width:100%;
border-radius:6px;
font-weight:bold;
cursor:pointer;
}

.loader{
display:none;
text-align:center;
margin-top:20px;
font-weight:bold;
}

.otp-box{
display:none;
margin-top:15px;
padding:15px;
background:#f9f9f9;
border:1px solid #ddd;
border-radius:6px;
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

document.getElementById("otpBox").style.display="none";

let btn=document.querySelector(".pay-btn");
btn.innerHTML="Pay Now";
btn.disabled=false;

document.getElementById("loader").style.display="none";

}

window.onload=function(){
showSection('UPI','upi',document.getElementById('upiBtn'));
}

function processPayment(){

let type=document.getElementById("payment_type").value;

/* UPI */

if(type==="UPI"){

let upi=document.querySelector("input[name='upi_id']").value;

if(upi.trim()===""){
alert("Enter UPI ID");
return false;
}

window.location.href="<?php echo $upi_link; ?>";
return false;

}

/* CARD */

if(type==="Card"){

let card=document.querySelector("input[name='card_number']").value;
let exp=document.querySelector("input[name='expiry']").value;
let cvv=document.querySelector("input[name='cvv']").value;

if(card.length < 16){
alert("Enter valid card number");
return false;
}

if(exp===""){
alert("Enter expiry date");
return false;
}

if(cvv.length < 3){
alert("Enter valid CVV");
return false;
}

document.getElementById("otpBox").style.display="block";
document.getElementById("otp").focus();

return false;

}

/* NETBANKING + WALLET */

startProcessing();
return false;

}

function verifyOTP(){

let otp=document.getElementById("otp").value;

if(otp==="123456"){

startProcessing();

}else{

alert("Invalid OTP. Use 123456 for demo.");

}

}

function startProcessing(){

let btn=document.querySelector(".pay-btn");

btn.innerHTML="Processing Payment...";
btn.disabled=true;

document.getElementById("loader").style.display="block";

setTimeout(function(){

document.getElementById("paymentForm").submit();

},2000);

}

</script>

</head>

<body>

<header>DESIAROMA</header>

<form id="paymentForm" action="demo_payment_success.php" method="POST" onsubmit="return processPayment();">

<div class="payment-container">

<div class="left-panel">

<h3>DESIAROMA</h3>

<div class="summary">
Price Summary  
<h2>₹<?php echo number_format($total,2); ?></h2>
</div>

<br>

Order ID: <?php echo $order_id; ?>

</div>

<div class="middle-panel">

<div class="payment-method" id="upiBtn" onclick="showSection('UPI','upi',this)">UPI</div>
<div class="payment-method" onclick="showSection('Card','card',this)">Cards</div>
<div class="payment-method" onclick="showSection('Netbanking','bank',this)">Netbanking</div>
<div class="payment-method" onclick="showSection('Wallet','wallet',this)">Wallet</div>

<input type="hidden" name="payment_type" id="payment_type">
<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
<input type="hidden" name="total" value="<?php echo $total; ?>">

</div>

<div class="right-panel">

<h3>Payment Details</h3>

<div id="upi" class="section">

Enter UPI ID
<input type="text" name="upi_id" placeholder="example@upi">

</div>

<div id="card" class="section">

Card Number
<input type="text" name="card_number" placeholder="1234 5678 9012 3456">

Expiry
<input type="text" name="expiry" placeholder="MM/YY">

CVV
<input type="password" name="cvv" placeholder="123">

<div id="otpBox" class="otp-box">

Enter OTP (Demo OTP: 123456)
<input type="text" id="otp" placeholder="6 digit OTP">

<button type="button" onclick="verifyOTP()" class="pay-btn">Verify OTP</button>

</div>

</div>

<div id="bank" class="section">

Select Bank
<select name="bank">
<option>SBI</option>
<option>HDFC</option>
<option>ICICI</option>
<option>Axis Bank</option>
</select>

</div>

<div id="wallet" class="section">

Select Wallet
<select name="wallet">
<option>Paytm</option>
<option>PhonePe</option>
<option>Amazon Pay</option>
</select>

</div>

<button class="pay-btn">Pay Now</button>

<div id="loader" class="loader">
Processing Payment...
</div>

</div>

</div>

</form>

</body>
</html>