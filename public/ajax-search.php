<?php
include '../config/db.php';
include '../includes/checkRole.php';

$qraw = $_GET['q'] ?? '';
$q = "%" . $qraw . "%";

$stmt = $pdo->prepare("
    SELECT * FROM Assessment_movies
    WHERE title LIKE ?
       OR genre LIKE ?
       OR cast_members LIKE ?
    ORDER BY id DESC
");
$stmt->execute([$q, $q, $q]);

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
