<?php

    include 'connect.php';


$id_rack = $userData['id_rack'] ?? 14;

    // Ambil ID terakhir untuk id_rack = 9
    $sql_ID = mysqli_query($conn, "SELECT MAX(ID) AS max_id FROM daily_monitoring WHERE id_rack = '$id_rack'");
    $data_ID = mysqli_fetch_array($sql_ID);
    $ID_akhir = $data_ID['max_id'];
    $ID_awal = $ID_akhir - 5;

    // Ambil data created_daily dan current khusus id_rack 9 dan rentang ID
    $created_daily = mysqli_query($conn, "
        SELECT created_daily FROM daily_monitoring 
        WHERE id_rack = '$id_rack' AND ID >= '$ID_awal' AND ID <= '$ID_akhir' 
        ORDER BY ID ASC
    ");

    $current = mysqli_query($conn, "
        SELECT current FROM daily_monitoring 
        WHERE id_rack = '$id_rack' AND ID >= '$ID_awal' AND ID <= '$ID_akhir' 
        ORDER BY ID ASC
    ");

?>

<div class="panel panel-primary">
    <!-- <div class="panel-heading">
        Gafik current
    </div> -->

    <div class="panel-body" style="display: flex; align-items: center"; >

    <div style="writing-mode: vertical-rl; transform: rotate(180deg); color: #04940c; font-weight: normal; font-style: italic; font-size: 30px; margin-right: 10px;">
                    Ampere
    </div>

        <canvas id="myChart_current"></canvas>

        <script type="text/javascript">
            var canvas = document.getElementById('myChart_current').getContext('2d');
            var data = {
                labels: [
                    <?php
                        while($data_created_daily = mysqli_fetch_array($created_daily))
                        {
                            echo '"'.$data_created_daily['created_daily'].'",' ;
                        }
                    ?>
                ],
                datasets: [{
                    label: "Current (Ampere)",
                    fill: true,
                    backgroundColor: "rgba(47, 247, 57, 0.5)",
                    borderColor: "rgba(10, 196, 19, 1)",
                    lineTension: 0.5,
                    pointRadius: 5, 
                    data: [
                        <?php
                        while($data_current = mysqli_fetch_array($current))
                        {
                            echo $data_current['current'].',' ;
                        }
                    ?>
                    ]
                }]
            };

            var options = {
                responsive: true,
                animation: {
                    duration: 4000, // Animasi berlangsung 2 detik
                    easing: 'easeInOutQuart' // Efek animasi yang halus
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };

            var myLineChart = new Chart(canvas, {
                type: 'line',
                data: data,
                options: options
            });


        </script>
    </div>
</div>