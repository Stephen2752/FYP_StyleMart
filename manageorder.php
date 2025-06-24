<?php
require 'db.php';
session_start();
include 'adminlayout.php';

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

<style>
body {
margin: 0;
font-family: 'Inter', sans-serif;
background: #f2f2f2;
color: #333;
display: flex;
flex-direction: column;
min-height: 100vh; /* full viewport height */
margin: 0;
}

.container {
  padding: 20px;
}


.card {
  width: 100%;
  max-width: 600px;
  background: rgb(44, 44, 44);
  font-family: "Courier New", Courier, monospace;
  border-radius: 12px;
  margin: 16px auto;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  color: #000;
}

.card__title {
  color: white;
  font-weight: bold;
  padding: 10px 16px;
  font-size: 1rem;
  border-bottom: 1px solid #999;
  background: #333;
}

.card__data {
  font-size: 0.85rem;
  display: flex;
  flex-direction: column;
  border-left: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  background-color: white;
}

.item {
  display: flex;
  justify-content: space-between;
  padding: 8px 12px;
  border-bottom: 1px solid #eee;
}

.item:last-child {
  border-bottom: none;
}

.item span:first-child {
  font-weight: bold;
  color: #444;
}

.item span:last-child a {
  color: #1e90ff;
  font-weight: bold;
  text-decoration: none;
}

.canceled .card__data {
  background-color: #f8d7da;
}

.card__data .item:nth-child(odd) {
  background-color: #ffffff;
}

.card__data .item:nth-child(even) {
  background-color: #f3f4f6; /* 浅灰色 */
}

</style>

<div class="container">
  <h2 class="page-title">Manage Orders (Admin Panel)</h2>

  <?php foreach ($transactions as $t): ?>
    <div class="card <?= $t['status'] === 'canceled' ? 'canceled' : '' ?>">
      <div class="card__title">Transaction #<?= $t['transaction_id'] ?></div>
      <div class="card__data">
        <div class="item"><span>Buyer:</span> <span><?= htmlspecialchars($t['buyer']) ?></span></div>
        <div class="item"><span>Seller:</span> <span><?= htmlspecialchars($t['seller']) ?></span></div>
        <div class="item"><span>Amount (RM):</span> <span><?= number_format($t['total_amount'], 2) ?></span></div>
        <div class="item"><span>Payment Status:</span> <span><?= htmlspecialchars($t['payment_status']) ?></span></div>
        <div class="item"><span>Status:</span> <span><?= htmlspecialchars($t['status']) ?></span></div>
        <div class="item"><span>Date:</span> <span><?= htmlspecialchars($t['transaction_date']) ?></span></div>
        <div class="item"><span>Action:</span> 
          <span><a href="admin_order_details.php?id=<?= $t['transaction_id'] ?>">Details</a></span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</table>
