<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM product ORDER BY created_at DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        .product {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product h2 {
            margin-top: 0;
        }
        .images img {
            height: 100px;
            margin-right: 10px;
            border-radius: 4px;
        }
        .info {
            margin-top: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
    </style>
</head>
<body>

<h1>All Products</h1>

<?php foreach ($products as $product): ?>
    <div class="product">
        <h2><?= htmlspecialchars($product['product_name']) ?></h2>

        <div class="images">
            <?php
                $images = explode(',', $product['images']);
                foreach ($images as $imgPath):
                    if (trim($imgPath) !== ''):
            ?>
                <img src="<?= htmlspecialchars($imgPath) ?>" alt="Product Image">
            <?php endif; endforeach; ?>
        </div>

        <div class="info"><span class="label">Category:</span> <?= htmlspecialchars($product['category']) ?></div>
        <div class="info"><span class="label">Description:</span> <?= nl2br(htmlspecialchars($product['description'])) ?></div>
        <div class="info"><span class="label">Price:</span> RM<?= number_format($product['price'], 2) ?></div>
        <div class="info"><span class="label">Sizes & Stock:</span> <?= htmlspecialchars($product['comment']) ?></div>
        <div class="info"><span class="label">Status:</span> <?= htmlspecialchars($product['status']) ?></div>
        <div class="info"><span class="label">Total Stock:</span> <?= $product['stock_quantity'] ?></div>
        <div class="info"><span class="label">Created At:</span> <?= $product['created_at'] ?></div>
    </div>
<?php endforeach; ?>

</body>
</html>
