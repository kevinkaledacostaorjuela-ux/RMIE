
<?php
// app/controllers/CategoriaController.php
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../../config/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
$categoriaModel = new Categoria($pdo);
$action = $_GET['action'] ?? 'list';
switch ($action) {
    case 'list':
        $categorias = $categoriaModel->obtenerTodas();
        include __DIR__ . '/../views/categorias/list.php';
        break;
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $categoriaModel->crear($nombre, $descripcion);
            header('Location: /RMIE/app/controllers/CategoriaController.php?action=list');
            exit();
        }
        include __DIR__ . '/../views/categorias/create.php';
        break;
    case 'edit':
        $id = $_GET['id'] ?? null;
        $categoria = $categoriaModel->obtenerPorId($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $categoriaModel->actualizar($id, $nombre, $descripcion);
            header('Location: /RMIE/app/controllers/CategoriaController.php?action=list');
            exit();
        }
        include __DIR__ . '/../views/categorias/edit.php';
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        $categoriaModel->eliminar($id);
    header('Location: /RMIE/app/controllers/CategoriaController.php?action=list');
        exit();
        break;
    default:
        header('Location: /RMIE/app/controllers/CategoriaController.php?action=list');
        exit();
}
?>