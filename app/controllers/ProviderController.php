<?php
// Headers anti-caché para evitar problemas de navegación
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

require_once __DIR__ . '/../models/Provider.php';
require_once __DIR__ . '/../../config/db.php';

class ProviderController {
    private $baseUrl = '/RMIE/app/controllers/ProviderController.php';
    
    public function index() {
        try {
            global $conn;
            
            require_once __DIR__ . '/../utils/FilterHelper.php';
              // Definir reglas de filtro
            $filterRules = [
                'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'correo' => ['type' => 'email'],
                'telefono' => ['type' => 'text', 'options' => ['max_length' => 20]],
                'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['activo', 'inactivo', 'pendiente']]],
                'producto' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'fecha_desde' => ['type' => 'date'],
                'fecha_hasta' => ['type' => 'date'],
                'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
            ];
            
            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);
            
            // Mapear filtros para el modelo
            $filtrosModelo = [
                'nombre' => $filtros['nombre'] ?? '',
                'correo' => $filtros['correo'] ?? '',
                'telefono' => $filtros['telefono'] ?? '',
                'estado' => $filtros['estado'] ?? '',
                'producto' => $filtros['producto'] ?? '',
                'fecha_desde' => $filtros['fecha_desde'] ?? '',
                'fecha_hasta' => $filtros['fecha_hasta'] ?? '',
                'buscar' => $filtros['buscar'] ?? ''
            ];
            
            // Validar rango de fechas
            if (!empty($filtrosModelo['fecha_desde']) && !empty($filtrosModelo['fecha_hasta'])) {
                $dateRange = FilterHelper::validateDateRange($filtrosModelo['fecha_desde'], $filtrosModelo['fecha_hasta']);
                $filtrosModelo = array_merge($filtrosModelo, $dateRange);
            }
            
            // Obtener proveedores con filtros
            $proveedores = Provider::getAll($conn, $filtrosModelo);

            // Cargar productos asociados para cada proveedor usando tabla intermedia
            require_once __DIR__ . '/../models/Product.php';
            $productosPorProveedor = [];
            if (is_array($proveedores)) {
                foreach ($proveedores as $prov) {
                    $productos = Provider::getProductosByProveedor($conn, $prov->id_proveedores);
                    $productosPorProveedor[$prov->id_proveedores] = $productos;
                }
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
                $ubicacion = trim($_POST['ubicacion'] ?? '');
                
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
                // Crear proveedor y obtener su id
                $newProveedorId = Provider::create($conn, $nombre_distribuidor, $correo, $cel_proveedor, $estado, $ubicacion);

                if ($newProveedorId !== false && is_numeric($newProveedorId)) {
                    // Si se enviaron productos seleccionados, asignarlos al nuevo proveedor
                    if (!empty($_POST['productos']) && is_array($_POST['productos'])) {
                        require_once __DIR__ . '/../models/Product.php';
                        foreach ($_POST['productos'] as $prodId) {
                            $prodId = intval($prodId);
                            if ($prodId > 0) {
                                Product::assignToProvider($conn, $prodId, intval($newProveedorId));
                            }
                        }
                    }

                    header('Location: ' . $this->baseUrl . '?accion=index&success=created');
                } else {
                    throw new Exception("Error al crear el proveedor");
                }
                exit();
            }
            // Para mostrar el formulario necesitamos la lista de productos disponibles
            global $conn;
            require_once __DIR__ . '/../models/Product.php';
            $productos = Product::getAll($conn);
            include __DIR__ . '/../views/proveedores/create.php';
        } catch (Exception $e) {
            error_log("Error en ProviderController::create: " . $e->getMessage());
            $error = $e->getMessage();
            // Intentar pasar productos si es posible
            if (!isset($productos)) {
                global $conn;
                require_once __DIR__ . '/../models/Product.php';
                $productos = Product::getAll($conn);
            }
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

            // Cargar productos disponibles y productos del proveedor usando tabla intermedia
            require_once __DIR__ . '/../models/Product.php';
            $productosDisponibles = Product::getAll($conn);
            $productosDelProveedor = Provider::getProductosByProveedor($conn, $id);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validación de datos
                $nombre_distribuidor = trim($_POST['nombre_distribuidor'] ?? '');
                $correo = trim($_POST['correo'] ?? '');
                $cel_proveedor = trim($_POST['cel_proveedor'] ?? '');
                $estado = trim($_POST['estado'] ?? '');
                $ubicacion = trim($_POST['ubicacion'] ?? '');
                
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
                
                $resultado = Provider::update($conn, $id, $nombre_distribuidor, $correo, $cel_proveedor, $estado, $ubicacion);
                
                // Actualizar productos del proveedor si se enviaron
                if (isset($_POST['productos']) && is_array($_POST['productos'])) {
                    // Usar nuevo método para actualizar productos
                    Provider::updateProductos($conn, $id, $_POST['productos']);
                } else {
                    // Si no se enviaron productos, remover todas las asignaciones
                    Provider::removeAllProductos($conn, $id);
                }
                
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
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/ProviderController.php?accion=index";</script>';
            exit();
        }
        
        try {
            if ($id === null) {
                $id = $_GET['id'] ?? null;
            }
            
            if (!$id) {
                throw new Exception("ID de proveedor no especificado");
            }
            
            global $conn;
            $resultado = Provider::delete($conn, $id);
            
            // Manejar diferentes tipos de respuesta
            if (is_array($resultado)) {
                if ($resultado['success']) {
                    echo '<script>alert("Proveedor eliminado exitosamente."); window.location.href = "/RMIE/app/controllers/ProviderController.php?accion=index";</script>';
                } else {
                    // Error: dependencias o SQL
                    $mensaje = $resultado['message'] ?? 'Error desconocido';
                    
                    if ($resultado['error'] === 'dependencies') {
                        // Mostrar mensaje específico sobre productos - solo alerta, sin redirección a productos
                        echo '<script>alert("' . addslashes($mensaje) . '"); window.location.href = "/RMIE/app/controllers/ProviderController.php?accion=index";</script>';
                    } else {
                        echo '<script>alert("' . addslashes($mensaje) . '"); window.location.href = "/RMIE/app/controllers/ProviderController.php?accion=index";</script>';
                    }
                }
            } else {
                // Respuesta booleana antigua (por compatibilidad)
                if ($resultado) {
                    echo '<script>alert("Proveedor eliminado exitosamente."); window.location.href = "/RMIE/app/controllers/ProviderController.php?accion=index";</script>';
                } else {
                    throw new Exception("Error al eliminar el proveedor");
                }
            }
        } catch (Exception $e) {
            error_log("Error en ProviderController::delete: " . $e->getMessage());
            echo '<script>alert("Error: ' . addslashes($e->getMessage()) . '"); window.location.href = "/RMIE/app/controllers/ProviderController.php?accion=index";</script>';
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
 
