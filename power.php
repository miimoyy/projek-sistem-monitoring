<?php

    include 'connect.php';

    $id_rack = $userData['id_rack'] ?? 14;




    // Ambil ID terakhir khusus untuk rack 9
    $sql_ID = mysqli_query($conn, "SELECT MAX(ID) AS max_id FROM daily_monitoring WHERE id_rack = '$id_rack'");
    $data_ID = mysqli_fetch_array($sql_ID);
    $ID_akhir = $data_ID['max_id'];
    $ID_awal = $ID_akhir - 5;

    // Ambil 5 data terakhir berdasarkan ID dan id_rack = 9
    $created_daily = mysqli_query($conn, "
        SELECT created_daily FROM daily_monitoring 
        WHERE id_rack = '$id_rack' 
        AND ID BETWEEN '$ID_awal' AND '$ID_akhir' 
        ORDER BY ID ASC
    ");

    $power = mysqli_query($conn, "
        SELECT power FROM daily_monitoring 
        WHERE id_rack = '$id_rack' 
        AND ID BETWEEN '$ID_awal' AND '$ID_akhir' 
        ORDER BY ID ASC
    ");

?>

<div class="panel panel-primary">
    <!-- <div class="panel-heading">
        Gafik power
    </div> -->
    

    <div class="panel-body" style="display: flex; align-items: center;">

    <div style="writing-mode: vertical-rl; transform: rotate(180deg); color: #015078; font-weight: normal; font-style: italic; font-size: 30px; margin-right: 10px;">
                    Watt
    </div>
        <canvas id="myChart_power"></canvas>

        <script type="text/javascript">
            var canvas = document.getElementById('myChart_power').getContext('2d');
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
                    label: "Power (Watt)",
                    fill: true,
                    backgroundColor: "rgba(1, 80, 120, 0.5)",
                    borderColor: "rgba(1, 80, 120, 1)",
                    lineTension: 0.5,
                    pointRadius: 5, 
                    data: [
                        <?php
                        while($data_power = mysqli_fetch_array($power))
                        {
                            echo $data_power['power'].',' ;
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