<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Set product status to "Sold Out"
    $stmt = $pdo->prepare("UPDATE product SET status = 'Sold Out' WHERE product_id = ?");
    $stmt->execute([$product_id]);

    // Set all product_stock quantities to 0
    $stmt = $pdo->prepare("UPDATE product_stock SET quantity = 0 WHERE product_id = ?");
    $stmt->execute([$product_id]);

    header("Location: admin_product.php?id=$product_id&banned=1");
    exit;
}
?>
