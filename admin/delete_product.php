<?php
session_start();
include '../config.php';

if(!isset($_SESSION['admin'])){
    header("Location:admin_login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']); // safer numeric value

    mysqli_query($conn,"DELETE FROM products WHERE id=$id");
}

header("Location:products.php");
exit();
?>
    