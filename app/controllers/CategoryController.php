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
		
		// Verificar si es eliminación forzada
		$force = isset($_GET['force']) && $_GET['force'] == '1';
		
		$result = Category::delete($conn, $id, $force);
		
		if (isset($result['error'])) {
			switch ($result['error']) {
				case 'dependencies':
					$deps = $result['data'];
					$message = "No se puede eliminar la categoría porque tiene dependencias:\\n\\n";
					
					if (isset($deps['productos'])) {
						$message .= "• {$deps['productos']} producto(s) asociado(s)\\n";
					}
					if (isset($deps['subcategorias'])) {
						$message .= "• {$deps['subcategorias']} subcategoría(s) asociada(s)\\n";
					}
					if (isset($deps['ventas'])) {
						$message .= "• {$deps['ventas']} venta(s) relacionada(s) con productos de esta categoría\\n";
					}
					
					if (!isset($deps['ventas'])) {
						$message .= "\\n¿Desea eliminar la categoría y reasignar/eliminar las dependencias?";
						echo '<script>
							if (confirm("' . $message . '")) {
								window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=delete&id=' . $id . '&force=1";
							} else {
								window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";
							}
						</script>';
					} else {
						$message .= "\\nNo se puede realizar eliminación forzada porque hay ventas asociadas.";
						echo '<script>alert("' . $message . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
					}
					exit();
					break;
					
				case 'has_sales':
					echo '<script>alert("No se puede eliminar la categoría porque tiene ventas asociadas. Las ventas no pueden ser eliminadas automáticamente por seguridad."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
					exit();
					break;
					
				case 'delete_failed':
					echo '<script>alert("Error al eliminar la categoría."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
					exit();
					break;
					
				case 'exception':
					echo '<script>alert("Error de base de datos: ' . addslashes($result['message']) . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
					exit();
					break;
			}
		} else if (isset($result['success'])) {
			$successMessage = $force ? "Categoría eliminada exitosamente junto con sus dependencias." : "Categoría eliminada exitosamente.";
			echo '<script>alert("' . $successMessage . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
			exit();
		}
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
