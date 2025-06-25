<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all transactions for the current user
$stmt = $pdo->prepare("SELECT * FROM transaction WHERE buyer_id = ? ORDER BY transaction_id DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's saved addresses
$addrStmt = $pdo->prepare("SELECT address_id, address FROM user_address WHERE user_id = ?");
$addrStmt->execute([$user_id]);
$savedAddresses = $addrStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch related items per transaction
$allOrders = [];
foreach ($transactions as $t) {
    $stmt = $pdo->prepare("
        SELECT ti.*, p.product_name, p.price, 
               (SELECT pi.image_path 
                FROM product_image pi 
                WHERE pi.product_id = ti.product_id 
                ORDER BY pi.image_id ASC LIMIT 1) AS image_path
        FROM transaction_item ti
        JOIN product p ON ti.product_id = p.product_id
        WHERE ti.transaction_id = ?
    ");
    $stmt->execute([$t['transaction_id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $allOrders[] = [
        'transaction_id' => $t['transaction_id'],
        'total_amount' => $t['total_amount'],
        'payment_status' => $t['payment_status'],
        'status' => $t['status'],
        'receipt' => $t['receipt'],
        'shipping_address' => $t['shipping_address'],
        'items' => $items
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
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

.topbar {
  display: flex;
  justify-content: flex-start; /* logo靠左 */
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

.logo a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  font-size: 20px;
}

h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 24px;
}

.order-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 25px;
}

.order-card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  padding: 20px;
  width: 100%;
  max-width: 750px;
  box-sizing: border-box;
}

.order-status {
  font-weight: bold;
  padding: 6px 12px;
  border-radius: 20px;
  margin-bottom: 15px;
  display: inline-block;
  font-size: 14px;
}

.status-pending    { background-color: orange;   color: white; }
.status-shipped    { background-color: #3498db; color: white; }
.status-received   { background-color: green;    color: white; }
.status-canceled   { background-color: red;      color: white; }

.item-row {
  display: flex;
  border-top: 1px solid #eee;
  padding: 15px 0;
  gap: 15px;
}

.product-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 6px;
}

.order-info {
  flex: 1;
}

.order-info h4 {
  margin: 0 0 5px;
  font-size: 16px;
}

.order-info p {
  margin: 3px 0;
  font-size: 14px;
  color: #555;
}

.summary-info {
  margin-top: 15px;
  font-size: 15px;
}

.address-section {
  margin-top: 12px;
}

.address-dropdown {
  padding: 6px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.save-address-btn {
  padding: 6px 10px;
  margin-left: 10px;
  background-color: #2980b9;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.receive-btn-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-top: 20px;
}

.receive-btn {
  padding: 8px 16px;
  background-color: #2ecc71;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

.receive-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
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

.container {
padding: 20px;
}

    </style>
</head>
<body>
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

<div class="container">
    
<div class="back-btn"><a href="profile.php"><img src="uploads/previous.png" alt="Back">Back</a></div>
<h2>Your Order History</h2>

<div class="order-container">
<?php foreach ($allOrders as $order): ?>
    <div class="order-card" data-id="<?= $order['transaction_id'] ?>">
        <?php
            $statusClass = match ($order['status']) {
                'received' => 'status-received',
                'shipped' => 'status-shipped',
                'canceled' => 'status-canceled',
                default => 'status-pending',
            };
        ?>
        <div class="order-status <?= $statusClass ?>">
            <?= ucfirst($order['status']) ?>
        </div>

        <?php foreach ($order['items'] as $item): ?>
            <div class="item-row">
                <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="Product Image" class="product-image">
                <div class="order-info">
                    <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                    <p>Size: <?= htmlspecialchars($item['size']) ?></p>
                    <p>Quantity: <?= $item['quantity'] ?></p>
                    <p>Price: RM <?= number_format($item['price'], 2) ?></p>
                    <p>Subtotal: RM <?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <p><strong>Total Paid:</strong> RM <?= number_format($order['total_amount'], 2) ?></p>
        <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['payment_status']) ?></p>

        <div>
            <strong>Shipping Address:</strong><br>
            <?php if ($order['status'] === 'pending'): ?>
                <select class="address-dropdown" data-transaction-id="<?= $order['transaction_id'] ?>">
                    <?php foreach ($savedAddresses as $addr): ?>
                        <option value="<?= htmlspecialchars($addr['address']) ?>" <?= ($addr['address'] === $order['shipping_address']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($addr['address']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="save-address-btn">Save Address</button>
            <?php else: ?>
                <p><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <?php
                $isDisabled = in_array($order['status'], ['received', 'canceled']);
                $btnText = $order['status'] === 'received' ? 'Received' : (
                    $order['status'] === 'canceled' ? 'Canceled' : 'Mark as Received'
                );
            ?>
            <div class="receive-btn-wrapper">
                <button class="receive-btn" <?= $isDisabled ? 'disabled' : '' ?>>
                    <?= $btnText ?>
                </button>
            </div>

        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
<script>
document.querySelectorAll('.receive-btn').forEach(button => {
    button.addEventListener('click', function () {
        if (this.disabled) return;

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

document.querySelectorAll('.save-address-btn').forEach(button => {
    button.addEventListener('click', function () {
        const card = this.closest('.order-card');
        const transactionId = card.dataset.id;
        const select = card.querySelector('.address-dropdown');
        const selectedAddress = select.value;

        fetch('update_transaction_address.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ transaction_id: transactionId, shipping_address: selectedAddress })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Address updated successfully!');
            } else {
                alert(data.error || 'Failed to update address.');
            }
        });
    });
});
</script>

</body>
</html>
