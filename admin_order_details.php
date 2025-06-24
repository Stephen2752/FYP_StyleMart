<?php
require 'db.php';
session_start();

// Get transaction ID
$transaction_id = $_GET['id'] ?? null;
if (!$transaction_id) {
    echo "Invalid transaction ID.";
    exit;
}

// Get transaction details
$stmt = $pdo->prepare("
    SELECT t.*, b.username AS buyer_name, s.username AS seller_name, b.contact_info AS buyer_contact, b.phone_number AS buyer_phone
    FROM transaction t
    JOIN user b ON t.buyer_id = b.user_id
    JOIN user s ON t.seller_id = s.user_id
    WHERE t.transaction_id = ?
");
$stmt->execute([$transaction_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit;
}

$isFinalized = in_array($order['payment_status'], ['Verified', 'Payment Failed']);

// Get transaction items
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
<html>
<head>
    <title>Admin Order Details</title>
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

        h2 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: #343a40;
            color: white;
        }

        .btn-green {
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-red {
            background-color: #dc3545;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-gray {
            background-color: gray;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: not-allowed;
        }

        img {
            border: 1px solid #ccc;
            margin-top: 8px;
        }

        .info {
            margin-bottom: 15px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<!-- Topbar -->
<header class="topbar">
<div class="logo"><a href="admin_dashboard.php">StyleMart Admin Dashboard</a></div>
</header>
<div class="container">
    <div class="back-btn"><a href="manageorder.php"><img src="uploads/previous.png">Back</a></div>
    <h2 class="page-title">Admin Order Details - Transaction #<?= $transaction_id ?></h2>

    <div class="info">
        <p><strong>Buyer:</strong> <?= htmlspecialchars($order['buyer_name']) ?> (<?= $order['buyer_contact'] ?><?= $order['buyer_phone'] ?>)</p>
        <p><strong>Seller:</strong> <?= htmlspecialchars($order['seller_name']) ?></p>
        <p><strong>Total Amount:</strong> RM <?= number_format($order['total_amount'], 2) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
        <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['payment_status']) ?></p>
        <p><strong>Receipt:</strong>
            <?php if ($order['receipt']): ?>
                <br><img src="<?= htmlspecialchars($order['receipt']) ?>" width="200">
            <?php else: ?>
                Not uploaded
            <?php endif; ?>
        </p>
    </div>

    <h3>Purchased Items</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Unit Price (RM)</th>
            <th>Subtotal (RM)</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= htmlspecialchars($item['size']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (!$isFinalized): ?>
        <br>
        <form method="post" action="admin_verify_payment.php" id="verifyForm">
            <input type="hidden" name="transaction_id" value="<?= $transaction_id ?>">

            <button type="submit" name="action" value="verify" class="btn-green" id="btn-verify">✅ Verify Payment</button>
            <button type="submit" name="action" value="fail" class="btn-red" id="btn-fail">❌ Payment Failed</button>
        </form>
    <?php else: ?>
        <p><strong>Payment has already been processed.</strong></p>
    <?php endif; ?>
</div>
<script>
document.getElementById('verifyForm')?.addEventListener('submit', function(e) {
    const action = e.submitter.value;
    if (action === 'verify') {
        document.getElementById('btn-fail').className = 'btn-gray';
        document.getElementById('btn-fail').disabled = true;
    } else {
        document.getElementById('btn-verify').className = 'btn-gray';
        document.getElementById('btn-verify').disabled = true;
    }
});
</script>

</body>
</html>
