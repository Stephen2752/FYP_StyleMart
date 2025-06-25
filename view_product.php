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

    h1 {
      text-align: center;
      color: #333;
    }

    form {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 20px;
      color: #444;
    }

    input[type="text"], input[type="number"], textarea, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 8px;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    button {
      background-color: #2ba8fb;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 20px;
      margin-right: 10px;
    }

    button:hover {
      background-color: #1e90ff;
    }

    .delete-btn{
      background-color: #dc3545;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 20px;
      margin-right: 10px;
    }

    .delete-btn:hover{
      background-color: #c82333;
    }

    .update-btn{
      background-color: #15d62f;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 20px;
      margin-right: 10px;
    }

    .update-btn:hover{
      background-color: #0f9b22;
    }

    img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      margin: 5px;
      border-radius: 6px;
    }

    .size-stock-row, .category-row {
      display: flex;
      gap: 10px;
      margin-top: 10px;
      align-items: center;
    }

    .remove-btn {
      cursor: pointer;
      color: red;
      font-size: 18px;
      margin-left: 8px;
    }
  </style>
</head>
<body>
    <div class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </div>

  <div class="container">
    <!-- Back Button -->
    <div class="back-btn">
      <a href="view_product_list.php"><img src="uploads/previous.png">Back</a>
    </div>
  <h1>Edit Product: <?= htmlspecialchars($product['product_name']) ?></h1>

  <form action="update_product.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

    <label>Product Name</label>
    <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

    <label>Price</label>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

    <label>Description</label>
    <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

    <label>Categories</label>
    <div id="categoryWrapper"></div>
    <button type="button" onclick="addCategoryRow()">+ Add Category</button>
    <input type="hidden" name="categories_json" id="categories_json">

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

    <button type="submit" class="update-btn">Update Product</button>
    <button type="button" class="delete-btn" onclick="confirmDelete()">Delete Product</button>
  </form>

  <script>
    const categoryOptions = {
      "Men": ["Clothes", "Shoes", "Pants"],
      "Women": ["Clothes", "Shoes", "Pants"],
      "Kids": ["Clothes", "Shoes", "Pants"]
    };

    const existingCategories = <?= json_encode(array_map('trim', explode(',', $product['category']))) ?>;

    function createCategoryRow(selectedMain = '', selectedSub = '') {
      const row = document.createElement('div');
      row.className = 'category-row';

      const mainSelect = document.createElement('select');
      mainSelect.name = 'category_main[]';
      mainSelect.required = true;

      const subSelect = document.createElement('select');
      subSelect.name = 'category_sub[]';
      subSelect.required = true;

      const removeBtn = document.createElement('span');
      removeBtn.className = 'remove-btn';
      removeBtn.innerHTML = '🗑';
      removeBtn.onclick = () => row.remove();

      for (let main in categoryOptions) {
        const opt = document.createElement('option');
        opt.value = main;
        opt.textContent = main;
        if (main === selectedMain) opt.selected = true;
        mainSelect.appendChild(opt);
      }

      const updateSubOptions = () => {
        subSelect.innerHTML = '';
        categoryOptions[mainSelect.value].forEach(sub => {
          const opt = document.createElement('option');
          opt.value = sub;
          opt.textContent = sub;
          if (sub === selectedSub) opt.selected = true;
          subSelect.appendChild(opt);
        });
      };

      mainSelect.onchange = updateSubOptions;
      updateSubOptions();

      row.appendChild(mainSelect);
      row.appendChild(subSelect);
      row.appendChild(removeBtn);

      document.getElementById('categoryWrapper').appendChild(row);
    }

    function addCategoryRow() {
      createCategoryRow();
    }

    existingCategories.forEach(cat => {
      const parts = cat.split(' - ');
      if (parts.length === 2) {
        createCategoryRow(parts[0].trim(), parts[1].trim());
      }
    });

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

    document.querySelector('form').addEventListener('submit', function (e) {
      const mains = document.getElementsByName('category_main[]');
      const subs = document.getElementsByName('category_sub[]');
      const categories = [];

      for (let i = 0; i < mains.length; i++) {
        const main = mains[i].value;
        const sub = subs[i].value;
        categories.push({ main, sub });
      }

      document.getElementById('categories_json').value = JSON.stringify(categories);
    });

    function confirmDelete() {
      if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        const productId = <?= json_encode($product['product_id']) ?>;
        window.location.href = 'view_product_delete.php?product_id=' + productId;
      }
    }
  </script>
</body>
</html>
