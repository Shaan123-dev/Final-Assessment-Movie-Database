<?php
include '../config/db.php';
include '../includes/checkRole.php';
$message = '';

// Check if requests.txt exists, if not create it
if (!file_exists('requests.txt')) {
    file_put_contents('requests.txt', '');
}

// 1. ADMIN LOGIC: Clear all requests
if (isset($_POST['clear_requests']) && isAdmin()) {
    file_put_contents('requests.txt', '');
    $message = "Requests cleared successfully.";
}

// 2. USER LOGIC: Submit a new request
if (isset($_POST['submit_request']) && !isAdmin()) {
    $title = trim($_POST['movie_title']);
    $reason = trim($_POST['reason']);
    
    if (!empty($title) && !empty($reason)) {
        $timestamp = date('Y-m-d H:i');
        $user = $_SESSION['username'];
        $entry = "[$timestamp] $user requested: $title | Reason: $reason\n";
        
        file_put_contents('requests.txt', $entry, FILE_APPEND | LOCK_EX);
        $message = "Thank you! Your request has been sent to the admin.";
    } else {
        $message = "Please fill in all fields.";
    }
}

include '../includes/header.php';
?>

<div class="hero" style="padding: 20px 0;">
    <h1>🎬 Movie Requests</h1>
    <p>Help us grow our database by suggesting your favorites.</p>
</div>

<?php if ($message): ?>
    <div style="background: rgba(99, 102, 241, 0.1); color: #818cf8; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid rgba(99, 102, 241, 0.2); text-align: center;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="request-container" style="display: grid; grid-template-columns: <?php echo isAdmin() ? '1fr' : '1fr 1fr'; ?>; gap: 30px;">
    
    <!-- LEFT SIDE: REQUEST FORM (Visible to standard users) -->
    <?php if (!isAdmin()): ?>
    <div class="form-card" style="margin: 0;">
        <h3>Suggest a Movie</h3>
        <form method="POST">
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
                    <button type="submit" name="clear_requests" style="background: #ef4444; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">Clear All Logs</button>
                </form>
            <?php endif; ?>
        </div>

        <div style="background: #0f172a; border-radius: 8px; padding: 15px; font-family: monospace; color: #94a3b8; max-height: 400px; overflow-y: auto; border: 1px solid #334155;">
            <?php 
                $content = file_get_contents('requests.txt');
                if (empty(trim($content))) {
                    echo "No active requests at the moment.";
                } else {
                    echo nl2br(htmlspecialchars($content));
                }
            ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>