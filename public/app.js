// app.js
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
