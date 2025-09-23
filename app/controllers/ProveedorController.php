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
        $filtroProducto = isset($_GET['producto']) ? $_GET['producto'] : '';
        $filtroNombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
        $filtroEstado = isset($_GET['estado']) ? trim($_GET['estado']) : '';
        $productos = $productoModel->obtenerTodos();
        $proveedores = $proveedorModel->obtenerTodos();
        // Filtrar por producto
        if ($filtroProducto) {
            $proveedores = array_filter($proveedores, function($prov) use ($proveedorModel, $filtroProducto) {
                $prods = $proveedorModel->obtenerProductosPorProveedor($prov['id_proveedores']);
                foreach ($prods as $p) {
                    if ($p['id_productos'] == $filtroProducto) return true;
                }
                return false;
            });
        }
        // Filtrar por nombre
        if ($filtroNombre) {
            $proveedores = array_filter($proveedores, function($prov) use ($filtroNombre) {
                return stripos($prov['nombre_distribuidor'], $filtroNombre) !== false;
            });
        }
        // Filtrar por estado
        if ($filtroEstado) {
            $proveedores = array_filter($proveedores, function($prov) use ($filtroEstado) {
                return stripos($prov['estado'], $filtroEstado) !== false;
            });
        }
        include __DIR__ . '/../views/proveedores/proveedores_listar.php';
        break;
    default:
        header('Location: ProveedorController.php?action=listar_proveedores');
        exit();
}
?>