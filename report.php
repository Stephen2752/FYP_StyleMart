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
    <textarea name="complaint_text" required></textarea>

    <label>Upload Proof Image 1:</label>
    <input type="file" name="image1" accept="image/*" required>

    <label>Upload Proof Image 2:</label>
    <input type="file" name="image2" accept="image/*" required>

    <button type="submit">Submit Report</button>
</form>
