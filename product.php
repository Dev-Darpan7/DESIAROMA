<?php
session_start();
include 'config.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product details from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Product not found!";
    exit();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++; // Increase quantity
    } else {
        $_SESSION['cart'][$product_id] = 1; // Add new product
    }

    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $product['product_name']; ?> - DESIAROMA</title>
<link rel="stylesheet" href="style.css">
</head>
<body id="top">

<!-- Header -->
<header>
    <div class="logo">DESIAROMA</div>
    <nav>
        <ul>
            <li><a href="index.php#top">Home</a></li>
            <li><a href="index.php#products">Shop</a></li>
            <li><a href="index.php#about">About</a></li>
            <li><a href="index.php#contact">Contact</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>
                <li style="color:gold;">Hello, <?php echo $_SESSION['user_name']; ?></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Product Detail Section -->
<section class="products">
    <h2><?php echo $product['product_name']; ?></h2>

    <div class="product-container">

        <div class="card" style="width: 100%; max-width: 600px; margin: auto;">
            <div class="product-image">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
            </div>
            <div class="product-info" style="text-align:center; margin-top:15px;">
                <p class="price">₹<?php echo $product['price']; ?></p>
                <p><?php echo $product['description']; ?></p>
                <form method="POST">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        </div>

    </div>
</section>

</body>
</html>
