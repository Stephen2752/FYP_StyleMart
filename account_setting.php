<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Account Settings</title>
</head>
<body>
  <h2>Account Settings</h2>
  <p>Settings page content goes here...</p>
  <a href="profile.php">← Back to Profile</a>
</body>
</html>
