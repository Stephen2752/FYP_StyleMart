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

<h2>Order #<?= $transaction_id ?> (Sell Side)</h2>

<p><strong>Buyer:</strong> <?= htmlspecialchars($order['buyer_name']) ?></p>
<p><strong>Contact:</strong> <?= htmlspecialchars($order['contact_info']) ?> | <?= htmlspecialchars($order['phone_number']) ?></p>
<p><strong>Total:</strong> RM <?= number_format($order['total_amount'], 2) ?></p>
<p><strong>Payment Status:</strong> <?= $order['payment_status'] ?></p>
<p><strong>Status:</strong> <?= $order['status'] ?></p>

<h3>Items</h3>
<table border="1" cellpadding="6">
    <tr><th>Product</th><th>Size</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
    <?php foreach ($items as $i): ?>
        <tr>
            <td><?= htmlspecialchars($i['product_name']) ?></td>
            <td><?= $i['size'] ?></td>
            <td><?= $i['quantity'] ?></td>
            <td>RM <?= number_format($i['price'], 2) ?></td>
            <td>RM <?= number_format($i['price'] * $i['quantity'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($order['payment_status'] === 'Verified' && $order['status'] !== 'shipped'): ?>
    <form method="post" action="mark_shipped.php" style="margin-top: 20px;">
        <input type="hidden" name="transaction_id" value="<?= $transaction_id ?>">
        <button type="submit" style="background-color: blue; color: white;">🚚 Mark as Shipped</button>
    </form>
<?php endif; ?>
