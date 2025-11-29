<?php
// Punto de entrada para el módulo de rutas
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Incluir el controlador de rutas
require_once __DIR__ . '/app/controllers/RouteController.php';

// Obtener la acción solicitada
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'index';

// Crear instancia del controlador
$routeController = new RouteController();

// Ejecutar la acción correspondiente
switch ($accion) {
    case 'index':
        $routeController->index();
        break;
    case 'dia':
        // Ver rutas específicas del día
        $dia = isset($_GET['dia']) ? $_GET['dia'] : null;
        $rutas = $routeController->rutasDelDia($dia);
        
        // Preparar variables para la vista simplificada
        $dia_seleccionado = $dia ?? date('l'); // día en inglés por defecto
        $total_rutas = count($rutas);
        
        include __DIR__ . '/app/views/rutas/dia.php';
        break;
    case 'create':
        $routeController->create();
        break;
    case 'edit':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $routeController->edit($id);
        break;
    case 'delete':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $routeController->delete($id);
        break;
    default:
        $routeController->index();
        break;
}
?>