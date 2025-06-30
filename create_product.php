<?php
// create_product.php
session_start();
require 'db.php'; // include your database connection

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to add a product.');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic product details
    $product_name = $_POST['product_name'];

    // Decode categories JSON
    $categories_json = $_POST['categories_json'] ?? '[]';
    $categories = json_decode($categories_json, true);

    if (!$categories || !is_array($categories) || count($categories) === 0) {
        die('No categories selected.');
    }

    // Convert categories array to a single string like "Men - Clothes, Women - Shoes"
    $category_strings = array_map(function($cat) {
        return $cat['main'] . ' - ' . $cat['sub'];
    }, $categories);
    $category = implode(', ', $category_strings);

    $price = $_POST['price'];
    $description = $_POST['description'];

    // Determine status based on total stock (default Available if stock > 0)
    $status = 'Available'; // default
    $total_stock = 0;

    if (isset($_POST['stock']) && is_array($_POST['stock'])) {
        $total_stock = array_sum($_POST['stock']);
        if ($total_stock <= 0) {
            $status = 'Out of Stock';
        }
    }

    
    $stmt = $pdo->prepare("INSERT INTO product (user_id, product_name, category, price, description, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $product_name, $category, $price, $description, $status]);
    $product_id = $pdo->lastInsertId();

    // Handle images
    if (!empty($_FILES['images']['name'][0])) {
        $upload_dir = 'uploads/';
        foreach ($_FILES['images']['name'] as $index => $filename) {
            $tmp_name = $_FILES['images']['tmp_name'][$index];
            $target_file = $upload_dir . uniqid() . '_' . basename($filename);
            if (move_uploaded_file($tmp_name, $target_file)) {
                $img_stmt = $pdo->prepare("INSERT INTO product_image (product_id, image_path) VALUES (?, ?)");
                $img_stmt->execute([$product_id, $target_file]);
            }
        }
    }

    // Handle sizes and stock
    if (isset($_POST['sizes']) && isset($_POST['stock'])) {
        foreach ($_POST['sizes'] as $index => $size) {
            $stock = $_POST['stock'][$index];
            $stock_stmt = $pdo->prepare("INSERT INTO product_stock (product_id, size, quantity) VALUES (?, ?, ?)");
            $stock_stmt->execute([$product_id, $size, $stock]);
        }
    }

    
    echo "<script>alert('Create Product Success.'); window.location='sellerlog.php';</script>";
}   
?>
