<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location:admin_login.php");
    exit();
}
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

    <div class="admin-card">
        <h2 class="admin-title">Quick Actions</h2>

        <a class="btn" href="products.php">Manage Products</a>
        <a class="btn" href="add_product.php">Add New Product</a>
        <a class="btn" href="admin_orders.php">View Orders</a>
        <a class="btn" href="logout.php">Logout</a>
    </div>

</div>
