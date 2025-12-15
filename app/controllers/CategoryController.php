<?php
// Headers anti-caché para evitar problemas de navegación
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

// Mostrar errores de PHP para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/db.php';

class CategoryController {
	public function index() {
		global $conn;
		
		try {
			require_once __DIR__ . '/../utils/FilterHelper.php';
			
			// Definir reglas de filtro
			$filterRules = [
				'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
				'descripcion' => ['type' => 'text', 'options' => ['max_length' => 255]],
				'fecha_desde' => ['type' => 'date'],
				'fecha_hasta' => ['type' => 'date'],
				'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
			];
			
			// Procesar filtros del GET
			$filtros = FilterHelper::processFilters($_GET, $filterRules);
			
			// Validar rango de fechas
			if (!empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
				$dateRange = FilterHelper::validateDateRange($filtros['fecha_desde'], $filtros['fecha_hasta']);
				$filtros = array_merge($filtros, $dateRange);
			}
			
			// Obtener categorías con filtros
			$categorias = Category::getAll($conn, $filtros);
			
		} catch (Exception $e) {
			error_log("Error en CategoryController::index: " . $e->getMessage());
			$categorias = Category::getAll($conn); // Fallback sin filtros
		}
		
		include __DIR__ . '/../views/categorias/index.php';
	}

	public function create() {
		global $conn;
		$errorMsg = null;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
			$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
			$result = Category::create($conn, $nombre, $descripcion);
			if (!$result) {
				// Registrar el error en el log y preparar mensaje para la vista
				if (!empty($conn->error)) {
					error_log('[CategoryController] SQL error: ' . $conn->error);
					$errorMsg = 'Error al guardar la categoría: ' . $conn->error;
				} else {
					$errorMsg = 'Error al guardar la categoría.';
				}
			} else {
				// Redirigir a la lista (usar ruta absoluta)
				header('Location: /RMIE/app/controllers/CategoryController.php?accion=index');
				exit();
			}
		}
		// La vista podrá leer $errorMsg si existe
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
		// Verificar sesión activa
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		
		// Verificar si el usuario está logueado
		if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
			echo '<script>alert("Debe iniciar sesión para realizar esta acción."); window.location.href = "/RMIE/index.php";</script>';
			exit();
		}
		
		// Verificar si el rol es coordinador y restringir eliminación
		if ($_SESSION['rol'] === 'coordinador') {
			echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
			exit();
		}
		
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
					
				case 'has_sales':
					echo '<script>alert("No se puede eliminar la categoría porque tiene ventas asociadas. Las ventas no pueden ser eliminadas automáticamente por seguridad."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
					exit();
					
				case 'delete_failed':
					echo '<script>alert("Error al eliminar la categoría."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
					exit();
					
				case 'exception':
					echo '<script>alert("Error de base de datos: ' . addslashes($result['message']) . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
					exit();
			}
		} else if (isset($result['success'])) {
			$successMessage = $force ? "Categoría eliminada exitosamente junto con sus dependencias." : "Categoría eliminada exitosamente.";
			echo '<script>alert("' . $successMessage . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
			exit();
		}
	}

	public function cleanEmpty() {
		global $conn;
		
		// Verificar sesión activa
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		
		// Verificar si el usuario está logueado
		if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
			echo '<script>alert("Debe iniciar sesión para realizar esta acción."); window.location.href = "/RMIE/index.php";</script>';
			exit();
		}
		
		// Verificar si el rol es admin
		if ($_SESSION['rol'] !== 'admin') {
			echo '<script>alert("Solo administradores pueden realizar esta acción."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
			exit();
		}
		
		try {
			// Obtener categorías sin productos
			$query = "SELECT c.id_categoria FROM categorias c 
					  LEFT JOIN productos p ON c.id_categoria = p.id_categoria 
					  WHERE p.id_productos IS NULL 
					  GROUP BY c.id_categoria";
			
			$result = $conn->query($query);
			$emptyCategories = [];
			
			if ($result && $result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$emptyCategories[] = $row['id_categoria'];
				}
			}
			
			if (empty($emptyCategories)) {
				echo '<script>alert("No hay categorías vacías para eliminar."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
				exit();
			}
			
			// Inicia transacción para mantener consistencia
			$conn->begin_transaction();
			
			try {
				$ids = implode(',', $emptyCategories);
				
				// 1. Primero, eliminar todas las subcategorías asociadas a estas categorías
				$deleteSubcategories = "DELETE FROM subcategorias WHERE id_categoria IN ($ids)";
				if (!$conn->query($deleteSubcategories)) {
					throw new Exception("Error al eliminar subcategorías: " . $conn->error);
				}
				
				// 2. Luego, eliminar las categorías vacías
				$deleteCategories = "DELETE FROM categorias WHERE id_categoria IN ($ids)";
				if (!$conn->query($deleteCategories)) {
					throw new Exception("Error al eliminar categorías: " . $conn->error);
				}
				
				// Confirma la transacción
				$conn->commit();
				
				$count = count($emptyCategories);
				echo '<script>alert("Se eliminaron ' . $count . ' categoría(s) vacía(s) correctamente."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
				exit();
				
			} catch (Exception $e) {
				// Revierte los cambios si algo falla
				$conn->rollback();
				throw $e;
			}
			
		} catch (Exception $e) {
			echo '<script>alert("Error: ' . addslashes($e->getMessage()) . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
			exit();
		}
	}

	public function cleanAll() {
		global $conn;
		
		// Verificar sesión activa
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		
		// Verificar si el usuario está logueado
		if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
			echo '<script>alert("Debe iniciar sesión para realizar esta acción."); window.location.href = "/RMIE/index.php";</script>';
			exit();
		}
		
		// Verificar si el rol es admin
		if ($_SESSION['rol'] !== 'admin') {
			echo '<script>alert("Solo administradores pueden realizar esta acción."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
			exit();
		}
		
		try {
			// Confirmar eliminación de todas las categorías
			if (!isset($_GET['confirm']) || $_GET['confirm'] != '1') {
				echo '<script>
					if (confirm("¿ESTÁS SEGURO DE QUE DESEAS ELIMINAR TODAS LAS CATEGORÍAS? Esta acción no se puede deshacer.")) {
						window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=clean_all&confirm=1";
					} else {
						window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";
					}
				</script>';
				exit();
			}
			
			// Eliminar todas las categorías
			$deleteQuery = "DELETE FROM categorias";
			
			if ($conn->query($deleteQuery)) {
				echo '<script>alert("Se eliminaron todas las categorías correctamente."); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
				exit();
			} else {
				echo '<script>alert("Error al eliminar categorías: ' . addslashes($conn->error) . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
				exit();
			}
			
		} catch (Exception $e) {
			echo '<script>alert("Error: ' . addslashes($e->getMessage()) . '"); window.location.href = "/RMIE/app/controllers/CategoryController.php?accion=index";</script>';
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
        case 'clean_empty':
            $controller->cleanEmpty();
            break;
        case 'clean_all':
            $controller->cleanAll();
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
