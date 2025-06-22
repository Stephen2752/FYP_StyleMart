<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo "Product not found.";
    exit;
}

$product_id = (int)$_GET['id'];

// Start session to get logged-in user (assuming you have a session-based login)
session_start();
$logged_in_user_id = $_SESSION['user_id'] ?? null; // adjust if your session variable is different

try {
    // Get product details and creator info
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS creator_username 
        FROM product p 
        JOIN user u ON p.user_id = u.user_id 
        WHERE product_id = ?
    ");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    // Handle comment form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'], $_POST['rate']) && $logged_in_user_id) {
        $comment_text = trim($_POST['comment_text']);
        $rate = (int)$_POST['rate'];

        // Simple validation
        if ($comment_text !== '' && $rate >= 1 && $rate <= 5) {
            $insertStmt = $pdo->prepare("INSERT INTO comment (product_id, user_id, comment_text, rate, created_at) VALUES (?, ?, ?, ?, NOW())");
            $insertStmt->execute([$product_id, $logged_in_user_id, $comment_text, $rate]);
            // Redirect to avoid form resubmission
            header("Location: product.php?id=" . $product_id);
            exit;
        } else {
            $error_message = "Please provide a valid comment and rating.";
        }
    }

    // Get images
    $imgStmt = $pdo->prepare("SELECT image_path FROM product_image WHERE product_id = ?");
    $imgStmt->execute([$product_id]);
    $images = $imgStmt->fetchAll(PDO::FETCH_COLUMN);

    // Get stock (size and quantity)
    $stockStmt = $pdo->prepare("SELECT size, quantity FROM product_stock WHERE product_id = ?");
    $stockStmt->execute([$product_id]);
    $stock = $stockStmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Get comments with user info
    $commentStmt = $pdo->prepare("
        SELECT c.comment_text, c.rate, u.username 
        FROM comment c 
        JOIN user u ON c.user_id = u.user_id 
        WHERE c.product_id = ? 
        ORDER BY c.created_at DESC
    ");
    $commentStmt->execute([$product_id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

function displayStars($rate) {
    $fullStars = str_repeat("★", $rate);
    $emptyStars = str_repeat("☆", 5 - $rate);
    return $fullStars . $emptyStars;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Product Details - StyleMart</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        /* Simple styles for comment form */
    .comment-form {
      margin-top: 1rem;
      border-top: 1px solid #ccc;
      padding-top: 1rem;
    }
    .comment-form textarea {
      width: 100%;
      height: 80px;
      padding: 0.5rem;
      resize: vertical;
    }
    .stars-input {
      font-size: 1.5rem;
      direction: rtl;
      unicode-bidi: bidi-override;
      display: inline-block;
    }
    .stars-input input {
      display: none;
    }
    .stars-input label {
      color: #ccc;
      cursor: pointer;
    }
    .stars-input input:checked ~ label,
    .stars-input label:hover,
    .stars-input label:hover ~ label {
      color: gold;
    }
    .error-message {
      color: red;
      margin-bottom: 0.5rem;
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

    
  body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background: #f4f4f4;
  color: #333;

  }



  /* Topbar */
  .topbar {
    background: #3e3e3e;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
  }

  .topbar .logo {
    font-size: 20px;
    font-weight: bold;
  }

  .icons .icon {
    margin-left: 15px;
    font-size: 20px;
    cursor: pointer;
  }

  .product-container {
  padding: 20px;
  }

  .product-details {
  display: flex;
  gap: 40px;
  align-items: flex-start;
  }

  .left-panel img {
  width: 300px;
  height: auto;
  border: 1px #ccc;
  border-radius: 4px;
  }

  .product-info-panel {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 15px;
  }

  .product-title {
  margin: 0;
  font-size: 20px;
  font-weight: bold;
  }

  /* 新增：让产品名字和价钱并排，留大一点空隙 */
  .title-price {
  display: flex;
  align-items: baseline; /* 产品名和价钱底部对齐 */
  gap: 30px; /* 空隙留大一点 */
  }

  /* From Uiverse.io by Pradeepsaranbishnoi */ 
  :focus {
  outline: 0;
  border-color: #2260ff;
  box-shadow: 0 0 0 4px #b5c9fc;
  }

  .mydict div {
  display: flex;
  flex-wrap: wrap;
  margin-top: 0.5rem;
  justify-content: flex-start; /* 改成左对齐 */
  }


  .mydict input[type="radio"] {
  clip: rect(0 0 0 0);
  clip-path: inset(100%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
  }

  .mydict input[type="radio"]:checked + span {
  box-shadow: 0 0 0 0.0625em #0043ed;
  background-color: #dee7ff;
  z-index: 1;
  color: #0043ed;
  }

  label span {
  display: block;
  cursor: pointer;
  background-color: #fff;
  padding: 0.375em .75em;
  position: relative;
  margin-left: .0625em;
  box-shadow: 0 0 0 0.0625em #b5bfd9;
  letter-spacing: .05em;
  color: #3e4963;
  text-align: center;
  transition: background-color .5s ease;
  }

  label:first-child span {
  border-radius: .375em 0 0 .375em;
  }

  label:last-child span {
  border-radius: 0 .375em .375em 0;
  }


  .action-buttons {
  display: flex;
  align-items: center;
  gap: 10px;
  }

  .number-control {
  display: flex;
  align-items: center;
  border: 1px solid #ccc;
  border-radius: 4px;
  overflow: hidden;
  }

  .number-left,
  .number-right {
  background: #333;
  color: white;
  width: 25px;
  height: 25px;
  text-align: center;
  line-height: 25px;
  cursor: pointer;
  user-select: none;
  }

  .number-quantity {
  width: 40px;
  border: none;
  text-align: center;
  outline: none;
  }

  /* From Uiverse.io by suda-code */ 
  .add-to-cart {
  padding: 12.5px 30px;
  border: 0;
  border-radius: 100px;
  background-color: #2ba8fb;
  color: #ffffff;
  font-weight: Bold;
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  }

  .add-to-cart:hover {
  background-color: #6fc5ff;
  box-shadow: 0 0 20px #6fc5ff50;
  transform: scale(1.1);
  }

  .add-to-cart:active {
  background-color: #3d94cf;
  transition: all 0.25s;
  -webkit-transition: all 0.25s;
  box-shadow: none;
  transform: scale(0.98);
  }

  input[type="checkbox"] {
  display: none;
  }

  input:checked + label svg {
  fill: red;
  stroke: red;
  }

  label.container {
  display: flex;
  align-items: center;
  cursor: pointer;
  }

  .right-panel {
  background: white;
  padding: 15px;
  border-radius: 6px;
  }

  .price {
  font-weight: bold;
  color: #6a5acd;
  font-size: 18px;
  margin: 8px 0;
  }

  .description {
  margin: 15px 0;
  }

  .report-btn {
  background: none;
  border: none;
  color: red;
  cursor: pointer;
  }

  .store-comment-section {
  margin-top: 30px;
  }

  .store-name {
  display: flex;       /* 并排 */
  align-items: center; /* 垂直居中 */
  font-weight: bold;
  margin-bottom: 10px;
  }

  .store-name img {
  width: 24px;         /* 可根据需要调整头像大小 */
  height: 24px;
  margin-right: 8px;   /* 头像和文字间距 */
  border-radius: 50%;  /* 如果需要圆形头像 */
  }


  /* From Uiverse.io by vinodjangid07 */ 
  .messageBox {
  width: fit-content;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff; /* 改为白色 */
  color: #000;            /* 文字颜色黑色 */
  padding: 0 15px;
  border-radius: 10px;
  border: 1px #ccc; /* 改为灰色边框 */
  }

  .messageBox:focus-within {
  border: 1px solid rgb(110, 110, 110);
  }
  .fileUploadWrapper {
  width: fit-content;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: Arial, Helvetica, sans-serif;
  }

  #file {
  display: none;
  }
  .fileUploadWrapper label {
  cursor: pointer;
  width: fit-content;
  height: fit-content;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  }
  .fileUploadWrapper label svg {
  height: 18px;
  }
  .fileUploadWrapper label svg path {
  transition: all 0.3s;
  }
  .fileUploadWrapper label svg circle {
  transition: all 0.3s;
  }
  .fileUploadWrapper label:hover svg path {
  stroke: #fff;
  }
  .fileUploadWrapper label:hover svg circle {
  stroke: #fff;
  fill: #3c3c3c;
  }
  .fileUploadWrapper label:hover .tooltip {
  display: block;
  opacity: 1;
  }
  .tooltip {
  position: absolute;
  top: -40px;
  display: none;
  opacity: 0;
  color: white;
  font-size: 10px;
  text-wrap: nowrap;
  background-color: #000;
  padding: 6px 10px;
  border: 1px #3c3c3c;
  border-radius: 5px;
  box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.596);
  transition: all 0.3s;
  }
  #messageInput {
  width: 200px;
  height: 100%;
  background-color: transparent;
  outline: none;
  border: none;
  padding-left: 10px;
  color: black; /* 黑色字体 */
  }


  #sendButton {
  display: flex;       /* 保证按钮永远是flex显示 */
  align-items: center;
  justify-content: center;
  background-color: transparent;
  border: none;
  outline: none;
  cursor: pointer;
  }


  #sendButton svg {
  height: 18px;
  transition: all 0.3s;
  }
  #sendButton svg path {
  transition: all 0.3s;
  }
  #sendButton:hover svg path {
  fill: #3c3c3c;
  stroke: white;
  }

  /* From Uiverse.io by andrew-demchenk0 */ 
  .rating:not(:checked) > input {
  position: absolute;
  appearance: none;
  }

  .rating:not(:checked) > label {
  float: right;
  cursor: pointer;
  font-size: 30px;
  color: #666;
  }

  .rating:not(:checked) > label:before {
  content: '★';
  }

  .rating > input:checked + label:hover,
  .rating > input:checked + label:hover ~ label,
  .rating > input:checked ~ label:hover,
  .rating > input:checked ~ label:hover ~ label,
  .rating > label:hover ~ input:checked ~ label {
  color: #e58e09;
  }

  .rating:not(:checked) > label:hover,
  .rating:not(:checked) > label:hover ~ label {
  color: #ff9e0b;
  }

  .rating > input:checked ~ label {
  color: #ffa723;
  }

  .comment-box {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 10px;
  }

  .comment-input-area {
  display: flex;
  align-items: center;
  gap: 10px;
  }

  .comment {
  background: #fff;
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 10px;
  }

  .comment strong {
  display: block;
  margin-bottom: 4px;
  }

  .comment-rating {
  display: flex;
  align-items: center;
  margin-bottom: 4px;
  }

  .comment-rating .stars {
  color: #ffa723; /* 星星的颜色，可根据需要更改 */
  font-size: 14px;
  letter-spacing: 1px;
  }


  .messageBox {
  flex-shrink: 0;
  }

  .rating {
  flex-shrink: 0;
  }

  /* 保证输入框中的文字是黑色 */
  #messageInput {
  color: black;
  }

  .mydict label.disabled {
  opacity: 0.5;
  pointer-events: none;
  cursor: not-allowed;
  }

  /* --- Search Results Styles --- */
  .search-wrapper {
  position: relative;
  flex: 1;
  margin: 0 20px;
  max-width: 500px;
  }

  .search {
  display: flex;
  align-items: center;
  width: 100%;
  }

  .search__input {
  font-size: 1rem;
  font-family: inherit;
  background-color: #f4f2f2;
  border: none;
  color: #646464;
  padding: 0.7rem 1rem;
  border-radius: 30px;
  width: 100%;
  transition: all ease-in-out 0.5s;
  }

  .search__input:hover,
  .search__input:focus {
  box-shadow: 0 0 1em #00000013;
  background-color: #f0eeee;
  outline: none;
  }

  .search__input::placeholder {
  font-weight: 100;
  color: #ccc;
  }

  .search__button {
  border: none;
  background-color: #f4f2f2;
  margin-left: -3rem;
  z-index: 1;
  }

  .search__icon {
  height: 1.3em;
  width: 1.3em;
  fill: #b4b4b4;
  }

  /* Search results (dropdown) */
  .search-results {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background:rgb(95, 95, 95);
  color: white;
  border: 1px solid #3e3e3e;
  border-radius: 5px;
  margin-top: 6px;
  z-index: 999;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
  font-size: 0.95rem;
  }

  .search-result-item {
  padding: 10px;
  border-bottom: 1px solid #5a5a5a;
  }

  .search-results a,
  .search-result-item a {
  color: white !important;
  text-decoration: none;
  display: block;
  }

  .search-result-item a:hover {
  color: #ddd;
  text-decoration: underline;
  }

  </style>
</head>
<body>

<!-- Topbar -->
  <header class="topbar">
  <!-- Left: Logo -->
  <div class="logo">StyleMart</div>

  <!-- Center: Search Bar -->
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

<!-- JS for live search -->
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
</script>



      <!-- Right: Icons -->
   <div class="icons">
    <span class="icon" onclick="checkLogin('profile.php')">👤</span>
    <span class="icon" onclick="checkLogin('cart.php')">🛒</span>
    <span class="icon" onclick="checkLogin('favorite.php')">❤️</span>
  </div>
</header>

<main class="product-container">
  <div class="back-btn"><a href="MainPage.php"><img src="uploads\previous.png" alt="Back">Back</a></div>

  <div class="product-details">
    <div class="left-panel">
      <?php foreach ($images as $img): ?>
        <img src="<?= htmlspecialchars($img) ?>" alt="Product Image" />
      <?php endforeach; ?>
    </div>

    <div class="product-info-panel">
      <div class="title-price">
        <h2 class="product-title"><?= htmlspecialchars($product['product_name']) ?></h2>
        <p class="price">RM<?= number_format($product['price'], 2) ?></p>
      </div>

      <div class="mydict">
        <div>
          <?php foreach ($stock as $size => $qty): ?>
            <label class="<?= $qty < 1 ? 'disabled' : '' ?>">
              <input type="radio" name="radio" <?= $qty < 1 ? 'disabled' : '' ?>>
              <span><?= htmlspecialchars($size) ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="action-buttons">
  <form action="add_to_cart.php" method="POST" id="cart-form">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <input type="hidden" name="size" id="selected-size">
    <input type="hidden" name="quantity" id="selected-quantity">

    <div class="number-control">
      <div class="number-left">-</div>
      <input type="number" name="quantity_display" class="number-quantity" value="1" min="1">
      <div class="number-right">+</div>
    </div>

    <button type="submit" class="add-to-cart">Add to Cart</button>
  </form>

  <form action="add_to_favorite.php" method="POST" id="fav-form">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <input type="hidden" name="size" id="favorite-size">
    <button type="submit" class="add-to-favorite">❤️</button>
  </form>
</div>

      <div class="right-panel">
        <h3>Description</h3>
        <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <button class="report-btn">⚠️ Report</button>
      </div>
    </div>
  </div>

  <!-- Creator / Store Name -->
  <div class="store-comment-section">
    <div class="store-name">
      Seller: <a href="seller_info_html"><?= htmlspecialchars($product['creator_username']) ?></a>
    </div>

    <h4>Comments</h4>
    <div class="comments">
      <!-- Existing comments -->
      <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $c): ?>
          <div class="comment">
            <strong><?= htmlspecialchars($c['username']) ?></strong>
            <div class="comment-rating">
              <div class="stars"><?= displayStars((int)$c['rate']) ?></div>
            </div>
            <p><?= nl2br(htmlspecialchars($c['comment_text'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No comments yet. Be the first to comment!</p>
      <?php endif; ?>
    </div>

    <!-- Comment form -->
    <?php if ($logged_in_user_id): ?>
    <form class="comment-form" method="POST" action="product.php?id=<?= $product_id ?>">
      <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
      <?php endif; ?>

      <label for="rate">Rate this product:</label><br>
      <div class="stars-input">
        <!-- stars in reverse order for CSS trick -->
        <input type="radio" id="star5" name="rate" value="5"><label for="star5">★</label>
        <input type="radio" id="star4" name="rate" value="4"><label for="star4">★</label>
        <input type="radio" id="star3" name="rate" value="3"><label for="star3">★</label>
        <input type="radio" id="star2" name="rate" value="2"><label for="star2">★</label>
        <input type="radio" id="star1" name="rate" value="1" checked><label for="star1">★</label>
      </div>

      <br><br>
      <label for="comment_text">Your Comment:</label><br>
      <textarea name="comment_text" id="comment_text" required></textarea><br><br>

      <button type="submit">Add Comment</button>
    </form>
    <?php else: ?>
      <p><a href="login.html">Log in</a> to add a comment.</p>
    <?php endif; ?>
  </div>
</main>

<script>
  const minusBtn = document.querySelector('.number-left');
  const plusBtn = document.querySelector('.number-right');

  minusBtn.addEventListener('click', () => {
    let value = parseInt(quantityInput.value);
    if (value > 1) {
      quantityInput.value = value - 1;
    }
  });

  plusBtn.addEventListener('click', () => {
    let value = parseInt(quantityInput.value);
    quantityInput.value = value + 1;
  });
   document.getElementById("profile").addEventListener("click", function () {
      fetch("check_login.php")
        .then((res) => res.json())
        .then((data) => {
          if (data.loggedIn) {
            window.location.href = "profile.php";
          } else {
            window.location.href = "login.html";
          }
        })
        .catch((err) => {
          console.error("Error checking login status:", err);
          window.location.href = "login.html"; // fallback if error
        });
    });
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
  const sizeRadios = document.querySelectorAll('input[name="radio"]');
  const selectedSizeInput = document.getElementById('selected-size');
  const favoriteSizeInput = document.getElementById('favorite-size');
  const quantityInput = document.querySelector('.number-quantity');
  const selectedQuantityInput = document.getElementById('selected-quantity');

  function updateSelectedSize() {
    const selectedRadio = document.querySelector('input[name="radio"]:checked');
    if (selectedRadio) {
      const size = selectedRadio.nextElementSibling.textContent.trim();
      selectedSizeInput.value = size;
      favoriteSizeInput.value = size;
    }
  }

  sizeRadios.forEach(radio => {
    radio.addEventListener('change', updateSelectedSize);
  });

  // Ensure quantity is updated before submitting
  document.getElementById("cart-form").addEventListener("submit", function(e) {
    selectedQuantityInput.value = quantityInput.value;

    // Check if a size is selected
    if (!selectedSizeInput.value) {
      e.preventDefault();
      alert("Please select a size before adding to cart.");
    }
  });

  document.getElementById("fav-form").addEventListener("submit", function(e) {
    updateSelectedSize(); // Ensure size is updated

    if (!favoriteSizeInput.value) {
      e.preventDefault();
      alert("Please select a size before adding to favorites.");
    }
  });
</script>

</body>
</html>
