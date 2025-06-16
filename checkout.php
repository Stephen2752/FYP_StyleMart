<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

// Group cart items by seller
$cartDetails = [];
$totalAmount = 0;
$sellerId = null;

foreach ($cart_ids as $cart_id) {
    $stmt = $pdo->prepare("SELECT c.*, p.price, p.user_id AS seller_id FROM cart c JOIN product p ON c.product_id = p.product_id WHERE c.cart_id = ?");
    $stmt->execute([$cart_id]);
    $item = $stmt->fetch();

    if (!$item) continue;

    if ($sellerId === null) {
        $sellerId = $item['seller_id']; // all items must belong to the same seller
    }

    // Calculate total
    $itemTotal = $item['price'] * $item['quantity'];
    $totalAmount += $itemTotal;

    $cartDetails[] = [
        'product_id' => $item['product_id'],
        'size' => $item['size'],
        'quantity' => $item['quantity'],
        'price' => $item['price']
    ];
}

// Insert a single transaction
$stmt = $pdo->prepare("
    INSERT INTO transaction (buyer_id, seller_id, product_id, payment_status, total_amount, receipt)
    VALUES (?, ?, ?, 'Paid', ?, ?)
");
$stmt->execute([
    $user_id,
    $sellerId,
    $cartDetails[0]['product_id'], // still need a product_id (can be the first one)
    $totalAmount,
    $targetFile
]);

$transaction_id = $pdo->lastInsertId();

// Insert each item into transaction_item
foreach ($cartDetails as $item) {
    $stmt = $pdo->prepare("
        INSERT INTO transaction_item (transaction_id, product_id, size, quantity, price)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $transaction_id,
        $item['product_id'],
        $item['size'],
        $item['quantity'],
        $item['price']
    ]);

    // Decrease product_stock
    $updateStock = $pdo->prepare("
        UPDATE product_stock 
        SET quantity = quantity - ? 
        WHERE product_id = ? AND size = ?
    ");
    $updateStock->execute([
        $item['quantity'],
        $item['product_id'],
        $item['size']
    ]);
}

// Remove items from cart
$inClause = implode(',', array_fill(0, count($cart_ids), '?'));
$deleteStmt = $pdo->prepare("DELETE FROM cart WHERE cart_id IN ($inClause)");
$deleteStmt->execute($cart_ids);

echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
