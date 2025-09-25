<?php
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../../config/db.php';

class AlertController {
    public function index() {
        global $conn;
        $productos = Product::getAll($conn);
        $filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
        $alertas = Alert::getFiltered($conn, $filtro_producto);
        include __DIR__ . '/../views/alertas/index.php';
    }

    public function create() {
        global $conn;
        $productos = Product::getAll($conn);
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_producto = $_POST['id_productos'] ?? '';
            $cantidad_minima = $_POST['cantidad_minima'] ?? '';
            $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
            $id_cliente = $_POST['id_clientes'] ?? '';
            if ($id_producto && $cantidad_minima && $fecha_caducidad && $id_cliente) {
                $resultado = Alert::create($conn, $id_producto, $cantidad_minima, $fecha_caducidad, $id_cliente);
                if ($resultado) {
                    $mensaje = '¡Alerta creada exitosamente!';
                } else {
                    $mensaje = 'Error al crear la alerta.';
                }
            } else {
                $mensaje = 'Por favor, completa todos los campos.';
            }
        }
        include __DIR__ . '/../views/alertas/create.php';
    }

    public function edit() {
        global $conn;
        $id = $_GET['id'] ?? 0;
        $errors = [];
        $success = '';
        $alerta = Alert::getById($conn, $id);
        if (!$alerta) {
            header('Location: index.php');
            exit();
        }
        $productos = Product::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerta['id_productos'] = $_POST['id_productos'] ?? '';
            $alerta['cantidad_minima'] = $_POST['cantidad_minima'] ?? '';
            $alerta['fecha_caducidad'] = $_POST['fecha_caducidad'] ?? '';
            $alerta['id_clientes'] = $_POST['id_clientes'] ?? '';
            if (empty($alerta['id_productos'])) $errors[] = 'El producto es obligatorio';
            if (empty($alerta['cantidad_minima'])) $errors[] = 'La cantidad mínima es obligatoria';
            if (empty($alerta['fecha_caducidad'])) $errors[] = 'La fecha de caducidad es obligatoria';
            if (empty($alerta['id_clientes'])) $errors[] = 'El cliente es obligatorio';
            if (empty($errors)) {
                $result = Alert::update($conn, $id, $alerta);
                if ($result) $success = 'Alerta actualizada exitosamente';
                else $errors[] = 'Error al actualizar la alerta';
            }
        }
        include __DIR__ . '/../views/alertas/edit.php';
    }

    public function delete() {
        global $conn;
        $id = $_GET['id'] ?? 0;
        $errors = [];
        $success = '';
        $alerta = Alert::getById($conn, $id);
        if (!$alerta) {
            header('Location: index.php');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_eliminar'])) {
            $result = Alert::delete($conn, $id);
            if ($result) {
                $success = 'Alerta eliminada exitosamente';
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
            } else {
                $errors[] = 'Error al eliminar la alerta';
            }
        }
        include __DIR__ . '/../views/alertas/delete.php';
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
                break;
        }
    }
}

// Ejecutar el controlador
$controller = new AlertController();
$controller->handleRequest();
?>
