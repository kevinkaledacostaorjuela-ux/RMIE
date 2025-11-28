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
            
            // Definir reglas de filtro (coincidir con los nombres del formulario de la vista)
            $filterRules = [
                'filtro_producto' => ['type' => 'text'],
                'filtro_cliente' => ['type' => 'text'],
                'filtro_estado' => ['type' => 'select', 'options' => ['allowed_values' => ['pendiente', 'completada', 'cancelada', 'procesando']]],
                'filtro_fecha' => ['type' => 'date'],
                'monto_min' => ['type' => 'float', 'options' => ['min' => 0]],
                'monto_max' => ['type' => 'float', 'options' => ['min' => 0]]
            ];

            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);

            // Mapear filtros para el modelo (coincidir con los nombres usados en Sale::getFiltered)
            $filtrosModelo = [
                'producto' => $filtros['filtro_producto'] ?? '',
                'cliente' => $filtros['filtro_cliente'] ?? '',
                'estado' => $filtros['filtro_estado'] ?? '',
                'fecha_desde' => $filtros['filtro_fecha'] ?? '',
                'precio_min' => $filtros['monto_min'] ?? '',
                'precio_max' => $filtros['monto_max'] ?? ''
            ];
            
            // Validar rangos de monto
            if (!empty($filtrosModelo['precio_min']) && !empty($filtrosModelo['precio_max']) && $filtrosModelo['precio_min'] > $filtrosModelo['precio_max']) {
                $temp = $filtrosModelo['precio_min'];
                $filtrosModelo['precio_min'] = $filtrosModelo['precio_max'];
                $filtrosModelo['precio_max'] = $temp;
            }
            
            // Obtener ventas con filtros
            $ventas = Sale::getFiltered($conn, $filtrosModelo);
            
            // Cargar productos para cada venta
            foreach ($ventas as $venta) {
                $venta->productos_asignados = Sale::getProductos($conn, $venta->id_ventas);
            }
            
            // Obtener datos para selectores
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            // Filtrar usuarios: excluir admins
            $usuarios = array_values(array_filter($usuarios, function($u) {
                return $u->rol !== 'admin';
            }));
            
            include __DIR__ . '/../views/ventas/index.php';
        } catch (Exception $e) {
            error_log("Error en SaleController::index: " . $e->getMessage());
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
    
    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validar datos básicos
                $id_clientes = trim($_POST['id_clientes'] ?? '');
                $fecha_venta = trim($_POST['fecha_venta'] ?? '');
                $estado = trim($_POST['estado'] ?? 'pendiente');
                $num_doc = trim($_POST['num_doc'] ?? '');
                $productos_ids = $_POST['id_productos'] ?? [];
                
                if (empty($id_clientes)) {
                    throw new Exception("Debe seleccionar un cliente");
                }
                
                if (empty($fecha_venta)) {
                    $fecha_venta = date('Y-m-d');
                }
                
                if (empty($num_doc)) {
                    throw new Exception("Debe seleccionar un usuario responsable");
                }
                
                if (empty($productos_ids) || !is_array($productos_ids)) {
                    throw new Exception("Debe seleccionar al menos un producto");
                }
                
                global $conn;
                
                // Iniciar transacción
                $conn->begin_transaction();
                
                try {
                    // Calcular total de la venta y preparar datos de productos
                    $total_venta = 0;
                    $productos_data = [];
                    
                    foreach ($productos_ids as $id_producto) {
                        // Obtener datos del producto
                        $producto = Product::getById($conn, $id_producto);
                        if ($producto) {
                            $cantidad = 1; // Por defecto 1, puedes ajustar esto
                            $precio = floatval($producto->precio_unitario ?? 0);
                            $subtotal = $cantidad * $precio;
                            $total_venta += $subtotal;
                            
                            $productos_data[] = [
                                'id_productos' => $id_producto,
                                'cantidad' => $cantidad,
                                'precio_unitario' => $precio,
                                'subtotal' => $subtotal
                            ];
                        }
                    }
                    
                    // Crear UNA sola venta (mantener compatibilidad con estructura antigua)
                    $primer_producto = !empty($productos_ids) ? $productos_ids[0] : null;
                    $cantidad_total = count($productos_ids);
                    $precio_promedio = $total_venta / max($cantidad_total, 1);
                    
                    $resultado = Sale::create($conn, $primer_producto, $id_clientes, $fecha_venta, $cantidad_total, $precio_promedio, $total_venta, $estado, $num_doc);
                    
                    if (!$resultado) {
                        throw new Exception("Error al crear la venta");
                    }
                    
                    // Obtener el ID de la venta recién creada
                    $id_venta = $conn->insert_id;
                    
                    // Insertar productos en ventas_productos
                    Sale::updateProductos($conn, $id_venta, $productos_data);
                    
                    // Confirmar transacción
                    $conn->commit();
                    
                    $_SESSION['success'] = "Venta creada exitosamente con " . count($productos_ids) . " producto(s)";
                    header('Location: ' . $this->baseUrl . '?accion=index');
                    exit();
                    
                } catch (Exception $e) {
                    // Revertir transacción en caso de error
                    $conn->rollback();
                    throw $e;
                }
            }
            
            // Cargar datos necesarios para la vista
            global $conn;
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            // Cargar categorías y subcategorías para los filtros
            require_once __DIR__ . '/../models/Category.php';
            require_once __DIR__ . '/../models/Subcategory.php';
            $categorias = Category::getAll($conn);
            $subcategorias = Subcategory::getAll($conn);
            
            // Filtrar usuarios: excluir admins
            $usuarios = array_values(array_filter($usuarios, function($u) {
                return $u->rol !== 'admin';
            }));
            
            include __DIR__ . '/../views/ventas/create.php';
        } catch (Exception $e) {
            error_log("Error en SaleController::create: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            
            // Cargar datos para la vista en caso de error
            global $conn;
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            // Cargar categorías y subcategorías para los filtros
            require_once __DIR__ . '/../models/Category.php';
            require_once __DIR__ . '/../models/Subcategory.php';
            $categorias = Category::getAll($conn);
            $subcategorias = Subcategory::getAll($conn);
            
            // Filtrar usuarios: excluir admins
            $usuarios = array_values(array_filter($usuarios, function($u) {
                return $u->rol !== 'admin';
            }));
            
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
            
            // Cargar productos asignados
            $venta->productos_asignados = Sale::getProductos($conn, $id);
            $productos_asignados_ids = array_column(array_map(function($p) {
                return (array)$p;
            }, $venta->productos_asignados), 'id_productos');
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validación de datos
                $id_clientes = trim($_POST['id_clientes'] ?? '');
                $fecha_venta = trim($_POST['fecha_venta'] ?? '');
                $estado = trim($_POST['estado'] ?? '');
                $num_doc = trim($_POST['num_doc'] ?? '');
                $productos_ids = $_POST['id_productos'] ?? [];
                
                if (empty($id_clientes)) {
                    throw new Exception("Debe seleccionar un cliente");
                }
                
                if (empty($fecha_venta)) {
                    $fecha_venta = $venta->fecha_venta ?? date('Y-m-d');
                }
                
                if (empty($num_doc)) {
                    throw new Exception("Debe seleccionar un usuario responsable");
                }
                
                if (empty($productos_ids) || !is_array($productos_ids)) {
                    throw new Exception("Debe seleccionar al menos un producto");
                }
                
                // Iniciar transacción
                $conn->begin_transaction();
                
                try {
                    // Calcular total de la venta
                    $total_venta = 0;
                    $productos_data = [];
                    
                    foreach ($productos_ids as $id_producto) {
                        // Obtener datos del producto
                        $producto = Product::getById($conn, $id_producto);
                        if ($producto) {
                            $cantidad = 1; // Por defecto 1, puedes ajustar esto
                            $precio = floatval($producto->precio_unitario ?? 0);
                            $subtotal = $cantidad * $precio;
                            $total_venta += $subtotal;
                            
                            $productos_data[] = [
                                'id_productos' => $id_producto,
                                'cantidad' => $cantidad,
                                'precio_unitario' => $precio,
                                'subtotal' => $subtotal
                            ];
                        }
                    }
                    
                    // Actualizar venta principal (mantener compatibilidad)
                    $primer_producto = !empty($productos_ids) ? $productos_ids[0] : null;
                    $cantidad_total = count($productos_ids);
                    $precio_promedio = $total_venta / max($cantidad_total, 1);
                    
                    $resultado = Sale::update($conn, $id, $primer_producto, $id_clientes, $fecha_venta, $cantidad_total, $precio_promedio, $total_venta, $estado, $num_doc);
                    
                    if (!$resultado) {
                        throw new Exception("Error al actualizar la venta");
                    }
                    
                    // Actualizar productos en ventas_productos
                    Sale::updateProductos($conn, $id, $productos_data);
                    
                    $conn->commit();
                    
                    header('Location: ' . $this->baseUrl . '?accion=index&success=updated');
                    exit();
                    
                } catch (Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
            
            // Cargar datos necesarios para la vista
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
            $usuarios = User::getAll($conn);
            
            // Cargar categorías y subcategorías para los filtros
            require_once __DIR__ . '/../models/Category.php';
            require_once __DIR__ . '/../models/Subcategory.php';
            $categorias = Category::getAll($conn);
            $subcategorias = Subcategory::getAll($conn);
            
            // Filtrar usuarios: excluir admins
            $usuarios = array_values(array_filter($usuarios, function($u) {
                return $u->rol !== 'admin';
            }));
            
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
                
                // Filtrar usuarios: excluir admins
                $usuarios = array_values(array_filter($usuarios, function($u) {
                    return $u->rol !== 'admin';
                }));
                
                include __DIR__ . '/../views/ventas/edit.php';
            } else {
                header('Location: ' . $this->baseUrl . '?accion=index&error=' . urlencode($e->getMessage()));
                exit();
            }
        }
    }

    public function delete($id = null) {
        // Headers anti-cache
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");
        
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
                echo '<script>alert("Venta eliminada exitosamente."); window.location.href = "/RMIE/app/controllers/SaleController.php?accion=index";</script>';
            } else {
                throw new Exception("Error al eliminar la venta");
            }
        } catch (Exception $e) {
            error_log("Error en SaleController::delete: " . $e->getMessage());
            echo '<script>alert("Error: ' . addslashes($e->getMessage()) . '"); window.location.href = "/RMIE/app/controllers/SaleController.php?accion=index";</script>';
        }
        exit();
    }

    public function cleanProcessed() {
        // Verificar permisos de admin
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: ' . $this->baseUrl . '?accion=index');
            exit;
        }

        global $conn;
        try {
            // Contar ventas procesadas/entregadas
            $countSql = "SELECT COUNT(*) as total FROM ventas WHERE estado IN ('procesada', 'entregada', 'completada')";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalProcesadas = $countResult['total'];
            
            if ($totalProcesadas == 0) {
                $_SESSION['error'] = 'No se encontraron ventas procesadas para eliminar';
            } else {
                // Eliminar ventas procesadas/entregadas
                $sql = "DELETE FROM ventas WHERE estado IN ('procesada', 'entregada', 'completada')";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $_SESSION['success'] = "Se eliminaron {$deletedRows} ventas procesadas correctamente";
                } else {
                    $_SESSION['error'] = 'Error al eliminar las ventas procesadas';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar ventas procesadas: " . $e->getMessage());
            $_SESSION['error'] = 'Error al limpiar las ventas procesadas: ' . $e->getMessage();
        }
        
        header('Location: ' . $this->baseUrl . '?accion=index');
        exit;
    }

    public function cleanCancelled() {
        // Verificar permisos de admin
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: ' . $this->baseUrl . '?accion=index');
            exit;
        }

        global $conn;
        try {
            // Contar ventas canceladas
            $countSql = "SELECT COUNT(*) as total FROM ventas WHERE estado = 'cancelada'";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalCanceladas = $countResult['total'];
            
            if ($totalCanceladas == 0) {
                $_SESSION['error'] = 'No se encontraron ventas canceladas para eliminar';
            } else {
                // Eliminar ventas canceladas
                $sql = "DELETE FROM ventas WHERE estado = 'cancelada'";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $_SESSION['success'] = "Se eliminaron {$deletedRows} ventas canceladas correctamente";
                } else {
                    $_SESSION['error'] = 'Error al eliminar las ventas canceladas';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar ventas canceladas: " . $e->getMessage());
            $_SESSION['error'] = 'Error al limpiar las ventas canceladas: ' . $e->getMessage();
        }
        
        header('Location: ' . $this->baseUrl . '?accion=index');
        exit;
    }

    public function cleanAll() {
        // Verificar permisos de admin
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: ' . $this->baseUrl . '?accion=index');
            exit;
        }

        global $conn;
        try {
            // Contar todas las ventas
            $countSql = "SELECT COUNT(*) as total FROM ventas";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalVentas = $countResult['total'];
            
            if ($totalVentas == 0) {
                $_SESSION['error'] = 'No hay ventas para eliminar';
            } else {
                // Eliminar TODAS las ventas
                $sql = "DELETE FROM ventas";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $_SESSION['success'] = "Se eliminaron TODAS las {$deletedRows} ventas correctamente";
                } else {
                    $_SESSION['error'] = 'Error al eliminar todas las ventas';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar todas las ventas: " . $e->getMessage());
            $_SESSION['error'] = 'Error al limpiar todas las ventas: ' . $e->getMessage();
        }
        
        header('Location: ' . $this->baseUrl . '?accion=index');
        exit;
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
        case 'clean_processed':
            $controller->cleanProcessed();
            break;
        case 'clean_cancelled':
            $controller->cleanCancelled();
            break;
        case 'clean_all':
            $controller->cleanAll();
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
