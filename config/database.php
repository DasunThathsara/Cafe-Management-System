<?php
// Database configuration
$host = 'localhost';
$dbname = 'gallerycafe';
$username = 'root';
$password = '';

try {
    // Create a PDO instance for the database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
