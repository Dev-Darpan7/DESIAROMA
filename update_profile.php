<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

$name    = mysqli_real_escape_string($conn,$_POST['name']);
$email   = mysqli_real_escape_string($conn,$_POST['email']);
$phone   = mysqli_real_escape_string($conn,$_POST['phone']);
$address = mysqli_real_escape_string($conn,$_POST['address']);
$pincode = mysqli_real_escape_string($conn,$_POST['pincode']);   // ✅ ADDED

mysqli_query($conn,"
    UPDATE users 
    SET 
        name='$name',
        email='$email',
        phone='$phone',
        address='$address',
        pincode='$pincode'   -- ✅ ADDED
    WHERE id=$id
");

$_SESSION['user_name'] = $name;

header("Location: profile.php?updated=1");
exit();
?>