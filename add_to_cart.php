<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;
$size = $_POST['size'] ?? null;

if (!$product_id || !$quantity || !$size) {
    die("Missing data.");
}

// Check stock
$stmt = $pdo->prepare("SELECT quantity FROM product_stock WHERE product_id = ? AND size = ?");
$stmt->execute([$product_id, $size]);
$stock = $stmt->fetch();

if (!$stock) {
    die("Invalid size selected.");
}

if ($stock['quantity'] < $quantity) {
    die("Not enough stock available.");
}

// Insert to cart (assuming cart doesn't store size)
$stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $product_id, $quantity]);

// Set success message in session
$_SESSION['cart_message'] = "Added to cart successfully.";

// Redirect to cart.php
header("Location: cart.php");
exit;
