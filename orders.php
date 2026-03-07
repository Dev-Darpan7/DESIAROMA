<?php
session_start();
include 'config.php';

/* 🔒 REQUIRE LOGIN */
if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect_after_login'] = "orders.php";
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

/* 📦 GET USER ORDERS */
$order_query = mysqli_query($conn,
    "SELECT * FROM orders 
     WHERE user_id=$user_id 
     ORDER BY id DESC"
);

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders - DESIAROMA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">DESIAROMA</div>
</header>

<section class="cart-section">
<div class="cart-box">

<h2>My Orders</h2>

<?php if(mysqli_num_rows($order_query) == 0): ?>

    <p>You have not placed any orders yet.</p>
    <a href="index.php">Start Shopping</a>

<?php else: ?>

<?php while($order = mysqli_fetch_assoc($order_query)): ?>

    <div style="border:1px solid #444;padding:15px;margin-bottom:20px;">

        <h3>Order #<?php echo $order['id']; ?></h3>
        <p>Date: <?php echo $order['created_at']; ?></p>
        <p>Total: ₹<?php echo $order['total_price']; ?></p>

        <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>

        <?php
        $order_id = $order['id'];

        $items = mysqli_query($conn,
            "SELECT oi.*, p.product_name 
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = $order_id"
        );

        while($item = mysqli_fetch_assoc($items)):
        ?>

        <tr>
            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
            <td>₹<?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₹<?php echo $item['price'] * $item['quantity']; ?></td>
        </tr>

        <?php endwhile; ?>

        </table>

    </div>

<?php endwhile; ?>

<?php endif; ?>

</div>
</section>

</body>
</html>
