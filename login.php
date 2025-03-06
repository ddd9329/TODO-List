<?php
    require_once "config.php";

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        $stmt = mysqli_prepare($connection, "SELECT id, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $userId, $hashedPassword);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_fetch($stmt);
            if (password_verify($password, $hashedPassword)) {
                $_SESSION["user_id"] = $userId;
                $_SESSION["username"] = $username;
                header("Location: index.php");
                exit();
            } else {
                $message = "Invalid username or password.";
            }
        } else {
            $message = "Invalid username or password.";
        }
        mysqli_stmt_close($stmt);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Todo App</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Login</h2>
  <p><?php echo $message; ?></p>
  <form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" value="Log In">
  </form>
  <p>Not registered? <a href="register.php">Register here</a>.</p>
</body>
</html>