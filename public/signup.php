<?php
include '../config/db.php';

// If user is already logged in, send them to the dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validation
    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM Assessment_users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $error = "Username is already taken.";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user (Role is 'user' by default)
            $insert = $pdo->prepare("INSERT INTO Assessment_users (username, password, role) VALUES (?, ?, 'user')");
            if ($insert->execute([$username, $hashed_password])) {
                $success = "Account created! <a href='login.php' style='color:#818cf8'>Login here</a>";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | MovieDB</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">

<div class="auth-card" style="width: 100%; max-width: 400px;">
    <div style="text-align: center; margin-bottom: 25px;">
        <h1 style="color: #fff; margin: 0;">Join Movie<span style="color: #6366f1;">DB</span></h1>
        <p style="color: #94a3b8;">Create your free account</p>
    </div>

    <?php if ($error): ?>
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 12px; border-radius: 8px; margin-bottom: 15px; border: 1px solid rgba(239, 68, 68, 0.2);">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 12px; border-radius: 8px; margin-bottom: 15px; border: 1px solid rgba(16, 185, 129, 0.2);">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Choose a username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        
        <label>Password</label>
        <input type="password" name="password" required placeholder="At least 6 characters">
        
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required placeholder="Repeat your password">
        
        <button type="submit" class="btn-main" style="margin-top: 10px;">Create Account</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <p style="color: #94a3b8; font-size: 0.9rem;">
            Already have an account? <a href="login.php" style="color: #6366f1; text-decoration: none;">Login here</a>
        </p>
    </div>
</div>

</body>
</html>