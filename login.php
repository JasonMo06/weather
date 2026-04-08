<?php
session_start();
require_once "db/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1)
    {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user["password"]))
        {
            // Store user session
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["email"] = $email;

            header("Location: index.php");
            exit;
        }
        else
        {
            $error = "Invalid password";
        }
    }
    else
    {
        $error = "Incorrect email or password";
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="css/register_style.css">    
    </head>

    <body>
        <div class="container">
            <div class="register-box">
                <h2>Login</h2>    

                <div class="error">
                    <?php if (isset($error)) { echo $error; } ?>
                </div>

                <form method="post">
                    <label for="email">Email</label><br>
                    <input type="email" name="email" placeholder="Enter your email" required><br><br>

                    <label for="password">Password</label><br>
                    <input type="password" name="password" placeholder="Enter your password" required><br>

                    <button type="submit">Login</button><br><br>

                    <span>Don't have an account? <a href="register.php">Register here</a></span>
                </form>
            </div>
        </div>
    </body>
</html>
