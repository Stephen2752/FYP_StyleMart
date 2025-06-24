<?php
require 'db.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
$notification_id = $_POST['notification_id'] ?? null;

if ($admin_id && $notification_id) {
    $stmt = $pdo->prepare("UPDATE notification SET is_read = 1 WHERE notification_id = ? AND admin_id = ?");
    $stmt->execute([$notification_id, $admin_id]);
}

header('Location: adminmanagereport.php');
exit;
?>
