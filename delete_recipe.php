<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require 'src/db.php';

// Check if ID is given
if (!isset($_GET['id'])) {
    echo "No recipe selected to delete.";
    exit;
}

$recipe_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Delete the recipe (only if it belongs to the logged-in user)
$stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
try {
    $stmt->execute([$recipe_id, $user_id]);
    header('Location: dashboard.php');
    exit;
} catch (PDOException $e) {
    echo "Error deleting recipe: " . $e->getMessage();
}
?>
