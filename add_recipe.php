<?php
session_start();
require 'src/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO recipes (title, ingredients, instructions, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $ingredients, $instructions, $user_id]);

    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
</head>
<body>
    <h2>Add a New Recipe</h2>

    <form action="add_recipe.php" method="POST">
        <label for="title">Recipe Title</label>
        <input type="text" name="title" id="title" required><br>

        <label for="ingredients">Ingredients</label>
        <textarea name="ingredients" id="ingredients" rows="4" required></textarea><br>

        <label for="instructions">Instructions</label>
        <textarea name="instructions" id="instructions" rows="4" required></textarea><br>

        <button type="submit">Add Recipe</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
