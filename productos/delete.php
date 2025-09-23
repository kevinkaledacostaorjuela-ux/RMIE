<?php
require_once '../config/db.php';
require_once '../models/Producto.php';

$id = $_GET['id'] ?? null;
if ($id) {
    if (Producto::delete($pdo, $id)) {
        header('Location: list.php?msg=eliminado');
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
