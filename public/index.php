<?php
session_start();
require_once '../src/Auth.php';
require_once '../src/Recipe.php';

if (isset($_POST['login'])) {
    if (Auth::login($_POST['username'], $_POST['password'])) {
        header('Location: /');
    } else {
        echo 'Invalid login';
    }
}

if (isset($_POST['logout'])) {
    Auth::logout();
    header('Location: /');
}

if (isset($_POST['register'])) {
    // Register the user
    if (Auth::register($_POST['username'], $_POST['password'])) {
        echo 'Registration successful! Please login.';
    } else {
        echo 'Registration failed. Please try again.';
    }
}

$recipes = Recipe::getRecipes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food App</title>

    <style> 
#surprise-recipe-btn {
    padding: 10px 20px;
    background-color: #ff6600;
    color: white;
    border: none;
    cursor: pointer;
}

#recipe-details {
    margin-top: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
}

#recipe-title {
    font-size: 24px;
    font-weight: bold;
}

#recipe-ingredients, #recipe-instructions {
    font-size: 16px;
}
#add-recipe-form {
    margin-top: 20px;
}

#add-recipe-form input, #add-recipe-form textarea {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
}

#add-recipe-form button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
}

#add-recipe-form button:hover {
    background-color: #45a049;
}

#recipe-response {
    margin-top: 20px;
    font-size: 16px;
    font-weight: bold;
}

</style>
</head>



<body>
    <h1>Food Recipes</h1>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        
        <h2>Register</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>

    <?php else: ?>
        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <form method="post">
            <button type="submit" name="logout">Logout</button>
        </form>
        <!-- Link to the search page -->
<a href="search_recipes.php">Search Recipes by Ingredients</a>
<!-- Somewhere in your dashboard or navigation bar -->
<a href="add_recipe.php">Add a New Recipe</a>

        <h2>Your Recipes</h2>
        <ul>
            <?php foreach ($recipes as $recipe): ?>
                <li>
                    <strong><?php echo $recipe['title']; ?></strong><br>
                    Ingredients: <?php echo $recipe['ingredients']; ?><br>
                    Instructions: <?php echo $recipe['instructions']; ?>
                </li>
            <?php endforeach; ?>
        </ul>


        <h2>Add an Ingredient You Have:</h2>
<form id="ingredient-form">
    <input type="text" name="ingredient" placeholder="e.g., Chicken" required>
    <button type="submit">Add Ingredient</button>
</form>

<script>
document.getElementById('ingredient-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const ingredient = form.ingredient.value.trim();

    if (ingredient.length === 0) return;

    const response = await fetch('/add_ingredient.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ingredient })
    });

    const result = await response.json();
    if (result.success) {
        alert('Ingredient added successfully!');
        form.reset();
    } else {
        alert(result.error);
    }
});
</script>

    <?php endif; ?>

    <h2>Add Your Own Recipe</h2>
<form id="add-recipe-form">
    <label for="title">Recipe Title:</label>
    <input type="text" id="title" name="title" required><br><br>

    <label for="ingredients">Ingredients:</label>
    <textarea id="ingredients" name="ingredients" required></textarea><br><br>

    <label for="instructions">Instructions:</label>
    <textarea id="instructions" name="instructions" required></textarea><br><br>

    <button type="submit">Save Recipe</button>
</form>

<div id="recipe-response"></div>

<script>
    document.getElementById('add-recipe-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get the data from the form
        const title = document.getElementById('title').value;
        const ingredients = document.getElementById('ingredients').value;
        const instructions = document.getElementById('instructions').value;

        // Prepare the data to be sent to the backend
        const recipeData = {
            title: title,
            ingredients: ingredients,
            instructions: instructions
        };

        // Send the data to the backend using fetch
        fetch('src/save_recipe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(recipeData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('recipe-response').textContent = 'Error: ' + data.error;
            } else {
                document.getElementById('recipe-response').textContent = 'Success: ' + data.message;
                // Optionally, clear the form
                document.getElementById('add-recipe-form').reset();
            }
        })
        .catch(error => {
            document.getElementById('recipe-response').textContent = 'Error: ' + error;
        });
    });
</script>

    <button id="surprise-recipe-btn">Get Surprise Recipe</button>
<div id="recipe-details">
    <h3 id="recipe-title"></h3>
    <p id="recipe-ingredients"></p>
    <p id="recipe-instructions"></p>
</div>

<script>
    document.getElementById('surprise-recipe-btn').addEventListener('click', function() {
        // Send an AJAX request to get a random recipe
        fetch('src/surprise_recipe.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                } else {
                    // Display the recipe details in the UI
                    document.getElementById('recipe-title').textContent = data.title;
                    document.getElementById('recipe-ingredients').textContent = 'Ingredients: ' + data.ingredients;
                    document.getElementById('recipe-instructions').textContent = 'Instructions: ' + data.instructions;
                }
            })
            .catch(error => {
                alert('Error fetching recipe: ' + error);
            });
    });
</script>
<!-- Inside your HTML file (e.g., index.php) -->

<form id="add-recipe-form">
    <input type="text" id="title" placeholder="Recipe Title" required>
    <textarea id="ingredients" placeholder="Ingredients" required></textarea>
    <textarea id="instructions" placeholder="Instructions" required></textarea>
    <button type="submit">Save Recipe</button>
</form>

<div id="recipe-response"></div>

<!-- Include app.js here -->
<script src="public/js/app.js"></script>
</body>
</html>



</body>
</html>
