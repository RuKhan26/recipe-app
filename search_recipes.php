// src/search_recipes.php
<?php

// Include the DB connection
require_once 'db.php';

// Check if the user has submitted ingredients to search for
if (isset($_POST['ingredients'])) {
    $ingredients = $_POST['ingredients'];

    // Prepare the SQL query to search for recipes with the ingredients
    $query = "SELECT * FROM recipes WHERE ingredients LIKE :ingredients";
    
    // Prepare the statement
    $stmt = $pdo->prepare($query);
    
    // Bind the ingredients to the query (with wildcard for partial matches)
    $stmt->bindValue(':ingredients', '%' . $ingredients . '%');
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all the matching recipes
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if we have matching recipes
    if ($recipes) {
        echo "<h3>Found Recipes:</h3>";
        foreach ($recipes as $recipe) {
            echo "<div><h4>" . htmlspecialchars($recipe['title']) . "</h4>";
            echo "<p><strong>Ingredients:</strong> " . htmlspecialchars($recipe['ingredients']) . "</p>";
            echo "<p><strong>Instructions:</strong> " . htmlspecialchars($recipe['instructions']) . "</p></div>";
        }
    } else {
        echo "No recipes found with the given ingredients.";
    }
}
?>

<!-- HTML Form for user to input ingredients -->
<form method="POST" action="search_recipes.php">
    <label for="ingredients">Enter Ingredients:</label><br>
    <input type="text" id="ingredients" name="ingredients" placeholder="e.g., chicken, rice, tomato"><br><br>
    <input type="submit" value="Search Recipes">
</form>

