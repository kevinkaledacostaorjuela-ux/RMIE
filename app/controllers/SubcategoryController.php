<?php
require_once __DIR__ . '/../models/Subcategory.php';
require_once __DIR__ . '/../models/SubcategorySimple.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/db.php';

class SubcategoryController {
    public function index() {
        global $conn;
        // Usar la versión simple que no requiere fecha_creacion
        $subcategorias = SubcategorySimple::getAllSimple($conn);
        include __DIR__ . '/../views/subcategorias/index.php';
    }

    public function create() {
        global $conn;
        $categorias = Category::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<pre>POST: ' . print_r($_POST, true) . '</pre>';
            $nombre = $_POST['nombre'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $id_categoria = $_POST['id_categoria'] ?? null;
            $result = SubcategorySimple::createSimple($conn, $nombre, $descripcion, $id_categoria);
            if (!$result) {
                if (isset($conn->error)) {
                    echo '<pre>Error SQL: ' . $conn->error . '</pre>';
                } else {
                    echo '<pre>Error al guardar la subcategoría.</pre>';
                }
            } else {
                echo '<pre>Subcategoría guardada correctamente.</pre>';
                header('Location: /RMIE/app/controllers/SubcategoryController.php?accion=index');
                exit();
            }
        }
        include __DIR__ . '/../views/subcategorias/create.php';
    }

    public function edit($id) {
        global $conn;
        $subcategoria = SubcategorySimple::getByIdSimple($conn, $id);
        $categorias = Category::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $id_categoria = $_POST['id_categoria'] ?? null;
            $result = SubcategorySimple::updateSimple($conn, $id, $nombre, $descripcion, $id_categoria);
            if (!$result) {
                echo '<pre>Error al actualizar la subcategoría.</pre>';
            } else {
                echo '<pre>Subcategoría actualizada correctamente.</pre>';
                header('Location: /RMIE/app/controllers/SubcategoryController.php?accion=index');
                exit();
            }
        }
        include __DIR__ . '/../views/subcategorias/edit.php';
    }

    public function delete($id) {
        global $conn;
        $result = SubcategorySimple::deleteSimple($conn, $id);
        if (!$result) {
            echo '<pre>Error al eliminar la subcategoría.</pre>';
        } else {
            echo '<pre>Subcategoría eliminada correctamente.</pre>';
        }
        header('Location: /RMIE/app/controllers/SubcategoryController.php?accion=index');
        exit();
    }
}

// Manejo de acciones por parámetro GET
if (isset($_GET['accion'])) {
    $controller = new SubcategoryController();
    switch ($_GET['accion']) {
        case 'create':
            $controller->create();
            break;
        case 'edit':
            if (isset($_GET['id'])) {
                $controller->edit($_GET['id']);
            }
            break;
        case 'delete':
            if (isset($_GET['id'])) {
                $controller->delete($_GET['id']);
            }
            break;
        default:
            $controller->index();
            break;
    }
} else {
    $controller = new SubcategoryController();
    $controller->index();
}
?>
