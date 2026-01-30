<?php
define('TMDB_API_KEY', '2f41bd99eca83c25712a32781a538b05');

function searchTMDB($query) {
    $url = "https://api.themoviedb.org/3/search/movie?api_key=" . TMDB_API_KEY . "&query=" . urlencode($query);
    $response = file_get_contents($url);
    return json_decode($response, true);
}
?>