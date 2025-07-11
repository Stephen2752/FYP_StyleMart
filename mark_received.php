<?php
require 'db.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$transaction_id = $data['transaction_id'] ?? null;

if (!$transaction_id || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

// Make sure user owns this transaction
$stmt = $pdo->prepare("SELECT buyer_id FROM transaction WHERE transaction_id = ?");
$stmt->execute([$transaction_id]);
$order = $stmt->fetch();

if (!$order || $order['buyer_id'] != $_SESSION['user_id']) {
    echo json_encode(['success' => false]);
    exit;
}

// ✅ Mark order as received
$pdo->prepare("UPDATE transaction SET status = 'received' WHERE transaction_id = ?")->execute([$transaction_id]);

// After setting status to 'Received'
$stmt = $pdo->prepare("SELECT seller_id FROM transaction WHERE transaction_id = ?");
$stmt->execute([$transaction_id]);
$seller_id = $stmt->fetchColumn();

$stmt = $pdo->prepare("INSERT INTO notification (user_id, message) VALUES (?, ?)");
$stmt->execute([$seller_id, "The buyer has received the order (ID: $transaction_id)."]);

echo json_encode(['success' => true]);
