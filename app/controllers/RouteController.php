<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../models/Route.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../../config/db.php';

class RouteController {
    public function index() {
        global $conn;
        
        // Capturar mensajes de sesión
        $success_message = $_SESSION['success'] ?? '';
        $error_message = $_SESSION['error'] ?? '';
        
        // Limpiar mensajes de sesión después de capturarlos
        unset($_SESSION['success'], $_SESSION['error']);
        
        $filtro_venta = isset($_GET['venta']) ? $_GET['venta'] : '';
        $ventas = Sale::getFiltered($conn);
        $rutas = Route::getFiltered($conn, $filtro_venta);

        // Asegurarse de que $rutas sea un array válido
        if (!is_array($rutas)) {
            $rutas = [];
        }

        include __DIR__ . '/../views/rutas/index.php';
    }

    public function create() {
        global $conn;
        
        // Obtener datos para los selects
        $available_clients = Route::getAvailableClients($conn);
        $available_sales = Route::getAvailableSales($conn);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validación de datos
                $direccion = trim($_POST['direccion'] ?? '');
                $nombre_local = trim($_POST['nombre_local'] ?? '');
                $nombre_cliente = trim($_POST['nombre_cliente'] ?? '');
                $id_clientes = intval($_POST['id_clientes'] ?? 0);
                $id_ventas = intval($_POST['id_ventas'] ?? 0);

                // Validaciones básicas
                if (empty($direccion) || strlen($direccion) < 5) {
                    throw new Exception("La dirección debe tener al menos 5 caracteres.");
                }
                if (empty($nombre_local) || strlen($nombre_local) < 2) {
                    throw new Exception("El nombre del local debe tener al menos 2 caracteres.");
                }
                if (empty($nombre_cliente) || strlen($nombre_cliente) < 2) {
                    throw new Exception("El nombre del cliente debe tener al menos 2 caracteres.");
                }
                if ($id_clientes <= 0) {
                    throw new Exception("Debe seleccionar un cliente válido.");
                }
                if ($id_ventas <= 0) {
                    throw new Exception("Debe seleccionar una venta válida.");
                }

                // Validaciones de claves foráneas
                if (!Route::clientExists($conn, $id_clientes)) {
                    throw new Exception("El cliente seleccionado no existe en el sistema. Por favor, selecciona un cliente válido.");
                }
                
                if (!Route::saleExists($conn, $id_ventas)) {
                    throw new Exception("La venta seleccionada no existe en el sistema. Por favor, selecciona una venta válida.");
                }

                Route::create($conn, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas);
                
                // Mensaje de éxito y redirección
                $_SESSION['success_message'] = "Ruta creada exitosamente.";
                header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                exit;
                
            } catch (Exception $e) {
                $error_message = $e->getMessage();
                // Si es un error de clave foránea, hacer el mensaje más claro
                if (strpos($error_message, 'foreign key constraint fails') !== false) {
                    if (strpos($error_message, 'id_clientes') !== false) {
                        $error_message = "El cliente seleccionado no existe. Por favor, selecciona un cliente válido de la lista.";
                    } elseif (strpos($error_message, 'id_ventas') !== false) {
                        $error_message = "La venta seleccionada no existe. Por favor, selecciona una venta válida de la lista.";
                    }
                }
                echo "<div class='alert alert-error'><i class='fas fa-exclamation-triangle'></i> Error: " . $error_message . "</div>";
            }
        }
        include __DIR__ . '/../views/rutas/create.php';
    }

    public function edit($id) {
        global $conn;
        
        // Limpiar cualquier error residual de sesión cuando se carga por primera vez
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['from_post'])) {
            unset($_SESSION['error']);
        }
        
        try {
            $route = Route::getById($conn, $id);
            if (!$route) {
                $_SESSION['error'] = "Ruta no encontrada.";
                header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                exit;
            }

            // Solo procesar datos POST si es una petición POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validación de datos
                $direccion = trim($_POST['direccion'] ?? '');
                $nombre_local = trim($_POST['nombre_local'] ?? '');
                $nombre_cliente = trim($_POST['nombre_cliente'] ?? '');
                $id_clientes = intval($_POST['id_clientes'] ?? 0);
                $id_ventas = intval($_POST['id_ventas'] ?? 0);

                // Validaciones solo para POST
                if (empty($direccion) || strlen($direccion) < 5) {
                    throw new Exception("La dirección debe tener al menos 5 caracteres.");
                }
                if (empty($nombre_local) || strlen($nombre_local) < 2) {
                    throw new Exception("El nombre del local debe tener al menos 2 caracteres.");
                }
                if (empty($nombre_cliente) || strlen($nombre_cliente) < 2) {
                    throw new Exception("El nombre del cliente debe tener al menos 2 caracteres.");
                }
                if ($id_clientes <= 0) {
                    throw new Exception("El ID del cliente debe ser un número positivo.");
                }
                if ($id_ventas <= 0) {
                    throw new Exception("El ID de la venta debe ser un número positivo.");
                }

                // Intentar actualizar la ruta (mantener id_reportes existente)
                $id_reportes = $route['id_reportes']; // Mantener el valor existente
                $success = Route::update($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $id_reportes);
                
                if ($success) {
                    $_SESSION['success'] = "Ruta actualizada exitosamente.";
                    header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                    exit;
                } else {
                    throw new Exception("No se pudo actualizar la ruta. Inténtalo de nuevo.");
                }
            }
            
            // Si llegamos aquí y es GET, mostrar el formulario
            include __DIR__ . '/../views/rutas/edit.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            // Si es POST, redirigir para evitar reenvío del formulario
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Location: /RMIE/app/controllers/RouteController.php?accion=edit&id=' . $id . '&from_post=1');
                exit;
            } else {
                // Si es GET y hay error, mostrar el formulario con el error
                include __DIR__ . '/../views/rutas/edit.php';
            }
        }
    }

    public function delete($id) {
        global $conn;
        try {
            $route = Route::getById($conn, $id);
            if (!$route) {
                throw new Exception("Ruta no encontrada.");
            }
            
            Route::delete($conn, $id);
            header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
            exit;
        } catch (Exception $e) {
            echo "<div class='alert alert-error'><i class='fas fa-exclamation-triangle'></i> Error: " . $e->getMessage() . "</div>";
            header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
            exit;
        }
    }
}

// Manejo de acciones por parámetro GET
if (isset($_GET['accion'])) {
    $controller = new RouteController();
    $action = $_GET['accion'];
    
    switch ($action) {
        case 'index':
            $controller->index();
            break;
        case 'create':
            $controller->create();
            break;
        case 'edit':
            if (isset($_GET['id'])) {
                $controller->edit($_GET['id']);
            } else {
                $controller->index();
            }
            break;
        case 'delete':
            if (isset($_GET['id'])) {
                $controller->delete($_GET['id']);
            } else {
                $controller->index();
            }
            break;
        default:
            $controller->index();
            break;
    }
} else {
    // Si no hay acción, redirigir al login o mostrar índice
    $controller = new RouteController();
    $controller->index();
}
?>
