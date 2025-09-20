<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Subcategory.php';
require_once __DIR__ . '/../../config/db.php';

class ProductController {
    public function index() {
        global $conn;
        $categorias = Category::getAll($conn);
        $subcategorias = Subcategory::getAll($conn);
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
    }

    public function create() {
        global $conn;
        $categorias = Category::getAll($conn);
        $subcategorias = Subcategory::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $fecha_entrada = $_POST['fecha_entrada'];
            $fecha_fabricacion = $_POST['fecha_fabricacion'];
            $fecha_caducidad = $_POST['fecha_caducidad'];
            $stock = $_POST['stock'];
            $precio_unitario = $_POST['precio_unitario'];
            $precio_por_mayor = $_POST['precio_por_mayor'];
            $valor_unitario = $_POST['valor_unitario'];
            $marca = $_POST['marca'];
            $id_subcategoria = $_POST['id_subcategoria'];
            $id_categoria = $_POST['id_categoria'];
            Product::create($conn, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria);
            header('Location: index.php');
            exit();
        }
        include __DIR__ . '/../views/productos/create.php';
    }

    public function edit($id) {
        global $conn;
        $producto = Product::getById($conn, $id);
        $categorias = Category::getAll($conn);
        $subcategorias = Subcategory::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $fecha_entrada = $_POST['fecha_entrada'];
            $fecha_fabricacion = $_POST['fecha_fabricacion'];
            $fecha_caducidad = $_POST['fecha_caducidad'];
            $stock = $_POST['stock'];
            $precio_unitario = $_POST['precio_unitario'];
            $precio_por_mayor = $_POST['precio_por_mayor'];
            $valor_unitario = $_POST['valor_unitario'];
            $marca = $_POST['marca'];
            $id_subcategoria = $_POST['id_subcategoria'];
            $id_categoria = $_POST['id_categoria'];
            Product::update($conn, $id, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria);
            header('Location: index.php');
            exit();
        }
        include __DIR__ . '/../views/productos/edit.php';
    }

    public function delete($id) {
        global $conn;
        Product::delete($conn, $id);
        header('Location: index.php');
        exit();
    }
}
?>
