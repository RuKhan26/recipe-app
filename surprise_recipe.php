<?php
// Include the database connection
require_once 'db.php';

// SQL to fetch a random recipe
$sql = "SELECT * FROM recipes ORDER BY RAND() LIMIT 1";

try {
    $stmt = $pdo->query($sql);

    // Fetch the random recipe
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        // If a recipe was found, return it as JSON
        echo json_encode($recipe);
    } else {
        // If no recipe found, return an error message
        echo json_encode(["error" => "No recipes found"]);
    }
} catch (PDOException $e) {
    // Handle any errors with the database query
    echo json_encode(["error" => "Failed to fetch recipe: " . $e->getMessage()]);
}
?>

