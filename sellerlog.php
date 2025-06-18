<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    
    // Check file upload
    if (isset($_FILES['qrcode']) && $_FILES['qrcode']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = basename($_FILES['qrcode']['name']);
        $targetPath = $uploadDir . uniqid() . "_" . $filename;

        if (move_uploaded_file($_FILES['qrcode']['tmp_name'], $targetPath)) {
            // Store phone and qrcode path
            $stmt = $pdo->prepare("UPDATE user SET phone_number = ?, qrcode = ? WHERE user_id = ?");
            $stmt->execute([$phone, $targetPath, $user_id]);

            echo "Seller info saved successfully! <a href='profile.php'>Return to Profile</a>";
            exit;
        } else {
            $error = "Error uploading QR code.";
        }
    } else {
        $error = "QR code is required.";
    }
}

// Fetch user data
$stmt = $pdo->prepare("SELECT phone_number, qrcode FROM user WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$phoneIsNull = empty($user['phone_number']);
$qrcodeIsNull = empty($user['qrcode']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Seller Setup</title>
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

h2 {
    text-align: center;
    color: #333;
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

.form-container {
    max-width: 400px;
    margin: 40px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

form label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

form input[type="text"],
form input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

form input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #4F46E5;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
}

form input[type="submit"]:hover {
    background-color: #3730a3;
}

.form-container p {
    text-align: center;
}

.form-container a {
    color: #3e3e3e;
    text-decoration: none;
}

button {
  border: none;
  display: flex;
  padding: 0.75rem 1.5rem;
  background-color: #488aec;
  color: #ffffff;
  font-size: 0.75rem;
  line-height: 1rem;
  font-weight: 700;
  text-align: center;
  cursor: pointer;
  text-transform: uppercase;
  vertical-align: middle;
  align-items: center;
  border-radius: 0.5rem;
  user-select: none;
  gap: 0.75rem;
  box-shadow:
    0 4px 6px -1px #488aec31,
    0 2px 4px -1px #488aec17;
  transition: all 0.6s ease;
}

button:hover {
  box-shadow:
    0 10px 15px -3px #488aec4f,
    0 4px 6px -2px #488aec17;
}

button:focus,
button:active {
  opacity: 0.85;
  box-shadow: none;
}

button svg {
  width: 1.25rem;
  height: 1.25rem;
}

  </style>
</head>
<body>
    <!-- Topbar -->
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

  <div class="container">
    <div class="back-btn"><a href="MainPage.php"><img src="uploads/previous.png">Back</a></div>
    <div class="form-container">
      <h2>Seller Management</h2>
      <?php if ($phoneIsNull || $qrcodeIsNull): ?>
      <form action="sellerlog.php" method="POST" enctype="multipart/form-data">
        <label for="phone">Phone Number:</label><br>
        <input type="text" name="phone" id="phone" required><br><br>

        <label>Upload QR Code:</label><br>

        <!-- 隐藏真实 file input -->
        <input type="file" name="qrcode" id="qrcode" accept="image/*" required style="display: none;" onchange="previewQRCode()">

        <!-- 自定义按钮 -->
        <button type="button" onclick="document.getElementById('qrcode').click()">
          <svg
            aria-hidden="true"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-width="2"
              stroke="#ffffff"
              d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125"
              stroke-linejoin="round"
              stroke-linecap="round"
            ></path>
            <path
              stroke-linejoin="round"
              stroke-linecap="round"
              stroke-width="2"
              stroke="#ffffff"
              d="M17 15V18M17 21V18M17 18H14M17 18H20"
            ></path>
          </svg>
          ADD QR CODE
        </button>

        <!-- 预览图片 -->
        <br><br>
        <img id="preview" style="display:none; max-width: 200px; border: 1px solid #ccc;" />

        <!-- 取消上传按钮（默认隐藏） -->
        <br>
        <button type="button" id="cancelBtn" onclick="clearQRCode()" style="display:none; background-color: #dc3545;">
          ❌ CANCEL
        </button>


        <br><br>
        <input type="submit" value="Submit">
      </form>

        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
      <?php else: ?>
        <p>You have already set up your seller information.</p>
        <p><a href="create_product.html">Create Product</a></p>
        <p><a href="view_product_list.php">View Product</a></p>
        <p><a href="profile.php">Back to Profile</a></p>
	<p><a href="sell_orders.php">Sell Order</a></p>
    	<p><a href="sell_order_history.php">Sell History</a></p>
      <?php endif; ?>
    </div>
  </div>
</body>

<script>
  function previewQRCode() {
    const input = document.getElementById('qrcode');
    const preview = document.getElementById('preview');

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
  function clearQRCode() {
  const input = document.getElementById('qrcode');
  const preview = document.getElementById('preview');

  input.value = ''; // 清除文件
  preview.src = '';
  preview.style.display = 'none';
}
document.getElementById('qrcode').addEventListener('change', function () {
  const preview = document.getElementById('preview');
  const cancelBtn = document.getElementById('cancelBtn');
  const file = this.files[0];

  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
      cancelBtn.style.display = 'inline-block'; // 显示取消按钮
    };
    reader.readAsDataURL(file);
  } else {
    preview.src = '';
    preview.style.display = 'none';
    cancelBtn.style.display = 'none';
  }
});

function clearQRCode() {
  const input = document.getElementById('qrcode');
  const preview = document.getElementById('preview');
  const cancelBtn = document.getElementById('cancelBtn');

  input.value = '';              // 清空文件
  preview.src = '';              // 清除图片
  preview.style.display = 'none';
  cancelBtn.style.display = 'none'; // 隐藏取消按钮
}

</script>

</html>
