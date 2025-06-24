<?php
require 'db.php';
session_start();
include 'adminlayout.php';
// Filters
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'all';

$sql = "
SELECT 
    p.product_id,
    p.product_name,
    p.price,
    p.status,
    u.username,
    pi.image_path,
    COALESCE(SUM(ps.quantity), 0) AS total_quantity
FROM product p
JOIN user u ON p.user_id = u.user_id
LEFT JOIN (
    SELECT product_id, MIN(image_id) as min_image_id
    FROM product_image
    GROUP BY product_id
) fi ON p.product_id = fi.product_id
LEFT JOIN product_image pi ON pi.image_id = fi.min_image_id
LEFT JOIN product_stock ps ON p.product_id = ps.product_id
WHERE 1
";

$params = [];

if ($search !== '') {
    $sql .= " AND (p.product_name LIKE ? OR p.product_id = ?) ";
    $params[] = "%$search%";
    $params[] = $search;
}

if ($status !== 'all') {
    $sql .= " AND p.status = ? ";
    $params[] = $status;
}

$sql .= " GROUP BY p.product_id
          ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <style>
        body { font-family: Arial; }
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th, .product-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .product-table tr:hover {
            background-color: #f9f9f9;
        }

        .product-row {
            cursor: pointer;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        .filters {
            margin-bottom: 20px;
        }

        .filters input[type="text"] {
            padding: 5px;
            width: 250px;
        }

        .filters select {
            padding: 5px;
        }

        .filters button {
            padding: 5px 10px;
        }

        .status-selling {
            color: green;
            font-weight: bold;
        }

        .status-sold {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Manage Products</h2>

    <form class="filters" method="GET">
        <input type="text" name="search" placeholder="Search by name or ID" value="<?= htmlspecialchars($search) ?>">
        <select name="status">
            <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All</option>
            <option value="Available" <?= $status === 'Available' ? 'selected' : '' ?>>Selling</option>
            <option value="Sold Out" <?= $status === 'Sold Out' ? 'selected' : '' ?>>Sold Out</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <table class="product-table">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Product Name</th>
            <th>Price (RM)</th>
            <th>Total Quantity</th>
            <th>Status</th>
            <th>Seller</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr class="product-row" data-href="admin_product.php?id=<?= $product['product_id'] ?>">
            <td><?= $product['product_id'] ?></td>
            <td><img src="<?= htmlspecialchars($product['image_path'] ?? 'default.png') ?>" class="product-image" alt="Product Image"></td>
            <td><?= htmlspecialchars($product['product_name']) ?></td>
            <td><?= number_format($product['price'], 2) ?></td>
            <td><?= $product['total_quantity'] ?></td>
            <td class="<?= $product['status'] === 'Available' ? 'status-selling' : 'status-sold' ?>">
                <?= htmlspecialchars($product['status']) ?>
            </td>
            <td><?= htmlspecialchars($product['username']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
        document.querySelectorAll('.product-row').forEach(row => {
            row.addEventListener('click', () => {
                window.location.href = row.dataset.href;
            });
        });
    </script>
</body>
</html>
