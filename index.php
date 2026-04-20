<?php
session_start();
require_once "db/db.php";

// Get weather data sent in by users
$stmt = $conn->prepare("
    SELECT date, temperatur, wind_strength, rain, place
    FROM weather_data
");
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

// Array for chart data
$dates = [];
$temperatures = [];
$winds = [];
$rains = [];

foreach ($data as $row)
{
    $dates[] = $row["date"];
    $temperatures[] = $row["temperatur"];
    $winds[] = $row["wind_strength"];
    $rains[] = $row["rain"];
}

// Calculate average stats
$averageTemperature = array_sum($temperatures) / count($temperatures);
$averageWind = array_sum($winds) / count($winds);
$averageRain = array_sum($rains) / count($rains);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="css/index_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<div class="container">
    <?php require "includes/header.php" ?>
    <main>
        <div class="inner-main">
            <div>
                <h1>Weather</h1>

                <div class="input">
                    <input type="text" id="city" placeholder="Enter city name">
                    <button onclick="getWeather()">Get Weather</button>
                </div>

                <div class="display">
                    <p id="weather">City</p>
                </div>
            </div>

            <hr>

            <div>
                <!-- TODO: Add filtering -->
                <!-- TODO: Add delete button (CRUD) -->
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Temperature (C°)</th>
                        <th>Wind (m/s)</th>
                        <th>Rain (mm)</th>
                        <th>Place</th>
                        <th>Delete</th>
                    </tr>
                    <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["date"]) ?></td>
                        <td><?= htmlspecialchars($row["temperatur"]) ?></td>
                        <td><?= htmlspecialchars($row["wind_strength"]) ?></td>
                        <td><?= htmlspecialchars($row["rain"]) ?></td>
                        <td><?= htmlspecialchars($row["place"]) ?></td>
                        <td><a href="#">X</a></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <br>
                <a href="insert_weather.php">Insert weather data</a>
            </div>
            <br><br>
            <div>
                <canvas id="weather-graph" width="600" height="300"></canvas>
            </div>

            <h3>Statistics:</h3>
            <p>Average temperature: <?= round($averageTemperature, 2) ?> C°</p>
            <p>Average wind: <?= round($averageWind, 2) ?> m/s</p>
            <p>Average rain: <?= round($averageRain, 2) ?> mm</p>
        </div>
    </main>

    <?php require "includes/footer.php" ?>
</div>

<script>
async function getWeather() {
    const apiKey = '465ccabc4d7ea62666fd680fd39eb60d';
    const city = document.getElementById('city').value;
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.cod === 200) {
            document.getElementById('weather').innerText =
                `Temperature in ${data.name}: ${data.main.temp}°C`;
        }
        else {
            document.getElementById('weather').innerText = 'City not found!';
        }
    }
    catch (error) {
        console.error('Error fetching data:', error);
    }
}
</script>

<script>
const labels = <?= json_encode($dates) ?>;
const temperatures = <?= json_encode($temperatures) ?>;
const winds = <?= json_encode($winds) ?>;
const rains = <?= json_encode($rains) ?>;

const ctx = document.getElementById('weather-graph').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
            datasets: [
            {
                label: 'Temperature (°C)',
                    data: temperatures,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
            },
            {
                label: 'Wind (m/s)',
                    data: winds,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
            },
            {
                label: 'Rain (mm)',
                    data: rains,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
            }
        ]
    },
    options: {
        responsive: false, // makes it fill the whole parent container if true
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</body>
</html>
