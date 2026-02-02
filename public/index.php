<?php
include '../config/db.php';
include '../includes/checkRole.php';
include '../includes/header.php';
?>

<div class="hero">
    <h1>Movie Database</h1>

    <div class="search-box">
        <input type="text" id="localSearch"
               placeholder="Live search by title, genre, or cast..."
               autocomplete="off">
    </div>
</div>

<div class="table-card">
    <table>
        <thead>
        <tr>
            <th>Poster</th>
            <th>Movie</th>
            <th>Year</th>
            <th>Rating</th>
            <th>Genre</th>

            <?php if (isAdmin()): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
        </thead>

        <tbody id="movieTable">
        <?php
        $stmt = $pdo->query("SELECT * FROM Assessment_movies ORDER BY id DESC");

        while ($m = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $poster = !empty($m['poster_path'])
                ? "https://image.tmdb.org/t/p/w92" . $m['poster_path']
                : "https://via.placeholder.com/60x90?text=No";

            // ENTIRE ROW CLICKABLE
            echo "<tr class='click-row' onclick=\"window.location='movie.php?id=".$m['id']."'\">";

            echo "
                <td><img src='".htmlspecialchars($poster)."' class='table-poster' alt='Poster'></td>
                <td><strong>".htmlspecialchars($m['title'])."</strong></td>
                <td>".(int)$m['year']."</td>
                <td><span class='badge rating'>⭐ ".htmlspecialchars($m['rating'])."</span></td>
                <td><span class='badge genre'>".htmlspecialchars($m['genre'])."</span></td>
            ";

            // Actions (admin only)
            if (isAdmin()) {
                echo "<td>
                        <a class='btn-s edit'
                           href='edit.php?id=".$m['id']."'
                           onclick='event.stopPropagation();'>Edit</a>

                        <form method='POST' action='delete.php' style='display:inline;' onclick='event.stopPropagation();'>
                            <input type='hidden' name='id' value='".$m['id']."'>
                            <input type='hidden' name='csrf_token' value='".$_SESSION['csrf_token']."'>
                            <button type='submit' class='btn-s delete'
                                onclick='event.stopPropagation(); return confirm(\"Delete this movie?\");'>
                                Delete
                            </button>
                        </form>
                      </td>";
            }

            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
