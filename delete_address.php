<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) die("Unauthorized");

$user_id = $_SESSION['user_id'];
$address_id = $_GET['id'] ?? null;

if ($address_id) {
    $stmt = $pdo->prepare("DELETE FROM user_address WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$address_id, $user_id]);
}

header("Location: account_settings.php");
