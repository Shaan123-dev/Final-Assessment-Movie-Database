<?php
include '../config/db.php';
include '../includes/checkRole.php';

$message = '';

$uid  = (int)($_SESSION['user_id'] ?? 0);

// OPTIONAL: if not logged in, redirect
if ($uid <= 0) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $message = "Invalid token. Please refresh the page and try again.";
    } else {

        // ADMIN: Clear all requests
        if (isset($_POST['clear_requests']) && isAdmin()) {
            $pdo->exec("DELETE FROM Assessment_request");
            $message = "Requests cleared successfully.";
        }

        // USER: Submit request
        if (isset($_POST['submit_request']) && !isAdmin()) {
            $title  = trim($_POST['movie_title'] ?? '');
            $reason = trim($_POST['reason'] ?? '');

            if ($title === '' || $reason === '') {
                $message = "Please fill in all fields.";
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO Assessment_request (user_id, requested_title, reason, status)
                    VALUES (?, ?, ?, 'pending')
                ");
                $stmt->execute([$uid, $title, $reason]);
                $message = "Thank you! Your request has been sent to the admin.";
            }
        }
    }
}

/* ---------------------------
   FETCH REQUESTS
---------------------------- */
if (isAdmin()) {
    // Admin sees all requests with usernames
    $stmt = $pdo->query("
        SELECT r.id, r.requested_title, r.reason, r.status, r.created_at, u.username
        FROM Assessment_request r
        JOIN Assessment_users u ON u.id = r.user_id
        ORDER BY r.created_at DESC
        LIMIT 30
    ");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // User sees only their requests
    $stmt = $pdo->prepare("
        SELECT id, requested_title, reason, status, created_at
        FROM Assessment_request
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 30
    ");
    $stmt->execute([$uid]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include '../includes/header.php';
?>

<div class="hero" style="padding: 20px 0;">
    <h1>🎬 Movie Requests</h1>
    <p>Help us grow our database by suggesting your favorites.</p>
</div>

<?php if ($message): ?>
    <div style="background: rgba(99, 102, 241, 0.1); color: #818cf8; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid rgba(99, 102, 241, 0.2); text-align: center;">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="request-container" style="display: grid; grid-template-columns: <?php echo isAdmin() ? '1fr' : '1fr 1fr'; ?>; gap: 30px;">

    <!-- LEFT SIDE: REQUEST FORM (Visible to standard users) -->
    <?php if (!isAdmin()): ?>
    <div class="form-card" style="margin: 0;">
        <h3>Suggest a Movie</h3>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <label>Movie Title</label>
            <input type="text" name="movie_title" required placeholder="e.g. Interstellar">

            <label>Why should we add it?</label>
            <textarea name="reason" rows="5" required placeholder="Describe the genre or plot..."></textarea>

            <button type="submit" name="submit_request" class="btn-main">Submit Request</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- RIGHT SIDE / FULL WIDTH: VIEW REQUESTS -->
    <div class="table-card" style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Recent Requests</h3>

            <?php if (isAdmin()): ?>
                <form method="POST" onsubmit="return confirm('Clear all user requests?');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit" name="clear_requests"
                        style="background: #ef4444; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                        Clear All Logs
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div style="background: #0f172a; border-radius: 8px; padding: 15px; font-family: monospace; color: #94a3b8; max-height: 400px; overflow-y: auto; border: 1px solid #334155;">
            <?php if (empty($requests)): ?>
                No active requests at the moment.
            <?php else: ?>
                <?php foreach ($requests as $r): ?>
                    <?php
                        $who = isAdmin() ? ($r['username'] ?? 'unknown') : ($_SESSION['username'] ?? 'user');
                        echo htmlspecialchars("[" . $r['created_at'] . "] " . $who . " requested: " . $r['requested_title'] . " | Status: " . $r['status']);
                        echo "<br>";
                        echo htmlspecialchars("Reason: " . $r['reason']);
                        echo "<br><br>";
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
