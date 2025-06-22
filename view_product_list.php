<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to view products.');
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        p.product_id, 
        p.product_name, 
        p.category, 
        p.price, 
        p.status,
        (
            SELECT image_path FROM product_image 
            WHERE product_id = p.product_id 
            ORDER BY image_id ASC LIMIT 1
        ) AS first_image,
        (
            SELECT COALESCE(SUM(quantity), 0) 
            FROM product_stock 
            WHERE product_id = p.product_id
        ) AS total_stock
    FROM product p
    WHERE p.user_id = ?
");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Product List</title>
  <style>
    .product-card {
      border: 1px solid #ccc;
      padding: 10px;
      margin: 10px 0;
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .product-card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
    }
    .status-available {
      color: green;
      font-weight: bold;
    }
    .status-sold {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h1>My Products</h1>

  <?php foreach ($products as $product): ?>
    <div class="product-card">
      <img src="<?= htmlspecialchars($product['first_image'] ?? 'uploads/default.png') ?>" alt="Product Image">
      <div>
        <strong><?= htmlspecialchars($product['product_name']) ?></strong><br>
        Category: <?= htmlspecialchars($product['category']) ?><br>
        Price: RM<?= number_format($product['price'], 2) ?><br>
        Stock: <?= $product['total_stock'] ?><br>
        Status: <span class="<?= $product['status'] === 'Available' ? 'status-available' : 'status-sold' ?>">
          <?= htmlspecialchars($product['status']) ?>
        </span><br>
        <a href="view_product.php?product_id=<?= $product['product_id'] ?>">Edit Product</a>
      </div>
    </div>
  <?php endforeach; ?>
</body>
</html>
