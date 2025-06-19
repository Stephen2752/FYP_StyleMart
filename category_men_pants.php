<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "style_mart";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category = "Men";
$subcategory = "Pants"; 

$stmt = $conn->prepare("SELECT * FROM product WHERE category LIKE ? AND subcategory = ?");
$likeCategory = "$category%";
$stmt->bind_param("ss", $likeCategory, $subcategory);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= "$category - $subcategory" ?> Products</title>
</head>
<body>
    <h2><?= "$category - $subcategory" ?> Products</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($product = $result->fetch_assoc()): ?>
                <li>
                    <?php
                    $productId = $product['product_id'];
                    $imgQuery = $conn->query("SELECT image_path FROM product_image WHERE product_id = $productId LIMIT 1");
                    $image = $imgQuery->fetch_assoc();
                    $imagePath = $image ? $image['image_path'] : 'placeholder.png';
                    ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="Product Image" width="150"><br>
                    <strong><?= htmlspecialchars($product['product_name']) ?></strong><br>
                    Price: RM<?= $product['price'] ?><br>
                    <?= htmlspecialchars($product['description']) ?><br>
                    Status: <?= $product['status'] ?><br>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
