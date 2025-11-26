<?php
/**
 * Página de redirección directa para editar categorías
 * Acceder a: http://localhost/RMIE/edit_categoria.php?id=40
 */

// Headers anti-caché
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $url = "/RMIE/app/controllers/CategoryController.php?accion=edit&id=" . $id . "&t=" . time();
    header("Location: $url");
    exit();
} else {
    $url = "/RMIE/app/controllers/CategoryController.php?accion=index";
    header("Location: $url");
    exit();
}
?>
