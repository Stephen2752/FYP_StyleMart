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

    /* Topbar */
.topbar {
  background: #3e3e3e;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 20px;
}

.topbar .logo {
  font-size: 20px;
  font-weight: bold;
}

/* From Uiverse.io by joe-watson-sbf */ 
.search {
  display: flex;
  align-items: center;
  justify-content: space-between;
  text-align: center;
}

.search__input {
  font-family: inherit;
  font-size: inherit;
  background-color: #f4f2f2;
  border: none;
  color: #646464;
  padding: 0.7rem 1rem;
  border-radius: 30px;
  width: 30em;
  transition: all ease-in-out .5s;
  margin-right: -2rem;
}

.search__input:hover, .search__input:focus {
  box-shadow: 0 0 1em #00000013;
}

.search__input:focus {
  outline: none;
  background-color: #f0eeee;
}

.search__input::-webkit-input-placeholder {
  font-weight: 100;
  color: #ccc;
}

.search__input:focus + .search__button {
  background-color: #f0eeee;
}

.search__button {
  border: none;
  background-color: #f4f2f2;
  margin-top: .1em;
}

.search__button:hover {
  cursor: pointer;
}

.search__icon {
  height: 1.3em;
  width: 1.3em;
  fill: #b4b4b4;
}

.icons .icon {
margin-left: 15px;
font-size: 20px;
cursor: pointer;
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
    <div class="logo">StyleMart</div>

    <!-- Search -->
    <div class="search">
      <input type="text" class="search__input" placeholder="Type your text">
      <button class="search__button">
        <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
          <g>
            <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
          </g>
        </svg>
      </button>
    </div>

    <!-- Icons -->
    <div class="icons">
      <span class="icon" id="profile">👤</span>
      <span class="icon" onclick="checkLogin('cart.php')">🛒</span>
      <span class="icon" onclick="checkLogin('favorite.php')">❤️</span>
    </div>
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

        <form action="logout.php" method="POST">
          <button type="submit">Log Out</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
