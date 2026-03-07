<?php
session_start();
include '../config.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$order_query = "
    SELECT o.id AS order_id, o.total_price, o.order_status, o.order_date, u.name AS user_name, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = $order_id
";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

$items_query = "
    SELECT oi.product_id, oi.quantity, oi.price, p.product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $order_id
";
$items_result = mysqli_query($conn, $items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details #<?php echo $order_id; ?> - DESIAROMA</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<!-- HEADER -->
<div class="admin-header">
    <div class="admin-logo">Admin Panel</div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="add_product.php">Add Product</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="admin-container">

    <div class="admin-card">
        <h2 class="admin-title">Order Details #<?php echo $order_id; ?></h2>
        <a href="admin_orders.php" class="btn">← Back to Orders</a>

        <h3>Customer Info</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>
        <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>

        <h3>Products</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td>₹<?php echo number_format($item['price'],2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo number_format($item['price'] * $item['quantity'],2); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="3">Total</th>
                    <th>₹<?php echo number_format($order['total_price'],2); ?></th>
                </tr>
            </tbody>
        </table>

        <h3>Update Order Status</h3>
       <form method="POST" action="admin_update_order.php">
    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
    
    <select name="order_status" class="admin-form-select">
        <option value="Pending" <?php if($order['order_status']=='Pending') echo 'selected'; ?>>Pending</option>
        <option value="Completed" <?php if($order['order_status']=='Completed') echo 'selected'; ?>>Completed</option>
        <option value="Cancelled" <?php if($order['order_status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
    </select>

    <button type="submit" class="btn">Update</button>
</form>

    </div>
</div>

</body>
</html>
