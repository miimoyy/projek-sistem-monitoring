<?php
header('Content-Type: application/json');
include 'connect.php'; // Koneksi ke database

// Ambil data threshold dari tabel
$sql = "SELECT number_treshold FROM treshold WHERE id_rack = 14";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $threshold = floatval($row['number_treshold']);

        echo json_encode([
            "threshold" => $threshold
        ]);
    } else {
        echo json_encode([
            "error" => "Threshold not found"
        ]);
    }
} else {
    echo json_encode([
        "error" => "Database query failed: " . mysqli_error($conn)
    ]);
}
?>
