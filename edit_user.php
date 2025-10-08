<?php
include "auth.php";
include "header.php";
include "db.php";

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = intval($_GET['id']);
$error = '';
$success = '';

// Fetch user data
$sql = "SELECT * FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    header("Location: users.php");
    exit;
}

$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Basic validation
    if (empty($username) || empty($email)) {
        $error = "Username and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Update user
        $username_escaped = mysqli_real_escape_string($conn, $username);
        $email_escaped = mysqli_real_escape_string($conn, $email);

        $update_sql = "UPDATE users SET username='$username_escaped', email='$email_escaped' WHERE id=$user_id";
        if (mysqli_query($conn, $update_sql)) {
            $success = "User updated successfully.";
            // Refresh data
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_assoc($result);
        } else {
            $error = "Error updating user: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Edit User</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input 
                type="text" 
                class="form-control" 
                id="username" 
                name="username" 
                value="<?php echo htmlspecialchars($user['username']); ?>" 
                required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                class="form-control" 
                id="email" 
                name="email" 
                value="<?php echo htmlspecialchars($user['email']); ?>" 
                required>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="users.php" class="btn btn-secondary">Back to Users</a>
    </form>
</div>

<?php include "footer.php"; ?>
