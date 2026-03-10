<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location:admin_login.php");
    exit();
}

include '../config.php';

/* GET TOTAL REVENUE */
$rev_query = mysqli_query($conn,"SELECT SUM(total_price) AS revenue FROM orders");
$rev_data = mysqli_fetch_assoc($rev_query);
$total_revenue = $rev_data['revenue'] ?? 0;
?>

<link rel="stylesheet" href="admin_style.css">

<!-- HEADER -->
<div class="admin-header">
    <div class="admin-logo">Admin Panel</div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="add_product.php">Add Product</a>
        <a href="admin_orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="admin-container">

    <div class="admin-card">
        <h2 class="admin-title">Welcome Admin 👋</h2>
        <p>Use the dashboard below to manage your perfume store.</p>
    </div>

    <!-- REVENUE CARD -->
    <div class="admin-card">
        <h2 class="admin-title">Total Revenue</h2>
        <p style="font-size:24px;font-weight:bold;">
            ₹<?php echo number_format($total_revenue,2); ?>
        </p>
    </div>

    <div class="admin-card">
        <h2 class="admin-title">Quick Actions</h2>

        <a class="btn" href="products.php">Manage Products</a>
        <a class="btn" href="add_product.php">Add New Product</a>
        <a class="btn" href="admin_orders.php">View Orders</a>
        <a class="btn" href="logout.php">Logout</a>
    </div>

</div>