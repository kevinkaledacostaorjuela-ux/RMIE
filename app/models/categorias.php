<?php
require_once __DIR__ . '/app/controllers/CategoryController.php';

$controller = new CategoryController();
$accion = $_GET['accion'] ?? 'index';

switch ($accion) {
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->edit($id);
        } else {
            header('Location: categorias.php');
        }
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->delete($id);
        } else {
            header('Location: categorias.php');
        }
        break;
    case 'index':
    default:
        $controller->index();
        break;
}
?>
