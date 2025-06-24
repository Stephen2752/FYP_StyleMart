<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$ids = $data['favorite_ids'] ?? [];

if (!$ids) {
    echo json_encode(['success' => false, 'error' => 'No IDs given']);
    exit;
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$params = array_merge([$user_id], $ids);
$stmt = $pdo->prepare("DELETE FROM favorite WHERE user_id = ? AND favorite_id IN ($placeholders)");
$success = $stmt->execute($params);

echo json_encode(['success' => $success]);
