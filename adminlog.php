<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = trim($_POST["adminlogin_id"]);
    $password = $_POST["adminpassword"];

    try {
        $stmt = $pdo->prepare("SELECT admin_id, username, password FROM admin WHERE username = ? OR email = ?");
        $stmt->execute([$login_id, $login_id]);

        if ($stmt->rowCount() === 1) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($password === $admin['password']) {
                $_SESSION["admin_id"] = $admin["admin_id"];
                $_SESSION["admin_username"] = $admin["username"];
                header("Location: admin_dashboard.php");
                exit;
            } else {
                header("Location: adminlog.html?error=incorrect_password");
                exit;
            }
        } else {
            header("Location: adminlog.html?error=admin_not_found");
            exit;
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
