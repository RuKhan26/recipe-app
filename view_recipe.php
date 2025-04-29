<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require 'src/db.php';

// Get the recipe ID from the URL
if (!isset($_GET['id'])) {
    echo "No recipe selected.";
    exit;
}

$recipe_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch the recipe details from the database
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ? AND user_id = ?");
$stmt->execute([$recipe_id, $user_id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    echo "Recipe not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['title']) ?></title>
</head>
<body>
    <h2><?= htmlspecialchars($recipe['title']) ?></h2>
    <h4>Ingredients:</h4>
    <p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>

    <h4>Instructions:</h4>
    <p><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>

    <?php if (!empty($recipe['notes'])): ?>
        <h4>Your Notes:</h4>
        <p><?= nl2br(htmlspecialchars($recipe['notes'])) ?></p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
