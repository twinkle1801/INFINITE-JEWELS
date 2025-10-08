<?php
session_start();
include 'db.php';

$login_errors = [];
$register_errors = [];
$register_success = false;

// Handle Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $login_errors[] = "Password is required.";
    }

    if (empty($login_errors)) {
        $email_safe = $conn->real_escape_string($email);
        $sql = "SELECT id, username, password FROM users WHERE email='$email_safe' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit;
            } else {
                $login_errors[] = "Incorrect password.";
            }
        } else {
            $login_errors[] = "No account found with that email.";
        }
    }
}

// Handle Registration
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = trim($_POST['reg_username']);
    $email = trim($_POST['reg_email']);
    $password_raw = $_POST['reg_password'];

    if (strlen($username) < 3 || strlen($username) > 30) {
        $register_errors[] = "Username must be between 3 and 30 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_errors[] = "Please enter a valid email address.";
    }
    if (strlen($password_raw) < 6) {
        $register_errors[] = "Password must be at least 6 characters.";
    }

    if (empty($register_errors)) {
        $username_safe = $conn->real_escape_string($username);
        $email_safe = $conn->real_escape_string($email);
        $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

        $checkSQL = "SELECT id FROM users WHERE email='$email_safe' OR username='$username_safe' LIMIT 1";
        $result = $conn->query($checkSQL);

        if ($result && $result->num_rows > 0) {
            $register_errors[] = "Username or Email already registered.";
        } else {
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username_safe', '$email_safe', '$password_hashed')";
            if ($conn->query($sql)) {
                $register_success = true;
            } else {
                $register_errors[] = "Database error: " . htmlspecialchars($conn->error);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login / Register - Jewellery Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* === SAME CSS AS YOUR VERSION (no effect removed) === */
        :root { --gold: #ffd700; --gold-dark: #b8860b; --text-dark: #222; --bg-light: #fdf8f0; --bg-dark: #121212; --shadow-gold: rgba(184, 134, 11, 0.3);}
        body {margin:0;padding:0;min-height:100vh;font-family:'Segoe UI',sans-serif;background:var(--bg-light);color:var(--text-dark);display:flex;justify-content:center;align-items:center;transition:background 0.4s ease,color 0.4s ease;}
        .form-container {background:white;padding:40px 35px;max-width:420px;width:100%;border-radius:18px;box-shadow:0 10px 30px var(--shadow-gold);animation:slideFadeIn 1s ease forwards;}
        @keyframes slideFadeIn {from{opacity:0;transform:translateY(40px);}to{opacity:1;transform:translateY(0);}}
        h3{text-align:center;font-weight:900;margin-bottom:30px;color:var(--gold-dark);letter-spacing:1.4px;text-transform:uppercase;text-shadow:0 0 4px var(--gold);}
        label{font-weight:600;color:var(--gold-dark);margin-bottom:6px;display:block;}
        .form-control{height:48px;font-size:1rem;border:2px solid #ddd;border-radius:8px;transition:border-color 0.3s ease,box-shadow 0.3s ease;}
        .form-control:focus{border-color:var(--gold);box-shadow:0 0 10px var(--gold);}
        .btn-primary{width:100%;background:var(--gold);border:none;font-weight:700;font-size:1.15rem;padding:14px;border-radius:12px;color:#4a3700;box-shadow:0 6px 20px var(--shadow-gold);}
        .btn-primary:hover{background:var(--gold-dark);color:#fff;box-shadow:0 8px 28px var(--gold);}
        .alert{border-radius:10px;margin-top:20px;font-weight:600;}
        .toggle-mode{position:fixed;top:25px;right:25px;background:rgba(184,134,11,0.15);border:2px solid var(--gold-dark);padding:10px 16px;border-radius:30px;font-weight:600;font-size:0.9rem;color:var(--gold-dark);}
        .toggle-mode:hover{background:var(--gold-dark);color:#fff;}
        body.dark-mode{background:#121212;color:#e4c96a;}
        body.dark-mode .form-container{background:#1f1b10;box-shadow:0 10px 30px rgba(255,215,0,0.6);}
        body.dark-mode .form-control{background:#2a2514;border-color:#b8860b;color:#fff;}
        body.dark-mode .btn-primary{background:#b8860b;color:#fff;}
        .nav-tabs{border-bottom:2px solid var(--gold-dark);margin-bottom:30px;}
        .nav-tabs .nav-link{color:var(--gold-dark);font-weight:700;border:none;border-radius:12px 12px 0 0;}
        .nav-tabs .nav-link.active{background:var(--gold);color:#4a3700;}
    </style>
</head>
<body>
    <button class="toggle-mode" onclick="toggleDarkMode()">Toggle Dark Mode</button>
    <div class="form-container">
        <ul class="nav nav-tabs">
            <li class="nav-item"><button id="login-tab" class="nav-link" onclick="showTab('login')">Login</button></li>
            <li class="nav-item"><button id="register-tab" class="nav-link" onclick="showTab('register')">Register</button></li>
        </ul>

        <!-- Login Form -->
        <form id="login-form" method="POST">
            <input type="hidden" name="action" value="login" />
            <h3>Login</h3>
            <?php if ($login_errors): ?>
                <div class="alert alert-danger"><?php echo implode("<br>", array_map('htmlspecialchars', $login_errors)); ?></div>
            <?php endif; ?>
            <label for="login_email">Email</label>
            <input id="login_email" type="email" name="login_email" class="form-control" value="<?php echo isset($_POST['login_email']) ? htmlspecialchars($_POST['login_email']) : ''; ?>" required>
            <label for="login_password" class="mt-3">Password</label>
            <input id="login_password" type="password" name="login_password" class="form-control" required>
            <button type="submit" class="btn btn-primary mt-4">Login</button>
        </form>

        <!-- Register Form -->
        <form id="register-form" method="POST" style="display:none;">
            <input type="hidden" name="action" value="register" />
            <h3>Register</h3>
            <?php if ($register_errors): ?>
                <div class="alert alert-danger"><?php echo implode("<br>", array_map('htmlspecialchars', $register_errors)); ?></div>
            <?php endif; ?>
            <?php if ($register_success): ?>
                <div class="alert alert-success">Registration successful! You can now login.</div>
            <?php endif; ?>
            <label for="reg_username">Username</label>
            <input id="reg_username" type="text" name="reg_username" class="form-control" value="<?php echo isset($_POST['reg_username']) ? htmlspecialchars($_POST['reg_username']) : ''; ?>" required>
            <label for="reg_email" class="mt-3">Email</label>
            <input id="reg_email" type="email" name="reg_email" class="form-control" value="<?php echo isset($_POST['reg_email']) ? htmlspecialchars($_POST['reg_email']) : ''; ?>" required>
            <label for="reg_password" class="mt-3">Password</label>
            <input id="reg_password" type="password" name="reg_password" class="form-control" required>
            <button type="submit" class="btn btn-primary mt-4">Register</button>
        </form>
    </div>

    <script>
        function toggleDarkMode(){document.body.classList.toggle('dark-mode');localStorage.setItem('theme',document.body.classList.contains('dark-mode')?'dark':'light');}
        function showTab(tab){document.getElementById('login-form').style.display=(tab==='login')?'block':'none';document.getElementById('register-form').style.display=(tab==='register')?'block':'none';document.getElementById('login-tab').classList.toggle('active',tab==='login');document.getElementById('register-tab').classList.toggle('active',tab==='register');}
        window.onload=()=>{if(localStorage.getItem('theme')==='dark'){document.body.classList.add('dark-mode');}<?php if (!empty($register_errors) || $register_success): ?>showTab('register');<?php else: ?>showTab('login');<?php endif; ?>};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
