<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../login.php");
    exit();
}

$weather_id = $_GET["weather_id"];
$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("DELETE FROM weather_data WHERE weather_id = ? and user_id = ?");
$stmt->bind_param("ii", $weather_id, $user_id);
$stmt->execute();

header("Location: ../index.php");
?>
