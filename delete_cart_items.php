<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
$cart_ids = $input['cart_ids'] ?? [];

if (!$user_id || empty($cart_ids)) {
    echo json_encode(['success' => false]);
    exit;
}

$placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
$params = $cart_ids;

$stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id IN ($placeholders)");
$stmt->execute($params);

echo json_encode(['success' => true]);
