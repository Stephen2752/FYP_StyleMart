<?php
session_start();
$mainCategory = "Kids";
$subCategory = "Pants";
$fullCategory = "$mainCategory - $subCategory";
require 'db.php';


$stmt = $pdo->prepare("
    SELECT * FROM product 
    WHERE (
        category = ? 
        OR category LIKE ? 
        OR category LIKE ? 
        OR category LIKE ?
    ) AND status = 'available'
");
$stmt->execute([
    $fullCategory,
    "$fullCategory,%",
    "%, $fullCategory",
    "%, $fullCategory,%"
]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= "$mainCategory - $subCategory" ?> Products</title>
  <link rel="stylesheet" href="category.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
    <style>
    @media (max-width: 768px) {
  body {
    overflow-x: hidden;
  }

  .topbar {
    background: #3e3e3e;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    gap: 6px;
    flex-wrap: nowrap;
  }

  .topbar .logo {
    font-size: 18px;
    font-weight: bold;
    white-space: nowrap;
    flex-shrink: 0;
  }

  .search-wrapper {
    position: relative;
    flex: 1;
    margin: 0 10px;
    max-width: 100%;
  }

  .icons {
    display: flex;
    gap: 2px;
    font-size: 18px;
    white-space: nowrap;
    flex-shrink: 0;
    margin-left: 4px;
  }

  .search-results {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: #5f5f5f;
    color: white;
    z-index: 9999;
    border-radius: 5px;
    margin-top: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    max-height: 300px;
    overflow-y: auto;
  }

  .back-btn {
    font-size: 14px;
    margin-bottom: 12px;
  }

  .grid {
    grid-template-columns: repeat(2, 1fr);
    padding: 0 10px;
    gap: 20px;
  }

  .product-card {
    width: 100%;
  }

  .footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }

  .footer-section {
    max-width: 100%;
    flex: 1 1 100%;
  }
}

  </style>
</head>
<body>
<div class="page-wrapper">
  <header class="topbar">
    <div class="logo">
  <a href="MainPage.php" style="text-decoration: none; color: white; font-weight: bold;">StyleMart</a>
</div>


    <div class="search-wrapper">
      <div class="search">
        <input type="text" id="searchInput" class="search__input" placeholder="Type your text" oninput="searchProduct(this.value)">
        <button class="search__button">
          <svg class="search__icon" viewBox="0 0 24 24">
            <g>
              <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"/>
            </g>
          </svg>
        </button>
      </div>
      <div id="searchResults" class="search-results"></div>
    </div>

    <div class="icons">
      <span class="icon" onclick="checkLogin('profile.php')">👤</span>
      <span class="icon" onclick="checkLogin('cart.php')">🛒</span>
      <span class="icon" onclick="checkLogin('favorite.php')">❤️</span>
    </div>
  </header>

  <div class="container">
    <div class="back-btn"><a href="MainPage.php"><img src="uploads/previous.png">Back</a></div>
    <section class="products">
      <p style="text-align:left;">
        <?= "$mainCategory - $subCategory" ?> Products (Total: <?= isset($products) ? count($products) : 0 ?>)
      </p>
      <div class="grid">
        <?php foreach ($products as $product): ?>
          <?php
          $productId = $product['product_id'];
          $imgStmt = $pdo->prepare("SELECT image_path FROM product_image WHERE product_id = ? LIMIT 1");
          $imgStmt->execute([$productId]);
          $image = $imgStmt->fetch(PDO::FETCH_ASSOC);
          $imagePath = $image ? $image['image_path'] : 'placeholder.png';
          ?>
          <a class="product-card" href="product.php?id=<?= $productId ?>">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="Product Image">
            <p><strong><?= htmlspecialchars($product['product_name']) ?></strong></p>
            <p class="price">RM<?= number_format($product['price'], 2) ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</div>

<footer class="footer">
  <div class="footer-section about">
    <h4>About us</h4>
    <p>Style Mart is your go-to destination for trendy, affordable fashion. We offer a wide selection of styles, brands, and sizes—so you can find the perfect look without breaking the bank. New arrivals drop regularly, keeping your wardrobe fresh and fabulous.</p>
  </div>
  <div class="footer-section contact">
    <h4>Contact</h4>
    <p>012-121 2753 (Stephen)<br>012-123 6251 (Wen Hin)<br>012-112 2367 (Mun Kit)</p>
  </div>
  <div class="footer-section address">
    <h4>Address</h4>
    <p>Persiaran Multimedia, 63100<br>Cyberjaya, Selangor</p>
  </div>
  <div class="footer-section connect">
    <h4>Connect With Us</h4>
    <div class="social-icons">
      <a href="https://www.facebook.com" target="_blank"><img src="uploads/fb.png" alt="Facebook"></a>
      <a href="https://www.instagram.com" target="_blank"><img src="uploads/ig.png" alt="Instagram"></a>
      <a href="https://www.x.com" target="_blank"><img src="uploads/twitter.png" alt="X"></a>
    </div>
  </div>
</footer>

<script>
function searchProduct(keyword) {
  if (keyword.length === 0) {
    document.getElementById("searchResults").innerHTML = "";
    return;
  }
  fetch("search.php?query=" + encodeURIComponent(keyword))
    .then(response => response.text())
    .then(data => {
      document.getElementById("searchResults").innerHTML = data;
    });
}

function checkLogin(redirectUrl) {
  fetch("check_login.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.loggedIn) {
        window.location.href = redirectUrl;
      } else {
        alert("Please log in to use this feature.");
        window.location.href = "login.html";
      }
    })
    .catch((err) => {
      console.error("Login check failed:", err);
      window.location.href = "login.html";
    });
}
</script>
</body>
</html>
