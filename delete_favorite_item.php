<?php
require 'db.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
$favorite_ids = $input['favorite_ids'] ?? [];

if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

if (empty($favorite_ids)) {
    echo json_encode(['success' => false, 'error' => 'No favorite IDs provided']);
    exit;
}

try {
    $placeholders = implode(',', array_fill(0, count($favorite_ids), '?'));
    $stmt = $pdo->prepare("DELETE FROM favorite WHERE favorite_id IN ($placeholders)");
    $stmt->execute($favorite_ids);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
