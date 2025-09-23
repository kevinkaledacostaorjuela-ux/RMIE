<?php
require_once '../config/db.php';
require_once '../models/Categoria.php';

$id = $_GET['id'] ?? null;

if ($id) {
    if (Categoria::delete($pdo, $id)) {
        header('Location: list.php?msg=eliminada');
        exit;
    } else {
        header('Location: list.php?msg=error');
        exit;
    }
} else {
    header('Location: list.php');
    exit;
}
?>
