<?php 
include '../config/db.php'; 
include '../includes/checkRole.php'; 
include '../includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='auth-card'><h2>Movie not found</h2></div>";
    include '../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM Assessment_movies WHERE id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$m) {
    echo "<div class='auth-card'><h2>Movie not found</h2></div>";
    include '../includes/footer.php';
    exit;
}

$poster = $m['poster_path'] 
    ? "https://image.tmdb.org/t/p/w342" . $m['poster_path'] 
    : "https://via.placeholder.com/342x513?text=No+Poster";
?>

<div class="movie-detail">
    <div class="movie-detail-card">
        <div class="movie-detail-left">
            <img src="<?php echo htmlspecialchars($poster); ?>" class="movie-detail-poster" alt="Poster">
        </div>

        <div class="movie-detail-right">
            <h2 class="movie-detail-title">
                <?php echo htmlspecialchars($m['title']); ?>
                <span class="movie-detail-year">(<?php echo (int)$m['year']; ?>)</span>
            </h2>

            <div class="movie-detail-meta">
                <span class="badge rating">⭐ <?php echo htmlspecialchars($m['rating']); ?></span>
                <span class="badge genre"><?php echo htmlspecialchars($m['genre']); ?></span>
                <?php if (!empty($m['runtime'])): ?>
                    <span class="badge"><?php echo (int)$m['runtime']; ?> min</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($m['director'])): ?>
                <p><strong>Director:</strong> <?php echo htmlspecialchars($m['director']); ?></p>
            <?php endif; ?>

            <?php if (!empty($m['cast_members'])): ?>
                <p><strong>Cast:</strong> <?php echo htmlspecialchars($m['cast_members']); ?></p>
            <?php endif; ?>

            <?php if (!empty($m['description'])): ?>
                <div class="movie-detail-desc">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($m['description'])); ?></p>
                </div>
            <?php endif; ?>

            <div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn-main" href="index.php">← Back to list</a>
                <?php if (isAdmin()): ?>
                    <a class="btn-main" href="edit.php?id=<?php echo (int)$m['id']; ?>">Edit</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
