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
            
            // Filtros
            $filtroProducto = $_GET['filtro_producto'] ?? '';
            $filtroCliente = $_GET['filtro_cliente'] ?? '';
            $filtroFecha = $_GET['filtro_fecha'] ?? '';
            $filtroEstado = $_GET['filtro_estado'] ?? '';
            
            // Obtener todas las ventas
            $ventas = Sale::getAll($conn);
            
            // Aplicar filtros si existen
            if (!empty($filtroProducto) || !empty($filtroCliente) || !empty($filtroFecha) || !empty($filtroEstado)) {
                $ventasFiltradas = [];
                foreach ($ventas as $venta) {
                    $cumpleFiltro = true;
                    
                    if (!empty($filtroProducto) && stripos($venta->producto_nombre ?? '', $filtroProducto) === false) {
                        $cumpleFiltro = false;
                    }
                    
                    if (!empty($filtroCliente) && stripos($venta->cliente_nombre ?? '', $filtroCliente) === false) {
                        $cumpleFiltro = false;
                    }
                    
                    if (!empty($filtroFecha) && strpos($venta->fecha_venta ?? '', $filtroFecha) === false) {
                        $cumpleFiltro = false;
                    }
                    
                    if (!empty($filtroEstado) && $venta->estado !== $filtroEstado) {
                        $cumpleFiltro = false;
                    }
                    
                    if ($cumpleFiltro) {
                        $ventasFiltradas[] = $venta;
                    }
                }
                $ventas = $ventasFiltradas;
            }
            
            // Obtener datos para los filtros
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            
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
                    throw new Exception("La fecha de venta es requerida");
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
                    throw new Exception("La fecha de venta es requerida");
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
