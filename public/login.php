<?php
include '../config/db.php';

// If user is already logged in, send them to the dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'CSRF validation failed.';
    } else {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Use Prepared Statement to prevent SQL Injection
        $stmt = $pdo->prepare("SELECT * FROM Assessment_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify password hash
        if ($user && password_verify($password, $user['password'])) {
            // Set Session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
    }
}

// We don't include the standard header.php here because we want a clean login screen?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MovieDB</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">

<div class="auth-card" style="width: 100%; max-width: 400px; margin: 0;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #fff; font-size: 2rem; margin: 0;">🎬 Movie<span style="color: #6366f1;">DB</span></h1>
        <p style="color: #94a3b8;">Sign in to manage your database</p>
    </div>

    <?php if ($error): ?>
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid rgba(239, 68, 68, 0.2);">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label>Username</label>
        <input type="text" name="username" required autofocus placeholder="Enter your username">
        
        <label>Password</label>
        <input type="password" name="password" required placeholder="Enter your password">
        
        <button type="submit" class="btn-main" style="margin-top: 10px;">Login</button>
    </form>

    <div style="text-align: center; margin-top: 25px; border-top: 1px solid #334155; padding-top: 20px;">
        <p style="color: #94a3b8; font-size: 0.9rem;">
            Don't have an account? <a href="signup.php" style="color: #6366f1; text-decoration: none;">Create one</a>
        </p>
    </div>

</div>

</body>
</html>