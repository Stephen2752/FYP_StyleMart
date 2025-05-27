<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (!isset($_POST["agree_terms"])) {
        die("Error: You must agree to the Terms and Conditions.");
    }

    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM user WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            die("Error: Username or email already taken.");
        }

        // Insert new user (exclude contact_info)
        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        echo "Sign-up successful!";
         header("Location: login.html");
                exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
