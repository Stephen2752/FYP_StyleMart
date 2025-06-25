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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Seller Orders</title>
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: #f2f2f2;
      color: #333;
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

    .topbar a {
    text-decoration: none;
    color: white;
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

    a {
    text-decoration: none;
    color: inherit;
    }


    .wrapper {
      max-width: 1000px;
      margin: 0 auto 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    h2 {
      margin-top: 0;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    table th, table td {
      padding: 12px 16px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background-color: #f0f0f0;
    }

    table tr:hover {
      background-color: #f9f9f9;
    }

    .btn-details {
      padding: 6px 12px;
      background-color: #4F46E5;
      color: white;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      font-size: 14px;
      transition: background 0.3s ease;
    }

    .btn-details:hover {
      background-color: #3730a3;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header class="topbar">
    <a href="MainPage.php">StyleMart</a>
  </header>

  <!-- Back button OUTSIDE container -->
  <div class="container">
    <a href="sellerlog.php" class="back-btn">
      <img src="uploads/previous.png" alt="Back">Back
    </a>
  </div>

  <!-- Main content container -->
  <div class="wrapper">
    <h2>Seller Orders</h2>

    <table>
      <tr>
        <th>ID</th>
        <th>Buyer</th>
        <th>Total Price (RM)</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($transactions as $t): ?>
        <tr>
          <td><?= $t['transaction_id'] ?></td>
          <td><?= htmlspecialchars($t['buyer_name']) ?></td>
          <td><?= number_format($t['total_amount'], 2) ?></td>
          <td><?= htmlspecialchars($t['payment_status']) ?></td>
          <td><?= htmlspecialchars($t['status']) ?></td>
          <td>
            <a href="sell_order_details.php?id=<?= $t['transaction_id'] ?>" class="btn-details">Details</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

</body>
</html>

