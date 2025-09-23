<?php
require_once __DIR__ . '/../models/Provider.php';
require_once __DIR__ . '/../../config/db.php';

class ProviderController {
    private $baseUrl = '/RMIE/app/controllers/ProviderController.php';
    
    public function index() {
        try {
            global $conn;
            $proveedores = Provider::getAll($conn);
            
            // Filtros
            $filtroNombre = $_GET['filtro_nombre'] ?? '';
            $filtroEstado = $_GET['filtro_estado'] ?? '';
            $filtroEmail = $_GET['filtro_email'] ?? '';
            
            if (!empty($filtroNombre) || !empty($filtroEstado) || !empty($filtroEmail)) {
                $proveedoresFiltrados = [];
                foreach ($proveedores as $proveedor) {
                    $cumpleFiltro = true;
                    
                    if (!empty($filtroNombre) && stripos($proveedor->nombre_distribuidor, $filtroNombre) === false) {
                        $cumpleFiltro = false;
                    }
                    
                    if (!empty($filtroEstado) && $proveedor->estado !== $filtroEstado) {
                        $cumpleFiltro = false;
                    }
                    
                    if (!empty($filtroEmail) && stripos($proveedor->correo, $filtroEmail) === false) {
                        $cumpleFiltro = false;
                    }
                    
                    if ($cumpleFiltro) {
                        $proveedoresFiltrados[] = $proveedor;
                    }
                }
                $proveedores = $proveedoresFiltrados;
            }
            
            include __DIR__ . '/../views/proveedores/index.php';
        } catch (Exception $e) {
            error_log("Error en ProviderController::index: " . $e->getMessage());
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validación de datos
                $nombre_distribuidor = trim($_POST['nombre_distribuidor'] ?? '');
                $correo = trim($_POST['correo'] ?? '');
                $cel_proveedor = trim($_POST['cel_proveedor'] ?? '');
                $estado = trim($_POST['estado'] ?? '');
                
                if (empty($nombre_distribuidor)) {
                    throw new Exception("El nombre del distribuidor es requerido");
                }
                
                if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Se requiere un correo electrónico válido");
                }
                
                if (empty($cel_proveedor)) {
                    throw new Exception("El número de celular es requerido");
                }
                
                if (empty($estado)) {
                    throw new Exception("El estado es requerido");
                }
                
                global $conn;
                $resultado = Provider::create($conn, $nombre_distribuidor, $correo, $cel_proveedor, $estado);
                
                if ($resultado) {
                    header('Location: ' . $this->baseUrl . '?accion=index&success=created');
                } else {
                    throw new Exception("Error al crear el proveedor");
                }
                exit();
            }
            include __DIR__ . '/../views/proveedores/create.php';
        } catch (Exception $e) {
            error_log("Error en ProviderController::create: " . $e->getMessage());
            $error = $e->getMessage();
            include __DIR__ . '/../views/proveedores/create.php';
        }
    }

    public function edit($id = null) {
        try {
            if ($id === null) {
                $id = $_GET['id'] ?? null;
            }
            
            if (!$id) {
                throw new Exception("ID de proveedor no especificado");
            }
            
            global $conn;
            $proveedor = Provider::getById($conn, $id);
            
            if (!$proveedor) {
                throw new Exception("Proveedor no encontrado");
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validación de datos
                $nombre_distribuidor = trim($_POST['nombre_distribuidor'] ?? '');
                $correo = trim($_POST['correo'] ?? '');
                $cel_proveedor = trim($_POST['cel_proveedor'] ?? '');
                $estado = trim($_POST['estado'] ?? '');
                
                if (empty($nombre_distribuidor)) {
                    throw new Exception("El nombre del distribuidor es requerido");
                }
                
                if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Se requiere un correo electrónico válido");
                }
                
                if (empty($cel_proveedor)) {
                    throw new Exception("El número de celular es requerido");
                }
                
                if (empty($estado)) {
                    throw new Exception("El estado es requerido");
                }
                
                $resultado = Provider::update($conn, $id, $nombre_distribuidor, $correo, $cel_proveedor, $estado);
                
                if ($resultado) {
                    header('Location: ' . $this->baseUrl . '?accion=index&success=updated');
                } else {
                    throw new Exception("Error al actualizar el proveedor");
                }
                exit();
            }
            include __DIR__ . '/../views/proveedores/edit.php';
        } catch (Exception $e) {
            error_log("Error en ProviderController::edit: " . $e->getMessage());
            $error = $e->getMessage();
            if (isset($proveedor)) {
                include __DIR__ . '/../views/proveedores/edit.php';
            } else {
                header('Location: ' . $this->baseUrl . '?accion=index&error=' . urlencode($e->getMessage()));
                exit();
            }
        }
    }

    public function delete($id = null) {
        try {
            if ($id === null) {
                $id = $_GET['id'] ?? null;
            }
            
            if (!$id) {
                throw new Exception("ID de proveedor no especificado");
            }
            
            global $conn;
            $resultado = Provider::delete($conn, $id);
            
            if ($resultado) {
                header('Location: ' . $this->baseUrl . '?accion=index&success=deleted');
            } else {
                throw new Exception("Error al eliminar el proveedor");
            }
        } catch (Exception $e) {
            error_log("Error en ProviderController::delete: " . $e->getMessage());
            header('Location: ' . $this->baseUrl . '?accion=index&error=' . urlencode($e->getMessage()));
        }
        exit();
    }
}

// Sistema de enrutamiento
if (isset($_GET['accion'])) {
    $controller = new ProviderController();
    $accion = $_GET['accion'];
    
    switch ($accion) {
        case 'index':
            $controller->index();
            break;
        case 'create':
            $controller->create();
            break;
        case 'edit':
            $id = $_GET['id'] ?? null;
            $controller->edit($id);
            break;
        case 'delete':
            $id = $_GET['id'] ?? null;
            $controller->delete($id);
            break;
        default:
            $controller->index();
            break;
    }
} else {
    $controller = new ProviderController();
    $controller->index();
}
?>
?>
