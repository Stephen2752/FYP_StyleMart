<?php
require 'db.php';
session_start();

$transaction_id = $_POST['transaction_id'] ?? null;

if (!$transaction_id) {
    echo "Invalid transaction.";
    exit;
}

// Confirm seller owns it
$stmt = $pdo->prepare("SELECT seller_id FROM transaction WHERE transaction_id = ?");
$stmt->execute([$transaction_id]);
$row = $stmt->fetch();

if (!$row || $row['seller_id'] != $_SESSION['user_id']) {
    echo "Unauthorized.";
    exit;
}

// Update status
$pdo->prepare("UPDATE transaction SET status = 'shipped' WHERE transaction_id = ?")->execute([$transaction_id]);

header("Location: sell_orders.php");
exit;
