<?php
include '../config/db.php';
include '../config/tmdb.php';
include '../includes/checkRole.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF validation failed.');
    }
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Assessment_movies (title, year, rating, genre, cast_members, description, director, runtime, poster_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $runtime = isset($_POST['runtime']) && $_POST['runtime'] !== '' ? (int)$_POST['runtime'] : null;

        $stmt->execute([
            $_POST['title'], 
            $_POST['year'], 
            $_POST['rating'], 
            $_POST['genre'], 
            $_POST['cast_members'],
            $_POST['description'] ?? null,
            $_POST['director'] ?? null,
            $runtime,
            $_POST['poster_path'] ?? null
        ]);

        header('Location: index.php?msg=success');
        exit;
    } catch (Exception $e) {
        $error = "Error saving movie: " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="auth-card">
    <h2>Add New Movie</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- TMDB API Search Section -->
    <div class="api-search-container" style="position: relative; margin-bottom: 20px;">
        <label style="color: var(--primary); font-weight: bold;">Step 1: Quick Search TMDB (Auto-fill)</label>
        <input type="text" id="tmdbSearch" placeholder="🔍 Type movie name (e.g. Oppenheimer)..." style="border: 2px solid var(--primary); margin-bottom: 5px;">
        <small id="searchStatus" style="color: var(--text-muted); display: block; margin-bottom: 10px;"></small>
        <div id="tmdbResults" class="api-dropdown"></div>
    </div>

    <hr style="border: 0; border-top: 1px solid var(--glass); margin: 20px 0;">

    <!-- Manual/Edit Form Section -->
    <form method="POST" id="movieForm">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label style="color: var(--primary); font-weight: bold;">Step 2: Confirm Details</label>
        
        <input type="hidden" name="poster_path" id="f_poster">
        
        <label>Movie Title</label>
        <input type="text" name="title" id="f_title" required placeholder="Movie title">
        
        <div class="form-row">
            <div>
                <label>Release Year</label>
                <input type="number" name="year" id="f_year" required placeholder="Year">
            </div>
            <div>
                <label>Rating (0-10)</label>
                <input type="number" step="0.1" name="rating" id="f_rating" required placeholder="Rating">
            </div>
        </div>
        
        <label>Genre(s)</label>
        <input type="text" name="genre" id="f_genre" required placeholder="Genres (Action, Sci-Fi...)">
        
        <label>Cast Members</label>
        <textarea name="cast_members" id="f_cast" rows="3" required placeholder="Main actors..."></textarea>

        <div class="form-row">
            <div>
                <label>Director</label>
                <input type="text" name="director" id="f_director" placeholder="Director name">
            </div>
            <div>
                <label>Runtime (minutes)</label>
                <input type="number" name="runtime" id="f_runtime" min="1" max="600" placeholder="e.g. 128">
            </div>
        </div>

        <label>Description</label>
        <textarea name="description" id="f_description" rows="4" placeholder="Short plot/overview..."></textarea>
        
        <button type="submit" class="btn-main">Save to Database</button>
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

            status.innerText = 'Found matches. Click one to auto-fill:';
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
    status.innerText = 'Fetching full details (Cast, Genre, Runtime, Director)...';
    status.style.color = '';

    try {
        const res = await fetch(`tmdb-search.php?id=${movieId}`);
        const movie = await res.json();

        // Basic fields
        document.getElementById('f_title').value = movie.title || '';
        document.getElementById('f_year').value = movie.release_date ? movie.release_date.split('-')[0] : '';
        document.getElementById('f_rating').value = (movie.vote_average != null) ? Number(movie.vote_average).toFixed(1) : '';
        document.getElementById('f_poster').value = movie.poster_path || '';

        // Genres
        const genres = Array.isArray(movie.genres) ? movie.genres.map(g => g.name).join(', ') : '';
        document.getElementById('f_genre').value = genres;

        // Cast (Top 5)
        const cast = movie.credits && Array.isArray(movie.credits.cast)
            ? movie.credits.cast.slice(0, 5).map(c => c.name).join(', ')
            : '';
        document.getElementById('f_cast').value = cast;

        // Description (overview)
        document.getElementById('f_description').value = movie.overview || '';

        // Runtime
        document.getElementById('f_runtime').value = movie.runtime ? movie.runtime : '';

        // Director (from credits crew)
        let director = '';
        if (movie.credits && Array.isArray(movie.credits.crew)) {
            const dirObj = movie.credits.crew.find(p => p.job === 'Director');
            director = dirObj ? dirObj.name : '';
        }
        document.getElementById('f_director').value = director;

        // UI Cleanup
        document.getElementById('tmdbResults').innerHTML = '';
        document.getElementById('tmdbSearch').value = movie.title || '';
        status.innerText = '✅ Movie details loaded successfully!';
        status.style.color = '#10b981';

    } catch (err) {
        console.error("Fetch failed", err);
        status.innerText = '❌ Failed to get details.';
        status.style.color = '#ef4444';
    }
}
</script>

<?php include '../includes/footer.php'; ?>
