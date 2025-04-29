<?php
session_start();
require 'src/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $error = "Username already exists!";
    } else {
        // Create new user
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);

        // Log in the user
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
