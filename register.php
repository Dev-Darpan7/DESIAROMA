<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {

        $sql = "INSERT INTO users (name, email, password) 
                VALUES ('$name', '$email', '$password')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - DESIAROMA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- SAME HEADER STRUCTURE -->
<header>
    <div class="logo">DESIAROMA</div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#products">Shop</a></li>
            <li><a href="index.php#about">About</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<section class="auth-section">
    <div class="auth-box">
        <h2>Register</h2>

        <?php if(isset($error)) { ?>
            <p style="color:red; margin-bottom:15px;">
                <?php echo $error; ?>
            </p>
        <?php } ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>

        <p style="margin-top:15px;">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</section>

</body>
</html>
