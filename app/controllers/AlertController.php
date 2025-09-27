<?php
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../../config/db.php';

class AlertController {
    public function index() {
        global $conn;
        
        // Obtener productos para el filtro
        $productos = Product::getAll($conn);
        
        // Recoger filtros
        $filtros = [
            'producto' => $_GET['producto'] ?? '',
            'nombre_producto' => $_GET['nombre_producto'] ?? '',
            'cantidad_min' => $_GET['cantidad_min'] ?? '',
            'cantidad_max' => $_GET['cantidad_max'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        // Obtener alertas filtradas
        $alertas = Alert::getFiltered($conn, $filtros);
        
        // Calcular estadísticas
        $total_alertas = count($alertas);
        $alertas_proximas = 0;
        $alertas_vencidas = 0;
        $fecha_actual = date('Y-m-d');
        
        foreach ($alertas as $alerta) {
            if ($alerta['fecha_caducidad'] < $fecha_actual) {
                $alertas_vencidas++;
            } elseif ($alerta['fecha_caducidad'] <= date('Y-m-d', strtotime('+30 days'))) {
                $alertas_proximas++;
            }
        }
        
        $estadisticas = [
            'total' => $total_alertas,
            'proximas' => $alertas_proximas,
            'vencidas' => $alertas_vencidas
        ];
        
        include __DIR__ . '/../views/alertas/index.php';
    }

    public function create() {
        global $conn;
        $productos = Product::getAll($conn);
        $clientes = Client::getAll($conn);
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_producto = isset($_POST['id_productos']) ? (int)$_POST['id_productos'] : 0;
            $cantidad_minima = isset($_POST['cantidad_minima']) ? (int)$_POST['cantidad_minima'] : 0;
            $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
            $id_cliente = isset($_POST['id_clientes']) ? (int)$_POST['id_clientes'] : 0;

            if ($id_producto && $cantidad_minima && $fecha_caducidad && $id_cliente) {
                // Validar existencia en BD
                $prod = Product::getById($conn, $id_producto);
                $cli = Client::getById($conn, $id_cliente);
                if (!$prod) {
                    $mensaje = 'Producto no válido.';
                } elseif (!$cli) {
                    $mensaje = 'Cliente no válido.';
                } else {
                    try {
                        $resultado = Alert::create($conn, $id_producto, $cantidad_minima, $fecha_caducidad, $id_cliente);
                        $mensaje = $resultado ? '¡Alerta creada exitosamente!' : 'Error al crear la alerta.';
                    } catch (Throwable $e) {
                        $mensaje = 'Error al crear la alerta: ' . $e->getMessage();
                    }
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
            header('Location: /RMIE/app/controllers/AlertController.php?action=index');
            exit();
        }
        $productos = Product::getAll($conn);
        $clientes = Client::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerta['id_productos'] = isset($_POST['id_productos']) ? (int)$_POST['id_productos'] : 0;
            $alerta['cantidad_minima'] = isset($_POST['cantidad_minima']) ? (int)$_POST['cantidad_minima'] : 0;
            $alerta['fecha_caducidad'] = $_POST['fecha_caducidad'] ?? '';
            $alerta['id_clientes'] = isset($_POST['id_clientes']) ? (int)$_POST['id_clientes'] : 0;

            if (empty($alerta['id_productos'])) $errors[] = 'El producto es obligatorio';
            if (empty($alerta['cantidad_minima'])) $errors[] = 'La cantidad mínima es obligatoria';
            if (empty($alerta['fecha_caducidad'])) $errors[] = 'La fecha de caducidad es obligatoria';
            if (empty($alerta['id_clientes'])) $errors[] = 'El cliente es obligatorio';

            if (empty($errors)) {
                // Validar existencia en BD
                if (!Product::getById($conn, $alerta['id_productos'])) {
                    $errors[] = 'Producto no válido';
                }
                if (!Client::getById($conn, $alerta['id_clientes'])) {
                    $errors[] = 'Cliente no válido';
                }
            }

            if (empty($errors)) {
                $result = Alert::update($conn, (int)$id, $alerta);
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
            header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_eliminar'])) {
            $result = Alert::delete($conn, $id);
            if ($result) {
                $_SESSION['success'] = 'Alerta eliminada exitosamente';
                header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
                exit();
            } else {
                $errors[] = 'Error al eliminar la alerta';
            }
        }
        include __DIR__ . '/../views/alertas/delete.php';
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? $_GET['accion'] ?? 'index';
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
