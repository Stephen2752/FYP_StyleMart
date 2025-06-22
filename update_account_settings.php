<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$addresses_existing = $_POST['addresses_existing'] ?? [];
$addresses_new = $_POST['addresses_new'] ?? [];

try {
    $pdo->beginTransaction();

    // Update username and email
    $stmt = $pdo->prepare("UPDATE user SET username = ?, email = ? WHERE user_id = ?");
    $stmt->execute([$username, $email, $user_id]);

    // Change password if provided
    if ($current_password && $new_password) {
        // Get old hashed password
        $stmt = $pdo->prepare("SELECT password FROM user WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $storedHash = $stmt->fetchColumn();

        if (!password_verify($current_password, $storedHash)) {
            throw new Exception("Current password is incorrect.");
        }

        $hashedNewPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE user SET password = ? WHERE user_id = ?")->execute([$hashedNewPassword, $user_id]);
    }

    // Update existing addresses
    foreach ($addresses_existing as $addr_id => $address) {
        $pdo->prepare("UPDATE user_address SET address = ? WHERE address_id = ? AND user_id = ?")
            ->execute([$address, $addr_id, $user_id]);
    }

    // Insert new addresses
    foreach ($addresses_new as $addr) {
        if (trim($addr) !== '') {
            $pdo->prepare("INSERT INTO user_address (user_id, address) VALUES (?, ?)")
                ->execute([$user_id, $addr]);
        }
    }

    $pdo->commit();
    header("Location: account_settings.php?success=1");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Update failed: " . $e->getMessage());
}
