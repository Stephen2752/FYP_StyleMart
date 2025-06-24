<?php
require 'db.php';
include 'adminlayout.php';

// Fetch dashboard data
$totalProducts = $pdo->query("SELECT COUNT(*) FROM product")->fetchColumn();
$availableProducts = $pdo->query("SELECT COUNT(*) FROM product WHERE status = 'Available'")->fetchColumn();

$totalSellers = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM product")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
$totalBuyers = $totalUsers - $totalSellers;

$totalTransactions = $pdo->query("SELECT COUNT(*) FROM transaction")->fetchColumn();
$totalComplaints = $pdo->query("SELECT COUNT(*) FROM complaint")->fetchColumn();

$recentTransactions = $pdo->query("
    SELECT t.transaction_id, b.username AS buyer, s.username AS seller, t.total_amount, t.status
    FROM transaction t
    JOIN user b ON t.buyer_id = b.user_id
    JOIN user s ON t.seller_id = s.user_id
    ORDER BY t.transaction_date DESC LIMIT 5
")->fetchAll();

$recentComplaints = $pdo->query("
    SELECT c.complaint_id, u.username AS reporter, c.product_id, c.status
    FROM complaint c
    JOIN user u ON c.user_id = u.user_id
    ORDER BY c.created_at DESC LIMIT 5
")->fetchAll();
?>

<h1>Admin Dashboard</h1>

<div class="dashboard-grid">
    <a href="manage_seller.php">
    <div class="card">
        <h2>Total Sellers</h2>
        <p><?= $totalSellers ?></p>
    </div>
    </a>
    <a href="manage_users.php">
    <div class="card">
        <h2>Total Buyers</h2>
        <p><?= $totalBuyers ?></p>
    </div>
    </a>
    <a href="manage_product.php">
    <div class="card">
        <h2>Total Products</h2>
        <p><?= $totalProducts ?></p>
    </div>
    </a>
    <a href="manage_product.php">
    <div class="card">
        <h2>Available Products</h2>
        <p><?= $availableProducts ?></p>
    </div>
    </a>
</div>

<br>

<h2>Recent Transactions <a href="manageorder.php" class="btn">View All</a></h2>
<table>
    <tr>
        <th>ID</th>
        <th>Buyer</th>
        <th>Seller</th>
        <th>Amount (RM)</th>
        <th>Status</th>
    </tr>
    <?php foreach ($recentTransactions as $t): ?>
        <tr onclick="window.location='admin_order_details.php?id=<?= $t['transaction_id'] ?>'" style="cursor:pointer;">
            <td><?= $t['transaction_id'] ?></td>
            <td><?= htmlspecialchars($t['buyer']) ?></td>
            <td><?= htmlspecialchars($t['seller']) ?></td>
            <td><?= number_format($t['total_amount'], 2) ?></td>
            <td><?= htmlspecialchars($t['status']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>

<h2>Recent Complaints <a href="adminmanagereport.php" class="btn">View All</a></h2>
<table>
    <tr>
        <th>ID</th>
        <th>Reporter</th>
        <th>Product ID</th>
        <th>Status</th>
    </tr>
    <?php foreach ($recentComplaints as $c): ?>
        <tr onclick="window.location='adminmanagereport.php#complaint<?= $c['complaint_id'] ?>'" style="cursor:pointer;">
            <td><?= $c['complaint_id'] ?></td>
            <td><?= htmlspecialchars($c['reporter']) ?></td>
            <td><?= $c['product_id'] ?></td>
            <td><?= $c['status'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</div>
</body>
</html>