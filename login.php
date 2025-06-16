<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = trim($_POST["login_id"]);
    $password = $_POST["password"];

    try {
        // Check for username or email
        $stmt = $pdo->prepare("SELECT user_id, username, email, password FROM user WHERE username = ? OR email = ?");
        $stmt->execute([$login_id, $login_id]);

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Store session data
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];

                // Redirect to dashboard or welcome page
                header("Location: MainPage.php");
                exit;
            } else {
                header("Location: login.html?error=incorrect_password");
                exit;
            }
        } else {
            header("Location: login.html?error=user_not_found");
            exit;
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
