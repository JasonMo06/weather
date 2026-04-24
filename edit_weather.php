<?php
session_start();
require_once "db/db.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}

if (!isset($_GET["weather_id"]))
{
    die("Weather_id missing");
}

$weather_id = $_GET["weather_id"];
$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $temperature = trim($_POST["temperature"]);
    $wind_strength = trim($_POST["wind_strength"]);
    $rain = trim($_POST["rain"]);
    $place = trim($_POST["place"]);

    $stmt = $conn->prepare("
        UPDATE weather_data
        SET temperatur = ?,
        wind_strength = ?,
        rain = ?,
        place = ?
        WHERE weather_id = ? AND user_id = ?
    ");
    $stmt->bind_param("dddsii", $temperature, $wind_strength, $rain, $place, $weather_id, $user_id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}

// GET existing row data
$stmt = $conn->prepare("
SELECT temperatur, wind_strength, rain, place
FROM weather_data
WHERE weather_id = ? AND user_id = ?
");
$stmt->bind_param("ii", $weather_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit weather data</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>

<body>
<?php require "includes/header.php"?>
<div class="container">
    <main>
        <div class="inner-main">
            <h1>Edit weather data</h1>

            <form method="POST">
                <label for="temperature">Temperature:</label><br>
                <input type="number" step="any" name="temperature" value="<?= htmlspecialchars($row["temperatur"]) ?>"><br><br>

                <label for="wind_strength">Wind Strength:</label><br>
                <input type="number" step="any" name="wind_strength" value="<?= htmlspecialchars($row["wind_strength"]) ?>"><br><br>

                <label for="rain">Rain:</label><br>
                <input type="number" step="any" name="rain" value="<?= htmlspecialchars($row["rain"]) ?>"><br><br>

                <label for="place">Place:</label><br>
                <input type="text" name="place" value="<?= htmlspecialchars($row["place"]) ?>"><br><br>

                <input type="submit">
            </form>
        </div>
    </main>
</div>
</body>
</html>
