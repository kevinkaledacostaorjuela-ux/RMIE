<?php
// app/controllers/SubcategoriaController.php
require_once __DIR__ . '/../models/Subcategoria.php';
require_once __DIR__ . '/../../config/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
$subcategoriaModel = new Subcategoria($pdo);
$action = $_GET['action'] ?? 'listar_subcategorias';
switch ($action) {
    case 'listar_subcategorias':
        $subcategorias = $subcategoriaModel->obtenerTodas();
        $categorias = $subcategoriaModel->obtenerCategorias();
        include __DIR__ . '/../views/subcategorias/subcategorias_listar.php';
        break;
    case 'crear_subcategoria':
        $categorias = $subcategoriaModel->obtenerCategorias();
        include __DIR__ . '/../views/subcategorias/subcategorias_crear.php';
        break;
    case 'guardar_subcategoria':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $id_categoria = $_POST['id_categoria'] ?? '';
            $subcategoriaModel->crear($nombre, $descripcion, $id_categoria);
        }
    header('Location: /RMIE/app/controllers/SubcategoriaController.php?action=listar_subcategorias');
        exit();
    case 'editar_subcategoria':
        $id = $_GET['id'] ?? null;
        $subcategoria = $subcategoriaModel->obtenerPorId($id);
        $categorias = $subcategoriaModel->obtenerCategorias();
        include __DIR__ . '/../views/subcategorias/subcategorias_editar.php';
        break;
    case 'actualizar_subcategoria':
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $id_categoria = $_POST['id_categoria'] ?? '';
            $subcategoriaModel->actualizar($id, $nombre, $descripcion, $id_categoria);
        }
    header('Location: /RMIE/app/controllers/SubcategoriaController.php?action=listar_subcategorias');
        exit();
    case 'eliminar_subcategoria':
        $id = $_GET['id'] ?? null;
        $subcategoriaModel->eliminar($id);
    header('Location: /RMIE/app/controllers/SubcategoriaController.php?action=listar_subcategorias');
        exit();
    default:
        header('Location: /RMIE/app/controllers/SubcategoriaController.php?action=listar_subcategorias');
        exit();
}
?>