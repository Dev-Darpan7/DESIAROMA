<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);

        // If password is HASHED (recommended)
        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect after login
            if(isset($_SESSION['redirect_after_login'])){
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
            } else {
                header("Location: index.php");
            }
            exit();
        } 
        // If password stored as plain text (not recommended)
        elseif($password == $user['password']){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit();
        }
        else{
            $error = "Invalid Password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - DESIAROMA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">DESIAROMA</div>
</header>

<section class="auth-section">
    <div class="auth-box">
        <h2>User Login</h2>

        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>

        <p>New user? <a href="register.php">Register here</a></p>
    </div>
</section>

</body>
</html>
