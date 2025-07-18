<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Favorite - StyleMart</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      background-color: #f4f4f4;
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
    
    h2 {
      padding: 20px;
      margin: 0;
    }
    .back-btn {
      display: flex;
      align-items: center;
      margin: 10px 20px;
    }
    .back-btn img {
      width: 16px;
      margin-right: 6px;
    }
    .back-btn a {
      color: #333;
      text-decoration: none;
      font-weight: bold;
    }
    #favorite-wrapper {
      margin: 0 20px 80px;
    }
    .cart-container {
      background-color: white;
      margin-bottom: 30px;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 6px rgba(0,0,0,0.1);
    }
    .cart-container h3 {
      margin: 0 0 15px 0;
    }
    .cart-item {
      display: flex;
      padding: 10px 0;
      border-top: 1px solid #eee;
    }
    .cart-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
    }
    .cart-info {
      flex: 1;
    }
    .cart-info h4 {
      margin: 0 0 8px;
    }
    .cart-info p {
      margin: 3px 0;
      font-size: 14px;
    }
    .cart-actions {
      display: flex;
      align-items: center;
      padding-left: 10px;
    }
    #total-section {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: white;
      padding: 15px 20px;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: bold;
    }

  .top-action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .top-action-bar .back-btn a {
    display: flex;
    align-items: center;
    color: #000;
    text-decoration: none;
    font-weight: bold;
  }

  #delete-selected-fav {
    background-color: red;
    color: white;
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-right: 20px;
  }

    #payment-modal {
      z-index: 10;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      border-radius: 10px;
    }
    #payment-modal img {
      display: block;
      margin: 0 auto 15px;
    }
    #payment-modal button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 6px;
      cursor: pointer;
    }

    
  </style>
</head>
<body>

<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch favorite items
$stmt = $pdo->prepare("
    SELECT 
        f.favorite_id,
        p.product_id,
        p.product_name,
        p.price,
        p.status,
        IFNULL(SUM(s.quantity), 0) AS total_stock,
        (
            SELECT pi.image_path 
            FROM product_image pi 
            WHERE pi.product_id = p.product_id 
            ORDER BY pi.image_id ASC 
            LIMIT 1
        ) AS image_path,
        u.username AS seller,
        u.user_id AS seller_id
    FROM favorite f
    JOIN product p ON f.product_id = p.product_id
    JOIN user u ON p.user_id = u.user_id
    LEFT JOIN product_stock s ON s.product_id = p.product_id
    WHERE f.user_id = ?
    GROUP BY f.favorite_id
");

$stmt->execute([$user_id]);
$fav_items = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<header class="topbar">
  <div class="logo"><a href="MainPage.php">StyleMart</a></div>
</header>

<style>
.cart-item.out-of-stock {
  filter: grayscale(100%);
  opacity: 0.6;
}
</style>

<h2>Your Favorites</h2>
<div class="top-action-bar">
  <div class="back-btn">
    <a href="MainPage.php"><img src="uploads/previous.png" alt="Back">Back</a>
  </div>
  <button id="delete-selected-fav">🗑️</button>
</div>



<?php
if (!$fav_items) {
    echo "<p>Your favorites list is empty.</p>";
    exit;
}

// Group by seller
$grouped = [];
foreach ($fav_items as $item) {
    $grouped[$item['seller_id']]['seller'] = $item['seller'];
    $grouped[$item['seller_id']]['items'][] = $item;
}
?>

<div id="favorite-wrapper">
<?php foreach ($grouped as $seller_id => $group): ?>
  <div class="cart-container" data-seller-id="<?= $seller_id ?>">
    <h3>
      Seller: <a href="seller_info.html?id=<?= $seller_id ?>" class="seller-link"><?= htmlspecialchars($group['seller']) ?></a>
    </h3>


    <?php foreach ($group['items'] as $item): ?>
<div class="cart-item <?= $item['total_stock'] <= 0 ? 'out-of-stock' : '' ?>" data-favorite-id="<?= $item['favorite_id'] ?>">
  <input type="checkbox" class="favorite-checkbox">
  <a href="product.php?id=<?= $item['product_id'] ?>">
      <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="Product Image" class="cart-image">
    </a>
    <div class="cart-info">
      <h4><a href="product.php?id=<?= $item['product_id'] ?>"><?= htmlspecialchars($item['product_name']) ?></a></h4>
      <p>Price: RM <?= number_format($item['price'], 2) ?></p>
      <p>Stock: <?= $item['total_stock'] > 0 ? 'Available' : 'Out of Stock' ?></p>
    </div>
    <div class="cart-actions">
    </div>
  </div>
<?php endforeach; ?>

  </div>
<?php endforeach; ?>
</div>

<script>
document.getElementById('delete-selected-fav').addEventListener('click', () => {
  const checkboxes = document.querySelectorAll('.favorite-checkbox:checked');
  if (checkboxes.length === 0) return;

  const ids = Array.from(checkboxes).map(cb =>
    cb.closest('.cart-item').dataset.favoriteId
  );

  fetch('delete_favorite_item.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ favorite_ids: ids })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      ids.forEach(id => {
        const item = document.querySelector(`.cart-item[data-favorite-id="${id}"]`);
        if (item) item.remove();
      });

      document.querySelectorAll('.cart-container').forEach(container => {
        if (!container.querySelector('.cart-item')) {
          container.remove();
        }
      });
    } else {
      alert(data.error || "Delete failed.");
    }
  })
  .catch(err => {
    console.error('Delete error:', err);
  });
});
</script>


