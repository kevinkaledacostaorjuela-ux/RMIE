<?php
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/db.php';

class SaleController {
    private $baseUrl = '/RMIE/app/controllers/SaleController.php';
    
    public function index() {
        try {
            global $conn;
            
            require_once __DIR__ . '/../utils/FilterHelper.php';
            
            // Definir reglas de filtro
            $filterRules = [
                'filtro_producto' => ['type' => 'int', 'options' => ['min' => 1]],
                'filtro_cliente' => ['type' => 'int', 'options' => ['min' => 1]],
                'filtro_usuario' => ['type' => 'text'],
                'filtro_estado' => ['type' => 'select', 'options' => ['allowed_values' => ['pendiente', 'completada', 'cancelada', 'en_proceso']]],
                'precio_min' => ['type' => 'float', 'options' => ['min' => 0]],
                'precio_max' => ['type' => 'float', 'options' => ['min' => 0]],
                'cantidad_min' => ['type' => 'int', 'options' => ['min' => 1]],
                'cantidad_max' => ['type' => 'int', 'options' => ['min' => 1]],
                'fecha_desde' => ['type' => 'date'],
                'fecha_hasta' => ['type' => 'date'],
                'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
            ];
            
            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);
            
            // Mapear filtros para el modelo
            $filtrosModelo = [
                'producto' => $filtros['filtro_producto'] ?? '',
                'cliente' => $filtros['filtro_cliente'] ?? '',
                'usuario' => $filtros['filtro_usuario'] ?? '',
                'estado' => $filtros['filtro_estado'] ?? '',
                'precio_min' => $filtros['precio_min'] ?? '',
                'precio_max' => $filtros['precio_max'] ?? '',
                'cantidad_min' => $filtros['cantidad_min'] ?? '',
                'cantidad_max' => $filtros['cantidad_max'] ?? '',
                'fecha_desde' => $filtros['fecha_desde'] ?? '',
                'fecha_hasta' => $filtros['fecha_hasta'] ?? '',
                'buscar' => $filtros['buscar'] ?? ''
            ];
            
            // Validar rangos
            if (!empty($filtrosModelo['fecha_desde']) && !empty($filtrosModelo['fecha_hasta'])) {
                $dateRange = FilterHelper::validateDateRange($filtrosModelo['fecha_desde'], $filtrosModelo['fecha_hasta']);
                $filtrosModelo = array_merge($filtrosModelo, $dateRange);
            }
            
            if (!empty($filtrosModelo['precio_min']) && !empty($filtrosModelo['precio_max']) && $filtrosModelo['precio_min'] > $filtrosModelo['precio_max']) {
                // Intercambiar si están al revés
                $temp = $filtrosModelo['precio_min'];
                $filtrosModelo['precio_min'] = $filtrosModelo['precio_max'];
                $filtrosModelo['precio_max'] = $temp;
            }
            
            // Obtener ventas con filtros
            $ventas = Sale::getFiltered($conn, $filtrosModelo);
            
            // Obtener datos para selectores
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            include __DIR__ . '/../views/ventas/index.php';
        } catch (Exception $e) {
            error_log("Error en SaleController::index: " . $e->getMessage());
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
    
    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validación de datos
                $id_productos = trim($_POST['id_productos'] ?? '');
                $id_clientes = trim($_POST['id_clientes'] ?? '');
                $fecha_venta = trim($_POST['fecha_venta'] ?? '');
                $cantidad = trim($_POST['cantidad'] ?? '');
                $precio_unitario = trim($_POST['precio_unitario'] ?? '');
                $total = trim($_POST['total'] ?? '');
                $estado = trim($_POST['estado'] ?? 'pendiente');
                $num_doc = trim($_POST['num_doc'] ?? '');
                
                if (empty($id_productos)) {
                    throw new Exception("Debe seleccionar un producto");
                }
                
                if (empty($id_clientes)) {
                    throw new Exception("Debe seleccionar un cliente");
                }
                
                if (empty($fecha_venta)) {
                    // Si no se proporciona fecha, usar la fecha actual
                    $fecha_venta = date('Y-m-d');
                }
                
                if (empty($cantidad) || $cantidad <= 0) {
                    throw new Exception("La cantidad debe ser mayor a 0");
                }
                
                if (empty($precio_unitario) || $precio_unitario <= 0) {
                    throw new Exception("El precio unitario debe ser mayor a 0");
                }
                
                if (empty($num_doc)) {
                    throw new Exception("Debe seleccionar un usuario responsable");
                }
                
                global $conn;
                $resultado = Sale::create($conn, $id_productos, $id_clientes, $fecha_venta, $cantidad, $precio_unitario, $total, $estado, $num_doc);
                
                if ($resultado) {
                    header('Location: ' . $this->baseUrl . '?accion=index&success=created');
                } else {
                    throw new Exception("Error al crear la venta");
                }
                exit();
            }
            
            // Cargar datos necesarios para la vista
            global $conn;
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            include __DIR__ . '/../views/ventas/create.php';
        } catch (Exception $e) {
            error_log("Error en SaleController::create: " . $e->getMessage());
            $error = $e->getMessage();
            
            // Cargar datos para la vista en caso de error
            global $conn;
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            include __DIR__ . '/../views/ventas/create.php';
        }
    }

    public function edit($id = null) {
        try {
            if ($id === null) {
                $id = $_GET['id'] ?? null;
            }
            
            if (!$id) {
                throw new Exception("ID de venta no especificado");
            }
            
            global $conn;
            $venta = Sale::getById($conn, $id);
            
            if (!$venta) {
                throw new Exception("Venta no encontrada");
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validación de datos
                $id_productos = trim($_POST['id_productos'] ?? '');
                $id_clientes = trim($_POST['id_clientes'] ?? '');
                $fecha_venta = trim($_POST['fecha_venta'] ?? '');
                $cantidad = trim($_POST['cantidad'] ?? '');
                $precio_unitario = trim($_POST['precio_unitario'] ?? '');
                $total = trim($_POST['total'] ?? '');
                $estado = trim($_POST['estado'] ?? '');
                $num_doc = trim($_POST['num_doc'] ?? '');
                
                if (empty($id_productos)) {
                    throw new Exception("Debe seleccionar un producto");
                }
                  if (empty($id_clientes)) {
                    throw new Exception("Debe seleccionar un cliente");
                }
                
                if (empty($fecha_venta)) {
                    // Si no se proporciona fecha, mantener la fecha actual de la venta o usar la fecha actual
                    $fecha_venta = $venta->fecha_venta ?? date('Y-m-d');
                }
                
                if (empty($cantidad) || $cantidad <= 0) {
                    throw new Exception("La cantidad debe ser mayor a 0");
                }
                
                if (empty($precio_unitario) || $precio_unitario <= 0) {
                    throw new Exception("El precio unitario debe ser mayor a 0");
                }
                
                if (empty($num_doc)) {
                    throw new Exception("Debe seleccionar un usuario responsable");
                }
                
                $resultado = Sale::update($conn, $id, $id_productos, $id_clientes, $fecha_venta, $cantidad, $precio_unitario, $total, $estado, $num_doc);
                
                if ($resultado) {
                    header('Location: ' . $this->baseUrl . '?accion=index&success=updated');
                } else {
                    throw new Exception("Error al actualizar la venta");
                }
                exit();
            }
            
            // Cargar datos necesarios para la vista
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            include __DIR__ . '/../views/ventas/edit.php';
        } catch (Exception $e) {
            error_log("Error en SaleController::edit: " . $e->getMessage());
            $error = $e->getMessage();
            if (isset($venta)) {
                // Cargar datos para la vista en caso de error
                global $conn;
                $productos = Product::getAll($conn);
                $clientes = Client::getAll($conn);
                $usuarios = User::getAll($conn);
                include __DIR__ . '/../views/ventas/edit.php';
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
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/SaleController.php?accion=index";</script>';
            exit();
        }
        
        try {
            if ($id === null) {
                $id = $_GET['id'] ?? null;
            }
            
            if (!$id) {
                throw new Exception("ID de venta no especificado");
            }
            
            global $conn;
            $resultado = Sale::delete($conn, $id);
            
            if ($resultado) {
                header('Location: ' . $this->baseUrl . '?accion=index&success=deleted');
            } else {
                throw new Exception("Error al eliminar la venta");
            }
        } catch (Exception $e) {
            error_log("Error en SaleController::delete: " . $e->getMessage());
            header('Location: ' . $this->baseUrl . '?accion=index&error=' . urlencode($e->getMessage()));
        }
        exit();
    }
}

// Sistema de enrutamiento
if (isset($_GET['accion'])) {
    $controller = new SaleController();
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
    $controller = new SaleController();
    $controller->index();
}
?>
