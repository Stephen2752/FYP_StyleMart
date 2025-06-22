<?php
require 'db.php'; // your PDO connection

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($query === '') {
    exit;
}

$stmt = $pdo->prepare("SELECT product_id, product_name, price FROM product WHERE product_name LIKE ? ORDER BY product_name ASC LIMIT 10");
$searchTerm = $query . '%'; // matches: a, ab, abc
$stmt->execute([$searchTerm]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($results) === 0) {
    echo "<div class='search-result-item'>No results found.</div>";
} else {
    foreach ($results as $product) {
        echo "<div class='search-result-item'>";
        echo "<a href='product.php?id={$product['product_id']}'>" . htmlspecialchars($product['product_name']) . "</a>";
        echo "</div>";

    }
}

echo "<style>
.search-result-item a,
.search-result-item a:visited,
.search-result-item a:active {
  color: white !important;
  text-decoration: none;
}
</style>";

?>
