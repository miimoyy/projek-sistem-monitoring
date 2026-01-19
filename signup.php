<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $role     = 'User'; // default role
    $password = $_POST['password'] ?? '';
    $reset_coded = null; // default NULL

    // Validasi input
    if (!empty($email) && !empty($username) && !empty($password)) {
        $query = "INSERT INTO access (email, username, role, password, reset_coded, created_access)
                  VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($query);
        
        $stmt->bind_param("sssss", $email, $username, $role, $password, $reset_coded);

        if ($stmt->execute()) {
            echo "<script>alert('Account created successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Failed to add data!: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all colums!";
    }
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
<title>Sign Up - Interlink Data Center Sejahtera</title>

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
<h3>Create an Account</h3>
<h4>Continue where you left off</h4>
</div>

<form method="post" action="">
<div class="form-login">
<label>Email</label>
<div class="form-addons">
<input type="email" name="email" placeholder="Enter your email" required>
<img src="assets/img/icons/mail.svg" alt="img">
</div>
</div>
<div class="form-login">
<label>Username</label>
<div class="form-addons">
<input type="text" name="username" placeholder="Enter your full name" required>
<img src="assets/img/icons/users1.svg" alt="img">
</div>
</div>
<div class="form-login">
<label>Password</label>
<div class="pass-group">
<input type="password" name="password" class="pass-input" placeholder="Enter your password" required>
<span class="fas toggle-password fa-eye-slash"></span>
</div>
</div>
<div class="form-login">
<button class="btn btn-login" type="submit" name="login">Sign Up</button>
</div>
</form>

<div class="signinform text-center">
<h4>Already a user? <a href="index.php" class="hover-a">Sign In</a></h4>
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