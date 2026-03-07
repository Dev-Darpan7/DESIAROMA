<?php
session_start();
include '../config.php';

if(!isset($_SESSION['admin'])){
    header("Location:admin_login.php");
    exit();
}

if(isset($_POST['add'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $price = mysqli_real_escape_string($conn,$_POST['price']);

    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];

    if($image != ""){
        move_uploaded_file($tmp,"uploads/".$image);
    }

    mysqli_query($conn,"INSERT INTO products(name,price,image) VALUES('$name','$price','$image')");
    header("Location:products.php");
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
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="admin-container">

    <div class="admin-card">
        <h2 class="admin-title">Add New Product</h2>

        <form method="post" enctype="multipart/form-data" class="admin-form">

            <input type="text" name="name" placeholder="Product Name" required>

            <input type="number" name="price" placeholder="Price" required>

            <input type="file" name="image" required>

            <button name="add">Add Product</button>

        </form>

    </div>

</div>
