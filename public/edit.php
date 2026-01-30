<?php
include '../config/db.php';
include '../config/tmdb.php';
include '../includes/checkRole.php';

requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$message = '';

$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF validation failed.');
    }
    try {
        $runtime = isset($_POST['runtime']) && $_POST['runtime'] !== '' ? (int)$_POST['runtime'] : null;

        $sql = "UPDATE movies 
                SET title=?, year=?, rating=?, genre=?, cast_members=?, description=?, director=?, runtime=?, poster_path=? 
                WHERE id=?";
        $updateStmt = $pdo->prepare($sql);
        $updateStmt->execute([
            $_POST['title'], 
            $_POST['year'], 
            $_POST['rating'], 
            $_POST['genre'], 
            $_POST['cast_members'],
            $_POST['description'] ?? null,
            $_POST['director'] ?? null,
            $runtime,
            $_POST['poster_path'] ?? null,
            $id
        ]);

        header('Location: index.php?msg=updated');
        exit;
    } catch (Exception $e) {
        $message = "Error updating movie: " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="auth-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Edit Movie Details</h2>
        <a href="index.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">← Cancel & Back</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Step 1: Optional TMDB Sync -->
    <div class="api-search-container" style="position: relative; margin-bottom: 20px; border: 1px dashed var(--primary); padding: 15px; border-radius: 12px;">
        <label style="color: var(--primary); font-weight: bold;">Step 1: Update via TMDB (Optional)</label>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 10px;">Search to automatically refresh rating, poster, cast, runtime, director and description.</p>
        <input type="text" id="tmdbSearch" placeholder="🔍 Search TMDB to sync new details..." style="border: 1px solid var(--primary); margin-bottom: 5px;">
        <small id="searchStatus" style="color: var(--text-muted); display: block;"></small>
        <div id="tmdbResults" class="api-dropdown"></div>
    </div>

    <hr style="border: 0; border-top: 1px solid var(--glass); margin: 20px 0;">

    <!-- Step 2 -->
    <form method="POST" id="movieForm">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label style="color: var(--primary); font-weight: bold;">Step 2: Edit & Confirm Details</label>
        
        <input type="hidden" name="poster_path" id="f_poster" value="<?php echo htmlspecialchars($movie['poster_path']); ?>">
        
        <label>Movie Title</label>
        <input type="text" name="title" id="f_title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
        
        <div class="form-row">
            <div>
                <label>Release Year</label>
                <input type="number" name="year" id="f_year" value="<?php echo (int)$movie['year']; ?>" required>
            </div>
            <div>
                <label>Rating (0-10)</label>
                <input type="number" step="0.1" name="rating" id="f_rating" value="<?php echo htmlspecialchars($movie['rating']); ?>" required>
            </div>
        </div>
        
        <label>Genre(s)</label>
        <input type="text" name="genre" id="f_genre" value="<?php echo htmlspecialchars($movie['genre']); ?>" required>
        
        <label>Cast Members</label>
        <textarea name="cast_members" id="f_cast" rows="3" required><?php echo htmlspecialchars($movie['cast_members']); ?></textarea>

        <div class="form-row">
            <div>
                <label>Director</label>
                <input type="text" name="director" id="f_director" value="<?php echo htmlspecialchars($movie['director'] ?? ''); ?>" placeholder="Director name">
            </div>
            <div>
                <label>Runtime (minutes)</label>
                <input type="number" name="runtime" id="f_runtime" min="1" max="600" value="<?php echo htmlspecialchars($movie['runtime'] ?? ''); ?>" placeholder="e.g. 128">
            </div>
        </div>

        <label>Description</label>
        <textarea name="description" id="f_description" rows="4" placeholder="Short plot/overview..."><?php echo htmlspecialchars($movie['description'] ?? ''); ?></textarea>
        
        <button type="submit" class="btn-main">Update Record</button>
    </form>
</div>

<script>
let searchTimeout;

document.getElementById('tmdbSearch').addEventListener('input', function() {
    const q = this.value;
    const resultsDiv = document.getElementById('tmdbResults');
    const status = document.getElementById('searchStatus');

    clearTimeout(searchTimeout);
    if (q.length < 3) {
        resultsDiv.innerHTML = '';
        status.innerText = '';
        status.style.color = '';
        return;
    }

    status.innerText = 'Searching TMDB...';
    status.style.color = '';

    searchTimeout = setTimeout(async () => {
        try {
            const res = await fetch(`tmdb-search.php?q=${encodeURIComponent(q)}`);
            const data = await res.json();
            
            if (!Array.isArray(data) || data.length === 0) {
                status.innerText = 'No movies found.';
                resultsDiv.innerHTML = '';
                return;
            }

            status.innerText = 'Matches found. Click to overwrite current info:';
            resultsDiv.innerHTML = data.slice(0, 5).map(m => `
                <div class="drop-item" onclick="fetchDeepDetails(${m.id})">
                    <img src="${m.poster_path ? 'https://image.tmdb.org/t/p/w92'+m.poster_path : 'https://via.placeholder.com/30x45?text=No'}" width="30">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight:bold; color:white;">${m.title}</span>
                        <small style="color:#94a3b8;">${m.release_date ? m.release_date.split('-')[0] : 'N/A'}</small>
                    </div>
                </div>
            `).join('');
        } catch (err) {
            status.innerText = 'Error connecting to API.';
        }
    }, 500);
});

async function fetchDeepDetails(movieId) {
    const status = document.getElementById('searchStatus');
    status.innerText = 'Fetching latest details from API...';
    status.style.color = '';
    
    try {
        const res = await fetch(`tmdb-search.php?id=${movieId}`);
        const movie = await res.json();

        document.getElementById('f_title').value = movie.title || '';
        document.getElementById('f_year').value = movie.release_date ? movie.release_date.split('-')[0] : '';
        document.getElementById('f_rating').value = (movie.vote_average != null) ? Number(movie.vote_average).toFixed(1) : '';
        document.getElementById('f_poster').value = movie.poster_path || '';

        const genres = Array.isArray(movie.genres) ? movie.genres.map(g => g.name).join(', ') : '';
        document.getElementById('f_genre').value = genres;

        const cast = movie.credits && Array.isArray(movie.credits.cast)
            ? movie.credits.cast.slice(0, 5).map(c => c.name).join(', ')
            : '';
        document.getElementById('f_cast').value = cast;

        document.getElementById('f_description').value = movie.overview || '';
        document.getElementById('f_runtime').value = movie.runtime ? movie.runtime : '';

        let director = '';
        if (movie.credits && Array.isArray(movie.credits.crew)) {
            const dirObj = movie.credits.crew.find(p => p.job === 'Director');
            director = dirObj ? dirObj.name : '';
        }
        document.getElementById('f_director').value = director;

        document.getElementById('tmdbResults').innerHTML = '';
        document.getElementById('tmdbSearch').value = '';
        status.innerText = '✅ Form updated with TMDB data!';
        status.style.color = '#10b981';

    } catch (err) {
        console.error("Fetch failed", err);
        status.innerText = '❌ Failed to get details.';
        status.style.color = '#ef4444';
    }
}
</script>

<?php include '../includes/footer.php'; ?>
