<?php
require_once __DIR__ . '/../models/Proveedor.php';
require_once __DIR__ . '/../../config/db.php';

class ProvidersController {
    private $proveedorModel;

    public function __construct($pdo) {
        $this->proveedorModel = new Proveedor($pdo);
    }

    public function listar() {
        $productos = $this->proveedorModel->obtenerProductos();
        $filtro_producto = $_GET['producto'] ?? '';
        if ($filtro_producto) {
            $proveedores = array_filter($this->proveedorModel->obtenerTodos(), function($prov) use ($filtro_producto) {
                $productosProv = $this->proveedorModel->obtenerProductosPorProveedor($prov['id_proveedores']);
                foreach ($productosProv as $prod) {
                    if ($prod['id_productos'] == $filtro_producto) return true;
                }
                return false;
            });
        } else {
            $proveedores = $this->proveedorModel->obtenerTodos();
        }
        include __DIR__ . '/../views/proveedores_listar.php';
    }

    public function crear() {
        $productos = $this->proveedorModel->obtenerProductos();
        include __DIR__ . '/../views/proveedores_crear.php';
    }

    public function guardar() {
        $nombre = $_POST['nombre_distribuidor'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $cel = $_POST['cel_proveedor'] ?? '';
        $estado = $_POST['estado'] ?? '';
        $productos = $_POST['productos'] ?? [];
        $this->proveedorModel->crear($nombre, $correo, $cel, $estado, $productos);
        header('Location: ?action=listar_proveedores');
        exit;
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        $proveedor = $this->proveedorModel->obtenerPorId($id);
        $productos = $this->proveedorModel->obtenerProductos();
        $productosProveedor = $this->proveedorModel->obtenerProductosPorProveedor($id);
        include __DIR__ . '/../views/proveedores_editar.php';
    }

    public function actualizar() {
        $id = $_GET['id'] ?? null;
        $nombre = $_POST['nombre_distribuidor'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $cel = $_POST['cel_proveedor'] ?? '';
        $estado = $_POST['estado'] ?? '';
        $productos = $_POST['productos'] ?? [];
        $this->proveedorModel->actualizar($id, $nombre, $correo, $cel, $estado, $productos);
        header('Location: ?action=listar_proveedores');
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        $proveedor = $this->proveedorModel->obtenerPorId($id);
        include __DIR__ . '/../views/proveedores_eliminar.php';
    }

    public function eliminarConfirmar() {
        $id = $_GET['id'] ?? null;
        $this->proveedorModel->eliminar($id);
        header('Location: ?action=listar_proveedores');
        exit;
    }
}
