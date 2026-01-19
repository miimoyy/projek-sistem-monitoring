<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['current'], $_POST['power'])) {
        echo "INVALID";
        exit;
    }

    $current = $_POST['current'];
    $power   = $_POST['power'];

    $sql = "INSERT INTO daily_monitoring (current, power)
            VALUES ('$current', '$power')";

    if ($conn->query($sql) === TRUE) {
        echo "OK";
    } else {
        echo "DB_ERROR";
    }
}
?>
