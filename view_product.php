<?php
// view_product.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to view product details.');
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['product_id'])) {
    die('Product not specified.');
}

$product_id = $_GET['product_id'];

// Fetch product
$stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ? AND user_id = ?");
$stmt->execute([$product_id, $user_id]);
$product = $stmt->fetch();

if (!$product) {
    die('Product not found.');
}

// Fetch images
$img_stmt = $pdo->prepare("SELECT * FROM product_image WHERE product_id = ?");
$img_stmt->execute([$product_id]);
$images = $img_stmt->fetchAll();

// Fetch stock
$stock_stmt = $pdo->prepare("SELECT * FROM product_stock WHERE product_id = ?");
$stock_stmt->execute([$product_id]);
$stocks = $stock_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <style>
    img { width: 100px; margin: 5px; }
    .size-stock-row { display: flex; gap: 10px; margin-bottom: 5px; }
  </style>
</head>
<body>
  <h1>Edit Product: <?= htmlspecialchars($product['product_name']) ?></h1>

  <form action="update_product.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

    <label>Product Name</label>
    <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

    <label>Price</label>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

    <label>Description</label>
    <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

    <label>Images</label><br>
    <?php foreach ($images as $img): ?>
      <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Image">
    <?php endforeach; ?>
    <br>
    <input type="file" name="images[]" multiple accept="image/*">

    <label>Sizes & Stock</label>
    <div id="sizeStockWrapper">
      <?php foreach ($stocks as $stock): ?>
        <div class="size-stock-row">
          <input type="text" name="sizes[]" value="<?= htmlspecialchars($stock['size']) ?>" required>
          <input type="number" name="stock[]" value="<?= $stock['quantity'] ?>" required>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="button" onclick="addSizeStockRow()">+ Add Size</button>

    <button type="submit">Update Product</button>
  </form>

  <script>
    function addSizeStockRow() {
      const wrapper = document.getElementById('sizeStockWrapper');
      const div = document.createElement('div');
      div.className = 'size-stock-row';
      div.innerHTML = `
        <input type="text" name="sizes[]" placeholder="Size" required>
        <input type="number" name="stock[]" placeholder="Stock" required>
      `;
      wrapper.appendChild(div);
    }
  </script>
</body>
</html>
