<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MovieDB - Movie Management System">
    <title>Shaan's | MovieDB</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="index.php" class="logo">🎬 Movie<span>DB</span></a>
            <nav>
                <?php if (isset($_SESSION['username'])): ?>
                    <span>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="index.php">Dashboard</a>
                    <?php if (isAdmin()): ?><a href="add.php" class="btn-nav">+ Add Movie</a><?php endif; ?>
                    <a href="request.php">Requests</a>
                    <a href="logout.php" class="logout">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">