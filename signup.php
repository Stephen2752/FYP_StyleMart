<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (!isset($_POST["agree_terms"])) {
        header("Location: signup.html?error=terms");
        exit;
    }

    if ($password !== $confirm_password) {
        header("Location: signup.html?error=mismatch");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("SELECT user_id FROM user WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->rowCount() > 0) {
            header("Location: signup.html?error=taken");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);


        header("Location: login.html");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

