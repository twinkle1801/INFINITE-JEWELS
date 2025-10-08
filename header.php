<?php include "auth.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Infinite Jewels - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar .active {
            background-color: #007bff;
        }
        .topbar {
            background: #000; /* black background */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        .topbar .center-logo {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .topbar .dropdown-toggle {
            background-color: #000 !important; /* black button */
            color: #fff !important; /* white text */
            border: none;
        }
        .topbar .dropdown-toggle:hover {
            background-color: #111 !important; /* slightly lighter on hover */
        }
        .dropdown-menu {
            background-color: #000; /* dropdown black */
        }
        .dropdown-menu a {
            color: #fff !important; /* dropdown links white */
        }
        .dropdown-menu a:hover {
            background-color: #111 !important; /* hover effect */
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <div class="text-center mb-4">
                <img src="logo.jpg" alt="Logo" class="img-fluid" style="max-height:80px;">
                
            </div>
            <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="category.php"><i class="fas fa-list"></i> Categories</a>
            <a href="subcategory.php"><i class="fas fa-tags"></i> Subcategories</a>
            <a href="products.php"><i class="fas fa-gem"></i> Products</a>
            <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="view_contact.php"><i class="fas fa-envelope"></i> Contact</a>
            <a href="users.php"><i class="fas fa-users"></i> Registered Users</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <!-- Content -->
        <div class="col-md-10 p-0">
            <!-- Topbar -->
            <div class="topbar">
                <!-- Right Profile Dropdown -->
                <div class="dropdown ms-auto">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <?php echo isset($_SESSION['admin']) ? $_SESSION['admin'] : 'Admin'; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><a class="dropdown-item" href="change_password.php"><i class="fas fa-lock"></i> Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>

            <div class="p-4">
                <!-- Page Content Here -->
