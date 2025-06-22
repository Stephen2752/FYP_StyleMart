<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE user SET status = 'banned' WHERE user_id = ?");
    $stmt->execute([$user_id]);
    header("Location: manage_users.php");
}
?>
