<?php
session_start();
require_once "db/db.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}
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
            <h1>Page</h1>

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
