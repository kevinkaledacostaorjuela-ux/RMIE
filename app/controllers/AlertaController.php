<?php
// app/controllers/AlertaController.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../models/Alerta.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../../config/db.php';

$alertaModel = new Alerta($pdo);
$productoModel = new Producto($pdo);

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'crear_alerta':
        $clientes = $alertaModel->obtenerClientes();
        $productos = $alertaModel->obtenerProductos();
        include __DIR__ . '/../views/alertas/alertas_crear.php';
        break;
    case 'guardar_alerta':
        $mensaje = $_POST['cliente_no_disponible'];
        $id_cliente = $_POST['id_clientes'];
        $id_producto = $_POST['id_productos'];
        $alertaModel->crear($mensaje, $id_cliente, $id_producto);
        header('Location: AlertaController.php?action=listar_alertas');
        exit();
    case 'listar_alertas':
        $filtroProducto = isset($_GET['producto']) ? $_GET['producto'] : '';
        $productos = $productoModel->obtenerTodos();
        $alertas = $alertaModel->obtenerTodas();
        if ($filtroProducto) {
            $alertas = array_filter($alertas, function($alerta) use ($filtroProducto) {
                return $alerta['id_productos'] == $filtroProducto;
            });
        }
        include __DIR__ . '/../views/alertas/alertas_listar.php';
        break;
    default:
        header('Location: AlertaController.php?action=listar_alertas');
        exit();
}
?>