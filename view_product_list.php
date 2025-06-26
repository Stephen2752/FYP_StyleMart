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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Product List</title>
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: #f4f4f4;
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

    .logo a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 20px;
    }

    .container{
      margin: 20px;
    }

    .product-container {
      max-width: 900px;
      margin: 20px auto;
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

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    .product-card {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      padding: 15px;
      margin-bottom: 20px;
    }

    .product-card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    .product-info {
      flex: 1;
      margin-left: 20px;
    }

    .product-info strong {
      font-size: 18px;
      display: block;
      margin-bottom: 8px;
    }

    .product-info p {
      margin: 4px 0;
    }

    .status-available {
      color: green;
      font-weight: bold;
    }

    .status-sold {
      color: red;
      font-weight: bold;
    }

    .edit-btn {
      display: inline-block;
      margin-top: 10px;
      padding: 6px 12px;
      background-color: #2ba8fb;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 14px;
      transition: 0.3s;
    }

    .edit-btn:hover {
      background-color: #1a7dc2;
    }

    .btn-wrapper {
  display: flex;
  justify-content: flex-end; /* 让按钮靠右 */
}
  @media (max-width: 768px) {
  .product-card {
    flex-direction: column;
    align-items: flex-start;
  }

  .product-card img {
    width: 100%;
    height: auto;
    margin-bottom: 10px;
  }

  .product-info {
    margin-left: 0;
    width: 100%;
  }

  .edit-btn {
    width: 100%;
    text-align: center;
  }

  .btn-wrapper {
    justify-content: center;
  }
}

  </style>
</head>
<body>

  <!-- Topbar -->
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

  <div class="container">
    <div class="back-btn"><a href="sellerlog.php"><img src="uploads/previous.png">Back</a></div>
    <div class="product-container">
    <h1>My Products</h1>

    <?php foreach ($products as $product): ?>
      <div class="product-card">
        <img src="<?= htmlspecialchars($product['first_image'] ?? 'uploads/default.png') ?>" alt="Product Image">
          <div class="product-info">
            <strong><?= htmlspecialchars($product['product_name']) ?></strong>
            <p>Category: <?= htmlspecialchars($product['category']) ?></p>
            <p>Price: RM<?= number_format($product['price'], 2) ?></p>
            <p>Stock: <?= $product['total_stock'] ?></p>
            <p>Status: 
              <span class="<?= $product['status'] === 'Available' ? 'status-available' : 'status-sold' ?>">
                <?= htmlspecialchars($product['status']) ?>
              </span>
            </p>
            <div class="btn-wrapper">
              <a class="edit-btn" href="view_product.php?product_id=<?= $product['product_id'] ?>">Edit Product</a>
            </div>
          </div>
      </div>
    <?php endforeach; ?>
    </div>
  </div>

</body>
</html>
