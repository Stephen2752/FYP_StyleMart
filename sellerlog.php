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
</head>
<body>
  <h2>Seller Management</h2>

  <?php if ($phoneIsNull || $qrcodeIsNull): ?>
    <form action="sellerlog.php" method="POST" enctype="multipart/form-data">
      <label for="phone">Phone Number:</label><br>
      <input type="text" name="phone" id="phone" required><br><br>

      <label for="qrcode">Upload QR Code:</label><br>
      <input type="file" name="qrcode" id="qrcode" accept="image/*" required><br><br>

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
</body>
</html>
