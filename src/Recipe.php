<?php
require_once 'db.php';

class Recipe {
    public static function addRecipe($userId, $title, $ingredients, $instructions) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO recipes (user_id, title, ingredients, instructions) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $title, $ingredients, $instructions]);
    }

    public static function getRecipes() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM recipes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
