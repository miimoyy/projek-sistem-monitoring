<?php
include 'connect.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Log_Report.csv');

$output = fopen('php://output', 'w');

// Header kolom
fputcsv($output, array('No', 'Current (A)', 'Power (W)', 'Time'));

// Ambil data dari DB
$query = "SELECT * FROM log_monitoring";
$result = mysqli_query($conn, $query);

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array($no++, $row['current'], $row['power'], $row['created_log']));
}
fclose($output);
exit;
?>
