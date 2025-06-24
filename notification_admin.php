<?php
require 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    echo "Access denied. Admin not logged in.";
    exit;
}

$admin_id = $_SESSION['admin_id'];

$stmt = $pdo->prepare("SELECT * FROM notification WHERE admin_id = ? ORDER BY created_at DESC");
$stmt->execute([$admin_id]);
$notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Notifications</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
        }

        .topbar {
            background: #3e3e3e;
            color: white;
            height: 42px;
            display: flex;
            align-items: center;
            padding: 12px 20px;
        }

        .topbar .logo a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 20px;
        }

        .back-btn {
        display: flex;          /* 并排显示 */
        align-items: center;    /* 垂直居中 */
        margin-bottom: 15px;
        cursor: pointer;
        color: #000000;
        font-weight: bold;      /* 可选：让文字更醒目 */
        }

        .back-btn img {
        width: 16px;            /* 根据需要调整图片大小 */
        height: auto;
        margin-right: 6px;      /* 图片和文字的间距 */
        }

        .back-btn a {
        color: rgb(0, 0, 0);
        text-decoration: none;
        }

        .container {
        padding: 20px;
        }

        .notification-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background-color: #f9f9f9;
            padding: 15px 20px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li.unread {
            background-color: #fff9f4;
            border-left: 5px solid #f39c12;
        }

        .message {
            flex-grow: 1;
            margin-right: 20px;
        }

        .status {
            font-size: 0.9em;
            color: #888;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .no-data {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="logo"><a href="admin_dashboard.php">StyleMart Admin</a></div>
    </div>
<div class="container">
    
    <div class="back-btn"><a href="admin_dashboard.php"><img src="uploads/previous.png" alt="Back">Back</a></div>
    <div class="notification-container">

        <h2>Admin Notifications</h2>

        <?php if (count($notifications) > 0): ?>
            <ul>
                <?php foreach ($notifications as $note): ?>
                    <li class="<?= $note['is_read'] ? '' : 'unread' ?>">
                        <div class="message">
                            <?= htmlspecialchars($note['message']) ?>
                            <div class="status"><?= $note['is_read'] ? "Read" : "Unread" ?></div>
                        </div>
                        <?php if (!$note['is_read']): ?>
                        <form method="POST" action="mark_admin_notification_read.php" style="margin: 0;">
                            <input type="hidden" name="notification_id" value="<?= $note['notification_id'] ?>">
                            <button type="submit">Mark as Read</button>
                        </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">No notifications.</p>
        <?php endif; ?>
    </div>
        </div>
</body>
</html>
