<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "idcs";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB_ERROR");
}
?>