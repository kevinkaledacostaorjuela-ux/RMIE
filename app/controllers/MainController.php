<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        include __DIR__ . '/../views/dashboard.php';
        break;
    default:
        include __DIR__ . '/../views/dashboard.php';
        break;
}
?>
