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
            display: flex;
            align-items: center;
            margin-top: 20px;
            margin-left: 20px;
            cursor: pointer;
        }

        .back-btn img {
            width: 16px;
            height: auto;
            margin-right: 6px;
        }

        .back-btn a {
            color: #000000;
            text-decoration: none;
            font-weight: bold;
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

        .notification {
            background-color: #f9f9f9;
            padding: 15px 20px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .notification.unread {
            background-color: #fff9f4;
            border-left: 5px solid #f39c12;
            font-weight: bold;
        }

        .notification small {
            display: block;
            margin-top: 6px;
            color: #888;
        }

        form {
            display: inline;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        em {
            color: #555;
            font-size: 0.9em;
        }

        .no-data {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="logo"><a href="MainPage.php">StyleMart</a></div>
    </div>

    <div class="back-btn">
        <a href="profile.php"><img src="uploads/previous.png" alt="Back">Back</a>
    </div>

    <div class="container">
        <div class="notification-container">
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
                <p class="no-data">You have no notifications yet.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
