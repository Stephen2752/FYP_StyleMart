<?php
require 'db.php';
session_start();
include 'adminlayout.php';

if (!isset($_SESSION['admin_id'])) {
    echo "You must log in as admin to access this page.";
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Handle complaint assignment
if (isset($_POST['assign'])) {
    $complaint_id = $_POST['complaint_id'];

    $stmt = $pdo->prepare("UPDATE complaint SET assigned_admin_id = ?, status = 'In Review' WHERE complaint_id = ?");
    $stmt->execute([$admin_id, $complaint_id]);

    echo "<script>alert('Complaint assigned successfully.'); window.location='adminmanagereport.php';</script>";
}

// Handle admin response
if (isset($_POST['respond'])) {
    $complaint_id = $_POST['complaint_id'];
    $admin_response = $_POST['admin_response'];
    $status = $_POST['status'];

    // Update complaint
    $stmt = $pdo->prepare("UPDATE complaint SET admin_response = ?, status = ? WHERE complaint_id = ?");
    $stmt->execute([$admin_response, $status, $complaint_id]);

    // Get the user_id of the complaint owner
    $stmt = $pdo->prepare("SELECT user_id FROM complaint WHERE complaint_id = ?");
    $stmt->execute([$complaint_id]);
    $user_id = $stmt->fetchColumn();

    // Insert user notification
    $message = "Your complaint (ID: $complaint_id) has been processed. Admin Response: " . htmlspecialchars($admin_response);
    $stmt = $pdo->prepare("INSERT INTO notification (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);

    echo "<script>alert('Response submitted and user notified.'); window.location='adminmanagereport.php';</script>";
}

// Fetch all complaints
$stmt = $pdo->query("SELECT c.*, u.username AS reporter, s.username AS seller, a.username AS assigned_admin
                     FROM complaint c
                     JOIN user u ON c.user_id = u.user_id
                     JOIN user s ON c.seller_id = s.user_id
                     LEFT JOIN admin a ON c.assigned_admin_id = a.admin_id
                     WHERE c.status != 'Resolved'
                     ORDER BY c.created_at DESC");


$complaints = $stmt->fetchAll();
?>

<h1>Complaint Management</h1>

<?php foreach ($complaints as $c): ?>
    <div style="border:1px solid black; padding:15px; margin-bottom:20px;">
        <h3>Complaint ID: <?= $c['complaint_id'] ?> | Status: <?= $c['status'] ?></h3>
        <p><strong>Reported by:</strong> <?= $c['reporter'] ?> (User ID: <?= $c['user_id'] ?>)</p>
        <p><strong>Product ID:</strong> <?= $c['product_id'] ?></p>
        <p><strong>Seller:</strong> <?= $c['seller'] ?> (Seller ID: <?= $c['seller_id'] ?>)</p>
        <p><strong>Report Reason:</strong> <?= $c['report_reason'] ?></p>
        <p><strong>Details:</strong> <?= $c['complaint_text'] ?></p>
        <p><strong>Proof Images:</strong><br>
            <img src="<?= $c['image_path_1'] ?>" width="200" style="margin-right:10px;">
            <img src="<?= $c['image_path_2'] ?>" width="200">
        </p>
        <p><strong>Assigned Admin:</strong> <?= $c['assigned_admin'] ?? 'Not Assigned' ?></p>

        <?php if ($c['assigned_admin_id'] == NULL): ?>
            <form method="POST" style="margin-top:10px;">
                <input type="hidden" name="complaint_id" value="<?= $c['complaint_id'] ?>">
                <button type="submit" name="assign">Assign to Me</button>
            </form>
        <?php elseif ($c['assigned_admin_id'] == $admin_id): ?>
            <form method="POST" style="margin-top:10px;">
                <input type="hidden" name="complaint_id" value="<?= $c['complaint_id'] ?>">

                <label for="admin_response">Your Response:</label><br>
                <textarea name="admin_response" required style="width:100%; height:100px;"><?= $c['admin_response'] ?></textarea><br>

                <label for="status">Update Status:</label>
                <select name="status" required>
                    <option value="In Review" <?= $c['status'] == 'In Review' ? 'selected' : '' ?>>In Review</option>
                    <option value="Resolved" <?= $c['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                </select><br><br>

                <button type="submit" name="respond" class="btn-blue">Submit Response</button>
            </form>
        <?php else: ?>
            <p><strong>Admin Response:</strong> <?= $c['admin_response'] ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
