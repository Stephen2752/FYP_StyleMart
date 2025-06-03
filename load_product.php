<?php
require 'db.php';

session_start();
$isGuest = !isset($_SESSION['user_id']);

try {
    // Get all products
    $stmt = $pdo->prepare("
        SELECT p.product_id, p.product_name, p.price, pi.image_path
        FROM product p
        LEFT JOIN (
            SELECT product_id, MIN(image_id) as min_image_id
            FROM product_image
            GROUP BY product_id
        ) first_images ON p.product_id = first_images.product_id
        LEFT JOIN product_image pi ON pi.image_id = first_images.min_image_id
        WHERE p.status = 'Available'
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $imagePath = htmlspecialchars($product['image_path'] ?? 'default.png');
        $name = htmlspecialchars($product['product_name']);
        $price = number_format($product['price'], 2);

        echo "
        <a href='product.php?id={$product['product_id']}' class='product-card'>
            <img src='{$imagePath}' alt='Product Image'>
            <p>{$name}</p>
            <p class='price'>RM{$price}</p>
        </a>";

    }

} catch (Exception $e) {
    echo "<p>Error loading products: " . $e->getMessage() . "</p>";
}
?>
