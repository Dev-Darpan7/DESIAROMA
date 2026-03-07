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
</head>
<body id="top">

<header>
    <div class="logo">DESIAROMA</div>
    <nav>
        <ul>
            <li><a href="index.php#top">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="index.php#about">About</a></li>
            <li><a href="index.php#contact">Contact</a></li>
          
            <?php if(isset($_SESSION['user_id'])): ?>
                <li style="color:gold">
                    Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </li>

                <li><a href="profile.php">My Profile</a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="logout.php">Logout</a></li>

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

            /* ✅ FINAL IMAGE FIX */
            $webPath = $row['image'];

            // if DB only stores filename → add folder
            if(strpos($webPath, 'images/') !== 0){
                $webPath = "images/" . $webPath;
            }

            // if empty image → fallback
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

</body>
</html>