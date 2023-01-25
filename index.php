<?php

use InputData\Input;
use SMA\SimpleMovingAverage;

include('src/InputData/Input.php');
include('src/SMA/SimpleMovingAverage.php');

$io = new Input('weather_statistics.csv');
$data = $io->getStatistics();

$given_temp_by_days = array_map(fn($v) => "'$v'",
    array_column($data, 'T')
);
$given_temp_by_days_values = implode(',',$given_temp_by_days);


$sma = new SimpleMovingAverage($data);

$average_days = array_map(fn($v) => "'$v'",
    array_keys($sma->getDayAverage())
);
$average_days_labels = implode(',',$average_days);

$days_average_temp = implode(',', $sma->getDayAverage());


$average_weeks = array_map(fn($v) => "'$v'",
    array_keys($sma->getWeekAverage())
);
$average_weeks_labels = implode(',',$average_weeks);

$average_weeks_values = implode(',', $sma->getWeekAverage());


$average_months = array_map(fn($v) => "'$v'",
    array_keys($sma->getMonthAverage())
);
$average_months_labels = implode(',',$average_months);

$average_months_values = implode(',', $sma->getMonthAverage());


?>


<!doctype html>
<html lang="en">
<head>
    <!- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    <title>Weather</title>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="my-chart">
                <canvas id="line-chart-days" width="800" height="450"></canvas>
                <canvas id="line-chart-weeks" width="800" height="450"></canvas>
                <canvas id="line-chart-months" width="800" height="450"></canvas>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    new Chart(document.getElementById("line-chart-days"), {
        type: 'line',
        data: {
            labels: [<?= $average_days_labels ?>],
            datasets: [{
                data: [<?= $given_temp_by_days_values ?>],
                label: "Given temperatures by days",
                borderColor: "#3e95cd",
                fill: false
            }, {
                data: [<?= $days_average_temp ?>],
                label: "Average temperatures by days",
                borderColor: "#c45850",
                fill: false
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Moving average temperatures'
            }
        }
    });

    new Chart(document.getElementById("line-chart-weeks"), {
        type: 'line',
        data: {
            labels: [<?= $average_weeks_labels ?>],
            datasets: [{
                data: [<?= $average_weeks_values ?>],
                label: "Average temperatures by weeks",
                borderColor: "#50c76b",
                fill: false
            }]
        },
    });

    new Chart(document.getElementById("line-chart-months"), {
        type: 'line',
        data: {
            labels: [<?= $average_months_labels ?>],
            datasets: [{
                data: [<?= $average_months_values ?>],
                label: "Average temperatures by months",
                borderColor: "#d4b64c",
                fill: false
            }]
        },
    });

</script>

</body>