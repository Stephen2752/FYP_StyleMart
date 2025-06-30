<?php
require 'db.php';
session_start();

$seller_id = $_GET['seller_id'] ?? 0;

// Get seller info
$stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
$stmt->execute([$seller_id]);
$seller = $stmt->fetch();

if (!$seller) {
    echo "<p>Seller not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Seller Products</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: #f2f2f2;
      color: #333;
    }

    .topbar {
      background: #3e3e3e;
      color: white;
      height: 42px;
      display: flex;
      align-items: center;
      padding: 12px 20px;
    }

    .topbar .logo a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 20px;
    }

    .container {
      padding: 20px;
    }

    .back-btn {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      cursor: pointer;
      color: #000000;
      font-weight: bold;
    }

    .back-btn img {
      width: 16px;
      height: auto;
      margin-right: 6px;
    }

    .back-btn a {
      color: black;
      text-decoration: none;
    }

    h2 {
      font-size: 26px;
      margin-bottom: 20px;
    }

    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: flex-start;
    }

    .product-card {
      border: 1px solid #ccc;
      border-radius: 10px;
      width: 200px;
      padding: 10px;
      text-align: center;
      text-decoration: none;
      color: black;
      background-color: white;
      transition: box-shadow 0.3s;
    }

    .product-card:hover {
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    .product-card img {
      max-width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 5px;
    }

    .product-card .price {
      font-weight: bold;
      color: #6a5acd;
    }
  </style>
</head>
<body>

  <div class="topbar">
    <div class="logo"><a href="index.php">StyleMart</a></div>
  </div>

  <div class="container">
    <div class="back-btn"><a href="javascript:history.back()"><img src="uploads/previous.png" alt="Back">Back</a></div>

    <h2>Seller: <?= htmlspecialchars($seller['username']) ?></h2>

    <div class="product-grid">
      <?php
        try {
            $stmt = $pdo->prepare("
                SELECT p.product_id, p.product_name, p.price, pi.image_path
                FROM product p
                LEFT JOIN (
                    SELECT product_id, MIN(image_id) AS min_image_id
                    FROM product_image
                    GROUP BY product_id
                ) first_images ON p.product_id = first_images.product_id
                LEFT JOIN product_image pi ON pi.image_id = first_images.min_image_id
                WHERE p.user_id = ? AND p.status = 'Available'
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$seller_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$products) {
                echo "<p>No products found for this seller.</p>";
            }

            foreach ($products as $product) {
                $imagePath = htmlspecialchars($product['image_path'] ?? 'default.png');
                $name = htmlspecialchars($product['product_name']);
                $price = number_format($product['price'], 2);

                echo "
                <a href='product.php?id={$product['product_id']}' class='product-card'>
                    <img src='{$imagePath}' alt='Product Image'>
                    <p>{$name}</p>
                    <p class='price'>RM{$price}</p>
                </a>";
            }

        } catch (Exception $e) {
            echo "<p>Error loading seller's products: " . $e->getMessage() . "</p>";
        }
      ?>
    </div>
  </div>
</body>
</html>
