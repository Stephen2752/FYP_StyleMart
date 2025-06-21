<?php
// Define category parts
$mainCategory = "Men";
$subCategory = "Pants";
$fullCategory = "$mainCategory - $subCategory";

// Connect to DB
require 'db.php'; // this file should define $pdo

// Prepare and execute the query
$stmt = $pdo->prepare("SELECT * FROM product WHERE category = ?");
$stmt->execute([$fullCategory]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= "$mainCategory - $subCategory" ?> Products</title>
    <style>
        ul { padding: 0; }
        li {
            list-style: none;
            border: 1px solid #ccc;
            padding: 12px;
            margin: 12px;
            width: 220px;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }
        img {
            width: 150px;
            height: auto;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <h2><?= "$mainCategory - $subCategory" ?> Products</h2>
    <p>Total products found: <?= isset($products) ? count($products) : 0 ?></p>

    <?php if (!empty($products)): ?>
        <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?php
                $productId = $product['product_id'];
                $imgStmt = $pdo->prepare("SELECT image_path FROM product_image WHERE product_id = ? LIMIT 1");
                $imgStmt->execute([$productId]);
                $image = $imgStmt->fetch(PDO::FETCH_ASSOC);
                $imagePath = $image ? $image['image_path'] : 'placeholder.png';
                ?>
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="Product Image"><br>
                <strong><?= htmlspecialchars($product['product_name']) ?></strong><br>
                Price: RM<?= $product['price'] ?><br>
                <?= htmlspecialchars($product['description']) ?><br>
                Status: <?= $product['status'] ?><br>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No products found in <?= $fullCategory ?>.</p>
    <?php endif; ?>
</body>
</html>
