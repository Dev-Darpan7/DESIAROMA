<?php
session_start();
include '../config.php';

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    $query = mysqli_query($conn,"SELECT * FROM admins WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($query)>0){
        $_SESSION['admin']=$username;
        header("Location:index.php");
        exit();
    }else{
        $error="Invalid Username or Password";
    }
}
?>

<link rel="stylesheet" href="admin_style.css">

<div class="auth-section">

    <div class="auth-box">

        <h2>Admin Login</h2>

        <?php if(isset($error)) echo "<p style='color:#ff6b6b'>$error</p>"; ?>

        <form method="post">

            <input type="text" name="username" placeholder="Username" required>

            <input type="password" name="password" placeholder="Password" required>

            <button name="login">Login</button>

        </form>

    </div>

</div>
