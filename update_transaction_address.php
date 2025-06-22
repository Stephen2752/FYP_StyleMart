<?php
require 'db.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$transaction_id = $data['transaction_id'] ?? null;
$shipping_address = $data['shipping_address'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$transaction_id || !$shipping_address || !$user_id) {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
    exit;
}

$stmt = $pdo->prepare("UPDATE transaction SET shipping_address = ? WHERE transaction_id = ? AND buyer_id = ?");
$success = $stmt->execute([$shipping_address, $transaction_id, $user_id]);

echo json_encode(['success' => $success]);
