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
        
        require_once __DIR__ . '/../utils/FilterHelper.php';
        
        // Definir reglas de filtro
        $filterRules = [
            'cliente' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'venta' => ['type' => 'int', 'options' => ['min' => 1]],
            'reporte' => ['type' => 'int', 'options' => ['min' => 1]],
            'direccion' => ['type' => 'text', 'options' => ['max_length' => 200]],
            'local' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'estado' => ['type' => 'text', 'options' => ['max_length' => 20]],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
        ];
        
        // Procesar filtros del GET
        $filtros = FilterHelper::processFilters($_GET, $filterRules);
        // Mapear los filtros de select a los nombres correctos para el modelo
        if (!empty($filtros['cliente'])) {
            $filtros['nombre_cliente'] = $filtros['cliente'];
        }
        if (!empty($filtros['local'])) {
            $filtros['nombre_local'] = $filtros['local'];
        }
        
        // Obtener datos para selectores
        $ventas = Sale::getFiltered($conn);
        $available_clients = Route::getAvailableClients($conn);
        $available_locals = Route::getAvailableLocals($conn);
        
        // Obtener rutas con filtros
        $rutas = Route::getAll($conn, $filtros);

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
        $available_locals = Route::getAvailableLocals($conn);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validación de datos - ahora son arrays
                $id_locales_array = isset($_POST['id_locales']) ? array_map('intval', (array)$_POST['id_locales']) : [];
                $id_clientes_array = isset($_POST['id_clientes']) ? array_map('intval', (array)$_POST['id_clientes']) : [];
                $id_ventas_array = isset($_POST['id_ventas']) ? array_map('intval', (array)$_POST['id_ventas']) : [];
                $estado = isset($_POST['estado']) && in_array($_POST['estado'], ['activa', 'pendiente']) ? $_POST['estado'] : 'activa';

                // Validaciones básicas
                if (empty($id_locales_array)) {
                    throw new Exception("Debe seleccionar al menos un local válido.");
                }
                if (empty($id_clientes_array)) {
                    throw new Exception("Debe seleccionar al menos un cliente válido.");
                }
                if (empty($id_ventas_array)) {
                    throw new Exception("Debe seleccionar al menos una venta válida.");
                }

                // Validaciones de claves foráneas
                foreach ($id_clientes_array as $id_cliente) {
                    if (!Route::clientExists($conn, $id_cliente)) {
                        throw new Exception("El cliente $id_cliente no existe en el sistema.");
                    }
                }
                
                foreach ($id_ventas_array as $id_venta) {
                    if (!Route::saleExists($conn, $id_venta)) {
                        throw new Exception("La venta $id_venta no existe en el sistema.");
                    }
                }

                // Obtener nombres y direcciones de locales
                $nombre_local = '';
                $direccion_completa = '';
                $locales_nombres = [];
                $direcciones_array = [];
                
                foreach ($id_locales_array as $id_local) {
                    $localQuery = "SELECT nombre_local, direccion, localidad, barrio FROM locales WHERE id_locales = ?";
                    $stmt = $conn->prepare($localQuery);
                    $stmt->bind_param('i', $id_local);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $localData = $result->fetch_assoc();
                    
                    if (!$localData) {
                        throw new Exception("El local $id_local no existe.");
                    }
                    
                    $locales_nombres[] = $localData['nombre_local'];
                    
                    // Construir dirección completa para cada local
                    $dir = $localData['direccion'] ?? '';
                    if ($localData['barrio']) $dir .= ', ' . $localData['barrio'];
                    if ($localData['localidad']) $dir .= ', ' . $localData['localidad'];
                    $direcciones_array[] = $dir;
                }
                
                $nombre_local = implode(', ', $locales_nombres);
                
                // Usar la primera dirección como dirección principal
                $direccion = !empty($direcciones_array) ? $direcciones_array[0] : '';

                // Obtener nombres de clientes
                $clientes_nombres = [];
                foreach ($id_clientes_array as $id_cliente) {
                    $clienteQuery = "SELECT nombre FROM clientes WHERE id_clientes = ?";
                    $stmtCliente = $conn->prepare($clienteQuery);
                    $stmtCliente->bind_param('i', $id_cliente);
                    $stmtCliente->execute();
                    $resultCliente = $stmtCliente->get_result();
                    $clienteData = $resultCliente->fetch_assoc();
                    
                    if (!$clienteData) {
                        throw new Exception("El cliente $id_cliente no existe.");
                    }
                    
                    $clientes_nombres[] = $clienteData['nombre'];
                }
                
                $nombre_cliente = implode(', ', $clientes_nombres);

                // Crear una sola ruta con todos los valores
                Route::createMultiple($conn, $direccion, $nombre_local, $nombre_cliente, $id_locales_array, $id_clientes_array, $id_ventas_array, $estado, $direcciones_array);

                $_SESSION['success_message'] = "Ruta creada exitosamente con " . count($id_locales_array) . " local(es), " . count($id_clientes_array) . " cliente(s) y " . count($id_ventas_array) . " venta(s).";
                
                // Redirección
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

            // Obtener datos para los selects
            $available_clients = Route::getAvailableClients($conn);
            $available_locals = Route::getAvailableLocals($conn);

            // Solo procesar datos POST si es una petición POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Debug: Log de los datos recibidos
                error_log("DEBUG EDIT - POST recibido para ruta ID: $id");
                error_log("DEBUG EDIT - Datos POST: " . json_encode($_POST));
                
                // Validación de datos - ahora son arrays
                $id_locales_array = isset($_POST['id_locales']) ? array_map('intval', (array)$_POST['id_locales']) : [];
                
                // Para clientes, manejar tanto el nuevo campo array como el legacy
                $id_clientes_array = [];
                if (isset($_POST['id_clientes']) && is_array($_POST['id_clientes'])) {
                    $id_clientes_array = array_map('intval', $_POST['id_clientes']);
                } elseif (isset($_POST['id_clientes_legacy'])) {
                    $id_clientes_array = [intval($_POST['id_clientes_legacy'])];
                } elseif (isset($_POST['id_clientes'])) {
                    $id_clientes_array = [intval($_POST['id_clientes'])];
                }
                
                // Manejar id_ventas como valor único (no array como los otros)
                $id_ventas = isset($_POST['id_ventas']) ? intval($_POST['id_ventas']) : 0;
                $id_ventas_array = $id_ventas > 0 ? [$id_ventas] : [];
                $estado = isset($_POST['estado']) && in_array($_POST['estado'], ['activa', 'pendiente']) ? $_POST['estado'] : 'activa';
                
                error_log("DEBUG EDIT - Arrays procesados:");
                error_log("DEBUG EDIT - id_locales_array: " . json_encode($id_locales_array));
                error_log("DEBUG EDIT - id_clientes_array: " . json_encode($id_clientes_array));
                error_log("DEBUG EDIT - id_ventas_array: " . json_encode($id_ventas_array));

                // Validaciones básicas
                if (empty($id_locales_array)) {
                    throw new Exception("Debe seleccionar al menos un local válido.");
                }
                if (empty($id_clientes_array)) {
                    throw new Exception("Debe seleccionar al menos un cliente válido.");
                }
                if (empty($id_ventas_array)) {
                    throw new Exception("Debe seleccionar al menos una venta válida.");
                }

                // Obtener nombres y direcciones de locales
                $nombre_local = '';
                $direccion_completa = '';
                $locales_nombres = [];
                $direcciones_array = [];
                
                foreach ($id_locales_array as $id_local) {
                    $localQuery = "SELECT nombre_local, direccion, localidad, barrio FROM locales WHERE id_locales = ?";
                    $stmt = $conn->prepare($localQuery);
                    $stmt->bind_param('i', $id_local);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $localData = $result->fetch_assoc();
                    
                    if (!$localData) {
                        throw new Exception("El local $id_local no existe.");
                    }
                    
                    $locales_nombres[] = $localData['nombre_local'];
                    
                    // Construir dirección completa para cada local
                    $dir = $localData['direccion'] ?? '';
                    if ($localData['barrio']) $dir .= ', ' . $localData['barrio'];
                    if ($localData['localidad']) $dir .= ', ' . $localData['localidad'];
                    $direcciones_array[] = $dir;
                }
                
                $nombre_local = implode(', ', $locales_nombres);
                $direccion = !empty($direcciones_array) ? $direcciones_array[0] : '';

                // Obtener nombres de clientes
                $clientes_nombres = [];
                foreach ($id_clientes_array as $id_cliente) {
                    $clienteQuery = "SELECT nombre FROM clientes WHERE id_clientes = ?";
                    $stmtCliente = $conn->prepare($clienteQuery);
                    $stmtCliente->bind_param('i', $id_cliente);
                    $stmtCliente->execute();
                    $resultCliente = $stmtCliente->get_result();
                    $clienteData = $resultCliente->fetch_assoc();
                    
                    if (!$clienteData) {
                        throw new Exception("El cliente $id_cliente no existe.");
                    }
                    
                    $clientes_nombres[] = $clienteData['nombre'];
                }
                
                $nombre_cliente = implode(', ', $clientes_nombres);

                // Actualizar la ruta con datos JSON
                Route::updateMultiple($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_locales_array, $id_clientes_array, $id_ventas_array, $estado, $direcciones_array);
                
                $_SESSION['success'] = "Ruta actualizada exitosamente con " . count($id_locales_array) . " local(es), " . count($id_clientes_array) . " cliente(s) y " . count($id_ventas_array) . " venta(s).";
                header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                exit;
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
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/app/controllers/RouteController.php?accion=index";</script>';
            exit();
        }
        
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

    public function complete($id) {
        global $conn;
        
        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de ruta inválido';
            header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
            exit;
        }

        try {
            // Actualizar el estado de la ruta a 'completada'
            $sql = "UPDATE rutas SET estado = 'completada' WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Ruta completada correctamente';
            } else {
                $_SESSION['error'] = 'Error al completar la ruta';
            }
            
        } catch (Exception $e) {
            error_log("Error al completar ruta: " . $e->getMessage());
            $_SESSION['error'] = 'Error al completar la ruta: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
        exit;
    }

    public function completeAndDelete($id) {
        global $conn;
        
        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de ruta inválido';
            header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
            exit;
        }

        try {
            // Primero completar la ruta
            $sql = "UPDATE rutas SET estado = 'completada' WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            // Luego eliminarla
            $sql = "DELETE FROM rutas WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Ruta completada y eliminada correctamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar la ruta';
            }
            
        } catch (Exception $e) {
            error_log("Error al completar y eliminar ruta: " . $e->getMessage());
            $_SESSION['error'] = 'Error al completar y eliminar la ruta: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
        exit;
    }

    public function cleanCompleted() {
        global $conn;

        try {
            // Primero contar cuántas rutas completadas hay
            $countSql = "SELECT COUNT(*) as total FROM rutas WHERE estado = 'completada'";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalCompletadas = $countResult['total'];
            
            if ($totalCompletadas == 0) {
                $_SESSION['error'] = 'No se encontraron rutas completadas para eliminar';
            } else {
                // Eliminar todas las rutas con estado 'completada'
                $sql = "DELETE FROM rutas WHERE estado = 'completada'";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $_SESSION['success'] = "Se eliminaron {$deletedRows} rutas completadas correctamente";
                } else {
                    $_SESSION['error'] = 'Error al eliminar las rutas completadas';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar rutas completadas: " . $e->getMessage());
            $_SESSION['error'] = 'Error al limpiar las rutas completadas: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
        exit;
    }

    public function cleanAll() {
        global $conn;

        try {
            // Contar todas las rutas
            $countSql = "SELECT COUNT(*) as total FROM rutas";
            $countStmt = $conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result()->fetch_assoc();
            $totalRutas = $countResult['total'];
            
            if ($totalRutas == 0) {
                $_SESSION['error'] = 'No hay rutas para eliminar';
            } else {
                // Eliminar TODAS las rutas
                $sql = "DELETE FROM rutas";
                $stmt = $conn->prepare($sql);
                
                if ($stmt->execute()) {
                    $deletedRows = $stmt->affected_rows;
                    $_SESSION['success'] = "Se eliminaron TODAS las {$deletedRows} rutas correctamente";
                } else {
                    $_SESSION['error'] = 'Error al eliminar todas las rutas';
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al limpiar todas las rutas: " . $e->getMessage());
            $_SESSION['error'] = 'Error al limpiar todas las rutas: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
        exit;
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
        case 'complete':
            if (isset($_GET['id'])) {
                $controller->complete($_GET['id']);
            } else {
                $controller->index();
            }
            break;
        case 'complete_and_delete':
            if (isset($_GET['id'])) {
                $controller->completeAndDelete($_GET['id']);
            } else {
                $controller->index();
            }
            break;
        case 'clean_completed':
            $controller->cleanCompleted();
            break;
        case 'clean_all':
            $controller->cleanAll();
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
