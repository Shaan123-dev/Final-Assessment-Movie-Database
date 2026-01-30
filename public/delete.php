<?php
include '../config/db.php';
include '../includes/checkRole.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: index.php');
exit;