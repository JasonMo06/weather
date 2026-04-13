<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Get form data
    $date = trim($_POST["date"]);
    $temperature = trim($_POST["temperature"]);
    $wind_strength = trim($_POST["wind_strength"]);
    $rain = trim($_POST["rain"]);
    $place = trim($_POST["place"]);

    // Note: temperature is written wrong in the db and is written temperatur
    $stmt = $conn->prepare("
        INSERT INTO weather_data 
        (date, temperatur, wind_strength, rain, place, user_id) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sdddsi", $date, $temperature, $wind_strength, $rain, $place, $user_id);
    if ($stmt->execute())
    {
        header("Location: ../index.php");
        exit();
    }
}
?>
