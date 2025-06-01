<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $subcategory = $_POST['subcategory'] ?? '';
    $description = $_POST['description'] ?? '';
    $sizes = $_POST['sizes'] ?? [];
    $stocks = $_POST['stocks'] ?? [];
    $userId = 1; // Replace with session user ID if applicable

    // Combine category and subcategory
    $fullCategory = "$category > $subcategory";

    // Convert sizes + stock to comment format
    $stockDetails = [];
    $totalStock = 0;
    foreach ($sizes as $i => $size) {
        $qty = intval($stocks[$i] ?? 0);
        $stockDetails[] = "$size:$qty";
        $totalStock += $qty;
    }
    $stockString = implode(', ', $stockDetails);

    // Upload images
    $imagePaths = [];
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
        if ($_FILES['images']['error'][$i] === 0) {
            $filename = time() . '_' . basename($_FILES['images']['name'][$i]);
            $filePath = $uploadDir . $filename;
            if (move_uploaded_file($tmpName, $filePath)) {
                $imagePaths[] = $filePath;
            }
        }
    }

    $imageList = implode(',', $imagePaths); // Store as comma-separated string

    try {
        $stmt = $pdo->prepare("INSERT INTO product 
            (user_id, product_name, category, price, description, status, stock_quantity, sales_count, comment, images, created_at)
            VALUES 
            (:user_id, :product_name, :category, :price, :description, 'active', :stock_quantity, 0, :comment, :images, NOW())");

        $stmt->execute([
            ':user_id' => $userId,
            ':product_name' => $name,
            ':category' => $fullCategory,
            ':price' => $price,
            ':description' => $description,
            ':stock_quantity' => $totalStock,
            ':comment' => $stockString,
            ':images' => $imageList,
        ]);

        echo "Product created successfully!";
        header("Location: view_product.php");
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
