<?php
require 'db.php';
session_start();

$transaction_id = $_POST['transaction_id'] ?? null;
$action = $_POST['action'] ?? '';

if (!$transaction_id || !in_array($action, ['verify', 'fail'])) {
    echo "Invalid request.";
    exit;
}

// Enable error reporting for debugging (optional)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($action === 'verify') {
    // ✅ Set payment_status = Verified
    $stmt = $pdo->prepare("UPDATE transaction SET payment_status = 'Verified' WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);

    // 🔻 DECREASE stock
    $stmt = $pdo->prepare("SELECT product_id, size, quantity FROM transaction_item WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get seller ID
    $stmt = $pdo->prepare("SELECT seller_id FROM transaction WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);
    $seller_id = $stmt->fetchColumn();

    // Notify seller
    $stmt = $pdo->prepare("INSERT INTO notification (user_id, message) VALUES (?, ?)");
    $stmt->execute([$seller_id, "Transaction (ID: $transaction_id) has been verified. Please prepare to ship."]);


} elseif ($action === 'fail') {
    // ❌ Set payment_status = Failed + cancel order
    $pdo->prepare("UPDATE transaction SET payment_status = 'Payment Failed', status = 'canceled' WHERE transaction_id = ?")
        ->execute([$transaction_id]);

    // 🔁 RESTORE stock
    $stmt = $pdo->prepare("SELECT product_id, size, quantity FROM transaction_item WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
    $product_id = $item['product_id'];
    $size = trim($item['size']);

    // Restore stock for this size
    $restore = $pdo->prepare("
        UPDATE product_stock 
        SET quantity = quantity + ? 
        WHERE product_id = ? AND size = ?
    ");
    $restore->execute([$item['quantity'], $product_id, $size]);

    if ($restore->rowCount() === 0) {
        error_log("❗ Stock not restored: product_id={$product_id}, size={$size}");
    }

    // ✅ After restoring, check total stock for the product
    $totalStockStmt = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM product_stock WHERE product_id = ?");
    $totalStockStmt->execute([$product_id]);
    $total_stock = (int) $totalStockStmt->fetchColumn();

    // If total > 0, mark product as Available
    if ($total_stock > 0) {
        $updateStatusStmt = $pdo->prepare("UPDATE product SET status = 'Available' WHERE product_id = ?");
        $updateStatusStmt->execute([$product_id]);
    }

    $stmt = $pdo->prepare("SELECT buyer_id FROM transaction WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);
    $buyer_id = $stmt->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO notification (user_id, message) VALUES (?, ?)");
    $stmt->execute([$buyer_id, "Transaction (ID: $transaction_id) failed. Your payment was not verified."]);
    }

}


// ✅ Redirect back to admin page
header("Location: manageorder.php");
exit;
