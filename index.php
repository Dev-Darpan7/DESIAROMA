<?php 
session_start();
include 'config.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DESIAROMA | Luxury Perfumes</title>
<link rel="stylesheet" href="style.css">

<style>

/* NAVBAR */
header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 60px;
    background:#000;
}

.logo{
    color:gold;
    font-size:24px;
    font-weight:bold;
}

nav ul{
    display:flex;
    list-style:none;
    gap:22px;
    align-items:center;
    overflow:visible;
}

nav ul li {
    position:relative;
    overflow:visible;
    list-style:none;
}

nav ul li a{
    text-decoration:none;
    color:white;
    font-size:15px;
}

nav ul li a:hover{
    color:gold;
}

/* ===== PROFILE DROPDOWN ===== */
.profile-wrapper {
    position: relative;
}

.profile-trigger {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 215, 0, 0.08);
    border: 1px solid rgba(255, 215, 0, 0.25);
    border-radius: 40px;
    padding: 6px 16px 6px 6px;
    cursor: pointer;
    color: #f0f0f0;
    transition: all 0.2s ease;
}

.profile-trigger:hover {
    border-color: gold;
    background: rgba(255, 215, 0, 0.12);
}

.profile-avatar-pill {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ffd700 0%, #b8860b 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.profile-greeting {
    font-size: 13px;
    font-weight: 500;
    color: #e8e8e8;
    max-width: 110px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.profile-chevron {
    width: 15px;
    height: 15px;
    color: #999;
    flex-shrink: 0;
    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1), color 0.25s;
    display: block;
}

.profile-wrapper.open .profile-chevron {
    transform: rotate(180deg);
    color: gold;
}

/* Dropdown Panel */
.profile-dropdown {
    position: absolute;
    top: calc(100% + 15px);
    right: 0;
    width: 280px;
    background: #0a0a0a;
    border: 1px solid rgba(255, 215, 0, 0.2);
    border-radius: 12px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.8);
    z-index: 10000;
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.profile-wrapper.open .profile-dropdown {
    display: flex;
}

.dropdown-user-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: rgba(255, 215, 0, 0.03);
    border-bottom: 1px solid rgba(255, 215, 0, 0.1);
}

.dd-menu {
    list-style: none;
    padding: 8px 0;
    margin: 0;
}

.dd-menu li {
    padding: 0;
    margin: 0;
}

.dd-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    text-decoration: none;
    color: #ccc;
    font-size: 14px;
    transition: 0.2s;
}

.dd-item:hover {
    background: rgba(255, 215, 0, 0.08);
    color: gold;
}

.dd-logout {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border-top: 1px solid rgba(255, 215, 0, 0.1);
    text-decoration: none;
    color: #ff6b6b;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s;
}

.dd-logout:hover {
    background: rgba(255, 60, 60, 0.05);
}

</style>

</head>
<body id="top">

<header>

<div class="logo">DESIAROMA</div>

<nav>
<ul>

<li><a href="index.php#top">Home</a></li>
<li><a href="shop.php">Shop</a></li>
<li><a href="index.php#about">About</a></li>

<?php if(isset($_SESSION['user_id'])):
    $initials = strtoupper(substr($_SESSION['user_name'], 0, 1));
?>
<li>
    <div class="profile-wrapper" id="profileWrapper">
        <button class="profile-trigger" id="profileTrigger" aria-haspopup="true" aria-expanded="false">
            <div class="profile-avatar-pill"><?php echo $initials; ?></div>
            <span class="profile-greeting"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <svg class="profile-chevron" viewBox="0 0 24 24" fill="none">
                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="profile-dropdown" id="profileDropdown" role="menu">
            <!-- Header -->
            <div class="dropdown-user-header">
                <div class="profile-avatar-pill" style="width:40px; height:40px; font-size:16px;"><?php echo $initials; ?></div>
                <div>
                    <div style="color:white; font-weight:700; font-size:15px;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                    <div style="color:gold; font-size:11px;">Member</div>
                </div>
            </div>

            <!-- Menu -->
            <ul class="dd-menu">
                <li>
                    <a href="profile.php?tab=account" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" style="width:16px;"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="profile.php?tab=orders" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" style="width:16px;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        My Orders
                    </a>
                </li>
                <li>
                    <a href="shop.php" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" style="width:16px;"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        Shop Collection
                    </a>
                </li>
            </ul>

            <!-- Logout -->
            <a href="logout.php" class="dd-logout">
                <svg viewBox="0 0 24 24" fill="none" style="width:16px;"><path d="M17 16l4-4m0 0l-4-4m4 4H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Sign Out
            </a>
        </div>
    </div>
</li>

<?php else: ?>

<li><a href="login.php">Login</a></li>
<li><a href="register.php">Register</a></li>

<?php endif; ?>

</ul>
</nav>

</header>


<!-- Hero Section -->
<section class="hero">
<h1>Discover Your Signature Scent</h1>
<p>Premium fragrances crafted for elegance and confidence.</p>

<button onclick="window.location='shop.php';">
Shop Now
</button>

</section>


<!-- Products Section -->
<section id="products" class="products">

<h2>Our Collection</h2>

<div class="product-container">

<?php
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 4");

while($row = mysqli_fetch_assoc($result)) {

$webPath = $row['image'];

if(strpos($webPath, 'images/') !== 0){
$webPath = "images/" . $webPath;
}

if(empty($row['image'])){
$webPath = "images/default.png";
}
?>

<div class="card">

<a href="product.php?id=<?php echo $row['id']; ?>">
<img src="<?php echo $webPath; ?>" 
alt="<?php echo htmlspecialchars($row['product_name']); ?>">
</a>

<h3>
<a href="product.php?id=<?php echo $row['id']; ?>" style="text-decoration:none;color:gold;">
<?php echo htmlspecialchars($row['product_name']); ?>
</a>
</h3>

<p>₹<?php echo number_format($row['price'],2); ?></p>

<?php if(isset($_SESSION['user_id'])): ?>

<form method="POST" action="cart.php">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
<button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<?php else: ?>

<a href="login.php">
<button type="button">Login to Add</button>
</a>

<?php endif; ?>

</div>

<?php } ?>

</div>


<div style="text-align:center; margin-top:20px;">
<a href="shop.php">
<button class="checkout-btn">View All Products</button>
</a>
</div>

</section>


<!-- About Section -->
<section id="about" class="about">

<h2>About DESIAROMA</h2>

<p>
DESIAROMA brings you luxury fragrances inspired by Indian heritage and modern elegance.
Our perfumes are long-lasting, premium quality, and designed to leave a lasting impression.
</p>

</section>


<footer style="background:#000; color:#ccc; text-align:center; padding:25px 10px; margin-top:40px;">

<p style="color:gold; font-weight:500; margin-bottom:8px;">DESIAROMA</p>

<p style="font-size:14px; margin-bottom:8px;">
Luxury perfumes crafted for elegance and confidence.
</p>

<p style="font-size:13px;">© 2026 DESIAROMA • All Rights Reserved</p>

</footer>

<script>
(function(){
    var trigger  = document.getElementById('profileTrigger');
    var wrapper  = document.getElementById('profileWrapper');
    if(!trigger) return;

    trigger.addEventListener('click', function(e){
        e.stopPropagation();
        var open = wrapper.classList.toggle('open');
        trigger.setAttribute('aria-expanded', open);
    });

    document.addEventListener('click', function(e){
        if(wrapper && !wrapper.contains(e.target)){
            wrapper.classList.remove('open');
            trigger.setAttribute('aria-expanded','false');
        }
    });

    document.addEventListener('keydown', function(e){
        if(e.key==='Escape'){
            wrapper.classList.remove('open');
            trigger.setAttribute('aria-expanded','false');
        }
    });
})();
</script>

</body>
</html>