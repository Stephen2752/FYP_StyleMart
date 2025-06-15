<?php
require 'db.php';

// 这里假设mens clothes的分类ID是1（你可根据实际数据库调整）
$category_id = 1;

try {
    // 取出分类下所有产品
    $stmt = $pdo->prepare("SELECT product_id, product_name, price, (SELECT image_path FROM product_image WHERE product_id = p.product_id LIMIT 1) AS thumbnail FROM product p WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Mens Clothes - StyleMart</title>
  <style>
    .product-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .product-card {
      width: 200px;
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    .product-card img {
      max-width: 100%;
      height: 150px;
      object-fit: cover;
    }
    .product-card a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
      display: block;
      margin: 10px 0 5px;
    }
    .price {
      color: #f00;
    }
  </style>
</head>
<body>
  <h1>Mens Clothes</h1>
  <div class="product-list">
    <?php foreach ($products as $product): ?>
      <div class="product-card">
        <a href="product.php?id=<?= $product['product_id'] ?>">
          <img src="<?= htmlspecialchars($product['thumbnail'] ?: 'placeholder.png') ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" />
          <?= htmlspecialchars($product['product_name']) ?>
        </a>
        <div class="price">RM<?= number_format($product['price'], 2) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>


