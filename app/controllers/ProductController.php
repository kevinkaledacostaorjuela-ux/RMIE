<?php
// Mostrar errores de PHP para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Subcategory.php';
require_once __DIR__ . '/../models/SubcategorySimple.php';
require_once __DIR__ . '/../../config/db.php';

class ProductController {
    public function index() {
        global $conn;
        try {
            require_once __DIR__ . '/../utils/FilterHelper.php';
            
            // Obtener datos para selectores
            $categorias = Category::getAll($conn);
            $subcategorias = SubcategorySimple::getAllSimple($conn);
            require_once __DIR__ . '/../models/Provider.php';
            $proveedores = Provider::getAll($conn);
            require_once __DIR__ . '/../models/User.php';
            $usuarios = User::getAll($conn);
            
            // Filtrar usuarios: excluir admins, solo dejar auxiliar y coordinador
            $usuarios_filtrados = array_filter($usuarios, function($u) {
                return $u->rol !== 'admin';
            });
            $usuarios = array_values($usuarios_filtrados); // Reindexar array
            
            // Definir reglas de filtro
            $filterRules = [
                'categoria' => ['type' => 'int', 'options' => ['min' => 1]],
                'subcategoria' => ['type' => 'int', 'options' => ['min' => 1]],
                'proveedor' => ['type' => 'int', 'options' => ['min' => 1]],
                'usuario' => ['type' => 'text'],
                'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'marca' => ['type' => 'text', 'options' => ['max_length' => 50]],
                'precio_min' => ['type' => 'float', 'options' => ['min' => 0]],
                'precio_max' => ['type' => 'float', 'options' => ['min' => 0]],
                'stock_min' => ['type' => 'int', 'options' => ['min' => 0]],
                'stock_max' => ['type' => 'int', 'options' => ['min' => 0]],
                'fecha_entrada_desde' => ['type' => 'date'],
                'fecha_entrada_hasta' => ['type' => 'date'],
                'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
            ];
            
            // Procesar filtros del GET
            $filtros = FilterHelper::processFilters($_GET, $filterRules);
            
            // Validar rangos de fechas y precios
            if (!empty($filtros['fecha_entrada_desde']) && !empty($filtros['fecha_entrada_hasta'])) {
                $dateRange = FilterHelper::validateDateRange($filtros['fecha_entrada_desde'], $filtros['fecha_entrada_hasta']);
                $filtros = array_merge($filtros, $dateRange);
            }
            
            if (!empty($filtros['precio_min']) && !empty($filtros['precio_max']) && $filtros['precio_min'] > $filtros['precio_max']) {
                // Intercambiar si están al revés
                $temp = $filtros['precio_min'];
                $filtros['precio_min'] = $filtros['precio_max'];
                $filtros['precio_max'] = $temp;
            }
            
            // Obtener productos con filtros
            $productos = Product::getFiltered($conn, $filtros);
            
            include __DIR__ . '/../views/productos/index.php';
        } catch (Exception $e) {
            echo '<pre>Error en index: ' . $e->getMessage() . '</pre>';
        }
    }

    public function create() {
        global $conn;
        
        // Inicializar variables con valores por defecto para evitar errores
        $categorias = [];
        $subcategorias = [];
        $proveedores = [];
        $usuarios = [];
        $error_message = '';
        $success_message = '';
        
        try {
            $categorias = Category::getAll($conn) ?: [];
            $subcategorias = SubcategorySimple::getAllSimple($conn) ?: [];
            require_once __DIR__ . '/../models/Provider.php';
            $proveedores = Provider::getAll($conn) ?: [];
            require_once __DIR__ . '/../models/User.php';
            $usuarios = User::getAll($conn) ?: [];
        } catch (Exception $e) {
            error_log("Error cargando datos para create: " . $e->getMessage());
        }
        
        // Filtrar usuarios: excluir admins, solo dejar auxiliar y coordinador
        $usuarios_filtrados = array_filter($usuarios, function($u) {
            return $u->rol !== 'admin';
        });
        $usuarios = array_values($usuarios_filtrados); // Reindexar array
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nombre = $_POST['nombre'] ?? null;
                $descripcion = $_POST['descripcion'] ?? null;
                $fecha_entrada = $_POST['fecha_entrada'] ?? null;
                $fecha_fabricacion = $_POST['fecha_fabricacion'] ?? null;
                $fecha_caducidad = $_POST['fecha_caducidad'] ?? null;
                $stock = $_POST['stock'] ?? null;
                $precio_unitario = $_POST['precio_unitario'] ?? null;
                $precio_por_mayor = $_POST['precio_por_mayor'] ?? null;
                $valor_unitario = $_POST['valor_unitario'] ?? null;
                $marca = $_POST['marca'] ?? null;
                $id_subcategoria = $_POST['subcategoria_id'] ?? null;
                $id_categoria = $_POST['categoria_id'] ?? null;
                $id_proveedores = $_POST['proveedor_id'] ?? null;
                $num_doc = $_POST['usuario_id'] ?? null;
                
                // Validar campos requeridos
                if (empty($nombre)) {
                    throw new Exception("El nombre del producto es requerido");
                }
                
                if (empty($num_doc)) {
                    throw new Exception("Debe seleccionar un usuario responsable");
                }
                
                if (empty($id_categoria)) {
                    throw new Exception("Debe seleccionar una categoría");
                }
                
                if (empty($id_subcategoria)) {
                    throw new Exception("Debe seleccionar una subcategoría");
                }
                
                $result = Product::create($conn, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc);
                
                if ($result) {
                    $_SESSION['success'] = 'Producto creado exitosamente';
                    header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
                    exit();
                } else {
                    throw new Exception("Error al guardar el producto");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                // Volver a cargar la vista con el error
            }
        }
        
        // Asegurar que todas las variables existan antes de cargar la vista
        if (!isset($categorias)) $categorias = [];
        if (!isset($subcategorias)) $subcategorias = [];
        if (!isset($proveedores)) $proveedores = [];
        if (!isset($usuarios)) $usuarios = [];
        if (!isset($error_message)) $error_message = '';
        if (!isset($success_message)) $success_message = '';
        
        // Cargar datos necesarios para la vista
        include __DIR__ . '/../views/productos/create.php';
    }

    public function edit($id) {
        global $conn;
        $producto = Product::getById($conn, $id);
        $categorias = Category::getAll($conn);
        $subcategorias = SubcategorySimple::getAllSimple($conn);
        require_once __DIR__ . '/../models/Provider.php';
        $proveedores = Provider::getAll($conn);
        require_once __DIR__ . '/../models/User.php';
        $usuarios = User::getAll($conn);
        
        // Filtrar usuarios: excluir admins, solo dejar auxiliar y coordinador
        $usuarios_filtrados = array_filter($usuarios, function($u) {
            return $u->rol !== 'admin';
        });
        $usuarios = array_values($usuarios_filtrados); // Reindexar array
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $fecha_entrada = $_POST['fecha_entrada'] ?? null;
            $fecha_fabricacion = $_POST['fecha_fabricacion'] ?? null;
            $fecha_caducidad = $_POST['fecha_caducidad'] ?? null;
            $stock = $_POST['stock'] ?? null;
            $precio_unitario = $_POST['precio_unitario'] ?? null;
            $precio_por_mayor = $_POST['precio_por_mayor'] ?? null;
            $valor_unitario = $_POST['valor_unitario'] ?? null;
            $marca = $_POST['marca'] ?? null;
            $id_subcategoria = $_POST['subcategoria_id'] ?? $_POST['id_subcategoria'] ?? null;
            $id_categoria = $_POST['categoria_id'] ?? $_POST['id_categoria'] ?? null;
            $id_proveedores = $_POST['proveedor_id'] ?? null;
            $num_doc = $_POST['usuario_id'] ?? null;

            // Validación de datos requeridos
            if (empty($nombre) || empty($descripcion)) {
                $errorMessage = 'Nombre y descripción son obligatorios.';
            } else {
                $result = Product::update($conn, $id, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc);

                if (!$result) {
                    $errorMessage = 'Error al actualizar el producto.';
                } else {
                    // Iniciar sesión si no está activa
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    // Redirigir al index con mensaje de éxito
                    $_SESSION['success'] = 'Producto actualizado exitosamente.';
                    header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
                    exit();
                }
            }
        }
        include __DIR__ . '/../views/productos/edit.php';
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
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/ProductController.php?accion=index";</script>';
            exit();
        }
        
        global $conn;
        
        // Verificar si es eliminación forzada (aunque para productos no debería permitirse si hay ventas)
        $force = isset($_GET['force']) && $_GET['force'] == '1';
        
        $result = Product::deleteWithDependencies($conn, $id, $force);
        
        if (isset($result['error'])) {
            switch ($result['error']) {
                case 'dependencies':
                    $deps = $result['data'];
                    $message = "No se puede eliminar el producto porque tiene dependencias:\\n\\n";
                    
                    if (isset($deps['ventas'])) {
                        $message .= "• {$deps['ventas']} venta(s) asociada(s)\\n";
                        $message .= "\\nLos productos no pueden ser eliminados si tienen ventas asociadas por seguridad y trazabilidad.";
                    }
                    
                    echo '<script>alert("' . $message . '"); window.location.href = "/RMIE/app/controllers/ProductController.php?accion=index";</script>';
                    exit();
                    break;
                    
                case 'has_sales':
                    echo '<script>alert("No se puede eliminar el producto porque tiene ventas asociadas. Los productos con ventas no pueden ser eliminados por seguridad."); window.location.href = "/RMIE/app/controllers/ProductController.php?accion=index";</script>';
                    exit();
                    break;
                    
                case 'delete_failed':
                    echo '<script>alert("Error al eliminar el producto."); window.location.href = "/RMIE/app/controllers/ProductController.php?accion=index";</script>';
                    exit();
                    break;
                    
                case 'exception':
                    echo '<script>alert("Error de base de datos: ' . addslashes($result['message']) . '"); window.location.href = "/RMIE/app/controllers/ProductController.php?accion=index";</script>';
                    exit();
                    break;
            }
        } else if (isset($result['success'])) {
            echo '<script>alert("Producto eliminado exitosamente."); window.location.href = "/RMIE/app/controllers/ProductController.php?accion=index";</script>';
            exit();
        }
        
        // Fallback en caso de resultado inesperado
        header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
        exit();
    }

    public function cleanNoStock() {
        global $conn;
        
        // Verificar permisos de admin
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
            exit;
        }

        try {
            // Contar productos con stock = 0 que NO tienen ventas asociadas
            $countSql = "SELECT COUNT(*) as total FROM productos p 
                        LEFT JOIN ventas v ON p.id_productos = v.id_productos 
                        WHERE p.stock = 0 AND v.id_productos IS NULL";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalSinStock = $countResult['total'];
            
            // Contar productos con stock = 0 que SÍ tienen ventas (no se pueden eliminar)
            $countConVentasSql = "SELECT COUNT(*) as total FROM productos p 
                                 INNER JOIN ventas v ON p.id_productos = v.id_productos 
                                 WHERE p.stock = 0";
            $countConVentasStmt = $conn->prepare($countConVentasSql);
            $countConVentasStmt->execute();
            $countConVentasResult = $countConVentasStmt->get_result()->fetch_assoc();
            $totalConVentas = $countConVentasResult['total'];
            
            if ($totalSinStock == 0 && $totalConVentas == 0) {
                $_SESSION['error'] = 'No se encontraron productos con stock cero para eliminar';
            } else if ($totalSinStock == 0 && $totalConVentas > 0) {
                $_SESSION['error'] = "Los {$totalConVentas} productos con stock cero no se pueden eliminar porque tienen ventas asociadas";
            } else {
                // Eliminar solo productos con stock = 0 que NO tienen ventas
                $sql = "DELETE p FROM productos p 
                       LEFT JOIN ventas v ON p.id_productos = v.id_productos 
                       WHERE p.stock = 0 AND v.id_productos IS NULL";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $mensaje = "Se eliminaron {$deletedRows} productos con stock cero correctamente";
                    if ($totalConVentas > 0) {
                        $mensaje .= ". {$totalConVentas} productos con stock cero no se pudieron eliminar porque tienen ventas asociadas";
                    }
                    $_SESSION['success'] = $mensaje;
                } else {
                    $_SESSION['error'] = 'Error al eliminar los productos con stock cero';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar productos sin stock: " . $e->getMessage());
            $_SESSION['error'] = 'Error: Algunos productos no se pueden eliminar porque tienen ventas asociadas';
        }
        
        header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
        exit;
    }

    public function cleanInactive() {
        global $conn;
        
        // Verificar permisos de admin
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
            exit;
        }

        try {
            // Contar productos inactivos que NO tienen ventas asociadas
            $countSql = "SELECT COUNT(*) as total FROM productos p 
                        LEFT JOIN ventas v ON p.id_productos = v.id_productos 
                        WHERE p.estado = 'inactivo' AND v.id_productos IS NULL";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalInactivos = $countResult['total'];
            
            // Contar productos inactivos que SÍ tienen ventas
            $countConVentasSql = "SELECT COUNT(*) as total FROM productos p 
                                 INNER JOIN ventas v ON p.id_productos = v.id_productos 
                                 WHERE p.estado = 'inactivo'";
            $countConVentasStmt = $conn->prepare($countConVentasSql);
            $countConVentasStmt->execute();
            $countConVentasResult = $countConVentasStmt->get_result()->fetch_assoc();
            $totalConVentas = $countConVentasResult['total'];
            
            if ($totalInactivos == 0 && $totalConVentas == 0) {
                $_SESSION['error'] = 'No se encontraron productos inactivos para eliminar';
            } else if ($totalInactivos == 0 && $totalConVentas > 0) {
                $_SESSION['error'] = "Los {$totalConVentas} productos inactivos no se pueden eliminar porque tienen ventas asociadas";
            } else {
                // Eliminar solo productos inactivos que NO tienen ventas
                $sql = "DELETE p FROM productos p 
                       LEFT JOIN ventas v ON p.id_productos = v.id_productos 
                       WHERE p.estado = 'inactivo' AND v.id_productos IS NULL";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $mensaje = "Se eliminaron {$deletedRows} productos inactivos correctamente";
                    if ($totalConVentas > 0) {
                        $mensaje .= ". {$totalConVentas} productos inactivos no se pudieron eliminar porque tienen ventas asociadas";
                    }
                    $_SESSION['success'] = $mensaje;
                } else {
                    $_SESSION['error'] = 'Error al eliminar los productos inactivos';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar productos inactivos: " . $e->getMessage());
            $_SESSION['error'] = 'Error: Algunos productos no se pueden eliminar porque tienen ventas asociadas';
        }
        
        header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
        exit;
    }

    public function cleanAll() {
        global $conn;
        
        // Verificar permisos de admin
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
            exit;
        }

        try {
            // Contar productos que NO tienen ventas asociadas
            $countSql = "SELECT COUNT(*) as total FROM productos p 
                        LEFT JOIN ventas v ON p.id_productos = v.id_productos 
                        WHERE v.id_productos IS NULL";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalSinVentas = $countResult['total'];
            
            // Contar productos que SÍ tienen ventas
            $countConVentasSql = "SELECT COUNT(*) as total FROM productos p 
                                 INNER JOIN ventas v ON p.id_productos = v.id_productos";
            $countConVentasStmt = $conn->prepare($countConVentasSql);
            $countConVentasStmt->execute();
            $countConVentasResult = $countConVentasStmt->get_result()->fetch_assoc();
            $totalConVentas = $countConVentasResult['total'];
            
            if ($totalSinVentas == 0 && $totalConVentas == 0) {
                $_SESSION['error'] = 'No hay productos para eliminar';
            } else if ($totalSinVentas == 0 && $totalConVentas > 0) {
                $_SESSION['error'] = "No se pueden eliminar los {$totalConVentas} productos porque todos tienen ventas asociadas. Para eliminarlos, primero elimina las ventas correspondientes";
            } else {
                // Eliminar solo productos que NO tienen ventas
                $sql = "DELETE p FROM productos p 
                       LEFT JOIN ventas v ON p.id_productos = v.id_productos 
                       WHERE v.id_productos IS NULL";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $mensaje = "Se eliminaron {$deletedRows} productos correctamente";
                    if ($totalConVentas > 0) {
                        $mensaje .= ". {$totalConVentas} productos no se pudieron eliminar porque tienen ventas asociadas";
                    }
                    $_SESSION['success'] = $mensaje;
                } else {
                    $_SESSION['error'] = 'Error al eliminar los productos';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar todos los productos: " . $e->getMessage());
            $_SESSION['error'] = 'Error: No se pueden eliminar productos que tienen ventas asociadas. Elimina primero las ventas correspondientes';
        }
        
        header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
        exit;
    }
}

// Manejo de acciones por parámetro GET
if (isset($_GET['accion'])) {
    $controller = new ProductController();
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
        case 'clean_no_stock':
            $controller->cleanNoStock();
            break;
        case 'clean_inactive':
            $controller->cleanInactive();
            break;
        case 'clean_all':
            $controller->cleanAll();
            break;
        default:
            $controller->index();
            break;
    }
} else {
    $controller = new ProductController();
    $controller->index();
}
?>
