<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$cart_ids = json_decode($_POST['cart_ids'] ?? '[]', true);

if (!$user_id || empty($cart_ids) || !isset($_FILES['receipt'])) {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
    exit;
}

// Handle receipt upload
$uploadDir = 'uploads/receipts/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
$receiptName = basename($_FILES['receipt']['name']);
$targetFile = $uploadDir . time() . '_' . $receiptName;

if (!move_uploaded_file($_FILES['receipt']['tmp_name'], $targetFile)) {
    echo json_encode(['success' => false, 'error' => 'Upload failed']);
    exit;
}

foreach ($cart_ids as $cart_id) {
    // Get item details
    $stmt = $pdo->prepare("SELECT c.*, p.price, p.user_id AS seller_id FROM cart c JOIN product p ON c.product_id = p.product_id WHERE c.cart_id = ?");
    $stmt->execute([$cart_id]);
    $item = $stmt->fetch();

    if (!$item) continue;

    $totalAmount = $item['price'] * $item['quantity'];

    // Insert transaction
    $stmt = $pdo->prepare("
        INSERT INTO transaction (buyer_id, seller_id, product_id, payment_status, total_amount, receipt)
        VALUES (?, ?, ?, 'Paid', ?, ?)
    ");
    $stmt->execute([
        $user_id,
        $item['seller_id'],
        $item['product_id'],
        $totalAmount,
        $targetFile
    ]);

    // Remove from cart
    $pdo->prepare("DELETE FROM cart WHERE cart_id = ?")->execute([$cart_id]);
}

echo json_encode(['success' => true]);
