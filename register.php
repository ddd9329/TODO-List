<?php
    require_once "config.php";

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $confirm = trim($_POST["confirm"]);

        if ($password !== $confirm) {
            $message = "Passwords do not match.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($connection, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Registration successful! You can now <a href='login.php'>log in</a>.";
            } else {
                $message = "Registration failed: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Todo App</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Register</h2>
  <p><?php echo $message; ?></p>
  <form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email (optional)">
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm" placeholder="Confirm Password" required>
    <input type="submit" value="Register">
  </form>
  <p>Already registered? <a href="login.php">Log in here</a>.</p>
</body>
</html>