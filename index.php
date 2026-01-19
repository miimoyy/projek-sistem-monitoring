<?php

session_start();
include 'connect.php';

if (isset($_SESSION["login"])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
    $password = mysqli_real_escape_string($conn, htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));

    $result = mysqli_query($conn, "SELECT * FROM access WHERE username = '$username' AND password = '$password'");
    
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION["login"] = true;
        $_SESSION["username"] = $row['username'];
        $_SESSION["role"] = $row['role'];

        echo '<script>alert("Welcome, ' . $row['username'] .' as a ' . $row['role'] . '");
        location.href="dashboard.php";</script>';
        exit;
    }
    
    echo '<script>alert("Username/Password is Wrong");</script>';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="POS - Bootstrap Admin Template">
<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
<meta name="author" content="Dreamguys - Bootstrap Admin Template">
<meta name="robots" content="noindex, nofollow">
<title>Sign In - Interlink Data Center Sejahtera</title>

<link rel="shortcut icon" type="image/x-icon" href="assets/img/idcs.png">

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="account-page">

<div class="main-wrapper">
<div class="account-content">
<div class="login-wrapper">
<div class="login-content">
<div class="login-userset">
<div class="login-logo">
<img src="assets/img/idcs.png" alt="img">
</div>
<div class="login-userheading">
<h3>Sign In</h3>
<h4>Please login to your account</h4>
</div>
<div class="form-login">
<label>Username</label>

<form method="post">
<div class="form-addons">
<input type="text" name="username" placeholder="Enter your Username" oninput="this.value = this.value.replace(/[^a-zA-Z0-9#$%@]/g, '')" required>
<img src="assets/img/icons/users1.svg" alt="img">
</div>
</div>
<div class="form-login">
<label>Password</label>
<div class="pass-group">
<input type="password" name="password" class="pass-input" placeholder="Enter your password" oninput="this.value = this.value.replace(/[^a-zA-Z0-9#$%@]/g, '')" required>
<span class="fas toggle-password fa-eye-slash"></span>
</div>
</div>

<div class="signinform text-left">
<h4> <a href="forgot-password.php" class="hover-a">Forgot Passsword?</a></h4>
</div>

<div class="form-login">

</div>
<div class="form-login">
<button class="btn btn-login" type="submit" name="login">Sign In</button>
</div>
</form>

<div class="signinform text-center">
<h4>Don't have an account yet? <a href="signup.php" class="hover-a">Sign Up</a></h4>
</div>


</div>
</div>
<div class="login-img">
<img src="assets/img/interlink.jpg" alt="img">
</div>
</div>
</div>
</div>


<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/feather.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/script.js"></script>
</body>
</html>