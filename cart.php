<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_SESSION['cart_message'])) {
    echo "<p style='color:green;'>" . htmlspecialchars($_SESSION['cart_message']) . "</p>";
    unset($_SESSION['cart_message']);
}

$stmt = $pdo->prepare("
    SELECT c.cart_id, c.quantity, c.size, p.product_name, p.price, 
       (SELECT pi.image_path FROM product_image pi WHERE pi.product_id = p.product_id ORDER BY pi.image_id ASC LIMIT 1) AS image_path,
       p.product_id,
       u.username AS seller, u.user_id AS seller_id, u.qrcode
FROM cart c
JOIN product p ON c.product_id = p.product_id
JOIN user u ON p.user_id = u.user_id
WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

$grouped = [];
foreach ($cart_items as $item) {
    $grouped[$item['seller_id']]['seller'] = $item['seller'];
    $grouped[$item['seller_id']]['qrcode'] = $item['qrcode'];
    $grouped[$item['seller_id']]['items'][] = $item;
}
?>

<h2>Your Shopping Cart</h2>
<div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
  <button id="delete-selected" style="background-color:red;color:white;">Delete Selected</button>
</div>

<div class="back-btn"><a href="MainPage.php"><img src="uploads\previous.png" alt="Back">Back</a></div>

<div id="cart-wrapper">
<?php foreach ($grouped as $seller_id => $group): ?>
  <div class="cart-container" data-seller-id="<?= $seller_id ?>">
    <h3>
      Seller: <a href="seller_info.html?id=<?= $seller_id ?>" class="seller-link"><?= htmlspecialchars($group['seller']) ?></a>
    </h3>
    <?php foreach ($group['items'] as $item): ?>
      <div class="cart-item" data-cart-id="<?= $item['cart_id'] ?>">
        <a href="product.php?id=<?= $item['product_id'] ?>">
          <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="Product Image" class="cart-image">
        </a>
        <div class="cart-info">
          <h4><a href="product.php?id=<?= $item['product_id'] ?>"><?= htmlspecialchars($item['product_name']) ?></a></h4>
          <p>Size: <?= htmlspecialchars($item['size']) ?></p>
          <p>Price: RM <?= number_format($item['price'], 2) ?></p>
          <p>Quantity: <?= intval($item['quantity']) ?></p>
          <p>Total: RM <?= number_format($item['price'] * $item['quantity'], 2) ?></p>
        </div>
        <div class="cart-actions">
          <input type="checkbox" class="cart-item-checkbox" data-seller-id="<?= $seller_id ?>">
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>
</div>

<div id="total-section">
  <p>Total Price: <span id="total-price">RM 0.00</span></p>
  <button id="pay-btn" disabled>Pay</button>
</div>

<div id="payment-modal" style="display:none; position:fixed; top:20%; left:30%; background:#fff; padding:20px; border:1px solid #000;">
  <button onclick="document.getElementById('payment-modal').style.display='none'">✖</button>
  <div id="qrcode-area"></div>
  <form id="payment-form" enctype="multipart/form-data">
    <input type="file" name="receipt" required><br><br>
    <button type="submit">OK</button>
  </form>
</div>

<style>
.cart-container {
  border: 1px solid #ccc;
  padding: 15px;
  margin-bottom: 25px;
}
.cart-item {
  display: flex;
  align-items: center;
  border-top: 1px solid #ddd;
  padding: 10px 0;
  justify-content: space-between;
}
.cart-image {
  width: 80px;
  height: auto;
}
.cart-info {
  flex: 1;
  margin-left: 20px;
}
.cart-actions {
  padding-left: 10px;
}
.cart-info h4 a, .seller-link {
  text-decoration: none;
  color: black;
}
.cart-info h4 a:hover, .seller-link:hover {
  text-decoration: underline;
  color: blue;
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
</style>

<script>
let selectedSellerId = null;
const checkboxes = document.querySelectorAll('.cart-item-checkbox');
const totalPriceDisplay = document.getElementById('total-price');
const payBtn = document.getElementById('pay-btn');

checkboxes.forEach(cb => {
  cb.addEventListener('change', () => {
    const currentSeller = cb.dataset.sellerId;

    if (!selectedSellerId && cb.checked) {
      selectedSellerId = currentSeller;
    }

    if (cb.checked && currentSeller !== selectedSellerId) {
      alert('You can only select items from the same seller.');
      cb.checked = false;
      return;
    }

    const anyChecked = Array.from(checkboxes).some(c => c.checked);
    if (!anyChecked) selectedSellerId = null;

    updateTotal();
  });
});

function updateTotal() {
  let total = 0;
  document.querySelectorAll('.cart-item-checkbox:checked').forEach(cb => {
    const item = cb.closest('.cart-item');
    const price = parseFloat(item.querySelector('.cart-info p:nth-child(3)').textContent.replace('Price: RM ', ''));
    const qty = parseInt(item.querySelector('.cart-info p:nth-child(4)').textContent.replace('Quantity: ', ''));
    total += price * qty;
  });
  totalPriceDisplay.textContent = 'RM ' + total.toFixed(2);
  payBtn.disabled = total === 0;
}

document.getElementById('delete-selected').addEventListener('click', () => {
  const selected = Array.from(document.querySelectorAll('.cart-item-checkbox:checked'));
  if (selected.length === 0) return;

  const ids = selected.map(cb => cb.closest('.cart-item').dataset.cartId);
  const containersToCheck = new Set();
  selected.forEach(cb => containersToCheck.add(cb.closest('.cart-container')));

  fetch('delete_cart_items.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ cart_ids: ids })
  }).then(res => res.json())
    .then(data => {
      if (data.success) {
        ids.forEach(id => {
          const item = document.querySelector(`.cart-item[data-cart-id='${id}']`);
          if (item) item.remove();
        });

        containersToCheck.forEach(container => {
          if (container.querySelectorAll('.cart-item').length === 0) {
            container.remove();
          }
        });

        selectedSellerId = null; // Reset seller lock after deletion
        updateTotal();
      }
    });
});

document.getElementById('pay-btn').addEventListener('click', () => {
  const checkedRow = document.querySelector('.cart-item-checkbox:checked').closest('.cart-container');
  const sellerId = checkedRow.dataset.sellerId;

  fetch('get_qrcode.php?seller_id=' + sellerId)
    .then(res => res.json())
    .then(data => {
      document.getElementById('qrcode-area').innerHTML = '<img src="' + data.qrcode + '" width="200">';
      document.getElementById('payment-modal').style.display = 'block';
    });
});

document.getElementById('payment-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  const selectedCartIds = Array.from(document.querySelectorAll('.cart-item-checkbox:checked'))
    .map(cb => cb.closest('.cart-item').dataset.cartId);
  formData.append('cart_ids', JSON.stringify(selectedCartIds));

  fetch('checkout.php', {
    method: 'POST',
    body: formData
  }).then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Payment successful!');

        const containersToCheck = new Set();
        selectedCartIds.forEach(id => {
          const item = document.querySelector(`.cart-item[data-cart-id='${id}']`);
          if (item) {
            containersToCheck.add(item.closest('.cart-container'));
            item.remove();
          }
        });

        containersToCheck.forEach(container => {
          if (container.querySelectorAll('.cart-item').length === 0) {
            container.remove();
          }
        });

        selectedSellerId = null; // Reset lock for next merchant
        updateTotal();
        document.getElementById('payment-modal').style.display = 'none';
      }
    });
});
</script>
