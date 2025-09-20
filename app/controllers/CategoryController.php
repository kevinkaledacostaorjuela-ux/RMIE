<?php
// Mostrar errores de PHP para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/db.php';

class CategoryController {
	public function index() {
		global $conn;
		$categorias = Category::getAll($conn);
		include __DIR__ . '/../views/categorias/index.php';
	}

	public function create() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			global $conn;
			echo '<pre>POST: ' . print_r($_POST, true) . '</pre>';
			$nombre = $_POST['nombre'] ?? null;
			$descripcion = $_POST['descripcion'] ?? null;
			$result = Category::create($conn, $nombre, $descripcion);
			if (!$result) {
				if (isset($conn->error)) {
					echo '<pre>Error SQL: ' . $conn->error . '</pre>';
				} else {
					echo '<pre>Error al guardar la categoría.</pre>';
				}
			} else {
				echo '<pre>Categoría guardada correctamente.</pre>';
				header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
				exit();
			}
		}
		include __DIR__ . '/../views/categorias/create.php';
	}

	public function edit($id) {
		global $conn;
		$categoria = Category::getById($conn, $id);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nombre = $_POST['nombre'];
			$descripcion = $_POST['descripcion'];
			Category::update($conn, $id, $nombre, $descripcion);
			header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
			exit();
		}
		include __DIR__ . '/../views/categorias/edit.php';
	}

	public function delete($id) {
		global $conn;
		Category::delete($conn, $id);
	header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
		exit();
	}
}

// Manejo de acciones por parámetro GET
if (isset($_GET['accion'])) {
    $controller = new CategoryController();
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
    $controller = new CategoryController();
    $controller->index();
}
?>
