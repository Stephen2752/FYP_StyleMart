<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('Unauthorized');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle category update
    $categories_json = $_POST['categories_json'] ?? '[]';
    $categories = json_decode($categories_json, true);

    $category_strings = [];
    if (is_array($categories)) {
        foreach ($categories as $cat) {
            if (isset($cat['main']) && isset($cat['sub'])) {
                $category_strings[] = $cat['main'] . ' - ' . $cat['sub'];
            }
        }
    }
    $category = implode(', ', $category_strings);

    // Update product info
    $stmt = $pdo->prepare("UPDATE product SET product_name = ?, price = ?, description = ?, category = ? WHERE product_id = ? AND user_id = ?");
    $stmt->execute([$product_name, $price, $description, $category, $product_id, $user_id]);

    // Handle new image uploads
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

    // Update sizes and stock
    $delete_stmt = $pdo->prepare("DELETE FROM product_stock WHERE product_id = ?");
    $delete_stmt->execute([$product_id]);

    $total_stock = 0;
    if (isset($_POST['sizes']) && isset($_POST['stock'])) {
        foreach ($_POST['sizes'] as $index => $size) {
            $stock = $_POST['stock'][$index];
            $stock_stmt = $pdo->prepare("INSERT INTO product_stock (product_id, size, quantity) VALUES (?, ?, ?)");
            $stock_stmt->execute([$product_id, $size, $stock]);
            $total_stock += $stock;
        }
    }

    // Update total stock and status
    $status = $total_stock > 0 ? 'Available' : 'Out of Stock';
    $update_stock_stmt = $pdo->prepare("UPDATE product SET stock_quantity = ?, status = ? WHERE product_id = ?");
    $update_stock_stmt->execute([$total_stock, $status, $product_id]);

    // Redirect to product list
    header("Location: view_product_list.php");
    exit;
}
?>
