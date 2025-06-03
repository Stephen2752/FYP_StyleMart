<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Show success message if exists
if (isset($_SESSION['cart_message'])) {
    echo "<p style='color:green;'>" . htmlspecialchars($_SESSION['cart_message']) . "</p>";
    unset($_SESSION['cart_message']);
}

// Fetch cart items for the user, join with product_image to get image_path
$stmt = $pdo->prepare("
    SELECT c.quantity, p.product_name, p.price, pi.image_path, p.product_id
    FROM cart c
    JOIN product p ON c.product_id = p.product_id
    LEFT JOIN product_image pi ON p.product_id = pi.product_id
    WHERE c.user_id = ?
    GROUP BY p.product_id
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Display cart table
echo "<h2>Your Shopping Cart</h2>";
echo "<table border='1' cellpadding='10' cellspacing='0'>";
echo "<tr>
        <th>Product</th>
        <th>Image</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
      </tr>";

$total_price = 0;

foreach ($cart_items as $item) {
    $item_total = $item['price'] * $item['quantity'];
    $total_price += $item_total;

    echo "<tr>";
    echo "<td>" . htmlspecialchars($item['product_name']) . "</td>";
    if (!empty($item['image_path'])) {
        echo "<td><img src='" . htmlspecialchars($item['image_path']) . "' alt='" . htmlspecialchars($item['product_name']) . "' width='80'></td>";
    } else {
        echo "<td>No image available</td>";
    }
    echo "<td>$" . number_format($item['price'], 2) . "</td>";
    echo "<td>" . intval($item['quantity']) . "</td>";
    echo "<td>$" . number_format($item_total, 2) . "</td>";
    echo "</tr>";
}

echo "<tr>
        <td colspan='4' style='text-align:right;'><strong>Total Price:</strong></td>
        <td><strong>$" . number_format($total_price, 2) . "</strong></td>
      </tr>";
echo "</table>";
?>
