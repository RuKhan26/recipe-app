<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$ingredient = trim($data['ingredient'] ?? '');

if (empty($ingredient)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ingredient name is required']);
    exit;
}

$stmt = $pdo->prepare('INSERT INTO ingredients (user_id, ingredient_name) VALUES (?, ?)');
$stmt->execute([$_SESSION['user_id'], $ingredient]);

echo json_encode(['success' => true]);
?>

