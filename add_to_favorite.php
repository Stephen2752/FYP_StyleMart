<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    die("Missing product.");
}

// Prevent duplicate favorites
$stmt = $pdo->prepare("SELECT * FROM favorite WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$exists = $stmt->fetch();

if ($exists) {
    header("Location: favorite.php");
    exit;
}

// Add to favorite
$stmt = $pdo->prepare("INSERT INTO favorite (user_id, product_id) VALUES (?, ?)");
$stmt->execute([$user_id, $product_id]);

header("Location: favorite.php");
exit;
?>

