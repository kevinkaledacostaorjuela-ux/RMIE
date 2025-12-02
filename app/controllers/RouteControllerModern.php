<?php
// RouteController Modernizado - RMIE v3.0
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/route_errors.log');

// Incluir modelos necesarios
try {
    require_once __DIR__ . '/../models/Route.php';
    require_once __DIR__ . '/../../config/db.php';
} catch (Exception $e) {
    error_log('Error cargando archivos en RouteController: ' . $e->getMessage());
    die('Error interno del sistema. Por favor contacte al administrador.');
}

class RouteControllerModern {
    
    public function index() {
        global $conn;
        
        // Capturar mensajes de sesión
        $success_message = $_SESSION['success'] ?? '';
        $error_message = $_SESSION['error'] ?? '';
        
        // Limpiar mensajes de sesión después de capturarlos
        unset($_SESSION['success'], $_SESSION['error']);
        
        try {
            // Obtener filtros del GET
            $filtros = [
                'cliente' => $_GET['cliente'] ?? '',
                'local' => $_GET['local'] ?? '',
                'estado' => $_GET['estado'] ?? '',
                'dia' => $_GET['dia'] ?? '',
                'buscar' => $_GET['buscar'] ?? ''
            ];
            
            // Obtener rutas con filtros
            $rutas_raw = Route::getAll($conn, $filtros);
            
            // Agrupar rutas por día de la semana
            $rutas_agrupadas = [];
            foreach ($rutas_raw as $ruta) {
                $dia = $ruta['dia_semana'] ?? 'Sin día';
                if (!isset($rutas_agrupadas[$dia])) {
                    $rutas_agrupadas[$dia] = [
                        'dia' => $dia,
                        'rutas' => [],
                        'total_clientes' => 0,
                        'total_locales' => 0
                    ];
                }
                $rutas_agrupadas[$dia]['rutas'][] = $ruta;
                
                // Contar clientes y locales únicos por día
                $clientes_unicos = array_unique(array_column($rutas_agrupadas[$dia]['rutas'], 'cliente_nombre'));
                $locales_unicos = array_unique(array_column($rutas_agrupadas[$dia]['rutas'], 'local_nombre'));
                $rutas_agrupadas[$dia]['total_clientes'] = count(array_filter($clientes_unicos));
                $rutas_agrupadas[$dia]['total_locales'] = count(array_filter($locales_unicos));
            }
            
            // Ordenar por día de la semana
            $orden_dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
            uksort($rutas_agrupadas, function($a, $b) use ($orden_dias) {
                $pos_a = array_search($a, $orden_dias);
                $pos_b = array_search($b, $orden_dias);
                if ($pos_a === false) $pos_a = 999;
                if ($pos_b === false) $pos_b = 999;
                return $pos_a - $pos_b;
            });
            
            // Para mantener compatibilidad, también mantener el array plano
            $rutas = $rutas_raw;
            
            // Datos adicionales para la vista
            $available_clients = Route::getAvailableClients($conn);
            $available_locals = Route::getAvailableLocals($conn);
            $asignaciones_clientes = [];
            
            // Día actual
            $dia_actual = date('N');
            $nombres_dias = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
            $dia_hoy = $nombres_dias[$dia_actual] ?? 'Lunes';
            
            require_once __DIR__ . '/../views/rutas/index_modern.php';
            
        } catch (Exception $e) {
            error_log('Error en index de rutas: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar las rutas.';
            $rutas = [];
            require_once __DIR__ . '/../views/rutas/index_modern.php';
        }
    }
    
    public function create() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Obtener datos del formulario
                $dias_semana = $_POST['dias_semana'] ?? [];
                $combinaciones_data = $_POST['combinaciones_data'] ?? '[]';
                $estado = 'pendiente'; // Estado por defecto
                
                // Decodificar las combinaciones JSON
                $combinaciones = json_decode($combinaciones_data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Error al procesar las combinaciones cliente-local.");
                }
                
                // Validaciones básicas
                if (empty($dias_semana)) {
                    throw new Exception("Debe seleccionar al menos un día de la semana.");
                }
                if (empty($combinaciones)) {
                    throw new Exception("Debe agregar al menos una combinación cliente-local.");
                }
                
                // Preparar la consulta para insertar múltiples rutas
                $sql = "INSERT INTO rutas (direccion, nombre_local, nombre_cliente, id_clientes, estado, dia_semana) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                $rutas_creadas = 0;
                $conn->autocommit(false); // Iniciar transacción
                $transaction_started = true;
                
                // Iterar por cada día seleccionado
                foreach ($dias_semana as $dia) {
                    // Iterar por cada combinación cliente-local
                    foreach ($combinaciones as $combo) {
                        $clienteId = $combo['clienteId'];
                        $localId = $combo['localId'];
                        $clienteNombre = $combo['clienteNombre'];
                        $localNombre = $combo['localNombre'];
                        $localDireccion = $combo['localDireccion'] ?: 'Dirección del ' . $localNombre;
                        
                        $stmt->bind_param('sssiis', 
                            $localDireccion, 
                            $localNombre, 
                            $clienteNombre, 
                            $clienteId, 
                            $estado, 
                            $dia
                        );
                        
                        if ($stmt->execute()) {
                            $rutas_creadas++;
                        } else {
                            throw new Exception("Error al crear ruta para {$localNombre} - {$clienteNombre} - {$dia}");
                        }
                    }
                }
                
                $conn->commit(); // Confirmar transacción
                $conn->autocommit(true); // Restaurar autocommit
                $_SESSION['success'] = "{$rutas_creadas} rutas creadas exitosamente.";
                header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                exit;
                
            } catch (Exception $e) {
                if (isset($transaction_started) && $transaction_started) {
                    $conn->rollback(); // Revertir transacción en caso de error
                    $conn->autocommit(true); // Restaurar autocommit
                }
                error_log('Error al crear rutas: ' . $e->getMessage());
                $_SESSION['error'] = 'Error al crear rutas: ' . $e->getMessage();
                
                // Debug adicional
                error_log('POST data: ' . print_r($_POST, true));
            }
        }
        
        // Mostrar formulario de creación
        require_once __DIR__ . '/../views/rutas/create_modern.php';
    }
    
    private function getLocalesData($locales_ids) {
        global $conn;
        
        if (empty($locales_ids)) return [];
        
        $placeholders = str_repeat('?,', count($locales_ids) - 1) . '?';
        $sql = "SELECT id_locales as id, nombre_local as nombre, direccion 
                FROM locales 
                WHERE id_locales IN ($placeholders) AND estado = 'activo'";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($locales_ids)), ...$locales_ids);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $locales = [];
        while ($row = $result->fetch_assoc()) {
            $locales[] = $row;
        }
        
        // Si no hay datos en BD, usar datos fallback
        if (empty($locales)) {
            foreach ($locales_ids as $id) {
                $locales[] = [
                    'id' => $id,
                    'nombre' => "Local ID {$id}",
                    'direccion' => ''
                ];
            }
        }
        
        return $locales;
    }
    
    private function getClientesData($clientes_ids) {
        global $conn;
        
        if (empty($clientes_ids)) return [];
        
        $placeholders = str_repeat('?,', count($clientes_ids) - 1) . '?';
        $sql = "SELECT id_clientes as id, nombre 
                FROM clientes 
                WHERE id_clientes IN ($placeholders) AND estado = 'activo'";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($clientes_ids)), ...$clientes_ids);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
        
        // Si no hay datos en BD, usar datos fallback
        if (empty($clientes)) {
            $fallback_names = ['Supermercado Central', 'Panadería La Esquina', 'Tienda TPS'];
            foreach ($clientes_ids as $index => $id) {
                $clientes[] = [
                    'id' => $id,
                    'nombre' => $fallback_names[$index % count($fallback_names)]
                ];
            }
        }
        
        return $clientes;
    }
    
    public function view($id) {
        global $conn;
        
        try {
            // Primero obtener la ruta solicitada para conocer el día
            $sql = "SELECT * FROM rutas WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $ruta_principal = $result->fetch_assoc();
            
            if (!$ruta_principal) {
                $_SESSION['error'] = 'Ruta no encontrada.';
                header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                exit;
            }
            
            // Obtener TODAS las rutas del mismo día con direcciones reales
            $sql = "SELECT r.*, 
                           l.direccion as direccion_real,
                           l.nombre_local as nombre_local_real,
                           l.cel_local,
                           c.cel_cliente
                    FROM rutas r
                    LEFT JOIN clientes c ON r.id_clientes = c.id_clientes
                    LEFT JOIN locales l ON c.id_locales = l.id_locales
                    WHERE r.dia_semana = ? AND r.estado != 'eliminado'
                    ORDER BY r.id_ruta";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $ruta_principal['dia_semana']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $rutas_dia = [];
            while ($ruta = $result->fetch_assoc()) {
                $rutas_dia[] = $ruta;
            }
            
            // Variables para la vista
            $dia = $ruta_principal['dia_semana'];
            $fecha_planificacion = null; // No hay fecha de planificación en la tabla
            
            require_once __DIR__ . '/../views/rutas/view_modern.php';
        } catch (Exception $e) {
            error_log('Error al ver ruta: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar los detalles de la ruta.';
            header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
            exit;
        }
    }
    
    public function edit($id) {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Obtener datos generales del formulario
                $dia_semana = $_POST['dia_semana'] ?? '';
                $estado = $_POST['estado'] ?? 'pendiente';
                $clientes_data = $_POST['clientes'] ?? [];
                $nuevos_clientes = $_POST['nuevos_clientes'] ?? [];
                
                if (empty($dia_semana)) {
                    throw new Exception("El día de la semana es obligatorio.");
                }
                
                // Actualizar cada local/cliente existente en el día
                foreach ($clientes_data as $ruta_id => $datos) {
                    $id_cliente = intval($datos['id_cliente'] ?? 0);
                    $id_local = intval($datos['id_local'] ?? 0);
                    $nombre_cliente = trim($datos['nombre_cliente'] ?? '');
                    $nombre_local = trim($datos['nombre_local'] ?? '');
                    $direccion = trim($datos['direccion'] ?? '');
                    
                    if (!empty($nombre_cliente) && !empty($direccion)) {
                        $sql = "UPDATE rutas SET id_clientes=?, id_locales=?, nombre_cliente=?, nombre_local=?, direccion=?, estado=?, dia_semana=? WHERE id_ruta=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('iisssssi', $id_cliente, $id_local, $nombre_cliente, $nombre_local, $direccion, $estado, $dia_semana, $ruta_id);
                        
                        if (!$stmt->execute()) {
                            throw new Exception("Error al actualizar la ruta ID: $ruta_id");
                        }
                    }
                }
                
                // Insertar nuevos clientes/locales agregados
                foreach ($nuevos_clientes as $temp_id => $datos) {
                    $id_cliente = intval($datos['id_cliente'] ?? 0);
                    $id_local = intval($datos['id_local'] ?? 0);
                    $nombre_cliente = trim($datos['nombre_cliente'] ?? '');
                    $nombre_local = trim($datos['nombre_local'] ?? '');
                    $direccion = trim($datos['direccion'] ?? '');
                    $es_nuevo = $datos['es_nuevo'] ?? 0;
                    
                    if ($es_nuevo && $id_cliente > 0 && $id_local > 0 && !empty($nombre_cliente) && !empty($direccion)) {
                        // Insertar nueva ruta
                        $sql = "INSERT INTO rutas (id_clientes, id_locales, nombre_cliente, nombre_local, direccion, dia_semana, estado, fecha_creacion) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('iisssss', $id_cliente, $id_local, $nombre_cliente, $nombre_local, $direccion, $dia_semana, $estado);
                        
                        if (!$stmt->execute()) {
                            throw new Exception("Error al insertar nueva ruta para: $nombre_cliente");
                        }
                    }
                }
                
                $total_procesados = count($clientes_data) + count($nuevos_clientes);
                if ($total_procesados > 0) {
                    $mensaje = 'Rutas actualizadas exitosamente.';
                    if (count($nuevos_clientes) > 0) {
                        $mensaje .= ' Se agregaron ' . count($nuevos_clientes) . ' nuevos clientes/locales al día.';
                    }
                    $_SESSION['success'] = $mensaje;
                    
                    // Log de éxito
                    error_log('Edición exitosa, redirigiendo a index');
                    
                    // Redirección explícita con URL absoluta
                    $redirect_url = '/RMIE/app/controllers/RouteControllerModern.php?accion=index';
                    header('Location: ' . $redirect_url);
                    exit();
                } else {
                    throw new Exception("No se encontraron datos válidos para actualizar.");
                }
                
            } catch (Exception $e) {
                error_log('Error al editar ruta: ' . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
                
                // Redirección en caso de error también
                $redirect_url = '/RMIE/app/controllers/RouteControllerModern.php?accion=edit&id=' . $id;
                header('Location: ' . $redirect_url);
                exit();
            }
        }
        
        // Cargar datos de la ruta para edición
        try {
            // Obtener la ruta principal
            $sql = "SELECT * FROM rutas WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $ruta = $result->fetch_assoc();
            
            if (!$ruta) {
                $_SESSION['error'] = 'Ruta no encontrada.';
                header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                exit;
            }
            
            // Cargar TODAS las rutas del mismo día para mostrar todos los locales
            $sql_todas = "SELECT r.*, 
                                l.direccion as direccion_real, 
                                l.nombre_local as nombre_local_real,
                                l.id_locales as id_local_real,
                                c.nombre as nombre_cliente_real
                         FROM rutas r 
                         LEFT JOIN clientes c ON r.id_clientes = c.id_clientes 
                         LEFT JOIN locales l ON c.id_locales = l.id_locales 
                         WHERE r.dia_semana = ? AND r.estado != 'eliminado' 
                         ORDER BY r.id_ruta";
            $stmt_todas = $conn->prepare($sql_todas);
            $stmt_todas->bind_param('s', $ruta['dia_semana']);
            $stmt_todas->execute();
            $result_todas = $stmt_todas->get_result();
            
            $rutas_dia = [];
            while ($ruta_dia = $result_todas->fetch_assoc()) {
                $rutas_dia[] = $ruta_dia;
            }
            
            require_once __DIR__ . '/../views/rutas/edit_modern.php';
        } catch (Exception $e) {
            error_log('Error al cargar ruta para edición: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar la ruta.';
            header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
            exit;
        }
    }
    
    public function complete($id) {
        global $conn;
        
        try {
            $sql = "UPDATE rutas SET estado = 'completada' WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Ruta completada exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al completar la ruta.';
            }
        } catch (Exception $e) {
            error_log('Error al completar ruta: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al completar la ruta: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
        exit;
    }
    
    public function delete($id) {
        global $conn;
        
        // Verificar permisos de administrador
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tiene permisos para eliminar rutas.';
            header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
            exit;
        }
        
        try {
            $sql = "DELETE FROM rutas WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Ruta eliminada exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al eliminar la ruta.';
            }
        } catch (Exception $e) {
            error_log('Error al eliminar ruta: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al eliminar la ruta: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
        exit;
    }
    
    public function completarDia($dia) {
        global $conn;
        
        try {
            $sql = "UPDATE rutas SET estado = 'completada' WHERE dia_semana = ? AND estado != 'completada'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $dia);
            
            if ($stmt->execute()) {
                $rows_affected = $stmt->affected_rows;
                $_SESSION['success'] = "Se completaron exitosamente $rows_affected rutas del día $dia.";
            } else {
                $_SESSION['error'] = "Error al completar las rutas del día $dia.";
            }
        } catch (Exception $e) {
            error_log('Error al completar día: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al completar las rutas del día: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
        exit;
    }
    
    public function eliminarDia($dia) {
        global $conn;
        
        // Verificar permisos de administrador
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tiene permisos para eliminar rutas.';
            header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
            exit;
        }
        
        try {
            $sql = "DELETE FROM rutas WHERE dia_semana = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $dia);
            
            if ($stmt->execute()) {
                $rows_affected = $stmt->affected_rows;
                $_SESSION['success'] = "Se eliminaron exitosamente $rows_affected rutas del día $dia.";
            } else {
                $_SESSION['error'] = "Error al eliminar las rutas del día $dia.";
            }
        } catch (Exception $e) {
            error_log('Error al eliminar día: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al eliminar las rutas del día: ' . $e->getMessage();
        }
        
        header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
        exit;
    }
}

// Sistema de enrutamiento moderno
if (basename($_SERVER['PHP_SELF']) === 'RouteControllerModern.php') {
    try {
        $controller = new RouteControllerModern();
        $accion = $_GET['accion'] ?? 'index';
        
        switch ($accion) {
            case 'index':
                $controller->index();
                break;
                
            case 'create':
                $controller->create();
                break;
                
            case 'edit':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $controller->edit($id);
                } else {
                    $_SESSION['error'] = 'ID de ruta no especificado para editar.';
                    header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                    exit;
                }
                break;
                
            case 'view':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $controller->view($id);
                } else {
                    $_SESSION['error'] = 'ID de ruta no especificado para ver.';
                    header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                    exit;
                }
                break;
                
            case 'delete':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $controller->delete($id);
                } else {
                    $_SESSION['error'] = 'ID de ruta no especificado para eliminar.';
                    header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                    exit;
                }
                break;
                
            case 'complete':
            case 'completar':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $controller->complete($id);
                } else {
                    $_SESSION['error'] = 'ID de ruta no especificado para completar.';
                    header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                    exit;
                }
                break;
                
            case 'completar_dia':
                $dia = $_GET['dia'] ?? null;
                if ($dia) {
                    $controller->completarDia($dia);
                } else {
                    $_SESSION['error'] = 'Día no especificado para completar.';
                    header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                    exit;
                }
                break;
                
            case 'eliminar_dia':
                $dia = $_GET['dia'] ?? null;
                if ($dia) {
                    $controller->eliminarDia($dia);
                } else {
                    $_SESSION['error'] = 'Día no especificado para eliminar.';
                    header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                    exit;
                }
                break;
                
            default:
                $_SESSION['error'] = 'Acción no válida: ' . htmlspecialchars($accion);
                header('Location: /RMIE/app/controllers/RouteControllerModern.php?accion=index');
                exit;
        }
    } catch (Exception $e) {
        error_log('Error en RouteControllerModern: ' . $e->getMessage());
        $_SESSION['error'] = 'Error interno del sistema. Por favor contacte al administrador.';
        header('Location: /RMIE/app/views/dashboard.php');
        exit;
    }
}
?>