<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
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

// Set success message in session
$_SESSION['cart_message'] = "Added to cart successfully.";

// Check if item with same size already in cart
$stmt = $pdo->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
$stmt->execute([$user_id, $product_id, $size]);
$existing = $stmt->fetch();

if ($existing) {
    $newQty = $existing['quantity'] + $quantity;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $stmt->execute([$newQty, $existing['cart_id']]);
} else {
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity, size) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $product_id, $quantity, $size]);
}

// Redirect to cart.php
header("Location: cart.php");
exit;
