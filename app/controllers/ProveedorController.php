<?php
// app/controllers/ProveedorController.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../models/Proveedor.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../../config/db.php';

$proveedorModel = new Proveedor($pdo);
$productoModel = new Producto($pdo);

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'crear':
        // Mostrar formulario de creación
        $productos = $productoModel->obtenerTodos();
        include __DIR__ . '/../views/proveedores/proveedores_crear.php';
        break;
    case 'guardar_proveedor':
        // Guardar proveedor
        $nombre = $_POST['nombre_distribuidor'];
        $correo = $_POST['correo'];
        $cel = $_POST['cel_proveedor'];
        $estado = $_POST['estado'];
        $productos = isset($_POST['productos']) ? $_POST['productos'] : [];
        $proveedorModel->crear($nombre, $correo, $cel, $estado, $productos);
        header('Location: ProveedorController.php?action=listar_proveedores');
        exit();
    case 'listar_proveedores':
        // Aquí iría la lógica para listar proveedores (no implementada en este parche)
        echo '<h2>Listado de Proveedores (en construcción)</h2>';
        break;
    default:
        header('Location: ProveedorController.php?action=crear');
        exit();
}
?>