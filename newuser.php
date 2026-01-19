<?php
    session_start();

    if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
    }

    $username = isset($_SESSION["username"]) ? $_SESSION["username"] : "Guest";
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : "User";

    include 'connect.php';

    if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "Admin") {
        echo '<script>alert("Access denied! You are not authorized to view this page."); window.location.href = "dashboard.php";</script>';
        exit;
    }
    

    $id_access = '';
    $email = '';
    $username = '';
    $password = '';
    $role = '';
    $result['role'] = '';
    $id_rack = '';

    if (isset($_GET['change'])) {
    $id_access = $_GET['change'];

    $query = "SELECT * FROM access WHERE id_access = '$id_access';";
    $sql = mysqli_query($conn, $query);

    $result = mysqli_fetch_assoc($sql);

    $id_access = $result['id_access'];
    $email = $result["email"];
    $username = $result["username"];
    $password = $result["password"];
    $role = $result["role"];
    $id_rack = $result["id_rack"];
    }

    $racks = [];
    $rackQuery = mysqli_query($conn, "SELECT id_rack, number, company FROM number_rack");
    while ($row = mysqli_fetch_assoc($rackQuery)) {
        $racks[] = $row;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="POS - Bootstrap Admin Template">
<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
<meta name="author" content="Dreamguys - Bootstrap Admin Template">
<meta name="robots" content="noindex, nofollow">
<title>Add/Update User - Interlink Data Center Sejahtera</title>

<link rel="shortcut icon" type="image/x-icon" href="assets/img/idcs.png">

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<link rel="stylesheet" href="assets/css/animate.css">

<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div id="global-loader">
<div class="whirly-loader"> </div>
</div>

<div class="main-wrapper">

<div class="header">

<div class="header-left active">
<a href="dashboard.php" class="logo">
<img src="assets/img/idcs.png" alt="">
</a>
<a href="dashboard.php" class="logo-small">
<img src="assets/img/idcs.png" alt="">
</a>
<a id="toggle_btn" href="javascript:void(0);">
</a>
</div>

<a id="mobile_btn" class="mobile_btn" href="#sidebar">
<span class="bar-icon">
<span></span>
<span></span>
<span></span>
</span>
</a>

<ul class="nav user-menu">

<li class="nav-item dropdown">
<!-- <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
<img src="assets/img/icons/notification-bing.svg" alt="img"> <span class="badge rounded-pill">4</span>
</a> -->
<div class="dropdown-menu notifications">
<div class="topnav-dropdown-header">
<span class="notification-title">Notifications</span>
<a href="javascript:void(0)" class="clear-noti"> Clear All </a>
</div>
<div class="noti-content">
<ul class="notification-list">
<li class="notification-message">
<a href="activities.html">
<div class="media d-flex">
<span class="avatar flex-shrink-0">
<img alt="" src="assets/img/user.png">
</span>
<div class="media-body flex-grow-1">

</div>
</div>
</a>
</li>
</ul>
</div>
</div>
</li>

<li class="nav-item dropdown has-arrow main-drop">
<a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
<span class="user-img"><img src="assets/img/user.png" alt="">
<span class="status online"></span></span>
</a>
<div class="dropdown-menu menu-drop-user">
<div class="profilename">
<div class="profileset">
<span class="user-img"><img src="assets/img/user.png" alt="">
<span class="status online"></span></span>
<div class="profilesets">
<h6><?php echo htmlspecialchars($username); ?></h6>
<h5><?php echo htmlspecialchars($role); ?></h5>
</div>
</div>
<hr class="m-0">
<hr class="m-0">
<a class="dropdown-item logout pb-0" href="logout.php"><img src="assets/img/icons/log-out.svg" class="me-2" alt="img">Logout</a>
</div>
</div>
</li>
</ul>


<div class="dropdown mobile-user-menu">
<a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
<div class="dropdown-menu dropdown-menu-right">
<a class="dropdown-item" href="logout.php">Logout</a>
</div>
</div>

</div>


<div class="sidebar" id="sidebar">
<div class="sidebar-inner slimscroll">
<div id="sidebar-menu" class="sidebar-menu">
<ul>
    <?php if ($role === 'Admin'): ?>
    <li class="submenu">
        <a class="active" href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span> Users</span> <span class="menu-arrow"></span></a>
        <ul>
            <li><a href="newuser.php">New User </a></li>
            <li><a href="userlists.php">Users List</a></li>
        </ul>
    </li>
    <li class="">
        <a href="treshold.php"><img src="assets/img/icons/transfer1.svg" alt="img"><span> Threshold</span> </a>
    </li>
    <?php endif; ?>

    <li>
        <a href="dashboard.php"><img src="assets/img/icons/dashboard.svg" alt="img"><span> Dashboard</span> </a>
    </li>
    <li>
        <a href="graph.php"><i data-feather="bar-chart-2"></i><span> Graph</span> </a>
    </li>
    <li>
        <a href="logout.php"><i data-feather="log-out"></i><span> Logout</span> </a>
    </li>
</ul>
    
</div>
</div>
</div>

<div class="page-wrapper">
<div class="content">
<div class="page-header">
<div class="page-title">
<h4>User Management</h4>
<h6>Add/Update User</h6>
</div>
</div>

<div class="card">
<div class="card-body">
<div class="row">
<div class="col-lg-3 col-sm-6 col-12">

<form method="post" action="process.php">
<!-- <div class="form-group"> -->
<!-- <label>Id_Access</label> -->
<input type="hidden" name="id_access" value="<?php echo $id_access; ?>">
<!-- </div> -->
 <div class="form-group">
<label>Email</label>
<input type="text" name="email" value="<?php echo $email; ?>" required>
</div>
<div class="form-group">
<label>User Name</label>
<input type="text" name="username" value="<?php echo $username; ?>" oninput="this.value = this.value.replace(/[^a-zA-Z0-9#$%@]/g, '')" required>
</div>
<div class="form-group">
<label>Password</label>
<div class="pass-group">
<input type="password" class=" pass-input" name="password" value="<?php echo $password; ?>" oninput="this.value = this.value.replace(/[^a-zA-Z0-9#$%@]/g, '')" required>
<span class="fas toggle-password fa-eye-slash"></span>
</div>
</div>
</div>
<div class="col-lg-3 col-sm-6 col-12">
<div class="form-group">
<label>Role</label>
<select name="role" class="select">
    <option value="Admin" <?php if($result["role"] == "Admin"):?> selected <?php endif ?>  >Admin</option>
    <option value="User" <?php if($result["role"] == "User"):?> selected <?php endif ?> >User</option>
</select>
</div>
</div>
<div class="col-lg-3 col-sm-6 col-12">
<div class="form-group">
<label>Rack Server Number</label>
<select name="id_rack" id="rack" class="select" required>
<option value="">-- Choose Rack --</option>
<?php foreach ($racks as $rack): ?>
<option value="<?= $rack['id_rack']; ?>" <?= ($rack['id_rack'] == $id_rack) ? 'selected' : ''; ?>>
<?= htmlspecialchars("Rack {$rack['number']} - {$rack['company']}"); ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
<div class="col-lg-12">

<?php
                if(isset($_GET['change'])){
            ?>
        
                 <button type="submit" name="execute" value="edit" class="btn btn-submit me-2"><i class="bi bi-send-fill"></i> Save</button>
            <?php
                } else {
            ?>
            
                <button type="submit" name="execute" value="add" class="btn btn-submit me-2"><i class="bi bi-send-fill"></i> Add</button>
            
                <?php
                }
            ?>

            <a href="userlists.php"><button class="btn btn-cancel" type="button"><i class="bi bi-backspace-fill"></i> Cancel</button></a>
            
</div>
</div>
</div>
</div>
</form>

</div>
</div>
</div>


<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/feather.min.js"></script>

<script src="assets/js/jquery.slimscroll.min.js"></script>

<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap4.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/plugins/select2/js/select2.min.js"></script>

<script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
<script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

<script src="assets/js/script.js"></script>
</body>
</html>