<?php
include "auth.php";
include "db.php";

$admin_username = $_SESSION['admin'] ?? '';
$admin_email = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE admins SET username=?, email=? WHERE username=?");
    $stmt->bind_param("sss", $new_username, $new_email, $admin_username);
    if ($stmt->execute()) {
        $_SESSION['admin'] = $new_username;
        $admin_username = $new_username;
        $admin_email = $new_email;
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile.";
    }
    $stmt->close();
} else {
    // Fetch current data
    if ($admin_username) {
        $stmt = $conn->prepare("SELECT username, email FROM admins WHERE username=?");
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $admin_email = $admin['email'];
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Infinite Jewels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
    <h2>Admin Settings</h2>
    <?php if(isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
    <form method="post" style="max-width:500px;">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($admin_username); ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin_email); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
