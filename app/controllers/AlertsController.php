<?php
require_once __DIR__ . '/../models/Alerta.php';
require_once __DIR__ . '/../../config/db.php';

class AlertsController {
    private $alertaModel;

    public function __construct($pdo) {
        $this->alertaModel = new Alerta($pdo);
    }

    public function listar() {
        $productos = $this->alertaModel->obtenerProductos();
        $filtro_producto = $_GET['producto'] ?? '';
        if ($filtro_producto) {
            $alertas = array_filter($this->alertaModel->obtenerTodas(), function($a) use ($filtro_producto) {
                return $a['id_productos'] == $filtro_producto;
            });
        } else {
            $alertas = $this->alertaModel->obtenerTodas();
        }
        include __DIR__ . '/../views/alertas_listar.php';
    }

    public function crear() {
        $productos = $this->alertaModel->obtenerProductos();
        $clientes = $this->alertaModel->obtenerClientes();
        include __DIR__ . '/../views/alertas_crear.php';
    }

    public function guardar() {
        $cliente_no_disponible = $_POST['cliente_no_disponible'] ?? '';
        $id_clientes = $_POST['id_clientes'] ?? '';
        $id_productos = $_POST['id_productos'] ?? '';
        $this->alertaModel->crear($cliente_no_disponible, $id_clientes, $id_productos);
        header('Location: ?action=listar_alertas');
        exit;
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        $alerta = $this->alertaModel->obtenerPorId($id);
        $productos = $this->alertaModel->obtenerProductos();
        $clientes = $this->alertaModel->obtenerClientes();
        include __DIR__ . '/../views/alertas_editar.php';
    }

    public function actualizar() {
        $id = $_GET['id'] ?? null;
        $cliente_no_disponible = $_POST['cliente_no_disponible'] ?? '';
        $id_clientes = $_POST['id_clientes'] ?? '';
        $id_productos = $_POST['id_productos'] ?? '';
        $this->alertaModel->actualizar($id, $cliente_no_disponible, $id_clientes, $id_productos);
        header('Location: ?action=listar_alertas');
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        $alerta = $this->alertaModel->obtenerPorId($id);
        include __DIR__ . '/../views/alertas_eliminar.php';
    }

    public function eliminarConfirmar() {
        $id = $_GET['id'] ?? null;
        $this->alertaModel->eliminar($id);
        header('Location: ?action=listar_alertas');
        exit;
    }
}
