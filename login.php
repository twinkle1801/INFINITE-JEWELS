<?php
session_start();  // Start the session at the very beginning

include 'db.php';  // Include database connection

// Only run this part if the form is submitted
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $errors = [];

    // Validate email and password
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        // Sanitize email
        $email_safe = $conn->real_escape_string($email);

        // SQL query to check if email exists
        $sql = "SELECT id, username, password FROM users WHERE email='$email_safe' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the homepage (no output before header())
                header("Location: index.php");
                exit;  // Always exit after header redirect
            } else {
                // Password is incorrect
                $login_error = 'Incorrect password.';
            }
        } else {
            // No account found with that email
            $login_error = 'No account found with that email.';
        }
    } else {
        // Output validation errors
        $login_error = '<ul class="mb-0">';
        foreach ($errors as $error) {
            $login_error .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        $login_error .= '</ul>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login - Jewellery Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* SAME STYLES AS REGISTER PAGE */
        :root {
            --gold: #ffd700;
            --gold-dark: #b8860b;
            --text-dark: #222;
            --bg-light: #fdf8f0;
            --bg-dark: #121212;
            --shadow-gold: rgba(184, 134, 11, 0.3);
        }
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            transition: background 0.4s ease, color 0.4s ease;
            position: relative;
        }

        .form-container {
            background: white;
            padding: 40px 35px;
            max-width: 420px;
            width: 100%;
            border-radius: 18px;
            box-shadow: 0 10px 30px var(--shadow-gold);
            animation: slideFadeIn 1s ease forwards;
            position: relative;
            z-index: 1;
        }

        @keyframes slideFadeIn {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h3 {
            text-align: center;
            font-weight: 900;
            margin-bottom: 30px;
            color: var(--gold-dark);
            letter-spacing: 1.4px;
            text-transform: uppercase;
            text-shadow: 0 0 4px var(--gold);
        }

        label {
            font-weight: 600;
            color: var(--gold-dark);
            margin-bottom: 6px;
            display: block;
            user-select: none;
        }

        .form-control {
            height: 48px;
            font-size: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #fff;
            color: var(--text-dark);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-control:focus {
            border-color: var(--gold);
            box-shadow: 0 0 10px var(--gold);
            background: #fff;
        }

        .btn-primary {
            width: 100%;
            background: var(--gold);
            border: none;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 14px;
            border-radius: 12px;
            color: #4a3700;
            box-shadow: 0 6px 20px var(--shadow-gold);
        }

        .btn-primary:hover, .btn-primary:focus {
            background: var(--gold-dark);
            color: #fff;
            box-shadow: 0 8px 28px var(--gold);
        }

        .alert {
            border-radius: 10px;
            font-size: 0.95rem;
            margin-top: 25px;
            padding: 15px 20px;
            font-weight: 600;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        .toggle-mode {
            position: fixed;
            top: 25px;
            right: 25px;
            background: rgba(184, 134, 11, 0.15);
            border: 2px solid var(--gold-dark);
            padding: 10px 16px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--gold-dark);
            cursor: pointer;
            z-index: 100;
            box-shadow: 0 2px 10px var(--shadow-gold);
        }

        .toggle-mode:hover {
            background: var(--gold-dark);
            color: #fff;
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 30px 25px;
                margin: 0 20px;
                box-shadow: 0 8px 24px var(--shadow-gold);
            }
        }

        /* Dark Mode */
        body.dark-mode {
            background: #121212;
            color: #e4c96a;
        }
        body.dark-mode .form-container {
            background: #1f1b10;
            color: #e4c96a;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.6);
        }
        body.dark-mode label {
            color: #e4c96a;
        }
        body.dark-mode .form-control {
            background: #2a2514;
            border-color: #b8860b;
            color: #f5e58a;
        }
        body.dark-mode .form-control:focus {
            border-color: #ffd700;
            background: #3d371a;
            box-shadow: 0 0 12px #ffd700;
        }
        body.dark-mode .btn-primary {
            background: #b8860b;
            color: #fff;
        }
        body.dark-mode .btn-primary:hover {
            background: #ffd700;
            color: #4a3700;
        }
    </style>
</head>

<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    }
    window.onload = () => {
        if(localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    };
</script>

<body>
    <!-- Dark Mode Toggle -->
    <button class="toggle-mode" onClick="toggleDarkMode()">Toggle Mode</button>

    <!-- Login Form -->
    <section class="form-container" aria-labelledby="login-heading">
        <h3 id="login-heading">Login to Your Account</h3>

        <form method="post" action="" novalidate>
            <div class="mb-3">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required />
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required />
            </div>

            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </form>

        <?php if (isset($login_error)) { ?>
            <div class="alert alert-danger">
                <?php echo $login_error; ?>
            </div>
        <?php } ?>

    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
