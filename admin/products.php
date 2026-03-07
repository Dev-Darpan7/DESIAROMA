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

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>

            <?php while($row=mysqli_fetch_assoc($result)){ 
                // Safe image path
                $imagePath = '../images/' . $row['image'];
                if(!file_exists($imagePath) || empty($row['image'])){
                    $imagePath = '../images/default.png'; // fallback placeholder
                }
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td>₹<?= number_format($row['price'],2) ?></td>
                <td>
                    <img src="<?= $imagePath ?>" width="70" style="border-radius:8px;">
                </td>
                <td>
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
