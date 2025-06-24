<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
    </style>
</head>
<body>

<div class="sidebar">
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_seller.php">Manage Sellers</a>
    <a href="manage_product.php">Manage Products</a>
    <a href="manageorder.php">Manage Transactions</a>
    <a href="adminmanagereport.php">Manage Reports</a>
</div>

<div class="content">
