<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../../config/db.php';

class ProductsController {
    private $productoModel;

    public function __construct($pdo) {
        $this->productoModel = new Producto($pdo);
    }

    public function listar() {
        $categorias = (method_exists($this->productoModel, 'obtenerCategorias')) ? $this->productoModel->obtenerCategorias() : [];
        $filtro_categoria = $_GET['categoria'] ?? '';
        $filtro_subcategoria = $_GET['subcategoria'] ?? '';
        if ($filtro_categoria && method_exists($this->productoModel, 'obtenerSubcategoriasPorCategoria')) {
            $subcategorias = $this->productoModel->obtenerSubcategoriasPorCategoria($filtro_categoria);
        } else {
            $subcategorias = $this->productoModel->obtenerSubcategorias();
        }
        $productos = $this->productoModel->obtenerTodos();
        if ($filtro_categoria) {
            $productos = array_filter($productos, function($p) use ($filtro_categoria) {
                return $p['id_categoria'] == $filtro_categoria;
            });
        }
        if ($filtro_subcategoria) {
            $productos = array_filter($productos, function($p) use ($filtro_subcategoria) {
                return $p['id_subcategoria'] == $filtro_subcategoria;
            });
        }
        include __DIR__ . '/../views/productos_listar.php';
    }

    public function crear() {
        $subcategorias = $this->productoModel->obtenerSubcategorias();
        include __DIR__ . '/../views/productos_crear.php';
    }

    public function guardar() {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $fecha_entrada = $_POST['fecha_entrada'] ?? '';
        $fecha_fabricacion = $_POST['fecha_fabricacion'] ?? '';
        $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
        $id_subcategoria = $_POST['id_subcategoria'] ?? '';
        $this->productoModel->crear($nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria);
        header('Location: ?action=listar_productos');
        exit;
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        $producto = $this->productoModel->obtenerPorId($id);
        $subcategorias = $this->productoModel->obtenerSubcategorias();
        include __DIR__ . '/../views/productos_editar.php';
    }

    public function actualizar() {
        $id = $_GET['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $fecha_entrada = $_POST['fecha_entrada'] ?? '';
        $fecha_fabricacion = $_POST['fecha_fabricacion'] ?? '';
        $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
        $id_subcategoria = $_POST['id_subcategoria'] ?? '';
        $this->productoModel->actualizar($id, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria);
        header('Location: ?action=listar_productos');
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        $producto = $this->productoModel->obtenerPorId($id);
        include __DIR__ . '/../views/productos_eliminar.php';
    }

    public function eliminarConfirmar() {
        $id = $_GET['id'] ?? null;
        $this->productoModel->eliminar($id);
        header('Location: ?action=listar_productos');
        exit;
    }
}
