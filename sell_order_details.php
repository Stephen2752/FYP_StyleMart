<?php
require 'db.php';
session_start();

$transaction_id = $_GET['id'] ?? null;
if (!$transaction_id) {
    echo "Invalid order.";
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.*, b.username AS buyer_name, b.contact_info, b.phone_number
    FROM transaction t
    JOIN user b ON t.buyer_id = b.user_id
    WHERE t.transaction_id = ?
");
$stmt->execute([$transaction_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order || $order['seller_id'] != $_SESSION['user_id']) {
    echo "Unauthorized or order not found.";
    exit;
}

$stmt = $pdo->prepare("
    SELECT ti.*, p.product_name
    FROM transaction_item ti
    JOIN product p ON ti.product_id = p.product_id
    WHERE ti.transaction_id = ?
");
$stmt->execute([$transaction_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Details</title>
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background-color: #f2f2f2;;
      color: #333;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .topbar {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      background: #3e3e3e;
      color: white;
      height: 42px;
    }

    .topbar .logo a {
      color: white;
      font-size: 20px;
      font-weight: bold;
    }

    .container {
      padding: 20px;
    }

    .back-btn {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .back-btn img {
      width: 16px;
      margin-right: 6px;
    }

    .wrapper {
      max-width: 900px;
      margin: 0 auto 40px auto;
      background: #fff;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    h2, h3 {
      margin-top: 0;
    }

    p {
      margin: 10px 0;
      line-height: 1.5;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    table th, table td {
      padding: 12px 14px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    table th {
      background-color: #f0f0f0;
    }

    table tr:hover {
      background-color: #fafafa;
    }

    button {
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      background-color: #2563eb;
      color: white;
      font-weight: bold;
      cursor: pointer;
      font-size: 14px;
    }

    button:hover {
      background-color: #1e40af;
    }
  </style>
</head>
<body>

  <!-- Topbar -->
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

  <!-- Back Button -->
  <div class="container">
    <a href="sell_orders.php" class="back-btn">
      <img src="uploads/previous.png" alt="Back">Back
    </a>
  </div>

  <!-- Order Detail Content -->
  <div class="wrapper">
    <h2>Order #<?= $transaction_id ?> (Sell Side)</h2>

    <p><strong>Buyer:</strong> <?= htmlspecialchars($order['buyer_name']) ?></p>
    <p><strong>Contact:</strong> <?= htmlspecialchars($order['contact_info']) ?> | <?= htmlspecialchars($order['phone_number']) ?></p>
    <p><strong>Shipping Address:</strong><br><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
    <p><strong>Total:</strong> RM <?= number_format($order['total_amount'], 2) ?></p>
    <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['payment_status']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

    <h3>Items</h3>
    <table>
      <tr>
        <th>Product</th><th>Size</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th>
      </tr>
      <?php foreach ($items as $i): ?>
        <tr>
          <td><?= htmlspecialchars($i['product_name']) ?></td>
          <td><?= htmlspecialchars($i['size']) ?></td>
          <td><?= (int)$i['quantity'] ?></td>
          <td>RM <?= number_format($i['price'], 2) ?></td>
          <td>RM <?= number_format($i['price'] * $i['quantity'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>

    <?php if ($order['payment_status'] === 'Verified' && $order['status'] !== 'shipped'): ?>
      <form method="post" action="mark_shipped.php" style="margin-top: 20px;">
        <input type="hidden" name="transaction_id" value="<?= $transaction_id ?>">
        <button type="submit">Mark as Shipped</button>
      </form>
    <?php endif; ?>
  </div>

</body>
</html>
