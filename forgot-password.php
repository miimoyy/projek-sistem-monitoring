<?php
include 'connect.php'; // koneksi ke database

// Load Composer autoload
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil email dari form
    $email = trim($_POST['email']);

    // Cek apakah email terdaftar
    $stmt = $conn->prepare("SELECT * FROM access WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika email ditemukan
    if ($result->num_rows === 1) {
        $code = rand(100000, 999999); // 6 digit kode

        // Simpan kode ke database
        $update = $conn->prepare("UPDATE access SET reset_coded = ? WHERE email = ?");
        $update->bind_param("ss", $code, $email);
        $update->execute();

        // Mulai PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'archivesandika@gmail.com';     // ganti
            $mail->Password   = 'egfi azhi uoef trid'; // ganti
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Pengirim dan penerima
            $mail->setFrom('archivesandika@gmail.com', 'PT Interlink Data Center Sejahtera - Forgot Password');
            $mail->addAddress($email); // ke user

            // Konten email
            $mail->isHTML(true);
            $mail->Subject = 'Kode Reset Password Anda';
            $mail->Body    = "
                <p>Kepada Pelanggan Yth.,</p>
                <p>Kode untuk reset password Anda adalah:</p>
                <h2>$code</h2>
                <p>Masukkan kode ini ke halaman reset password untuk melanjutkan.</p>
                <br><small>Email ini dikirim otomatis oleh sistem PT Interlink Data Center Sejahtera.</small>
            ";

            $mail->send();
            echo "<script>alert('Reset code has been sent to your email, Please check your inbox or spam!'); window.location.href='reset-password.php';</script>";
        } catch (Exception $e) {
            echo "Failde to send email. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>alert('Email not found!'); history.back();</script>";
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
<title>Forgot Password - Interlink Data Center Sejahtera</title>

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
<h3>Forgot Password</h3>
<h4>Please enter your Email</h4>
</div>
<div class="form-login">
<label>Email</label>

<form method="post">
<div class="form-addons">
<input type="email" name="email" placeholder="Enter your Email" required>
<img src="assets/img/icons/mail.svg" alt="img">
</div>
</div>

<div class="form-login">

</div>
<div class="form-login">
<button class="btn btn-login" type="submit" name="login">Continue</button>
</div>
</form>

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