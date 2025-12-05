<?php
// Iniciar sesión solo si no está ya iniciada
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
    require_once __DIR__ . '/../models/RouteSchedule.php';
    require_once __DIR__ . '/../models/Report.php';
    require_once __DIR__ . '/../models/Sale.php';
    require_once __DIR__ . '/../models/Client.php';
    require_once __DIR__ . '/../models/Local.php';
    require_once __DIR__ . '/../../config/db.php';
} catch (Exception $e) {
    error_log('Error cargando archivos en RouteController: ' . $e->getMessage());
    die('Error interno del sistema. Por favor contacte al administrador.');
}

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
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'dia' => ['type' => 'text', 'options' => ['max_length' => 20]]
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
        // Mapear filtro de día a columna real
        if (!empty($filtros['dia'])) {
            $filtros['dia_semana'] = $filtros['dia'];
        }
        
        // Obtener datos para selectores
        $ventas = Sale::getFiltered($conn);
        $available_clients = Route::getAvailableClients($conn);
        $available_locals = Route::getAvailableLocals($conn);

        // Días de la semana para planificación de rutas
        $dias_predeterminados = [
            'Lunes' => 'Lunes',
            'Martes' => 'Martes', 
            'Miercoles' => 'Miércoles',
            'Jueves' => 'Jueves',
            'Viernes' => 'Viernes',
            'Sabado' => 'Sábado'
        ];
        
        // Día actual para destacar en la vista
        $dia_actual = date('N'); // 1=Lunes, 2=Martes, etc.
        $nombres_dias = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $dia_hoy = $nombres_dias[$dia_actual] ?? 'Lunes';

        // Reset manual de asignaciones
        if (isset($_GET['reset_asignaciones'])) {
            if (isset($_SESSION['user'])) {
                RouteSchedule::resetUserAssignments($conn, intval($_SESSION['user']));
            }
            unset($_SESSION['asignaciones_clientes']);
            header('Location: /RMIE/rutas.php?accion=index');
            exit;
        }

        // Cargar asignaciones persistentes desde DB (por usuario)
        $asignaciones_clientes = [];
        $usuarioActual = isset($_SESSION['user']) ? intval($_SESSION['user']) : null;
        if ($usuarioActual) {
            $asignaciones_clientes = RouteSchedule::getAssignmentsByUser($conn, $usuarioActual);
        }

        // Procesar asignación de clientes por día (POST) persistiendo en DB
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clientes_dia']) && is_array($_POST['clientes_dia'])) {
            $nuevas = [];
            $clientesIdsDisponibles = array_column($available_clients, 'id_clientes');
            foreach ($dias_predeterminados as $dia) {
                if (isset($_POST['clientes_dia'][$dia])) {
                    $ids = array_map('intval', (array)$_POST['clientes_dia'][$dia]);
                    $validos = [];
                    foreach ($ids as $idc) {
                        if (in_array($idc, $clientesIdsDisponibles)) {
                            $validos[] = $idc;
                        }
                    }
                    if (!empty($validos)) {
                        $nuevas[$dia] = $validos;
                    }
                }
            }
            if ($usuarioActual) {
                RouteSchedule::saveAssignments($conn, $usuarioActual, $nuevas);
            }
            $_SESSION['success'] = 'Planificación de rutas guardada correctamente para la semana';
            header('Location: /RMIE/rutas.php?accion=index');
            exit;
        }

        // Mapa rápido id -> nombre para vista
        $mapa_clientes = [];
        foreach ($available_clients as $cli) {
            $mapa_clientes[$cli['id_clientes']] = $cli['nombre'];
        }

        // Obtener rutas con filtros existentes (mantiene compatibilidad)
        $rutas = Route::getAll($conn, $filtros);

        // Filtro por día seleccionado - para auxiliares y coordinadores mostrar todas las rutas
        $dia_seleccionado = $_GET['dia'] ?? $dia_hoy;
        $rol_usuario = $_SESSION['rol'] ?? '';
        
        // Solo filtrar por asignaciones si el usuario tiene asignaciones específicas
        if ($rol_usuario !== 'auxiliar' && $rol_usuario !== 'coordinador' && 
            isset($asignaciones_clientes[$dia_seleccionado]) && !empty($asignaciones_clientes[$dia_seleccionado])) {
            $idsFiltro = $asignaciones_clientes[$dia_seleccionado];
            $rutas = array_filter($rutas, function($ruta) use ($idsFiltro) {
                // Decodificar clientes asociados a la ruta (json) si existe
                if (!empty($ruta['id_clientes_json'])) {
                    $lista = json_decode($ruta['id_clientes_json'], true);
                    if (is_array($lista)) {
                        return count(array_intersect($lista, $idsFiltro)) > 0;
                    }
                }
                // Fallback: usar id_clientes directo
                if (isset($ruta['id_clientes']) && in_array(intval($ruta['id_clientes']), $idsFiltro)) {
                    return true;
                }
                return false;
            });
        } elseif (isset($_GET['dia']) && $_GET['dia'] !== 'todos' && 
                  $rol_usuario !== 'auxiliar' && $rol_usuario !== 'coordinador') {
            // Si se selecciona un día sin asignaciones, mostrar array vacío (excepto auxiliar/coordinador)
            $rutas = [];
        }

        // Asegurarse de que $rutas sea un array válido
        if (!is_array($rutas)) {
            $rutas = [];
        }
        
        // Estadísticas por día para la vista
        $estadisticas_dia = [];
        foreach ($dias_predeterminados as $key => $nombre) {
            $clientes_dia = $asignaciones_clientes[$key] ?? [];
            $estadisticas_dia[$key] = [
                'nombre' => $nombre,
                'total_clientes' => count($clientes_dia),
                'es_hoy' => ($key === $dia_hoy),
                'clientes_nombres' => []
            ];
            
            // Obtener nombres de clientes para este día
            foreach ($clientes_dia as $cliente_id) {
                if (isset($mapa_clientes[$cliente_id])) {
                    $estadisticas_dia[$key]['clientes_nombres'][] = $mapa_clientes[$cliente_id];
                }
            }
        }

        // Agrupar rutas por día para la vista
        $rutas_agrupadas = [];
        if (!empty($rutas)) {
            $rutas_por_dia = [];
            
            // Agrupar rutas por día
            foreach ($rutas as $ruta) {
                $dia = $ruta['dia_semana'] ?? 'Sin día';
                if (!isset($rutas_por_dia[$dia])) {
                    $rutas_por_dia[$dia] = [];
                }
                $rutas_por_dia[$dia][] = $ruta;
            }
            
            // Crear estructura para la vista
            foreach ($rutas_por_dia as $dia => $rutas_dia) {
                $clientes_unicos = [];
                $locales_unicos = [];
                foreach ($rutas_dia as $ruta) {
                    if (!empty($ruta['nombre_cliente'])) {
                        $clientes_unicos[$ruta['nombre_cliente']] = true;
                    }
                    if (!empty($ruta['local_nombre'])) {
                        $locales_unicos[$ruta['local_nombre']] = true;
                    }
                }
                
                $rutas_agrupadas[] = [
                    'dia' => $dia,
                    'rutas' => $rutas_dia,
                    'total_clientes' => count($clientes_unicos),
                    'total_locales' => count($locales_unicos),
                    'clientes_nombres' => array_keys($clientes_unicos)
                ];
            }
        }

        include __DIR__ . '/../views/rutas/index_modern.php';
    }
    
    // Método específico para obtener rutas del día actual
    public function rutasDelDia($dia = null) {
        global $conn;
        
        if (!$dia) {
            $dia_actual = date('N');
            $nombres_dias = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
            $dia = $nombres_dias[$dia_actual] ?? 'Lunes';
        }
        
        $usuarioActual = isset($_SESSION['user']) ? intval($_SESSION['user']) : null;
        if (!$usuarioActual) return [];
        
        $asignaciones = RouteSchedule::getAssignmentsByUser($conn, $usuarioActual);
        $clientesDelDia = $asignaciones[$dia] ?? [];
        
        if (empty($clientesDelDia)) return [];
        
        // Obtener rutas filtradas por los clientes del día
        $rutas = Route::getAll($conn);
        return array_filter($rutas, function($ruta) use ($clientesDelDia) {
            if (!empty($ruta['id_clientes_json'])) {
                $lista = json_decode($ruta['id_clientes_json'], true);
                if (is_array($lista)) {
                    return count(array_intersect($lista, $clientesDelDia)) > 0;
                }
            }
            return isset($ruta['id_clientes']) && in_array(intval($ruta['id_clientes']), $clientesDelDia);
        });
    }

    public function create() {
        global $conn;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Obtener datos del formulario
                $direccion = $_POST['direccion'] ?? '';
                $nombre_local = $_POST['nombre_local'] ?? '';
                $nombre_cliente = $_POST['nombre_cliente'] ?? '';
                $dia_semana = $_POST['dia_semana'] ?? '';
                $id_clientes = !empty($_POST['id_clientes']) ? intval($_POST['id_clientes']) : null;
                $estado = $_POST['estado'] ?? 'pendiente';
                $id_reportes = !empty($_POST['id_reportes']) ? intval($_POST['id_reportes']) : null;
                $id_ventas = !empty($_POST['id_ventas']) ? intval($_POST['id_ventas']) : null;
                
                // Validaciones básicas
                if (empty($direccion)) {
                    throw new Exception("La dirección es obligatoria.");
                }
                if (empty($dia_semana)) {
                    throw new Exception("El día de la semana es obligatorio.");
                }
                
                // Insertar en la base de datos
                $sql = "INSERT INTO rutas (direccion, nombre_local, nombre_cliente, id_clientes, id_reportes, id_ventas, estado, dia_semana) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssiiiis', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_reportes, $id_ventas, $estado, $dia_semana);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Ruta creada exitosamente.';
                    header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                    exit;
                } else {
                    throw new Exception("Error al guardar la ruta en la base de datos.");
                }
                
            } catch (Exception $e) {
                error_log('Error al crear ruta: ' . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        // Mostrar formulario de creación
        require_once __DIR__ . '/../views/rutas/create_modern.php';
        $clientes_sugeridos = $planificacion_usuario[$dia_sugerido] ?? [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Obtener día seleccionado para la ruta
                $dia_seleccionado = isset($_POST['dia_plan']) && array_key_exists($_POST['dia_plan'], $dias_predeterminados) ? $_POST['dia_plan'] : $dia_sugerido;
                
                // Validación de datos - arrays múltiples
                $id_locales_array = isset($_POST['id_locales']) ? array_map('intval', (array)$_POST['id_locales']) : [];
                $id_clientes_array = isset($_POST['id_clientes']) ? array_map('intval', (array)$_POST['id_clientes']) : [];
                $id_ventas_array = isset($_POST['id_ventas']) ? array_map('intval', (array)$_POST['id_ventas']) : [];
                $estado = isset($_POST['estado']) && in_array($_POST['estado'], ['activa', 'pendiente', 'completada']) ? $_POST['estado'] : 'activa';
                
                // Validaciones básicas
                if (empty($id_locales_array)) {
                    throw new Exception("Debe seleccionar al menos un local válido.");
                }
                if (empty($id_clientes_array)) {
                    throw new Exception("Debe seleccionar al menos un cliente válido.");
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

                // Crear una sola ruta con todos los valores incluyendo el día
                Route::createMultiple($conn, $direccion, $nombre_local, $nombre_cliente, $id_locales_array, $id_clientes_array, $id_ventas_array, $estado, $direcciones_array, $dia_seleccionado);

                $extra = $dia_seleccionado ? " para el día: " . $dias_predeterminados[$dia_seleccionado] : '';
                $_SESSION['success'] = "Ruta creada exitosamente con " . count($id_locales_array) . " local(es), " . count($id_clientes_array) . " cliente(s) y " . count($id_ventas_array) . " venta(s)" . $extra . ". Los clientes pueden asignarse a múltiples días.";
                
                // Redirección al nuevo punto de entrada
                header('Location: /RMIE/rutas.php?accion=index&dia=' . urlencode($dia_seleccionado));
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
        
        // DEBUG: Log del método y ID recibido
        error_log("DEBUG EDIT - Método: " . $_SERVER['REQUEST_METHOD'] . ", ID recibido: " . $id);
        error_log("DEBUG EDIT - GET params: " . json_encode($_GET));
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("DEBUG EDIT - POST params: " . json_encode($_POST));
        }
        
        // Limpiar cualquier error residual de sesión cuando se carga por primera vez
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['from_post'])) {
            unset($_SESSION['error']);
        }
        
        try {
            $route = Route::getById($conn, $id);
            if (!$route) {
                $_SESSION['error'] = "Ruta no encontrada.";
                header('Location: /RMIE/rutas.php?accion=index');
                exit;
            }

            // Obtener datos para los selects
            $available_clients = Route::getAvailableClients($conn);
            $available_locals = Route::getAvailableLocals($conn);
            $available_sales = Route::getAvailableSales($conn);
            
            // Planificación por días
            require_once __DIR__ . '/../models/RouteSchedule.php';
            $dias_predeterminados = [
                'Lunes' => 'Lunes',
                'Martes' => 'Martes', 
                'Miercoles' => 'Miércoles',
                'Jueves' => 'Jueves',
                'Viernes' => 'Viernes',
                'Sabado' => 'Sábado'
            ];
            
            $usuarioActual = isset($_SESSION['user']) ? intval($_SESSION['user']) : null;
            $planificacion_usuario = $usuarioActual ? RouteSchedule::getAssignmentsByUser($conn, $usuarioActual) : [];
            
            // Determinar el día asociado con esta ruta desde la base de datos
            $dia_ruta = Route::getDayFromRoute($conn, $id);
            
            // Si no se encuentra en la BD, usar la planificación del usuario como fallback
            if (!$dia_ruta && !empty($route['id_clientes'])) {
                foreach ($planificacion_usuario as $dia => $clientes) {
                    if (in_array($route['id_clientes'], $clientes)) {
                        $dia_ruta = $dia;
                        break;
                    }
                }
            }
            
            // Si la ruta no tiene día almacenado y encontramos uno, actualizarlo en la BD
            if ($dia_ruta && !Route::hasStoredDay($conn, $id)) {
                Route::updateDay($conn, $id, $dia_ruta);
            }
            
            // DEBUG: Log del día determinado
            error_log("DEBUG EDIT - Día determinado para ruta existente desde BD: " . ($dia_ruta ?? 'NULL'));
            
            // Día actual para referencia
            $dia_actual = date('N');
            $nombres_dias = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
            $dia_hoy = $nombres_dias[$dia_actual] ?? 'Lunes';

            // Solo procesar datos POST si es una petición POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Debug: Log de los datos recibidos
                error_log("DEBUG EDIT - POST recibido para ruta ID: $id");
                error_log("DEBUG EDIT - Datos POST: " . json_encode($_POST));
                
                // Obtener día seleccionado para la edición - usar día original si está disponible
                $dia_original = isset($_POST['dia_original']) ? $_POST['dia_original'] : $dia_ruta;
                $dia_seleccionado = isset($_POST['dia_plan']) && array_key_exists($_POST['dia_plan'], $dias_predeterminados) ? $_POST['dia_plan'] : $dia_original;
                
                error_log("DEBUG EDIT - Día original: " . ($dia_original ?? 'NULL'));
                error_log("DEBUG EDIT - Día seleccionado final: " . ($dia_seleccionado ?? 'NULL'));
                
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
                
                // Manejar estado
                $estado = isset($_POST['estado']) && in_array($_POST['estado'], ['activa', 'pendiente']) ? $_POST['estado'] : 'activa';
                
                error_log("DEBUG EDIT - Arrays procesados:");
                error_log("DEBUG EDIT - id_locales_array: " . json_encode($id_locales_array));
                error_log("DEBUG EDIT - id_clientes_array: " . json_encode($id_clientes_array));
                error_log("DEBUG EDIT - Cliente principal original: " . ($cliente_principal_original ?? 'NULL'));
                error_log("DEBUG EDIT - Estado: " . $estado);
                
                // Permitir clientes duplicados - no eliminar duplicados del array
                // Esto permite que un cliente esté en múltiples días si es necesario

                // Validaciones básicas
                if (empty($id_locales_array)) {
                    throw new Exception("Debe seleccionar al menos un local válido.");
                }
                if (empty($id_clientes_array)) {
                    throw new Exception("Debe seleccionar al menos un cliente válido.");
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

                // Usar la venta actual de la ruta (mantener la existente)
                $id_ventas_actual = isset($route['id_ventas']) ? [$route['id_ventas']] : [];
                
                // Mantener el cliente principal original para preservar el día
                $cliente_principal_original = isset($route['id_clientes']) ? intval($route['id_clientes']) : null;
                
                // Permitir múltiples clientes sin restricciones - no forzar el cliente original
                // El cliente principal original se mantiene solo para el campo id_clientes principal
                // Los clientes seleccionados se guardan en el array JSON sin restricciones
                
                // Actualizar la ruta con datos JSON manteniendo el cliente principal original y el día
                Route::updateMultiplePreservingDay($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_locales_array, $id_clientes_array, $id_ventas_actual, $estado, $direcciones_array, $cliente_principal_original, $dia_seleccionado);
                
                $extra_dia = $dia_seleccionado ? " (Día: " . $dias_predeterminados[$dia_seleccionado] . ")" : "";
                $_SESSION['success'] = "Ruta actualizada exitosamente con " . count($id_locales_array) . " local(es), " . count($id_clientes_array) . " cliente(s)" . $extra_dia . ". Los clientes pueden repetirse en múltiples días.";
                
                // Redirigir sin parámetro de día para evitar problemas de filtrado
                header('Location: /RMIE/rutas.php?accion=index');
                exit;
            }
            
            // Si llegamos aquí y es GET, mostrar el formulario
            include __DIR__ . '/../views/rutas/edit.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            // Si es POST, redirigir para evitar reenvío del formulario
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Location: /RMIE/rutas.php?accion=edit&id=' . $id . '&from_post=1');
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
            echo '<script>alert("El rol de coordinador no tiene permisos para eliminar registros por políticas de seguridad."); window.location.href = "/RMIE/rutas.php?accion=index";</script>';
            exit();
        }
        
        global $conn;
        try {
            $route = Route::getById($conn, $id);
            if (!$route) {
                throw new Exception("Ruta no encontrada.");
            }
            
            Route::delete($conn, $id);
            $_SESSION['success'] = 'Ruta eliminada exitosamente.';
            header('Location: /RMIE/rutas.php?accion=index');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar ruta: ' . $e->getMessage();
            header('Location: /RMIE/rutas.php?accion=index');
            exit;
        }
    }

    public function completeAndDelete($id) {
        global $conn;
        
        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de ruta inválido';
            header('Location: /RMIE/rutas.php?accion=index');
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
        
        header('Location: /RMIE/rutas.php?accion=index');
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
        
        header('Location: /RMIE/rutas.php?accion=index');
        exit;
    }
    
    public function view($id) {
        global $conn;
        
        try {
            $ruta = Route::getById($conn, $id);
            if (!$ruta) {
                $_SESSION['error'] = 'Ruta no encontrada.';
                header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                exit;
            }
            
            // Cargar datos adicionales si es necesario
            $ventas = [];
            $reportes = [];
            
            require_once __DIR__ . '/../views/rutas/view.php';
        } catch (Exception $e) {
            error_log('Error al ver ruta: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar los detalles de la ruta.';
            header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
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
        
        header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
        exit;
    }
    
    public function planificar() {
        global $conn;
        
        try {
            // Cargar datos necesarios para planificación
            $clientes = [];
            $locales = [];
            $asignaciones = [];
            
            // Si existen los modelos, cargar datos
            if (class_exists('Client')) {
                $clientes = Client::getAll($conn);
            }
            if (class_exists('Local')) {
                $locales = Local::getAll($conn);
            }
            
            require_once __DIR__ . '/../views/rutas/planificar.php';
        } catch (Exception $e) {
            error_log('Error en planificación: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar la planificación.';
            header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
            exit;
        }
    }
    
    public function optimizar() {
        global $conn;
        
        try {
            // Lógica básica de optimización de rutas
            $rutasOptimizadas = [];
            
            require_once __DIR__ . '/../views/rutas/optimizar.php';
        } catch (Exception $e) {
            error_log('Error en optimización: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al optimizar las rutas.';
            header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
            exit;
        }
    }
}

// Sistema de enrutamiento moderno para RouteController
if (basename($_SERVER['PHP_SELF']) === 'RouteController.php') {
    try {
        $controller = new RouteController();
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
                    header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                    exit;
                }
                break;
                
            case 'view':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $controller->view($id);
                } else {
                    $_SESSION['error'] = 'ID de ruta no especificado para ver.';
                    header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                    exit;
                }
                break;
                
            case 'delete':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $controller->delete($id);
                } else {
                    $_SESSION['error'] = 'ID de ruta no especificado para eliminar.';
                    header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                    exit;
                }
                break;
                
            case 'complete':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $controller->complete($id);
                } else {
                    $_SESSION['error'] = 'ID de ruta no especificado para completar.';
                    header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                    exit;
                }
                break;
                
            case 'planificar':
                $controller->planificar();
                break;
                
            case 'optimizar':
                $controller->optimizar();
                break;
                
            default:
                $_SESSION['error'] = 'Acción no válida: ' . htmlspecialchars($accion);
                header('Location: /RMIE/app/controllers/RouteController.php?accion=index');
                exit;
        }
    } catch (Exception $e) {
        error_log('Error en RouteController: ' . $e->getMessage());
        $_SESSION['error'] = 'Error interno del sistema. Por favor contacte al administrador.';
        header('Location: /RMIE/app/views/dashboard.php');
        exit;
    }
}
?>
