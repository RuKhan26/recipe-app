<?php
$host = '127.0.0.1';
$dbname = 'food_app';  // Make sure your database is named "food_app"
$username = 'root';
$password = '';  // Set your MySQL password if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

