<?php
include '../config/tmdb.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
$id = $_GET['id'] ?? '';

// If an ID is provided, get full details (Genre + Cast)
if ($id) {
    $url = "https://api.themoviedb.org/3/movie/$id?api_key=" . TMDB_API_KEY . "&append_to_response=credits";
    $response = file_get_contents($url);
    echo $response;
    exit;
}

// Otherwise, perform a standard search
if ($q) {
    $results = searchTMDB($q);
    echo json_encode($results['results'] ?? []);
    exit;
}