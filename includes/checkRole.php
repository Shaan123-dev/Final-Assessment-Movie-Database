<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
function isAdmin() { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
function requireAdmin() { if (!isAdmin()) { header('Location: index.php'); exit; } }
?>