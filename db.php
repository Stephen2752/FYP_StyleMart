<?php
// db.php - database connection file

$host = 'localhost';
$dbname = 'style_mart';
$user = 'root';
$pass = '';

try {
    // Create a PDO instance and set error mode to exception
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, stop script and show error
    die("Database connection failed: " . $e->getMessage());
}