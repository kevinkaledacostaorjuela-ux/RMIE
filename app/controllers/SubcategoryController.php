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
            echo '<script>alert("' . $successMessage . '"); window.location.href = "/RMIE/app/controllers/SubcategoryController.php?accion=index";</script>';
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
        default:
            $controller->index();
            break;
    }
} else {
    $controller = new SubcategoryController();
    $controller->index();
}
?>
