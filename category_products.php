<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = ""; // update if needed
$db = "style_mart";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected category and subcategory
$selectedCategory = $_GET['category'] ?? null;
$selectedSubcategory = $_GET['subcategory'] ?? null;

// Fetch all unique categories
$categories = [];
$catResult = $conn->query("SELECT DISTINCT category FROM product");
while ($row = $catResult->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Fetch subcategories based on selected category
$subcategories = [];
if ($selectedCategory) {
    $stmt = $conn->prepare("SELECT DISTINCT subcategory FROM product WHERE category = ?");
    $stmt->bind_param("s", $selectedCategory);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['subcategory']) {
            $subcategories[] = $row['subcategory'];
        }
    }
}

// Fetch products
$products = [];
if ($selectedCategory && $selectedSubcategory) {
    $stmt = $conn->prepare("SELECT * FROM product WHERE category = ? AND subcategory = ?");
    $stmt->bind_param("ss", $selectedCategory, $selectedSubcategory);
    $stmt->execute();
    $products = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products by Category & Subcategory</title>
</head>
<body>
    <h2>Filter Products</h2>
    <form method="get">
        <label>Category:</label>
        <select name="category" onchange="this.form.submit()">
            <option value="">-- Choose Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if ($selectedCategory): ?>
            <label>Subcategory:</label>
            <select name="subcategory">
                <option value="">-- Choose Subcategory --</option>
                <?php foreach ($subcategories as $sub): ?>
                    <option value="<?= htmlspecialchars($sub) ?>" <?= $selectedSubcategory === $sub ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sub) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="View Products">
        <?php endif; ?>
    </form>

    <?php if ($selectedCategory && $selectedSubcategory): ?>
        <h3>Products in "<?= htmlspecialchars($selectedCategory) ?> → <?= htmlspecialchars($selectedSubcategory) ?>"</h3>
        <?php if ($products->num_rows > 0): ?>
            <ul>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($product['product_name']) ?></strong><br>
                        Price: RM<?= $product['price'] ?><br>
                        Description: <?= htmlspecialchars($product['description']) ?><br>
                        Status: <?= $product['status'] ?><br>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No products found in this subcategory.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
