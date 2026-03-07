<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = intval($_SESSION['user_id']);
$result = mysqli_query($conn,"SELECT * FROM users WHERE id=$id");

if(!$result || mysqli_num_rows($result) == 0){
    echo "User not found";
    exit();
}

$user = mysqli_fetch_assoc($result);

$name    = htmlspecialchars($user['name'] ?? '');
$email   = htmlspecialchars($user['email'] ?? '');
$phone   = htmlspecialchars($user['phone'] ?? '');
$address = htmlspecialchars($user['address'] ?? '');
$pincode = htmlspecialchars($user['pincode'] ?? '');
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
<h2>DESIAROMA</h2>
</header>

<section class="account-container">

<!-- SIDEBAR -->
<div class="account-sidebar">

<div class="profile-mini">
<img src="https://ui-avatars.com/api/?name=<?php echo urlencode($name); ?>&background=000000&color=FFD700&size=120">
<h3><?php echo $name ?></h3>
</div>

<ul>
<li class="active">Account</li>
<li>Password</li>
<li>Security</li>
<li>Orders</li>
<li>Notifications</li>
</ul>

</div>


<!-- RIGHT CONTENT -->
<div class="account-content">

<h2>Account Settings</h2>

<form method="POST" action="update_profile.php">

<div class="form-row">

<div class="form-group">
<label>Name</label>
<input type="text" name="name" value="<?php echo $name ?>">
</div>

<div class="form-group">
<label>Pincode</label>
<input type="text" name="pincode" value="<?php echo $pincode ?>">
</div>

</div>

<div class="form-row">

<div class="form-group">
<label>Email</label>
<input type="email" name="email" value="<?php echo $email ?>">
</div>

<div class="form-group">
<label>Phone</label>
<input type="text" name="phone" value="<?php echo $phone ?>">
</div>

</div>

<div class="form-group">
<label>Address</label>
<textarea name="address"><?php echo $address ?></textarea>
</div>

<div class="form-actions">
<button type="submit">Update</button>
<a href="index.php">Cancel</a>
</div>

</form>

</div>

</section>

</body>
</html>