<?php
require_once __DIR__ . '/../models/Subcategory.php';
require_once __DIR__ . '/../models/SubcategorySimple.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/db.php';

class SubcategoryController {
    public function index() {
        global $conn;
        
        try {
            require_once __DIR__ . '/../utils/FilterHelper.php';
            
            // Definir reglas de filtro
            $filterRules = [
                'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'descripcion' => ['type' => 'text', 'options' => ['max_length' => 255]],
                'categoria' => ['type' => 'int', 'options' => ['min' => 1]],
                'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
            ];
            
            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);
            
            // Obtener subcategorías con filtros
            $subcategorias = SubcategorySimple::getAllSimple($conn, $filtros);
            
            // Obtener categorías para los filtros
            $categorias = Category::getAll($conn);
            
        } catch (Exception $e) {
            error_log("Error en SubcategoryController::index: " . $e->getMessage());
            $subcategorias = SubcategorySimple::getAllSimple($conn); // Fallback sin filtros
            $categorias = Category::getAll($conn);
        }
        
        include __DIR__ . '/../views/subcategorias/index.php';
    }

    public function create() {
        global $conn;
        $categorias = Category::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $id_categoria = $_POST['id_categoria'] ?? null;
            // Validaciones básicas y mensajes amigables
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            try {
                if (empty($nombre) || mb_strlen(trim($nombre)) < 3 || mb_strlen($nombre) > 45) {
                    throw new Exception('El nombre debe tener entre 3 y 45 caracteres');
                }
                if (empty($id_categoria) || !ctype_digit((string)$id_categoria)) {
                    throw new Exception('Seleccione una categoría válida');
                }

                $result = SubcategorySimple::createSimple($conn, $nombre, $descripcion, (int)$id_categoria);
                if (!$result) {
                    throw new Exception('No se pudo guardar la subcategoría');
                }

                $_SESSION['success'] = 'Subcategoría creada correctamente';
                header('Location: /RMIE/app/controllers/SubcategoryController.php?accion=index');
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
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
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            try {
                if (empty($nombre) || mb_strlen(trim($nombre)) < 3 || mb_strlen($nombre) > 45) {
                    throw new Exception('El nombre debe tener entre 3 y 45 caracteres');
                }
                if (empty($id_categoria) || !ctype_digit((string)$id_categoria)) {
                    throw new Exception('Seleccione una categoría válida');
                }

                $result = SubcategorySimple::updateSimple($conn, $id, $nombre, $descripcion, (int)$id_categoria);
                if (!$result) {
                    throw new Exception('No se pudo actualizar la subcategoría');
                }

                $_SESSION['success'] = 'Subcategoría actualizada correctamente';
                header('Location: /RMIE/app/controllers/SubcategoryController.php?accion=index');
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        include __DIR__ . '/../views/subcategorias/edit.php';
    }
    
    public function getByCategory() {
        global $conn;
        header('Content-Type: application/json');
        
        $categoria_id = $_GET['categoria_id'] ?? null;
        
        if (!$categoria_id) {
            echo json_encode([]);
            return;
        }
        
        try {
            $sql = "SELECT id_subcategoria, nombre FROM subcategorias WHERE id_categoria = ? ORDER BY nombre ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $categoria_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $subcategorias = [];
            while ($row = $result->fetch_assoc()) {
                $subcategorias[] = $row;
            }
            
            echo json_encode($subcategorias);
        } catch (Exception $e) {
            error_log("Error en getByCategory: " . $e->getMessage());
            echo json_encode([]);
        }
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
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";</script>';
            exit();
        }
        
        global $conn;
        
        // Verificar si es eliminación forzada
        $force = isset($_GET['force']) && $_GET['force'] == '1';
        
        $result = SubcategorySimple::delete($conn, $id, $force);
        
        if (isset($result['error'])) {
            switch ($result['error']) {
                case 'dependencies':
                    $deps = $result['data'];
                    $message = "No se puede eliminar la subcategoría porque tiene dependencias:\\n\\n";
                    
                    if (isset($deps['productos'])) {
                        $message .= "• {$deps['productos']} producto(s) asociado(s)\\n";
                    }
                    if (isset($deps['ventas'])) {
                        $message .= "• {$deps['ventas']} venta(s) relacionada(s) con productos de esta subcategoría\\n";
                    }
                    
                    if (!isset($deps['ventas'])) {
                        $message .= "\\n¿Desea eliminar la subcategoría y desasociar los productos?";
                        echo '<script>
                            if (confirm("' . $message . '")) {
                                window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=' . $id . '&force=1";
                            } else {
                                window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";
                            }
                        </script>';
                    } else {
                        $message .= "\\nNo se puede realizar eliminación forzada porque hay ventas asociadas.";
                        echo '<script>alert("' . $message . '"); window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";</script>';
                    }
                    exit();
                    break;
                    
                case 'has_sales':
                    echo '<script>alert("No se puede eliminar la subcategoría porque tiene ventas asociadas. Las ventas no pueden ser eliminadas automáticamente por seguridad."); window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";</script>';
                    exit();
                    break;
                    
                case 'delete_failed':
                    echo '<script>alert("Error al eliminar la subcategoría."); window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";</script>';
                    exit();
                    break;
                    
                case 'exception':
                    echo '<script>alert("Error de base de datos: ' . addslashes($result['message']) . '"); window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";</script>';
                    exit();
                    break;
            }
        } else if (isset($result['success'])) {
            $successMessage = $force ? "Subcategoría eliminada exitosamente y productos desasociados." : "Subcategoría eliminada exitosamente.";
            
            // Feedback mejorado para Selenium con delay
            echo '<script>
                // Mostrar mensaje de éxito
                alert("' . $successMessage . '");
                
                // Pequeño delay para que Selenium pueda capturar el resultado
                setTimeout(function() {
                    console.log("✅ Eliminación completada: ' . $successMessage . '");
                    window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";
                }, 1000);
            </script>';
            exit();
        }
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
        case 'getByCategory':
            $controller->getByCategory();
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
