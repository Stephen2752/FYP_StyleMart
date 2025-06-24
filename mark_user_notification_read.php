<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$notification_id = $_POST['notification_id'] ?? null;

if ($user_id && $notification_id) {
    $stmt = $pdo->prepare("UPDATE notification SET is_read = 1 WHERE notification_id = ? AND user_id = ?");
    $stmt->execute([$notification_id, $user_id]);
}

header('Location: notification_user.php'); // or wherever the user came from
exit;
?>
