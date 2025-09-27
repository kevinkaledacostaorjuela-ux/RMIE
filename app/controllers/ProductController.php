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
            $id_proveedores = $_POST['id_proveedor'] ?? null;
            $num_doc = $_POST['id_usuario'] ?? null;
            
            // Validar campos requeridos
            if (empty($id_proveedores)) {
                echo '<pre>Error: Debe seleccionar un proveedor.</pre>';
                return;
            }
            
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
            $id_proveedores = $_POST['id_proveedor'] ?? null;
            $num_doc = $_POST['id_usuario'] ?? null;
            
            $result = Product::update($conn, $id, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc);
            
            if (!$result) {
                echo '<pre>Error al actualizar el producto.</pre>';
            } else {
                echo '<pre>Producto actualizado correctamente.</pre>';
                header('Location: /RMIE/app/controllers/ProductController.php?accion=index');
                exit();
            }
        }
        include __DIR__ . '/../views/productos/edit.php';
    }

    public function delete($id) {
        global $conn;
        $result = Product::delete($conn, $id);
        if (!$result) {
            echo '<pre>Error al eliminar el producto.</pre>';
        } else {
            echo '<pre>Producto eliminado correctamente.</pre>';
        }
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
