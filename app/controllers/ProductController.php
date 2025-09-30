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
            $categorias = Category::getAll($conn);
            $subcategorias = SubcategorySimple::getAllSimple($conn);
            require_once __DIR__ . '/../models/Provider.php';
            $proveedores = Provider::getAll($conn);
            require_once __DIR__ . '/../models/User.php';
            $usuarios = User::getAll($conn);
            
            $filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
            $filtro_subcategoria = isset($_GET['subcategoria']) ? $_GET['subcategoria'] : '';
            $filtro_proveedor = isset($_GET['proveedor']) ? $_GET['proveedor'] : '';
            $filtro_usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
            
            $productos = Product::getFiltered($conn, $filtro_categoria, $filtro_subcategoria, $filtro_proveedor, $filtro_usuario);
            include __DIR__ . '/../views/productos/index.php';
        } catch (Exception $e) {
            echo '<pre>Error en index: ' . $e->getMessage() . '</pre>';
        }
    }

    public function create() {
        global $conn;
        $categorias = Category::getAll($conn);
        $subcategorias = SubcategorySimple::getAllSimple($conn);
        require_once __DIR__ . '/../models/Provider.php';
        $proveedores = Provider::getAll($conn);
        require_once __DIR__ . '/../models/User.php';
        $usuarios = User::getAll($conn);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<pre>POST: ' . print_r($_POST, true) . '</pre>';
            
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
            $id_subcategoria = $_POST['id_subcategoria'] ?? null;
            $id_categoria = $_POST['id_categoria'] ?? null;
            $id_proveedores = !empty($_POST['id_proveedor']) ? $_POST['id_proveedor'] : null;
            $num_doc = $_POST['id_usuario'] ?? null;
            
            // Validar campos requeridos
            // El proveedor ahora es opcional
            if (empty($num_doc)) {
                echo '<pre>Error: Debe seleccionar un usuario responsable.</pre>';
                return;
            }
            
            $result = Product::create($conn, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc);
            
            if (!$result) {
                echo '<pre>Error al guardar el producto.</pre>';
            } else {
                echo '<pre>Producto guardado correctamente.</pre>';
                header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
                exit();
            }
        }
        
        // Cargar datos necesarios para la vista
        global $conn;
        $categorias = Category::getAll($conn);
        $subcategorias = SubcategorySimple::getAllSimple($conn);
        $proveedores = Provider::getAll($conn);
        $usuarios = User::getAll($conn);
        
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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<pre>POST recibido: ' . print_r($_POST, true) . '</pre>';
            echo '<pre>ID del producto: ' . $id . '</pre>';
            
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
            $id_subcategoria = $_POST['id_subcategoria'] ?? null;
            $id_categoria = $_POST['id_categoria'] ?? null;
            $id_proveedores = !empty($_POST['id_proveedor']) ? $_POST['id_proveedor'] : null;
            $num_doc = $_POST['id_usuario'] ?? null;
            
            // Validación de datos requeridos
            if (empty($nombre) || empty($descripcion)) {
                echo '<pre>Error: Nombre y descripción son obligatorios.</pre>';
            } else {
                echo '<pre>Intentando actualizar producto...</pre>';
                $result = Product::update($conn, $id, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc);
                
                if (!$result) {
                    echo '<pre>Error al actualizar el producto.</pre>';
                } else {
                    echo '<pre>Producto actualizado correctamente.</pre>';
                    echo '<script>alert("Producto actualizado exitosamente."); window.location.href = "/RMIE/app/controllers/ProductController.php?accion=index";</script>';
                    exit();
                }
            }
        }
        include __DIR__ . '/../views/productos/edit.php';
    }

    public function delete($id) {
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
        default:
            $controller->index();
            break;
    }
} else {
    $controller = new ProductController();
    $controller->index();
}
?>
