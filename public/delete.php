<?php
include '../config/db.php';
include '../includes/checkRole.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// CSRF Protection
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF validation failed.');
}

$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM Assessment_movies WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
