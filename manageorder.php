<?php
require 'db.php';
session_start();

// OPTIONAL: Ensure only admin can access
// if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit(); }

$stmt = $pdo->query("
    SELECT t.transaction_id, t.total_amount, t.payment_status, t.status, t.receipt, 
           b.username AS buyer, s.username AS seller, t.transaction_date
    FROM transaction t
    JOIN user b ON t.buyer_id = b.user_id
    JOIN user s ON t.seller_id = s.user_id
    ORDER BY t.transaction_id DESC
");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Orders (Admin Panel)</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th><th>Buyer</th><th>Seller</th><th>Amount (RM)</th>
        <th>Payment Status</th><th>Status</th><<th>Actions</th>
    </tr>
    <?php foreach ($transactions as $t): ?>
        <tr style="background-color: <?= $t['status'] === 'canceled' ? '#f8d7da' : 'white' ?>">
            <td><?= $t['transaction_id'] ?></td>
            <td><?= htmlspecialchars($t['buyer']) ?></td>
            <td><?= htmlspecialchars($t['seller']) ?></td>
            <td><?= number_format($t['total_amount'], 2) ?></td>
            <td><?= htmlspecialchars($t['payment_status']) ?></td>
            <td><?= htmlspecialchars($t['status']) ?></td>
            <td>
                <a href="admin_order_details.php?id=<?= $t['transaction_id'] ?>">Details</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
