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
  </style>
</head>
<body>
  <h2>My Profile</h2>

  <div class="avatar-circle" style="background-color: <?= getColorFromUsername($user['username']) ?>;">
    <?= strtoupper(htmlspecialchars($user['username'][0])) ?>
  </div>

  <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>



  <form action="sellerlog.php" method="GET">
  <button type="submit">Seller Manage</button>
  </form>

</body>
</html>
