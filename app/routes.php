<?php
require_once __DIR__ . '/controllers/RouteController.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$routeController = new RouteController();

switch ($action) {
    case 'create_ruta':
        $routeController->create();
        break;
    case 'edit_ruta':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $routeController->edit($id);
        break;
    case 'delete_ruta':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $routeController->delete($id);
        break;
    default:
        $routeController->index();
        break;
}
