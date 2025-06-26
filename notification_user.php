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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background-color: #f4f4f4;
  color: #333;
}

/* Topbar 样式，统一高度与 Product Page 相同 */
.topbar {
  background: #3e3e3e;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 20px;
  height: 42px;
}

.topbar .logo a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  font-size: 20px;
}

/* 返回按钮样式 */
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

/* 页面容器 */
.container {
  padding: 20px;
}

/* 通知卡片容器 */
.notification-container {
  max-width: 800px;
  margin: 30px auto;
  background: white;
  padding: 20px 30px;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* 标题样式 */
h2 {
  text-align: center;
  margin-bottom: 30px;
}

/* 每条通知样式 */
.notification {
  background-color: #f9f9f9;
  padding: 15px 20px;
  border: 1px solid #ddd;
  margin-bottom: 10px;
  border-radius: 8px;
  word-wrap: break-word;
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

/* 按钮样式 */
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
  font-size: 14px;
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

@media (max-width: 768px) {
.topbar {
  background: #3e3e3e;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 20px;
}


  .topbar .logo a {
    font-size: 18px;
  }

  .container {
    padding: 15px;
  }

  .back-btn {
    font-size: 14px;
    margin-bottom: 10px;
  }

  .back-btn img {
    width: 14px;
    margin-right: 4px;
  }


  /* 通知容器 */
  .notification-container {
    padding: 20px 16px;
    margin: 16px;
  }

  /* 放大通知框 */
  .notification {
    padding: 20px 18px;
    font-size: 16px;
  }

  .notification small {
    font-size: 14px;
  }

  /* 放大按钮 */
  button {
    width: 100%;
    padding: 14px;
    font-size: 16px;
    border-radius: 8px;
    margin-top: 10px;
  }

  h2 {
    font-size: 20px;
  }

  .no-data {
    font-size: 16px;
  }
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
