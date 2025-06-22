<?php
require 'db.php';
session_start();

// Filter & search
$status = $_GET['status'] ?? 'all';
$search_id = $_GET['search_id'] ?? '';

$sql = "SELECT * FROM user WHERE 1";
if ($status !== 'all') $sql .= " AND status = ?";
if ($search_id) $sql .= " AND user_id = ?";
$stmt = $pdo->prepare($sql);

$params = [];
if ($status !== 'all') $params[] = $status;
if ($search_id) $params[] = $search_id;
$stmt->execute($params);
$users = $stmt->fetchAll();
?>

<h2>Manage Users</h2>
<form method="GET">
    <input type="text" name="search_id" placeholder="Search by User ID">
    <button type="submit">Search</button>
</form>

<ul>
    <li><a href="?status=all">All</a></li>
    <li><a href="?status=active">Active</a></li>
    <li><a href="?status=banned">Banned</a></li>
</ul>

<table border="1">

    <tr><th>ID</th><th>Username</th><th>Email</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['user_id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= $user['status'] ?? 'active' ?></td>
        <td>
            <?php if (($user['status'] ?? 'active') === 'active'): ?>
                <form method="POST" action="ban_user.php" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    <button type="submit">Ban</button>
                </form>
            <?php else: ?>
                Banned
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    
</table>
