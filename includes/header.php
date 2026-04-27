<?php
session_start();
require_once "db/db.php";

// Get username of user
$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$username = $row["username"];
?>


<header>
<div class="inner-header">
    <div class="left-header">
        <a href="index.php">HOME</a>
    </div>
    
    <div class="middle-header">
    <p><?php echo htmlspecialchars($username) ?></p>
    </div>

    <div class="right-header">
        <a href="db/logout.php">Logout</a>
    </div>
</div>
</header>
