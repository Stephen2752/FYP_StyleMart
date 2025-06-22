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

<h2>Account Settings</h2>
<form action="update_account_settings.php" method="POST">
  <label>Username:</label><br>
  <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

  <label>Email:</label><br>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

  <label>Change Password:</label><br>
  <input type="password" name="current_password" placeholder="Current password"><br>
  <input type="password" name="new_password" placeholder="New password"><br><br>

  <label>Addresses:</label><br>
  <div id="addressWrapper">
    <?php foreach ($addresses as $addr): ?>
      <div>
        <input type="text" name="addresses_existing[<?= $addr['address_id'] ?>]" value="<?= htmlspecialchars($addr['address']) ?>" style="width:300px;">
        <a href="delete_address.php?id=<?= $addr['address_id'] ?>" style="color:red;">Delete</a>
      </div>
    <?php endforeach; ?>
  </div>
  <br>
  <button type="button" onclick="addAddress()">+ Add Address</button><br><br>

  <div id="newAddressInputs"></div>

  <button type="submit">Update Account</button>
</form>

<script>
function addAddress() {
  const div = document.createElement('div');
  div.innerHTML = `<input type="text" name="addresses_new[]" placeholder="New Address" style="width:300px;">`;
  document.getElementById('newAddressInputs').appendChild(div);
}
</script>
