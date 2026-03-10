<?php

/* START SESSION SAFELY */
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

include 'config.php';

/* CHECK LOGIN */
if(!isset($_SESSION['user_id'])){
    echo "<p>Please login to view your orders.</p>";
    exit();
}

$user_id = intval($_SESSION['user_id']);

$order_query = mysqli_query($conn,"
SELECT * FROM orders 
WHERE user_id=$user_id
ORDER BY id DESC
");
?>

<h2>My Orders</h2>

<?php if(mysqli_num_rows($order_query) == 0): ?>

<p>You have not placed any orders yet.</p>

<?php else: ?>

<table class="orders-table">

<thead>
<tr>
<th>Order ID</th>
<th>Date</th>
<th>Status</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($order = mysqli_fetch_assoc($order_query)): ?>

<?php
$date = isset($order['created_at']) ? date("d M Y", strtotime($order['created_at'])) : "N/A";
$status = $order['status'] ?? "pending";
?>

<tr>

<td class="order-id">
#<?php echo $order['id']; ?>
</td>

<td>
<?php echo $date; ?>
</td>

<td>
<span class="status <?php echo strtolower($status); ?>">
<?php echo ucfirst($status); ?>
</span>
</td>

<td class="order-price">
₹<?php echo $order['total_price']; ?>
</td>

<td>
<a href="view_order.php?id=<?php echo $order['id']; ?>" class="order-view">
View
</a>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<?php endif; ?>