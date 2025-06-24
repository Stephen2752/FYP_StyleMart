<?php
require 'db.php'; // Make sure this file defines $pdo

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    echo "Access denied. Admin not logged in.";
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Get admin notifications
$stmt = $pdo->prepare("SELECT * FROM notification WHERE admin_id = ? ORDER BY created_at DESC");
$stmt->execute([$admin_id]);
$notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Notifications</title>
</head>
<body>
    <h2>Admin Notifications</h2>
    <?php if (count($notifications) > 0): ?>
        <ul>
            <?php foreach ($notifications as $note): ?>
                <li>
                    <?= htmlspecialchars($note['message']) ?> 
                    <?= $note['is_read'] ? "(Read)" : "(Unread)" ?>
                    <form method="POST" action="mark_admin_notification_read.php" style="display:inline;">
                        <input type="hidden" name="notification_id" value="<?= $note['notification_id'] ?>">
                        <button type="submit">Mark as Read</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No notifications.</p>
    <?php endif; ?>
</body>
</html>
