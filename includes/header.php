<?php
require_once "db/db.php";

$username = "Guest";

if (isset($_SESSION["user_id"]))
{
    // Get username of user
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row)
    {
        $username = $row["username"];
    }
}
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
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="db/logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>
</header>
