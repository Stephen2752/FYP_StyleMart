<?php
require 'db.php';
session_start();

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    echo "<p>Invalid product ID.</p>";
    exit;
}

// Fetch product details
$stmt = $pdo->prepare("SELECT p.*, u.username FROM product p JOIN user u ON p.user_id = u.user_id WHERE p.product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Fetch product image
$stmt = $pdo->prepare("SELECT image_path FROM product_image WHERE product_id = ? ORDER BY image_id ASC LIMIT 1");
$stmt->execute([$product_id]);
$image = $stmt->fetchColumn();

// Fetch product stock
$stmt = $pdo->prepare("SELECT size, quantity FROM product_stock WHERE product_id = ?");
$stmt->execute([$product_id]);
$stock = $stmt->fetchAll();

// Fetch sales records
$stmt = $pdo->prepare("SELECT ti.size, ti.quantity, ti.price, t.transaction_date, u.username AS buyer_name
                       FROM transaction_item ti
                       JOIN transaction t ON ti.transaction_id = t.transaction_id
                       JOIN user u ON t.buyer_id = u.user_id
                       WHERE ti.product_id = ?
                       ORDER BY t.transaction_date DESC");
$stmt->execute([$product_id]);
$sales = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin View Product</title>
    <style>
        img {
            max-width: 300px;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .sold-btn {
            background-color: red;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Product Details (ID: <?= $product_id ?>)</h2>
    <img src="<?= htmlspecialchars($image ?? 'default.png') ?>" alt="Product Image">
    <p><strong>Name:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
    <p><strong>Price:</strong> RM<?= number_format($product['price'], 2) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($product['status']) ?></p>
    <p><strong>Seller:</strong> <?= htmlspecialchars($product['username']) ?></p>

    <h3>Stock Info:</h3>
    <ul>
        <?php foreach ($stock as $s): ?>
            <li>Size <?= htmlspecialchars($s['size']) ?>: <?= $s['quantity'] ?> pcs</li>
        <?php endforeach; ?>
    </ul>

    <form method="POST" action="ban_product.php">
        <input type="hidden" name="product_id" value="<?= $product_id ?>">
        <button class="sold-btn" onclick="return confirm('Are you sure you want to ban this product?')">Ban Product</button>
    </form>

    <h3>Sales Records</h3>
    <table>
        <tr><th>Buyer</th><th>Size</th><th>Qty</th><th>Price</th><th>Date</th></tr>
        <?php if (count($sales) > 0): ?>
            <?php foreach ($sales as $sale): ?>
            <tr>
                <td><?= htmlspecialchars($sale['buyer_name']) ?></td>
                <td><?= htmlspecialchars($sale['size']) ?></td>
                <td><?= $sale['quantity'] ?></td>
                <td>RM<?= number_format($sale['price'], 2) ?></td>
                <td><?= $sale['transaction_date'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">No sales records found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
