<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo "You must be logged in to report.";
        exit;
    }

    $product_id = $_POST['product_id'];
    $seller_id = $_POST['seller_id'];
    $report_reason = $_POST['report_reason'];
    $complaint_text = $_POST['complaint_text'];

    // File upload
    $upload_dir = 'uploads/reports/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image1 = $_FILES['image1'];
    $image2 = $_FILES['image2'];

    if ($image1['error'] != 0 || $image2['error'] != 0) {
        echo "Both images are required.";
        exit;
    }

    $image1_path = $upload_dir . uniqid() . '_' . basename($image1['name']);
    $image2_path = $upload_dir . uniqid() . '_' . basename($image2['name']);

    move_uploaded_file($image1['tmp_name'], $image1_path);
    move_uploaded_file($image2['tmp_name'], $image2_path);

    // After complaint is inserted
$stmt = $pdo->prepare("
    INSERT INTO complaint (user_id, product_id, seller_id, report_reason, complaint_text, image_path_1, image_path_2, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')
");
$stmt->execute([$user_id, $product_id, $seller_id, $report_reason, $complaint_text, $image1_path, $image2_path]);

// Notify all admins
$adminStmt = $pdo->query("SELECT admin_id FROM admin");
$admins = $adminStmt->fetchAll();

foreach ($admins as $admin) {
    $stmt = $pdo->prepare("
        INSERT INTO notification (admin_id, message) 
        VALUES (?, ?)
    ");
    $stmt->execute([
        $admin['admin_id'],
        "A new complaint has been submitted and requires your attention."
    ]);
}

echo "<script>alert('Report successfully.'); window.location='MainPage.php';</script>";

exit;

} else {

    echo "<script>alert('Invalid request.'); window.location='MainPage.php';</script>";
}
?>
