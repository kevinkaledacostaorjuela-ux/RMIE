<?php
// app/controllers/ProductoController.php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../../config/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
$productoModel = new Producto($pdo);
$action = $_GET['action'] ?? 'listar_productos';
switch ($action) {
    case 'listar_productos':
        $productos = $productoModel->obtenerTodos();
        $categorias = $productoModel->obtenerCategorias();
        $subcategorias = $productoModel->obtenerSubcategorias();
        include __DIR__ . '/../views/productos/productos_listar.php';
        break;
    case 'crear_producto':
        $subcategorias = $productoModel->obtenerSubcategorias();
        include __DIR__ . '/../views/productos/productos_crear.php';
        break;
    case 'guardar_producto':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha_entrada = $_POST['fecha_entrada'] ?? '';
            $fecha_fabricacion = $_POST['fecha_fabricacion'] ?? '';
            $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
            $id_subcategoria = $_POST['id_subcategoria'] ?? '';
            $productoModel->crear($nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria);
        }
        header('Location: /RMIE/app/controllers/ProductoController.php?action=listar_productos');
        exit();
    case 'editar_producto':
        $id = $_GET['id'] ?? null;
        $producto = $productoModel->obtenerPorId($id);
        $subcategorias = $productoModel->obtenerSubcategorias();
        include __DIR__ . '/../views/productos/productos_editar.php';
        break;
    case 'actualizar_producto':
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha_entrada = $_POST['fecha_entrada'] ?? '';
            $fecha_fabricacion = $_POST['fecha_fabricacion'] ?? '';
            $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
            $id_subcategoria = $_POST['id_subcategoria'] ?? '';
            $productoModel->actualizar($id, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria);
        }
        header('Location: /RMIE/app/controllers/ProductoController.php?action=listar_productos');
        exit();
    case 'eliminar_producto':
        $id = $_GET['id'] ?? null;
        $productoModel->eliminar($id);
        header('Location: /RMIE/app/controllers/ProductoController.php?action=listar_productos');
        exit();
    default:
        header('Location: /RMIE/app/controllers/ProductoController.php?action=listar_productos');
        exit();
}
?>