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

    foreach ($items as $item) {
        $size = trim($item['size']); // remove whitespace

        $decrease = $pdo->prepare("
            UPDATE product_stock 
            SET quantity = quantity - ? 
            WHERE product_id = ? AND size = ?
        ");
        $decrease->execute([$item['quantity'], $item['product_id'], $size]);

        if ($decrease->rowCount() === 0) {
            error_log("❗ Stock not decreased: product_id={$item['product_id']}, size={$size}");
        }
    }

} elseif ($action === 'fail') {
    // ❌ Set payment_status = Failed + cancel order
    $pdo->prepare("UPDATE transaction SET payment_status = 'Payment Failed', status = 'canceled' WHERE transaction_id = ?")
        ->execute([$transaction_id]);

    // 🔁 RESTORE stock
    $stmt = $pdo->prepare("SELECT product_id, size, quantity FROM transaction_item WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        $size = trim($item['size']);

        $restore = $pdo->prepare("
            UPDATE product_stock 
            SET quantity = quantity + ? 
            WHERE product_id = ? AND size = ?
        ");
        $restore->execute([$item['quantity'], $item['product_id'], $size]);

        if ($restore->rowCount() === 0) {
            error_log("❗ Stock not restored: product_id={$item['product_id']}, size={$size}");
        }
    }
}

// ✅ Redirect back to admin page
header("Location: manageorder.php");
exit;
