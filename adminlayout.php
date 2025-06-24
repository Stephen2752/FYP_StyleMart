<?php
require 'db.php'; // if not already included above
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    echo "Access denied. Admin not logged in.";
    exit;
}
$admin_id = $_SESSION['admin_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM notification WHERE admin_id = ? AND is_read = 0");
$stmt->execute([$admin_id]);
$count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; display: flex; min-height: 100vh; }
        .sidebar {
            width: 220px;
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            position: fixed;
            height: 100%;
        }
        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #555;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
            flex-grow: 1;
            background-color: #f0f0f0;
            min-height: 100vh;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h2 { margin-bottom: 10px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        a.btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }

        .card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.2s ease;
}
.card:hover {
    transform: translateY(-3px);
}
.card h2 {
    font-size: 18px;
    color: #555;
}
.card p {
    font-size: 28px;
    font-weight: bold;
    color: #333;
    margin-top: 8px;
}

/* 表格样式增强 */
table {
    background-color: white;
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.1);
}
table thead {
    background-color: #007bff;
    color: white;
}
table th, table td {
    padding: 12px;
    text-align: center;
}
table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}
table tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

/* Section title + view all button */
h2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 20px;
    margin-top: 30px;
    color: #333;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
}
a.btn {
    font-size: 14px;
    padding: 6px 10px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}


h2 {
  font-size: 28px;
  margin-bottom: 20px;
  color: #333;
}

form input[type="text"] {
  padding: 8px 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  margin-right: 8px;
}
form button {
  padding: 8px 14px;
  background-color: #007bff;
  border: none;
  color: white;
  border-radius: 6px;
  cursor: pointer;
}
form button:hover {
  opacity: 0.9;
}

ul {
  list-style: none;
  display: flex;
  gap: 10px;
  margin: 15px 0;
  padding: 0;
}
ul li a {
  text-decoration: none;
  color: #007bff;
  font-weight: bold;
  padding: 6px 12px;
  border-radius: 4px;
}
ul li a:hover {
  background-color: #e0e0e0;
}

table {
  width: 100%;
  border-collapse: collapse;
  background-color: white;
  border: 1px solid #ddd;
  margin-top: 10px;
}
th, td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}
th {
  background-color: #f7f7f7;
  color: #333;
}
tr:hover {
  background-color: #f2f2f2;
}


/* 蓝色按钮样式（Search） */
.btn-blue {
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 6px 12px;
  cursor: pointer;
}
.btn-blue:hover {
  background-color: #0056b3;
}

/* 红色按钮样式（Ban） */
.btn-red {
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 6px 12px;
  cursor: pointer;
}
.btn-red:hover {
  background-color: #c82333;
}

    </style>
</head>
<body>

<div class="sidebar">
    <a href="notification_admin.php">🔔 Notifications (<?= $count ?>)</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_seller.php">Manage Sellers</a>
    <a href="manage_product.php">Manage Products</a>
    <a href="manageorder.php">Manage Transactions</a>
    <a href="adminmanagereport.php">Manage Reports</a>
    <a href="login.html">Log Out</a>
</div>

<div class="content">
