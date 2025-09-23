<?php
require_once __DIR__ . '/../models/Venta.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'listar';

switch ($action) {
    case 'crear':
        require_once __DIR__ . '/../models/Cliente.php';
        require __DIR__ . '/../../config/db.php';
        $clienteModel = new Cliente($pdo);
        $clientes = $clienteModel->obtenerTodos();
        require_once __DIR__ . '/../views/ventas/ventas_crear.php';
        break;
    case 'guardar':
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $cantidad = $_POST['cantidad'];
        $fecha_venta = $_POST['fecha_venta'];
        $id_clientes = isset($_POST['id_clientes']) ? $_POST['id_clientes'] : null;
        if ($id_clientes) {
            Venta::crear($nombre, $direccion, $cantidad, $fecha_venta, $id_clientes);
        }
        header('Location: /RMIE/app/controllers/VentaController.php?action=listar');
        exit;
    case 'editar':
        $id = $_GET['id'];
        $venta = Venta::obtenerPorId($id);
        require_once __DIR__ . '/../views/ventas/ventas_editar.php';
        break;
    case 'actualizar':
        // Recoge los datos del formulario y actualiza la venta
        // ...
        header('Location: /RMIE/app/controllers/VentaController.php?action=listar');
        exit;
    case 'eliminar':
        $id = $_GET['id'];
        Venta::eliminar($id);
        header('Location: /RMIE/app/controllers/VentaController.php?action=listar');
        exit;
    case 'filtrar':
        $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
        $ventas = array_filter(Venta::getAll(), function($venta) use ($nombre, $estado) {
            $nombreMatch = $nombre === '' || (isset($venta['nombre']) && stripos($venta['nombre'], $nombre) !== false);
            $estadoMatch = $estado === '' || (isset($venta['estado']) && $venta['estado'] === $estado);
            return $nombreMatch && $estadoMatch;
        });
        require_once __DIR__ . '/../views/ventas/ventas_listar.php';
        break;
    case 'listar':
    default:
        $ventas = Venta::getAll();
        require_once __DIR__ . '/../views/ventas/ventas_listar.php';
        break;
}
