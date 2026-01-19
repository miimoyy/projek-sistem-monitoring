<?php
include 'connect.php';
session_start();

$step = 1; // Langkah awal: input kode

// Validasi kode reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $reset_coded = $_POST['reset'];

    $stmt = $conn->prepare("SELECT email FROM access WHERE reset_coded = ?");
    $stmt->bind_param("s", $reset_coded);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['reset_email'] = $row['email'];
        $step = 2; // lanjut ke form ubah password
    } else {
        $error = "Invalid Code!";
    }
}

// Simpan password baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];
    $email = $_SESSION['reset_email'] ?? null;

    if ($email) {
        $stmt = $conn->prepare("UPDATE access SET password = ?, reset_coded = NULL WHERE email = ?");
        $stmt->bind_param("ss", $new_password, $email);

        if ($stmt->execute()) {
            unset($_SESSION['reset_email']);
            echo "<script>alert('Password changed successfully!'); window.location.href='index.php';</script>";
            exit;
        } else {
            $error = "Failed to change password.";
        }
    } else {
        $error = "Session not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Interlink Data Center Sejahtera</title>
  <link rel="shortcut icon" href="assets/img/idcs.png">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
            <img src="assets/img/idcs.png" alt="logo">
          </div>

          <div class="login-userheading">
            <h3>Reset Password</h3>
            <h4><?= $step === 1 ? 'Please check your email' : 'Enter your new password' ?></h4>
          </div>

          <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

          <form method="post">
            <?php if ($step === 1): ?>
              <div class="form-login">
                <label>Verification Code</label>
                <div class="form-addons">
                  <input type="number" name="reset" placeholder="Enter your Code" required>
                </div>
              </div>
              <div class="form-login">
                <button class="btn btn-login" type="submit">Submit</button>
              </div>
            <?php else: ?>
              <div class="form-login">
                <label>New Password</label>
                <div class="form-addons">
                  <input type="password" name="new_password" placeholder="Enter new password" required>
                </div>
              </div>
              <div class="form-login">
                <button class="btn btn-login" type="submit">Change Password</button>
              </div>
            <?php endif; ?>
          </form>

        </div>
      </div>

      <div class="login-img">
        <img src="assets/img/interlink.jpg" alt="interlink image">
      </div>
    </div>
  </div>
</div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
