<?php
require 'db.php';
$seller_id = $_GET['seller_id'] ?? null;

if (!$seller_id) {
    echo json_encode(['error' => 'Missing seller ID']);
    exit;
}

$stmt = $pdo->prepare("SELECT qrcode FROM user WHERE user_id = ?");
$stmt->execute([$seller_id]);
$qrcode = $stmt->fetchColumn();

echo json_encode(['qrcode' => $qrcode]);
