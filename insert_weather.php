<?php
session_start();
require_once "db/db.php";

// Have to be logged in to insert weather data
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
            <h1>Insert Weather Data</h1>

            <form action="db/insert_weather_data.php" method="POST">
                <label for="date">Date:</label><br>
                <input type="date" name="date"><br><br>

                <label for="temperature">Temperature:</label><br>
                <input type="number" step="any" name="temperature"><br><br>

                <label for="wind_strength">Wind Strength:</label><br>
                <input type="number" step="any" name="wind_strength"><br><br>

                <label for="rain">Rain:</label><br>
                <input type="number" step="any" name="rain"><br><br>

                <label for="place">Place:</label><br>
                <input type="text" name="place"><br><br>

                <input type="submit">
            </form>
            
            <a href="index.php">Back to home page</a>
        </div>
    </main>
    <?php require "includes/footer.php" ?>
</div>
</body>
