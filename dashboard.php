<?php

session_start();

// Cek login
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "Guest";
$role = isset($_SESSION["role"]) ? $_SESSION["role"] : "User";

include 'connect.php';

$userQuery = "SELECT a.id_rack, nr.number, nr.company 
              FROM access a
              LEFT JOIN number_rack nr ON a.id_rack = nr.id_rack 
              WHERE a.username = '$username' 
              LIMIT 1";

$userResult = mysqli_query($conn, $userQuery);
$userData = mysqli_fetch_assoc($userResult);

$id_rack = $userData['id_rack'];   //ini buat nentuin data rak apa yang bakal diambil next harus dinamis berdasaran rak apa si user ter attach
$rackInfoQuery = "SELECT number, company FROM number_rack WHERE id_rack = '$id_rack'";
$rackInfoResult = mysqli_query($conn, $rackInfoQuery);
$rackInfo = mysqli_fetch_assoc($rackInfoResult);

$rack_number = $rackInfo['number'] ?? 'Unknown';
$rack_company = $rackInfo['company'] ?? 'Unknown';


$query = "SELECT * FROM daily_monitoring WHERE id_rack = '$id_rack'";
$sql = mysqli_query($conn, $query);
$no = 0;

if (isset($_POST['filter'])) {
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    
    $sql = mysqli_query($conn, "
        SELECT * FROM daily_monitoring 
        WHERE id_rack = '$id_rack' 
        AND created_daily BETWEEN '$start_date' AND '$end_date'
    ");
} else {
    $sql = mysqli_query($conn, "SELECT * FROM daily_monitoring WHERE id_rack = '$id_rack'");
}

$query = "SELECT number_treshold FROM treshold WHERE id_rack = '$id_rack'"; // atau id lainnya
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

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
<title>Dashboard - Interlink Data Center Sejahtera</title>

<link rel="shortcut icon" type="image/x-icon" href="assets/img/idcs.png">

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<link rel="stylesheet" href="assets/css/animate.css">

<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="assets/css/style.css">

<style>
    tr.table-danger td {
        background-color: #f8d7da !important;
    }
</style>

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

    <li class="active">
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
<h2>Daily Monitoring Rack Server <?php echo htmlspecialchars($rack_number); ?> - <?php echo htmlspecialchars($rack_company); ?></h2>
</div>

</div>

<div class="card">
<div class="card-body">
<div class="table-top">
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

    <!-- Export Buttons to CSV & PDF -->
    <div class="ms-auto d-flex gap-2">
        <a href="export_daily.php" class="btn btn-sm btn-success">
            <i class="fas fa-file-csv me-1"></i> CSV
        </a>
        <button onclick="exportToPDF()" class="btn btn-sm btn-danger">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </button>
    </div>
    

<div class="wordset">

</div>
</div>

<div class="card" id="filter_inputs">
<div class="card-body pb-0">
<form method="post" action="">
<div class="row g-3 align-items-center">
    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="form-group">
            <label for="end_date" class="form-label">End Date</label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
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

<div class="table-responsive">
<table class="table  datanew">
<thead>
<tr>
<th>No </th>
<th>Current (A)</th>
 <th>Power (W)</th>
<th>Time</th>
</tr>
</thead>
<tbody>
<?php
$threshold = isset($row['number_treshold']) ? floatval($row['number_treshold']) : 5.5;
while($result = mysqli_fetch_assoc($sql)){
    $no++;
    $current = floatval($result['current']);
    $rowClass = ($current > $threshold) ? 'table-danger' : '';
?>
<tr class="<?php echo $rowClass; ?>">
    <td style="text-align: center;"><?php echo +$no; ?></td>
    <td><?php echo $result['current']; ?></td>
    <td><?php echo $result['power']; ?></td>
    <td><?php echo $result['created_daily']; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
</div>
</div>
</div>

</div>
</div>
</div>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
    async function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFontSize(18);
        doc.text('Report Monitoring Data', 14, 15);

        const headers = [["No", "Current (A)", "Power (W)", "Time"]];
        const rows = [];

        const table = document.querySelector("table tbody");
        const trs = table.querySelectorAll("tr");

        trs.forEach((tr) => {
            const cols = tr.querySelectorAll("td");
            const row = [];
            cols.forEach((td) => row.push(td.textContent.trim()));
            rows.push(row);
        });

        doc.autoTable({
            startY: 25,
            head: headers,
            body: rows
        });

        doc.save("Daily_Monitoring.pdf");
    }
</script>

</body>
</html>