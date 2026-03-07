<?php
session_start();
include '../config.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$query = "
    SELECT o.id AS order_id, o.user_id, o.total_price, o.order_status, o.order_date, u.name AS user_name, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Orders - DESIAROMA</title>
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
        <h2 class="admin-title">Orders Management</h2>
        <p>View all orders placed by users. Click "View" to see order details.</p>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>₹<?php echo number_format($row['total_price'],2); ?></td>
                    <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td>
                        <a class="btn" href="admin_order_details.php?order_id=<?php echo $row['order_id']; ?>">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
