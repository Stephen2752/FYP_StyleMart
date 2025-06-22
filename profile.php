<?php
session_start();
require 'db.php';

// Simulated login check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare("SELECT username, email FROM user WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

// Function to assign consistent color
function getColorFromUsername($username) {
    $colors = ['#e57373', '#f06292', '#ba68c8', '#64b5f6', '#4db6ac', '#81c784', '#ffd54f', '#ffb74d'];
    $index = ord(strtoupper($username[0])) % count($colors);
    return $colors[$index];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <style>
body {
margin: 0;
font-family: 'Inter', sans-serif;
background: #f2f2f2;
color: #333;
}  

.container {
padding: 20px;
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


.profile-wrapper {
display: flex;
justify-content: center;
min-height: calc(100vh - 60px); /* 除去 topbar 高度 */
padding: 30px 20px;
box-sizing: border-box;
}

.profile-box {
background-color: #fff;
padding: 2rem;
width: 100%;
max-width: 500px;
border-radius: 0.5rem;
box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
            0 4px 6px -2px rgba(0, 0, 0, 0.05);
box-sizing: border-box;
text-align: center;
}

.profile-box h2 {
margin-top: 0;
}

.profile-box .avatar-circle {
margin: 0 auto 10px auto;
}

.profile-box .button-group {
display: flex;
flex-direction: column;
gap: 10px;
margin-top: 20px;
}

.profile-box .button-group form {
margin: 0;
}

.profile-box .button-group button {
padding: 10px 20px;
font-size: 16px;
width: 60%;
border-radius: 8px;
border: none;
background-color: #4F46E5;
color: white;
cursor: pointer;
transition: background-color 0.2s ease-in-out;
}

.profile-box .button-group button:hover {
background-color: #3730a3;
}


.avatar-circle {
width: 100px;
height: 100px;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
font-size: 40px;
color: white;
font-weight: bold;
margin-bottom: 10px;
}
.back-btn a {
text-decoration: none;
color: black;
}
.button-group {
margin-top: 20px;
}
.button-group form {
display: inline-block;
margin-right: 10px;
}
.button-group button {
padding: 10px 20px;
font-size: 16px;
cursor: pointer;
}
  </style>
</head>
<body>
    <!-- Topbar -->
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

<div class="container">
  <div class="back-btn"><a href="MainPage.php"><img src="uploads/previous.png" alt="Back">Back</a></div>

  <div class="profile-wrapper">
    <div class="profile-box">
      <h2>My Profile</h2>

      <div class="avatar-circle" style="background-color: <?= getColorFromUsername($user['username']) ?>;">
        <?= strtoupper(htmlspecialchars($user['username'][0])) ?>
      </div>

      <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

      <div class="button-group">
        <form action="sellerlog.php" method="GET">
          <button type="submit">Seller Manage</button>
        </form>

        <form action="account_settings.php" method="GET">
          <button type="submit">Account Settings</button>
        </form>

        <form action="order_history.php" method="GET">
          <button type="submit">Order History</button>
        </form>

        <form action="logout.php" method="POST">
          <button type="submit">Log Out</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
