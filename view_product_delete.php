<?php
// delete_product.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('Unauthorized.');
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'] ?? null;

if (!$product_id) {
    die('No product ID provided.');
}

// Verify the product belongs to the current user
$stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ? AND user_id = ?");
$stmt->execute([$product_id, $user_id]);
$product = $stmt->fetch();

if (!$product) {
    die('Product not found or access denied.');
}

// Start transaction
$pdo->beginTransaction();

try {
    // Delete from cart
    $pdo->prepare("DELETE FROM cart WHERE product_id = ?")->execute([$product_id]);

    // Delete from favorite
    $pdo->prepare("DELETE FROM favorite WHERE product_id = ?")->execute([$product_id]);

    // Delete from comment
    $pdo->prepare("DELETE FROM comment WHERE product_id = ?")->execute([$product_id]);

    // Delete product images
    $pdo->prepare("DELETE FROM product_image WHERE product_id = ?")->execute([$product_id]);

    // Delete product stock
    $pdo->prepare("DELETE FROM product_stock WHERE product_id = ?")->execute([$product_id]);

    // Delete from transaction_item (safety check, even though ON DELETE CASCADE is set)
    $pdo->prepare("DELETE FROM transaction_item WHERE product_id = ?")->execute([$product_id]);

    // Finally delete product
    $pdo->prepare("DELETE FROM product WHERE product_id = ? AND user_id = ?")->execute([$product_id, $user_id]);

    $pdo->commit();
    header('Location: view_product_list.php?message=Product+deleted');
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die('Error deleting product: ' . $e->getMessage());
}
