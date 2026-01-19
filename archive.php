<?php
include "connect.php";

mysqli_query($conn, "INSERT INTO daily_monitoring_archive
SELECT * FROM daily_monitoring
WHERE created_daily < NOW() - INTERVAL 30 DAY");

mysqli_query($conn, "DELETE FROM daily_monitoring
WHERE created_daily < NOW() - INTERVAL 30 DAY");

echo "ARCHIVE OK";