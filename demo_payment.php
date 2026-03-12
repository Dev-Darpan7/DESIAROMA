<?php
session_start();
include 'config.php';

if(!isset($_SESSION['demo_order_id'], $_SESSION['demo_total'])){
    header("Location: checkout.php");
    exit();
}

$order_id = $_SESSION['demo_order_id'];
$total = $_SESSION['demo_total'];

$upi_id = "yourupi@ybl"; // Fake UPI
$upi_name = "DESIAROMA";
// Using UPILINK format: upi://pay?pa=UPI_ID&pn=NAME&am=AMOUNT
$upi_link = "upi://pay?pa=".$upi_id."&pn=".$upi_name."&am=".$total."&cu=INR";

// Generate QR Code URL using free API
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($upi_link);
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

/* REALISTIC UPI STYLES */
.upi-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.upi-tab {
    flex: 1;
    padding: 10px;
    text-align: center;
    background: #eee;
    cursor: pointer;
    border-radius: 6px;
    font-size: 14px;
    font-weight: bold;
}
.upi-tab.active {
    background: #ffd000;
    color: #000;
}
.upi-content {
    display: none;
}
.upi-content.active {
    display: block;
    text-align: center;
}
.qr-box {
    margin: 15px auto;
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    display: inline-block;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.qr-box img {
    width: 180px;
    height: 180px;
}
.upi-apps {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}
.upi-app {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #eee;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
}

/* ADDED FULLSCREEN LOADER FOR IMPRESSIVE DEMO */
.overlay-loader {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.9);
    z-index: 9999;
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #ffd000;
    backdrop-filter: blur(8px);
}

.spinner {
    width: 70px;
    height: 70px;
    border: 6px solid rgba(255,208,0,0.2);
    border-top-color: #ffd000;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 25px;
    transition: 0.3s;
}

@keyframes spin { 100% { transform: rotate(360deg); } }

.loading-text {
    font-size: 24px;
    font-weight: bold;
    letter-spacing: 1.5px;
    transition: 0.3s;
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

function showUpiTab(tabId, element) {
    document.querySelectorAll('.upi-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.upi-content').forEach(c => c.classList.remove('active'));
    
    element.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

function processPayment(){

let type=document.getElementById("payment_type").value;

/* UPI */

if(type==="UPI"){
    
    // Check which tab is active
    let activeTab = document.querySelector(".upi-tab.active").innerText;
    
    if(activeTab === "Enter UPI ID") {
        let upi=document.querySelector("input[name='upi_id']").value;
        if(upi.trim()===""){
            alert("Please enter a valid UPI ID (e.g. name@okhdfcbank)");
            return false;
        }
    }
    
    // Instead of forcing mobile app open, we show the processing animation
    startProcessing();
    return false;

}

/* CARD */

if(type==="Card"){

let card=document.querySelector("input[name='card_number']").value;
let exp=document.querySelector("input[name='expiry']").value;
let cvv=document.querySelector("input[name='cvv']").value;

if(card.length < 16){
alert("Enter valid 16-digit card number");
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
btn.disabled=true;

let overlay = document.getElementById("overlayLoader");
let text = document.getElementById("loadingText");
let spinner = document.querySelector(".spinner");

overlay.style.display = "flex";

text.innerText = "Initiating Secure Connection...";

setTimeout(() => { text.innerText = "Connecting to Bank Gateway..."; }, 1500);
setTimeout(() => { text.innerText = "Verifying Payment Details..."; }, 3000);
setTimeout(() => { text.innerText = "Authorizing Transaction..."; }, 4500);

setTimeout(() => { 
    text.innerText = "Payment Approved! Redirecting..."; 
    text.style.color = "#00ff88"; // Success Green
    spinner.style.borderTopColor = "#00ff88";
    spinner.style.borderColor = "rgba(0, 255, 136, 0.2)";
}, 6000);

setTimeout(function(){
    document.getElementById("paymentForm").submit();
}, 7500);

}

</script>

</head>

<body>

<!-- FULLSCREEN ANIMATED LOADER -->
<div id="overlayLoader" class="overlay-loader">
    <div class="spinner"></div>
    <div id="loadingText" class="loading-text">Processing Payment...</div>
</div>

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

    <div class="upi-tabs">
        <div class="upi-tab active" onclick="showUpiTab('upi_qr', this)">Scan QR</div>
        <div class="upi-tab" onclick="showUpiTab('upi_id_box', this)">Enter UPI ID</div>
    </div>

    <!-- QR CODE SECTION -->
    <div id="upi_qr" class="upi-content active">
        <p style="font-size:14px; color:#555;">Scan with any UPI App</p>
        <div class="qr-box">
            <img src="<?php echo $qr_url; ?>" alt="UPI QR Code">
        </div>
        <p style="font-size:16px; font-weight:bold;">₹<?php echo number_format($total,2); ?></p>
        
        <div class="upi-apps">
            <div class="upi-app" style="background:#ea4335; color:white;">GPay</div>
            <div class="upi-app" style="background:#5f259f; color:white;">PhonePe</div>
            <div class="upi-app" style="background:#00baf2; color:white;">Paytm</div>
        </div>
    </div>
    
    <!-- UPI ID SECTION -->
    <div id="upi_id_box" class="upi-content" style="text-align:left;">
        <p style="margin-bottom:10px;">Enter your Virtual Payment Address (VPA)</p>
        <input type="text" name="upi_id" placeholder="example@okhdfcbank">
        <p style="font-size:12px; color:#777; margin-top:10px;">A payment request will be sent to this UPI ID.</p>
    </div>

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