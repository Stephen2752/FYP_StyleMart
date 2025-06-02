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
  <link rel="stylesheet" href="product.css" />
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
  </style>
</head>
<body>

<!-- Topbar -->
<header class="topbar">
  <div class="logo">StyleMart</div>
  <div class="search">
      <input type="text" class="search__input" placeholder="Type your text">
      <button class="search__button">
          <!-- SVG omitted -->
      </button>
  </div>
  <div class="icons">
    <span class="icon">👤</span>
    <span class="icon">🛒</span>
  </div>
</header>

<main class="product-container">
  <div class="back-btn"><a href="MainPage.php"><img src="image/previous.png" alt="Back">Back</a></div>

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
            <label class="<?= $qty <= 1 ? 'disabled' : '' ?>">
              <input type="radio" name="radio" <?= $qty <= 1 ? 'disabled' : '' ?>>
              <span><?= htmlspecialchars($size) ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="action-buttons">
        <div class="number-control">
          <div class="number-left">-</div>
          <input type="number" name="number" class="number-quantity" value="1" min="1">
          <div class="number-right">+</div>
        </div>

        <button class="add-to-cart">Add to Cart</button>

        <input type="checkbox" checked id="favorite" name="favorite-checkbox">
        <label for="favorite" class="container">
          ❤️
        </label>
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
      <p><a href="login.php">Log in</a> to add a comment.</p>
    <?php endif; ?>
  </div>
</main>

<script>
  const minusBtn = document.querySelector('.number-left');
  const plusBtn = document.querySelector('.number-right');
  const quantityInput = document.querySelector('.number-quantity');

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
</script>

</body>
</html>
