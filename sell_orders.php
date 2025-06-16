<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$seller_id = $_SESSION['user_id'];

// Get all relevant transactions where seller is current user
$stmt = $pdo->prepare("
    SELECT t.transaction_id, t.total_amount, t.payment_status, t.status, t.receipt,
           b.username AS buyer_name, t.transaction_date
    FROM transaction t
    JOIN user b ON t.buyer_id = b.user_id
    WHERE t.seller_id = ? AND t.status != 'received' AND t.payment_status != 'Payment Failed'
    ORDER BY t.transaction_id DESC
");
$stmt->execute([$seller_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Seller Orders</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th><th>Buyer</th><th>Total Price (RM)</th><th>Payment</th><th>Status</th><th>Actions</th>
    </tr>
    <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= $t['transaction_id'] ?></td>
            <td><?= htmlspecialchars($t['buyer_name']) ?></td>
            <td><?= number_format($t['total_amount'], 2) ?></td>
            <td><?= htmlspecialchars($t['payment_status']) ?></td>
            <td><?= htmlspecialchars($t['status']) ?></td>
            <td>
                <a href="sell_order_details.php?id=<?= $t['transaction_id'] ?>">Details</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
