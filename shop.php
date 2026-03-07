<?php
session_start();
include 'config.php';

/* 🔥 SHOW ERRORS (REMOVE LATER IN PRODUCTION) */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Pagination settings */
$limit = 12; // products per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

/* Total products count */
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

/* Fetch products for current page */
$query = "SELECT * FROM products ORDER BY id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - DESIAROMA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">DESIAROMA</div>
    <nav>
        <ul>
            <li><a href="index.php#top">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="index.php#about">About</a></li>
            <li><a href="index.php#contact">Contact</a></li>
        </ul>
    </nav>
</header>

<section class="products">
    <h2>Shop All Products</h2>

    <div class="product-container">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <a href="product.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                             alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    </a>

                    <h3>
                        <a href="product.php?id=<?php echo $row['id']; ?>" style="text-decoration:none;color:gold;">
                            <?php echo htmlspecialchars($row['product_name']); ?>
                        </a>
                    </h3>

                    <p>₹<?php echo number_format($row['price'],2); ?></p>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <a href="login.php"><button type="button">Login to Add</button></a>
                    <?php endif; ?>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found!</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination" style="text-align:center; margin-top:20px;">
        <?php if($page > 1): ?>
            <a href="shop.php?page=<?php echo $page-1; ?>"><button>Previous</button></a>
        <?php endif; ?>

        <?php if($page < $total_pages): ?>
            <a href="shop.php?page=<?php echo $page+1; ?>"><button>Next</button></a>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
