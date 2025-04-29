<?php
require_once 'db.php';  // Ensure the database connection is included

// Check if the request is a POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the POST request (assuming they are sent as JSON)
    $input = json_decode(file_get_contents('php://input'), true);

    // Get user data from session or you could hardcode a user ID for now
    $user_id = 1;  // Replace with the actual logged-in user ID if available

    // Extract data
    $title = $input['title'] ?? '';
    $ingredients = $input['ingredients'] ?? '';
    $instructions = $input['instructions'] ?? '';

    // Validate if all fields are provided
    if (empty($title) || empty($ingredients) || empty($instructions)) {
        echo json_encode(["error" => "All fields are required"]);
        exit;
    }

    // SQL to insert the recipe into the database
    $sql = "INSERT INTO recipes (title, ingredients, instructions, user_id) VALUES (:title, :ingredients, :instructions, :user_id)";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->bindParam(':user_id', $user_id);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["message" => "Recipe saved successfully"]);
        } else {
            echo json_encode(["error" => "Failed to save recipe"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
