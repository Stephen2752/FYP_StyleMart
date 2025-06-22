<?php
session_start();
require 'db.php';


$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT username, email FROM user WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch addresses
$addr_stmt = $pdo->prepare("SELECT * FROM user_address WHERE user_id = ?");
$addr_stmt->execute([$user_id]);
$addresses = $addr_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Settings - StyleMart</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
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

.form-container {
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  margin: 0 auto;
  margin-top: 20px;
}

    h2 {
      margin-top: 0px;
      margin-bottom: 20px;
      color: #333;
      text-align: center;
    }
    label {
      display: block;
      margin-top: 20px;
      font-weight: bold;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 600px; /* 控制宽度变短 */
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-left: 0;  /* 左边对齐 */
      box-sizing: border-box;
    }

    .address-group {
      display: flex;
      align-items: center;
      margin-top: 10px;
    }
    .address-group input {
      flex: 1;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    .address-group a {
      margin-left: 10px;
      color: red;
      text-decoration: none;
    }
    button[type="button"], button[type="submit"] {
      margin-top: 20px;
      padding: 12px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    button[type="button"] {
      background-color: #007bff;
    }
    button:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>
  <header class="topbar">
    <div class="logo"><a href="MainPage.php">StyleMart</a></div>
  </header>

  <div class="container">
    <div class="back-btn"><a href="profile.php"><img src="uploads/previous.png" alt="Back">Back</a></div>
    <div class="form-container">
      <h2>Account Settings</h2>
      <form action="update_account_settings.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Change Password:</label>
        <input type="password" name="current_password" placeholder="Current password">
        <input type="password" name="new_password" placeholder="New password">

        <label>Addresses:</label>
        <div id="addressWrapper">
          <?php foreach ($addresses as $addr): ?>
            <div class="address-group">
              <input type="text" name="addresses_existing[<?= $addr['address_id'] ?>]" value="<?= htmlspecialchars($addr['address']) ?>">
              <a href="delete_address.php?id=<?= $addr['address_id'] ?>">🗑️</a>
            </div>
          <?php endforeach; ?>
        </div>

        <button type="button" onclick="addAddress()">+ Add Address</button>

        <div id="newAddressInputs"></div>

        <button type="submit">Update Account</button>
      </form>
    </div>
  </div>

  <script>
    function addAddress() {
      const div = document.createElement('div');
      div.className = 'address-group';
      div.innerHTML = `
        <input type="text" name="addresses_new[]" placeholder="New Address">
      `;
      document.getElementById('newAddressInputs').appendChild(div);
    }
  </script>
</body>
</html>

