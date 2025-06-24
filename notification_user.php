<?php
require 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please log in as a user.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all notifications for this user
$stmt = $pdo->prepare("SELECT * FROM notification WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html>
<head>
    <title>User Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f8f8f8; }
        .notification {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .unread {
            background-color: #eaf6ff;
            font-weight: bold;
        }
        form {
            display: inline;
        }
    </style>
</head>
<body>

    <h2>Your Notifications</h2>

    <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $note): ?>
            <div class="notification <?= $note['is_read'] ? '' : 'unread' ?>">
                <?= htmlspecialchars($note['message']) ?><br>
                <small><?= $note['created_at'] ?></small><br>
                <?php if (!$note['is_read']): ?>
                    <form method="POST" action="mark_user_notification_read.php">
                        <input type="hidden" name="notification_id" value="<?= $note['notification_id'] ?>">
                        <button type="submit">Mark as Read</button>
                    </form>
                <?php else: ?>
                    <em>Read</em>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have no notifications yet.</p>
    <?php endif; ?>

</body>
</html>
