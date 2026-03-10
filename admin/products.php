<?php
session_start();
include '../config.php';

if(!isset($_SESSION['admin'])){
    header("Location:admin_login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM products");
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
        <h2 class="admin-title">All Products</h2>

        <a href="add_product.php" class="btn">+ Add New Product</a>

        <br><br>

        <table style="width:100%; border-collapse:collapse;">

            <tr>
                <th style="text-align:center; width:10%;">ID</th>
                <th style="text-align:left; width:50%;">Name</th>
                <th style="text-align:center; width:20%;">Price</th>
                <th style="text-align:center; width:20%;">Action</th>
            </tr>

            <?php while($row=mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td style="text-align:center;">
                    <?= $row['id'] ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['product_name']) ?>
                </td>

                <td style="text-align:center;">
                    ₹<?= number_format($row['price'],2) ?>
                </td>

                <td style="text-align:center;">
                    <a class="btn" href="delete_product.php?id=<?= $row['id'] ?>" 
                       onclick="return confirm('Delete this product?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php } ?>

        </table>

    </div>

</div>