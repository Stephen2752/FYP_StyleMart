<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT t.transaction_id, t.product_id, t.total_amount, t.payment_status, t.status, t.receipt,
                              p.product_name, p.price, p.user_id AS seller_id,
                              (SELECT pi.image_path FROM product_image pi WHERE pi.product_id = p.product_id ORDER BY pi.image_id ASC LIMIT 1) AS image_path
                       FROM transaction t
                       JOIN product p ON t.product_id = p.product_id
                       WHERE t.buyer_id = ?
                       ORDER BY t.transaction_id DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <style>
        .order-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 20px;
        }
        .order-card {
            display: flex;
            border: 1px solid #ccc;
            padding: 15px;
            align-items: center;
            justify-content: space-between;
        }
        .order-info {
            flex: 1;
            margin-left: 15px;
        }
        .order-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .status-pending {
            background-color: orange;
            color: white;
        }
        .status-shipped {
            background-color: #3498db;
            color: white;
        }
        .status-received {
            background-color: green;
            color: white;
        }
        .product-image {
            width: 80px;
            height: auto;
        }
        .receive-btn {
            padding: 5px 10px;
            background-color: #2ecc71;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .receive-btn:disabled {
            background-color: grey;
            cursor: default;
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
    </style>
</head>
<body>

<h2 style="margin-left: 20px;">Your Order History</h2>

<div class="back-btn"><a href="profile.php"><img src="uploads\previous.png" alt="Back">Back</a></div>

<div class="order-container">
    <?php foreach ($orders as $order): ?>
        <div class="order-card" data-id="<?= $order['transaction_id'] ?>">
            <div class="order-status <?=
                $order['status'] === 'received' ? 'status-received' : (
                    $order['status'] === 'shipped' ? 'status-shipped' : 'status-pending')
            ?>">
                <?= ucfirst($order['status']) ?>
            </div>
            <img src="<?= htmlspecialchars($order['image_path']) ?>" alt="Product Image" class="product-image">
            <div class="order-info">
                <h3><?= htmlspecialchars($order['product_name']) ?></h3>
                <p>Total Paid: RM <?= number_format($order['total_amount'], 2) ?></p>
                <p>Status: <?= htmlspecialchars($order['payment_status']) ?></p>
            </div>
            <div>
                <button class="receive-btn" <?= $order['status'] === 'received' ? 'disabled' : '' ?>>
                    <?= $order['status'] === 'received' ? 'Received' : 'Mark as Received' ?>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.querySelectorAll('.receive-btn').forEach(button => {
    button.addEventListener('click', function () {
        const card = this.closest('.order-card');
        const transactionId = card.dataset.id;
        const statusDiv = card.querySelector('.order-status');

        fetch('mark_received.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ transaction_id: transactionId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.textContent = 'Received';
                this.disabled = true;
                statusDiv.textContent = 'Received';
                statusDiv.className = 'order-status status-received';
            }
        });
    });
});
</script>

</body>
</html>
