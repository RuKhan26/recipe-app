<?php
session_start();
require 'src/db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get all user recipes
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE user_id = ?");
$stmt->execute([$user_id]);
$recipes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <a href="logout.php">Logout</a>
    <h3>Your Recipes</h3>

    <a href="add_recipe.php">Add New Recipe</a>

    <ul>
        <?php foreach ($recipes as $recipe): ?>
            <li>
                <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>">
                    <?php echo htmlspecialchars($recipe['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
