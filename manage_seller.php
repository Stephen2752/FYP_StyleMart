<?php
require 'db.php';
session_start();

$status = $_GET['status'] ?? 'all';
$search_id = $_GET['search_id'] ?? '';

$sql = "SELECT * FROM user WHERE qrcode IS NOT NULL";
if ($status !== 'all') $sql .= " AND status = ?";
if ($search_id) $sql .= " AND user_id = ?";
$stmt = $pdo->prepare($sql);

$params = [];
if ($status !== 'all') $params[] = $status;
if ($search_id) $params[] = $search_id;
$stmt->execute($params);
$sellers = $stmt->fetchAll();
?>

<h2>Manage Sellers</h2>
<form method="GET">
    <input type="text" name="search_id" placeholder="Search by Seller ID">
    <button type="submit">Search</button>
</form>

<ul>
    <li><a href="?status=all">All</a></li>
    <li><a href="?status=active">Active</a></li>
    <li><a href="?status=banned">Banned</a></li>
</ul>

<style>
    tr.clickable-row {
        cursor: pointer;
    }
    tr.clickable-row:hover {
        background-color: #f2f2f2;
    }
</style>

<table border="1" width="100%">
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($sellers as $seller): ?>
    <tr class="clickable-row" data-href="seller_info.php?seller_id=<?= $seller['user_id'] ?>">
        <td><?= $seller['user_id'] ?></td>
        <td><?= htmlspecialchars($seller['username']) ?></td>
        <td><?= htmlspecialchars($seller['email']) ?></td>
        <td><?= $seller['status'] ?? 'active' ?></td>
        <td>
            <?php if (($seller['status'] ?? 'active') === 'active'): ?>
                <form method="POST" action="ban_user.php" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $seller['user_id'] ?>">
                    <button type="submit" onclick="event.stopPropagation();">Ban</button>
                </form>
            <?php else: ?>
                Banned
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
    document.querySelectorAll("tr.clickable-row").forEach(row => {
        row.addEventListener("click", () => {
            window.location = row.getAttribute("data-href");
        });
    });
</script>
