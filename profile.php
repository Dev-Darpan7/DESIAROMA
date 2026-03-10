<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = intval($_SESSION['user_id']);

$password_message = "";

/* Handle password update */
if(isset($_POST['current_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $check = mysqli_query($conn,"SELECT password FROM users WHERE id=$id");
    $userpass = mysqli_fetch_assoc($check);

    /* FIX: verify hashed password */
    if(!password_verify($current, $userpass['password'])){
        $password_message = "Current password is incorrect";
    }
    elseif($new !== $confirm){
        $password_message = "New passwords do not match";
    }
    else{

        /* FIX: hash new password before saving */
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $update = mysqli_query($conn,"UPDATE users SET password='$hashed' WHERE id=$id");

        if($update){
            $password_message = "Password updated successfully";
        }else{
            $password_message = "Error updating password";
        }
    }
}

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

<!-- LEFT SIDEBAR -->
<aside class="account-sidebar">

<div class="profile-mini">
<img src="https://ui-avatars.com/api/?name=<?php echo urlencode($name); ?>&background=000000&color=FFD700&size=120">
<h3><?php echo $name ?></h3>
</div>

<ul class="sidebar-links">

<li class="<?php if(!isset($_GET['tab']) || $_GET['tab']=='account') echo 'active'; ?>">
<a href="profile.php?tab=account">Account</a>
</li>

<li class="<?php if(isset($_GET['tab']) && $_GET['tab']=='password') echo 'active'; ?>">
<a href="profile.php?tab=password">Password</a>
</li>

<li class="<?php if(isset($_GET['tab']) && $_GET['tab']=='orders') echo 'active'; ?>">
<a href="profile.php?tab=orders">Orders</a>
</li>

<li>
<a href="logout.php">Logout</a>
</li>

</ul>

</aside>

<!-- RIGHT CONTENT -->
<main class="account-content">

<?php
$tab = $_GET['tab'] ?? "account";

if($tab == "orders"){
    include "orders.php";

} elseif($tab == "password") {
?>

<h2>Change Password</h2>

<?php if(!empty($password_message)){ ?>
<p style="margin-bottom:15px;color:red;"><?php echo $password_message; ?></p>
<?php } ?>

<div class="form-container">
<form method="POST" action="profile.php?tab=password">

<div class="form-row">
<div class="form-group">
<label>Current Password</label>
<input type="password" name="current_password" required>
</div>
</div>

<div class="form-row">

<div class="form-group">
<label>New Password</label>
<input type="password" name="new_password" required>
</div>

<div class="form-group">
<label>Confirm New Password</label>
<input type="password" name="confirm_password" required>
</div>

</div>

<div class="form-actions">
<button type="submit">Update Password</button>
<a href="profile.php?tab=account">Cancel</a>
</div>

</form>
</div>

<?php
} else {
?>

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

<?php } ?>

</main>

</section>
</body>
</html>