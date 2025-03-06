<?php
    require_once "config.php";

    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

    $message = "";
    $user_id = $_SESSION["user_id"];

    $result = mysqli_query($connection, "SELECT username, email FROM users WHERE id = '$user_id'");
    $userData = mysqli_fetch_assoc($result);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $newUsername = trim($_POST["username"]);
        $newEmail = trim($_POST["email"]);

        $updateQuery = "UPDATE users SET username = '$newUsername', email = '$newEmail' WHERE id = '$user_id'";
        if (mysqli_query($connection, $updateQuery)) {
            $message = "Profile updated successfully.";
            $_SESSION["username"] = $newUsername;
        } else {
            $message = "Error updating profile: " . mysqli_error($connection);
        }
        $result = mysqli_query($connection, "SELECT username, email FROM users WHERE id = '$user_id'");
        $userData = mysqli_fetch_assoc($result);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile - Todo App</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'menu.php'; ?>
  <h2>Your Profile</h2>
  <p><?php echo $message; ?></p>
  <form method="POST" action="">
    <input type="text" name="username" value="<?php echo $userData['username']; ?>" required>
    <input type="email" name="email" value="<?php echo $userData['email']; ?>" placeholder="Email">
    <input type="submit" value="Update Profile">
  </form>
  <p><a href="index.php">Go to Todo List</a></p>
</body>
</html>