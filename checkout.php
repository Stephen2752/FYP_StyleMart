<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$cart_ids = json_decode($_POST['cart_ids'] ?? '[]', true);
$shipping_address = trim($_POST['shipping_address'] ?? '');

if (!$user_id || empty($cart_ids) || !isset($_FILES['receipt']) || empty($shipping_address)) {
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
        $sellerId = $item['seller_id']; // ensure single seller
    }

    $itemTotal = $item['price'] * $item['quantity'];
    $totalAmount += $itemTotal;

    $cartDetails[] = [
        'product_id' => $item['product_id'],
        'size' => $item['size'],
        'quantity' => $item['quantity'],
        'price' => $item['price']
    ];

    // Check if there is enough stock for all cart items (combined by product_id + size)
$stockCheck = [];

foreach ($cartDetails as $item) {
    $key = $item['product_id'] . '_' . $item['size'];
    if (!isset($stockCheck[$key])) {
        // Get current stock from DB
        $stmt = $pdo->prepare("SELECT quantity FROM product_stock WHERE product_id = ? AND size = ?");
        $stmt->execute([$item['product_id'], $item['size']]);
        $stockCheck[$key] = (int)$stmt->fetchColumn();
    }

    $stockCheck[$key] -= $item['quantity'];

    if ($stockCheck[$key] < 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Insufficient stock for this product'
        ]);
        exit;
    }
}

}

// Insert transaction
$stmt = $pdo->prepare("
    INSERT INTO transaction (buyer_id, seller_id, product_id, payment_status, total_amount, receipt, shipping_address)
    VALUES (?, ?, ?, 'Paid', ?, ?, ?)
");
$stmt->execute([
    $user_id,
    $sellerId,
    $cartDetails[0]['product_id'], // any 1 product ID
    $totalAmount,
    $targetFile,
    $shipping_address
]);

$transaction_id = $pdo->lastInsertId();

// Insert transaction items
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

    // Reduce stock
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
     // ✅ Check and update product status
    $totalStockStmt = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM product_stock WHERE product_id = ?");
    $totalStockStmt->execute([$item['product_id']]);
    $total_stock = (int) $totalStockStmt->fetchColumn();

    if ($total_stock <= 0) {
        $updateStatusStmt = $pdo->prepare("UPDATE product SET status = 'Sold Out' WHERE product_id = ?");
        $updateStatusStmt->execute([$item['product_id']]);
    }
}

// Delete from cart
$inClause = implode(',', array_fill(0, count($cart_ids), '?'));
$deleteStmt = $pdo->prepare("DELETE FROM cart WHERE cart_id IN ($inClause)");
$deleteStmt->execute($cart_ids);

// Respond success
echo json_encode(['success' => true, 'message' => 'Order placed successfully']);

// Notify buyer
$stmt = $pdo->prepare("INSERT INTO notification (user_id, message) VALUES (?, ?)");
$stmt->execute([$user_id, "Your payment for transaction ID $transaction_id has been submitted."]);

// Notify all admins
$adminStmt = $pdo->query("SELECT admin_id FROM admin");
foreach ($adminStmt->fetchAll() as $admin) {
    $stmt = $pdo->prepare("INSERT INTO notification (admin_id, message) VALUES (?, ?)");
    $stmt->execute([$admin['admin_id'], "A new transaction (ID: $transaction_id) has been submitted."]);
}
