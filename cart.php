<?php
session_start();
include 'config.php';

/* ========= REQUIRE LOGIN BEFORE ADDING ========= */
if (isset($_POST['add_to_cart']) && !isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = "cart.php";
    header("Location: login.php");
    exit();
}

/* ========= INITIALIZE CART ========= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

/* ========= ADD TO CART ========= */
if (isset($_POST['add_to_cart'])) {

    $product_id = intval($_POST['product_id']);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    header("Location: cart.php");
    exit();
}

/* ========= INCREASE QUANTITY ========= */
if (isset($_GET['plus'])) {
    $id = intval($_GET['plus']);
    $_SESSION['cart'][$id]++;
    header("Location: cart.php");
    exit();
}

/* ========= DECREASE QUANTITY ========= */
if (isset($_GET['minus'])) {
    $id = intval($_GET['minus']);

    if ($_SESSION['cart'][$id] > 1) {
        $_SESSION['cart'][$id]--;
    } else {
        unset($_SESSION['cart'][$id]);
    }

    header("Location: cart.php");
    exit();
}

/* ========= REMOVE ITEM ========= */
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}

/* ========= FETCH CART PRODUCTS ========= */
$products_in_cart = array();
$total_price = 0;

if (!empty($_SESSION['cart'])) {

    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = mysqli_query($conn,"SELECT * FROM products WHERE id IN ($ids)");

    while ($row = mysqli_fetch_assoc($result)) {

        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];

        $products_in_cart[] = $row;
        $total_price += $row['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Cart - DESIAROMA</title>
<link rel="stylesheet" href="style.css">

<style>

.qty-box{
display:flex;
align-items:center;
gap:10px;
}

.qty-btn{
background:#111;
color:white;
padding:5px 10px;
text-decoration:none;
border-radius:4px;
font-weight:bold;
}

.qty-btn:hover{
background:gold;
color:black;
}

.remove-btn{
color:red;
text-decoration:none;
}

</style>

</head>
<body>

<header>
<div class="logo">DESIAROMA</div>

<nav>
<ul>

<li><a href="index.php">Home</a></li>
<li><a href="shop.php">Shop</a></li>

<?php if(isset($_SESSION['user_id'])): ?>

<li style="color:gold;">Hello <?php echo $_SESSION['user_name']; ?></li>

<li>
<a href="profile.php?tab=orders">My Orders</a>
</li>

<li><a href="logout.php">Logout</a></li>

<?php else: ?>

<li><a href="login.php">Login</a></li>
<li><a href="register.php">Register</a></li>

<?php endif; ?>

</ul>
</nav>
</header>


<section class="cart-section">

<div class="cart-box">

<h2>Your Cart</h2>

<?php if(empty($products_in_cart)): ?>

<p>Your cart is empty. <a href="shop.php">Shop Now</a></p>

<?php else: ?>

<table>

<tr>
<th>Product</th>
<th>Price</th>
<th>Qty</th>
<th>Subtotal</th>
<th>Action</th>
</tr>

<?php foreach($products_in_cart as $product): ?>

<tr>

<td><?php echo $product['product_name']; ?></td>

<td>₹<?php echo $product['price']; ?></td>

<td>

<div class="qty-box">

<a class="qty-btn" href="cart.php?minus=<?php echo $product['id']; ?>">−</a>

<span><?php echo $product['quantity']; ?></span>

<a class="qty-btn" href="cart.php?plus=<?php echo $product['id']; ?>">+</a>

</div>

</td>

<td>₹<?php echo $product['subtotal']; ?></td>

<td>
<a href="cart.php?remove=<?php echo $product['id']; ?>" class="remove-btn">
Remove
</a>
</td>

</tr>

<?php endforeach; ?>

<tr>
<th colspan="3">Total</th>
<th>₹<?php echo $total_price; ?></th>
<th></th>
</tr>

</table>

<form action="checkout.php" method="POST">
<button class="checkout-btn">Proceed to Checkout</button>
</form>

<?php endif; ?>

</div>

</section>

</body>
</html>