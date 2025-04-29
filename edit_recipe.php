<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
require 'src/db.php';

// Get the recipe ID from the URL
if (!isset($_GET['id'])) {
    echo "No recipe selected.";
    exit;
}

$recipe_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch the recipe details
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ? AND user_id = ?");
$stmt->execute([$recipe_id, $user_id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    echo "Recipe not found.";
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $notes = $_POST['notes'];

    $updateStmt = $pdo->prepare("UPDATE recipes SET title = ?, ingredients = ?, instructions = ?, notes = ? WHERE id = ? AND user_id = ?");
    try {
        $updateStmt->execute([$title, $ingredients, $instructions, $notes, $recipe_id, $user_id]);
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        echo "Error updating recipe: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
</head>
<body>
    <h2>Edit Recipe</h2>

    <form action="edit_recipe.php?id=<?= htmlspecialchars($recipe_id) ?>" method="POST">
        <label for="title">Recipe Title</label><br>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($recipe['title']) ?>" required><br><br>

        <label for="ingredients">Ingredients</label><br>
        <textarea name="ingredients" id="ingredients" rows="4" required><?= htmlspecialchars($recipe['ingredients']) ?></textarea><br><br>

        <label for="instructions">Instructions</label><br>
        <textarea name="instructions" id="instructions" rows="4" required><?= htmlspecialchars($recipe['instructions']) ?></textarea><br><br>

        <label for="notes">Your Notes (optional)</label><br>
        <textarea name="notes" id="notes" rows="3"><?= htmlspecialchars($recipe['notes']) ?></textarea><br><br>

        <button type="submit" name="submit">Update Recipe</button>
    </form>

    <br>
    <a href="dashbaord.php">Back to Dashboard</a>
</body>
</html>
