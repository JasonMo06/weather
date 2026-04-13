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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>

<body>
<div class="container">
    <?php require "includes/header.php" ?>
    <main>
        <div class="inner-main">
            <div>
                <h1>Weather</h1>

                <div class="text">
                    <h2>Weather App</h2>
                </div>

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
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Temperature</th>
                        <th>Wind</th>
                        <th>Rain</th>
                        <th>Place</th>
                    </tr>
                    <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["date"]) ?></td>
                        <td><?= htmlspecialchars($row["temperatur"]) ?></td>
                        <td><?= htmlspecialchars($row["wind_strength"]) ?></td>
                        <td><?= htmlspecialchars($row["rain"]) ?></td>
                        <td><?= htmlspecialchars($row["place"]) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <a href="insert_weather.php">Insert weather data</a>
            </div>
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
</body>
</html>
