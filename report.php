<?php
require 'db.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Product not found.";
    exit;
}

$product_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit;
}

$seller_id = $product['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Report Product - StyleMart</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .topbar {
      background: #3e3e3e;
      color: white;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      height: 44px;
    }

    .topbar .logo a {
      text-decoration: none;
      color: white;
      font-size: 20px;
      font-weight: bold;
    }

    .back-btn {
      display: flex;
      align-items: center;
      margin: 15px 20px 0;
    }

    .back-btn img {
      width: 16px;
      margin-right: 6px;
    }

    .back-btn a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }

    .report-container {
      max-width: 600px;
      margin: 25px auto 40px;
      background-color: white;
      padding: 25px 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .report-container h2 {
      margin-bottom: 20px;
      color: #333;
      text-align:center;
    }

    .report-container label {
      display: block;
      margin-top: 15px;
      margin-bottom: 6px;
      font-weight: bold;
      color: #444;
    }

    .report-container select,
    .report-container textarea,
    .report-container input[type="file"] {
      width: 100%;
      padding: 10px;
      font-size: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    .report-container textarea {
      height: 100px;
      resize: vertical;
    }

    .report-container button {
      margin-top: 20px;
      padding: 12px 20px;
      background-color: #2ba8fb;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
    }

    .report-container button:hover {
      background-color: #1a90db;
    }

    @media (max-width: 600px) {
      .report-container {
        margin: 20px 15px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header class="topbar">
  <div class="logo"><a href="MainPage.php">StyleMart</a></div>
</header>

<div class="back-btn">
  <a href="product.php?id=<?= $product_id ?>"><img src="uploads/previous.png" alt="Back">Back</a>
</div>

<div class="report-container">
  <h2>Report Product</h2>
  <form action="submit_report.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <input type="hidden" name="seller_id" value="<?= $seller_id ?>">

    <label>Report Reason:</label>
    <select name="report_reason" required>
      <option value="Fake Product">Fake Product</option>
      <option value="Wrong Description">Wrong Description</option>
      <option value="Damaged Item">Damaged Item</option>
      <option value="Scam">Scam</option>
      <option value="Others">Others</option>
    </select>

    <label>Details:</label>
    <textarea name="complaint_text" placeholder="Describe your complaint..." required></textarea>

    <label>Upload Proof Image 1:</label>
    <input type="file" name="image1" accept="image/*" required>

    <label>Upload Proof Image 2:</label>
    <input type="file" name="image2" accept="image/*" required>

    <button type="submit">Submit Report</button>
  </form>
</div>

</body>
</html>
