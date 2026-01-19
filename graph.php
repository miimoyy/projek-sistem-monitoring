<?php
session_start();

if (!isset($_SESSION["login"])) {
  header("Location: index.php");
  exit;
}

$username = $_SESSION["username"] ?? "Guest";
$role = $_SESSION["role"] ?? "User";

include 'connect.php';

// ambil rack user
$userQuery = "SELECT a.id_rack, nr.number, nr.company 
              FROM access a
              LEFT JOIN number_rack nr ON a.id_rack = nr.id_rack 
              WHERE a.username = '$username' 
              LIMIT 1";

$userResult = mysqli_query($conn, $userQuery);
$userData = mysqli_fetch_assoc($userResult);

$id_rack = $userData['id_rack']; 

// info rack
$rackInfoQuery = "SELECT number, company FROM number_rack WHERE id_rack = '$id_rack'";
$rackInfoResult = mysqli_query($conn, $rackInfoQuery);
$rackInfo = mysqli_fetch_assoc($rackInfoResult);

$rack_number = $rackInfo['number'] ?? 'Unknown';
$rack_company = $rackInfo['company'] ?? 'Unknown';

// data grafik
$query = "SELECT current, power, created_daily
FROM daily_monitoring
WHERE id_rack = '$id_rack'
ORDER BY created_daily ASC";

$sql = mysqli_query($conn, $query);

if (!$sql) {
  die(mysqli_error($conn));
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
<title>Graph - Interlink Data Center Sejahtera</title>
<!-- <link rel="stylesheet" type="text/css" href="assets_grafik/css/bootstrap.min.css"> -->
<script type="text/javascript" src="assets_grafik/js/jquery-3.4.0.min.js"></script>
<script type="text/javascript" src="assets_grafik/js/mdb.min.js"></script>
<script type="text/javascript" src="jquery-latest.js"></script>

<link rel="shortcut icon" type="image/x-icon" href="assets/img/idcs.png">

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<link rel="stylesheet" href="assets/css/animate.css">

<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="assets/css/style.css">


<script type="text/javascript">
    
        $(document).ready(function(){
            $('#responsecontainer_current').load('current.php');
            $('#responsecontainer_power').load('power.php');
        });


        // var refreshid_vol = setInterval(function(){
        //     $('#responsecontainer_vol').load('data_vol.php')
        // }, 3000);
</script>

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
        <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span> Users</span> <span class="menu-arrow"></span></a>
        <ul>
            <li><a href="newuser.php">New User </a></li>
            <li><a href="userlists.php">Users List</a></li>
        </ul>
    </li>
    <li class="">
        <a href="rack.php"><img src="assets/img/icons/purchase1.svg" alt="img"><span> List Rack Server</span> </a>
    </li>
    <li class="">
        <a href="treshold.php"><img src="assets/img/icons/transfer1.svg" alt="img"><span> Threshold</span> </a>
    </li>
    <?php endif; ?>
    <li>
        <a href="dashboard.php"><img src="assets/img/icons/dashboard.svg" alt="img"><span> Dashboard</span> </a>
    </li>
    <!-- <li>
        <a href="log.php"><img src="assets/img/icons/eye.svg" alt="img"><span> Log</span> </a>
    </li> -->
    <li class="active">
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
<h2>Daily Monitoring Rack Server <?php echo htmlspecialchars($rack_number); ?> - <?php echo htmlspecialchars($rack_company); ?></h2>
</div>

</div>

<div class="card">
<div class="card-body">
<!-- <div class="table-top">
<div class="search-set">
<div class="search-path">
<a class="btn btn-filter" id="filter_search">
<img src="assets/img/icons/filter.svg" alt="img">
<span><img src="assets/img/icons/closes.svg" alt="img"></span>
</a>
</div>
<div class="search-input">
<a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img"></a>
</div>
</div>
<div class="wordset">

</div>
</div> -->

<div class="card" id="filter_inputs">
<div class="card-body pb-0">
<form method="post" action="">
<div class="row g-3 align-items-center">
    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date">
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <label for="end_date" class="form-label">End Date</label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date">
        </div>
    </div>
    <div class="col-lg-2 col-md-6">
        <div class="d-grid">
            <button type="submit" name="filter" class="btn btn-primary">
                <img src="assets/img/icons/search-whites.svg" alt="Search" width="20" class="me-2"> Search
            </button>
        </div>
    </div>
</div>
</form>
</div>
</div>

        <div class="container" style="text-align: center;">
        <!-- <h3>Graph</h3>
        <p>(Show 6 Last Data)</p> -->
        </div>

        <div class="container">
        <div class="container" id="responsecontainer_current" style="width: 100%; text-align: center;"></div>
        </div><br><br><br>

        <div class="container">
        <div class="container" id="responsecontainer_power" style="width: 100%; text-align: center;"></div>
        </div>

        <!-- <div class="container">
        <div class="container" id="responsecontainer_vol" style="width: 80%; text-align: center;"></div>
        </div> -->

</div>
</div>

</div>
</div>
</div>
<script>
// function loadGraph() {
//   $('#responsecontainer_current')
//     .load('current.php?t=' + new Date().getTime());

//   $('#responsecontainer_power')
//     .load('power.php?t=' + new Date().getTime());
// }

// $(document).ready(function(){
//   loadGraph();
//   setInterval(loadGraph, 5000); // refresh tiap 5 detik
// });
// </script>


<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/js/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
<script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>