<?php
include "auth.php";
include "db.php";

// Fetch admin info
$admin_username = $_SESSION['admin'] ?? '';
$admin_email = '';

if ($admin_username) {
    $stmt = $conn->prepare("SELECT username, email FROM admins WHERE username = ?");
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $admin_email = $admin['email'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile - Infinite Jewels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
    <h2>My Profile</h2>
    <div class="card mt-3" style="max-width:500px;">
        <div class="card-body">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($admin_username); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($admin_email); ?></p>
            <a href="change_password.php" class="btn btn-warning"><i class="fas fa-lock"></i> Change Password</a>
            <a href="settings.php" class="btn btn-primary"><i class="fas fa-cog"></i> Edit Profile</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
