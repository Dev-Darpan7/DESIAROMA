<?php
session_start();
include '../config.php';  // Correct path

/* 🔒 Admin check */
if(!isset($_SESSION['admin'])){
    header("Location: ../admin_login.php");
    exit();
}

/* ✅ Sanitize input */
$order_id = intval($_POST['order_id']);
$order_status = $_POST['order_status'];

/* Allowed statuses */
$allowed_statuses = ['Pending', 'Completed', 'Cancelled'];
if(!in_array($order_status, $allowed_statuses)){
    die("Invalid order status!");
}

/* Update order status safely using prepared statements */
$stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE id=?");
$stmt->bind_param("si", $order_status, $order_id);
$success = $stmt->execute();
$stmt->close();

if(!$success){
    die("Failed to update order status.");
}

/* Redirect back to order details */
header("Location: /DESIAROMA/admin/admin_order_details.php?order_id=$order_id");
exit();
?>
