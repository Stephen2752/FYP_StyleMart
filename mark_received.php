<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
$transaction_id = $input['transaction_id'] ?? null;

if (!$user_id || !$transaction_id) {
    echo json_encode(['success' => false]);
    exit;
}

$stmt = $pdo->prepare("UPDATE transaction SET status = 'received' WHERE transaction_id = ? AND buyer_id = ?");
$stmt->execute([$transaction_id, $user_id]);

echo json_encode(['success' => true]);
