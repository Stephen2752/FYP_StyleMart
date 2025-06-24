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
<!-- Topbar -->
<header class="topbar">
<div class="logo"><a href="#">StyleMart Admin Dashboard</a></div>
</header>

<div class="container">
    <div class="back-btn"><a href="#"><img src="uploads/previous.png">Back</a></div>
    <h2 class="page-title">Manage Orders (Admin Panel)</h2>

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

/* Topbar */
.topbar {
display: flex;
justify-content: flex-start; /* logo靠左 */
align-items: center;
padding: 12px 20px;
background: #3e3e3e;
color: white;
height: 42px; /* 保持原来高度 */
}

.topbar .logo {
font-size: 20px;
font-weight: bold;
}

.logo a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  font-size: 20px;
}

.container {
  padding: 20px;
}

.back-btn {
  display: flex;          /* 并排显示 */
  align-items: center;    /* 垂直居中 */
  margin-bottom: 15px;
  cursor: pointer;
  color: #000000;
  font-weight: bold;      /* 可选：让文字更醒目 */
}

.back-btn img {
  width: 16px;            /* 根据需要调整图片大小 */
  height: auto;
  margin-right: 6px;      /* 图片和文字的间距 */
}

.back-btn a {
  color: rgb(0, 0, 0);
  text-decoration: none;
}

.page-title {
  text-align: center;
  font-size: 1.5rem;
  margin: 20px 0;
  color: #333;
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
</div> 
<?php endforeach; ?>

</table>
