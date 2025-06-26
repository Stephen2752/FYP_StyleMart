<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$seller_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT t.transaction_id, t.total_amount, t.payment_status, t.status,
           b.username AS buyer_name, t.transaction_date
    FROM transaction t
    JOIN user b ON t.buyer_id = b.user_id
    WHERE t.seller_id = ? AND t.status = 'received'
    ORDER BY t.transaction_id DESC
");
$stmt->execute([$seller_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order History</title>
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background-color: #f9f9f9;
      color: #333;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .topbar {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      padding: 12px 20px;
      background: #3e3e3e;
      color: white;
      height: 42px;
    }

    .topbar .logo {
      font-size: 20px;
      font-weight: bold;
    }

    .topbar .logo a {
      color: white;
    }

    .container {
      padding: 20px;
    }

    .back-btn {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      cursor: pointer;
      font-weight: bold;
    }

    .back-btn img {
      width: 16px;
      height: auto;
      margin-right: 6px;
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

    @media (max-width: 768px) {
  .wrapper {
    padding: 10px;
  }

  h2 {
    font-size: 16px;
  }

  table {
    font-size: 12px;
    table-layout: fixed;
    word-break: break-word;
  }

  table th, table td {
    padding: 8px;
    white-space: normal;
  }

  .container,
  .back-btn {
    font-size: 14px;
  }

  .back-btn img {
    width: 14px;
  }
}

  </style>
</head>
<body>

  <!-- Header -->
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

  <!-- Back Button Outside -->
  <div class="container">
    <a href="sellerlog.php" class="back-btn">
      <img src="uploads/previous.png" alt="Back">Back
    </a>
  </div>

  <!-- Main Content -->
  <div class="wrapper">
    <h2>Seller Order History (Completed Orders)</h2>

    <table>
      <tr>
        <th>ID</th>
        <th>Buyer</th>
        <th>Total Price (RM)</th>
        <th>Status</th>
        <th>Completed At</th>
      </tr>
      <?php foreach ($transactions as $t): ?>
        <tr>
          <td><?= $t['transaction_id'] ?></td>
          <td><?= htmlspecialchars($t['buyer_name']) ?></td>
          <td><?= number_format($t['total_amount'], 2) ?></td>
          <td><?= htmlspecialchars($t['status']) ?></td>
          <td><?= htmlspecialchars($t['transaction_date']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

</body>
</html>
