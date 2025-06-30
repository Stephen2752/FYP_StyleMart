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
    
    if (isset($_FILES['qrcode']) && $_FILES['qrcode']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = basename($_FILES['qrcode']['name']);
        $targetPath = $uploadDir . uniqid() . "_" . $filename;

        if (move_uploaded_file($_FILES['qrcode']['tmp_name'], $targetPath)) {
            $stmt = $pdo->prepare("UPDATE user SET phone_number = ?, qrcode = ? WHERE user_id = ?");
            $stmt->execute([$phone, $targetPath, $user_id]);

            echo "<script>alert('Create Seller Account Success.'); window.location='sellerlog.php';</script>";
            exit;
        } else {
            $error = "Error uploading QR code.";
        }
    } else {
        $error = "QR code is required.";
    }
}

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    button {
      border: none;
      display: flex;
      padding: 0.75rem 1.5rem;
      background-color: #488aec;
      color: #ffffff;
      font-size: 0.75rem;
      font-weight: 700;
      text-align: center;
      cursor: pointer;
      text-transform: uppercase;
      vertical-align: middle;
      align-items: center;
      border-radius: 0.5rem;
      user-select: none;
      gap: 0.75rem;
      box-shadow: 0 4px 6px -1px #488aec31, 0 2px 4px -1px #488aec17;
      transition: all 0.3s ease;
    }

    button:hover {
      box-shadow: 0 10px 15px -3px #488aec4f, 0 4px 6px -2px #488aec17;
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

    /* ✅ Done Setup Styles */
    .done-message {
      text-align: center;
      margin-top: 30px;
    }

    .done-message h2 {
      color: #2f2f2f;
    }

    .done-message p {
      color: #555;
      font-size: 16px;
    }

    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 12px;
      max-width: 300px;
      margin: 30px auto;
    }

    .btn-action {
      background-color: #4F46E5;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      text-align: center;
      transition: background-color 0.3s ease;
      text-decoration: none;
    }

    .btn-action:hover {
      background-color: #3730a3;
    }

    .btn-secondary {
      background-color: #6B7280;
    }

    .btn-secondary:hover {
      background-color: #4B5563;
    }

    @media (max-width: 768px) {
.topbar {
  background: #3e3e3e;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 20px;
}


  .topbar .logo a {
    font-size: 18px;
  }

  .container {
    padding: 15px;
  }

  .back-btn {
    font-size: 14px;
    margin-bottom: 10px;
  }

  .back-btn img {
    width: 14px;
    margin-right: 4px;
  }


  .container {
    padding: 12px;
  }

  .form-container {
    padding: 20px;
    margin: 20px 10px;
    border-radius: 10px;
  }

  form input[type="text"],
  form input[type="file"],
  form input[type="submit"],
  .btn-action {
    font-size: 16px;
    padding: 12px;
  }

  form input[type="submit"] {
    padding: 12px;
  }

  button {
    font-size: 14px;
    padding: 12px;
  }

  #preview {
    max-width: 100%;
    height: auto;
  }

  .action-buttons {
    max-width: 100%;
    padding: 0 10px;
  }

  .btn-action {
    font-size: 15px;
    padding: 12px;
  }

  .done-message p {
    font-size: 16px;
  }

  form input[type="text"]#phone {
  max-width: 250px;
}
}

  </style>
</head>
<body>
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

  <div class="container">
    <div class="back-btn"><a href="profile.php"><img src="uploads/previous.png">Back</a></div>
    <div class="form-container">
      <h2>Seller Management</h2>

      <?php if ($phoneIsNull || $qrcodeIsNull): ?>
        <form action="sellerlog.php" method="POST" enctype="multipart/form-data">
          <label for="phone">Phone Number:</label>
          <input type="text" name="phone" id="phone" required>

          <label>Upload QR Code:</label>
          <input type="file" name="qrcode" id="qrcode" accept="image/*" required style="display: none;" onchange="previewQRCode()">

          <button type="button" onclick="document.getElementById('qrcode').click()">
            <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none">
              <path stroke="#ffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"/>
              <path stroke="#ffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke-linejoin="round" stroke-linecap="round"/>
            </svg>
            ADD QR CODE
          </button>

          <br><br>
          <img id="preview" style="display:none; max-width: 200px; border: 1px solid #ccc;" />
          <br>
          <button type="button" id="cancelBtn" onclick="clearQRCode()" style="display:none; background-color: #dc3545;">
            ❌ CANCEL
          </button>
          <br><br>
          <input type="submit" value="Submit">
        </form>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
      <?php else: ?>
        <div class="done-message">
          <p>You’re now ready to start selling on StyleMart.</p>
          <div class="action-buttons">
            <a href="create_product.html" class="btn-action">Create Product</a>
            <a href="view_product_list.php" class="btn-action">View Products</a>
            <a href="sell_orders.php" class="btn-action">Sell Orders</a>
            <a href="sell_order_history.php" class="btn-action">Sell History</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

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

    document.getElementById('qrcode').addEventListener('change', function () {
      const preview = document.getElementById('preview');
      const cancelBtn = document.getElementById('cancelBtn');
      const file = this.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
          cancelBtn.style.display = 'inline-block';
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

      input.value = '';
      preview.src = '';
      preview.style.display = 'none';
      cancelBtn.style.display = 'none';
    }
  </script>
</body>
</html>
