<?php
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Provider.php';
require_once __DIR__ . '/../../config/db.php';

class AlertController {
    public function index() {
        // Iniciar sesión si es necesario
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        global $conn;
        
        // LIMPIAR AUTOMÁTICAMENTE ALERTAS VENCIDAS (cuya fecha de caducidad ya pasó)
        $fecha_hoy = date('Y-m-d');
        
        // Obtener alertas a vencer en 3 días para notificación previa
        $fecha_limite = date('Y-m-d', strtotime('+3 days'));
        $sql_proximas = "SELECT id_alertas, id_productos, fecha_caducidad FROM alertas 
                        WHERE fecha_caducidad IS NOT NULL 
                        AND DATE(fecha_caducidad) <= DATE(?) 
                        AND DATE(fecha_caducidad) > DATE(?)
                        AND fecha_caducidad != '0000-00-00'";
        
        $stmt = $conn->prepare($sql_proximas);
        if ($stmt) {
            $stmt->bind_param('ss', $fecha_limite, $fecha_hoy);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Crear notificaciones para alertas próximas a vencer
            while ($row = $result->fetch_assoc()) {
                $dias_restantes = (strtotime($row['fecha_caducidad']) - strtotime($fecha_hoy)) / (60 * 60 * 24);
                $mensaje = "La alerta vence en " . ceil($dias_restantes) . " días";
                Alert::createNotification($conn, $row['id_alertas'], 'vencimiento_proximo', $mensaje);
            }
            $stmt->close();
        }
        
        // Mover alertas vencidas a papelera en lugar de eliminarlas
        $sql_vencidas = "SELECT id_alertas FROM alertas 
                        WHERE fecha_caducidad IS NOT NULL 
                        AND fecha_caducidad != '0000-00-00' 
                        AND DATE(fecha_caducidad) < DATE(?)";
        
        $stmt = $conn->prepare($sql_vencidas);
        if ($stmt) {
            $stmt->bind_param('s', $fecha_hoy);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Mover a papelera
            while ($row = $result->fetch_assoc()) {
                Alert::moveToTrash($conn, $row['id_alertas'], 'Fecha de caducidad vencida - Eliminación automática');
            }
            $stmt->close();
        }
        
        // Limpiar errores previos cuando se accede a index
        if (isset($_SESSION['error']) && strpos($_SESSION['error'], 'ID de alerta inválido') !== false) {
            unset($_SESSION['error']);
        }
        
        // Obtener productos para el filtro
        $productos = Product::getAll($conn);
          // Recoger filtros
        $filtros = [
            'tipo' => $_GET['tipo'] ?? '',
            'prioridad' => $_GET['prioridad'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'fecha' => $_GET['fecha'] ?? '',
            'producto' => $_GET['producto'] ?? '',
            'nombre_producto' => $_GET['nombre_producto'] ?? '',
            'cantidad_min' => $_GET['cantidad_min'] ?? '',
            'cantidad_max' => $_GET['cantidad_max'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];

        // Interpretar filtros por estado en rangos de fecha
        $hoy = date('Y-m-d');
        if (!empty($filtros['estado'])) {
            switch ($filtros['estado']) {
                case 'Próxima':
                    // Próximas dentro de 7 días
                    $filtros['fecha_desde'] = $hoy;
                    $filtros['fecha_hasta'] = date('Y-m-d', strtotime('+7 days'));
                    break;
                case 'Vencida':
                    // Vencidas antes de hoy
                    $filtros['fecha_hasta'] = date('Y-m-d', strtotime('-1 day'));
                    $filtros['fecha_desde'] = '';
                    break;
                case 'Crítica':
                    // Críticas dentro de 7 días (igual que próximas pero podemos forzar <=7)
                    $filtros['fecha_desde'] = $hoy;
                    $filtros['fecha_hasta'] = date('Y-m-d', strtotime('+7 days'));
                    break;
                case 'Normal':
                    // Más allá de 30 días
                    $filtros['fecha_desde'] = date('Y-m-d', strtotime('+31 days'));
                    $filtros['fecha_hasta'] = '';
                    break;
                case 'Activo':
                default:
                    // Sin cambio adicional
                    break;
            }
        }
        
        // Obtener alertas filtradas (registradas en BD)
        $alertas_db = Alert::getFiltered($conn, $filtros);

        // Generar alertas automáticas a partir de los productos (stock y caducidad)
        $alertas_auto = [];
        $umbral_stock_default = 5; // umbral configurable para considerar stock bajo
        $fecha_actual = date('Y-m-d');

        foreach ($productos as $p) {
            // Productos retornados por Product::getAll son objetos Product
            $stock = isset($p->stock) ? (int)$p->stock : 0;
            $fc = $p->fecha_caducidad ?? null;
            $nombre_producto = $p->nombre_producto ?? $p->nombre ?? '';
            $id_producto = $p->id_productos ?? null;

            // Alerta automática por stock bajo
            if ($stock <= $umbral_stock_default) {
                $prioridad_stock = ($stock <= 2) ? 'Alta' : (($stock <= $umbral_stock_default) ? 'Media' : 'Baja');
                $estado_stock = ($stock <= 0) ? 'Vencida' : 'Activo';
                $alertas_auto[] = [
                    'id_alertas' => null,
                    'id_productos' => $id_producto,
                    'producto_nombre' => $nombre_producto,
                    'tipo_alerta' => 'stock_bajo',
                    'cantidad_minima' => $umbral_stock_default,
                    'fecha_caducidad' => $fc,
                    'prioridad' => $prioridad_stock,
                    'estado' => $estado_stock,
                    'id_proveedores' => $p->id_proveedores ?? null,
                    'proveedor_nombre' => $p->proveedor_nombre ?? null
                ];
            }

            // Alerta automática por caducidad
            if (!empty($fc) && $fc !== '0000-00-00') {
                $dias = (strtotime($fc) - strtotime($fecha_actual)) / (60*60*24);
                if ($dias < 0) {
                    $estado = 'Vencida';
                    $prioridad = 'Alta';
                } elseif ($dias <= 7) {
                    $estado = 'Crítica';
                    $prioridad = 'Alta';
                } elseif ($dias <= 30) {
                    $estado = 'Próxima';
                    $prioridad = 'Media';
                } else {
                    $estado = 'Normal';
                    $prioridad = 'Baja';
                }
                $alertas_auto[] = [
                    'id_alertas' => null,
                    'id_productos' => $id_producto,
                    'producto_nombre' => $nombre_producto,
                    'tipo_alerta' => 'expiration',
                    'cantidad_minima' => null,
                    'fecha_caducidad' => $fc,
                    'prioridad' => $prioridad,
                    'estado' => $estado,
                    'id_proveedores' => $p->id_proveedores ?? null,
                    'proveedor_nombre' => $p->proveedor_nombre ?? null
                ];
            }
        }

        // Unir alertas registradas en BD con las automáticas
        // Asignar IDs temporales a alertas automáticas (negativas para diferenciarlas)
        $contador_auto = 1;
        foreach ($alertas_auto as &$alerta) {
            if ($alerta['id_alertas'] === null) {
                $alerta['id_alertas'] = -$contador_auto; // IDs negativos para alertas automáticas
                $contador_auto++;
            }
        }
        unset($alerta);
        
        $alertas = array_values(array_merge(is_array($alertas_db) ? $alertas_db : [], $alertas_auto));

        // Si se solicitó filtrar por estado, aplicar el filtro sobre el conjunto combinado
        if (!empty($filtros['estado']) && is_array($alertas)) {
            $estadoFiltro = $filtros['estado'];
            $fecha_actual_calc = date('Y-m-d');
            $alertas = array_filter($alertas, function($a) use ($estadoFiltro, $fecha_actual_calc){
                $fc = $a['fecha_caducidad'] ?? null;
                if (!$fc) {
                    // Si no tiene fecha, dejar pasar sólo si se filtra por 'Activo' o no hay filtro estricto
                    return $estadoFiltro === 'Activo' || $estadoFiltro === '';
                }
                $dias = (strtotime($fc) - strtotime($fecha_actual_calc)) / (60*60*24);
                if ($estadoFiltro === 'Vencida') return ($dias < 0);
                if ($estadoFiltro === 'Crítica') return ($dias >= 0 && $dias <= 7);
                if ($estadoFiltro === 'Próxima') return ($dias > 7 && $dias <= 30);
                if ($estadoFiltro === 'Normal') return ($dias > 30);
                if ($estadoFiltro === 'Activo') return true; // sin restricción adicional
                return true;
            });
            // Reindexar array
            $alertas = array_values($alertas);
        }

        // Calcular estadísticas sobre el conjunto combinado (BD + automáticas)
        $total_alertas = count($alertas);
        $alertas_proximas = 0;
        $alertas_vencidas = 0;
        $fecha_actual = date('Y-m-d');

        foreach ($alertas as $alerta) {
            $fc = $alerta['fecha_caducidad'] ?? null;
            if (!$fc) continue;
            if ($fc < $fecha_actual) {
                $alertas_vencidas++;
            } elseif ($fc <= date('Y-m-d', strtotime('+30 days'))) {
                $alertas_proximas++;
            }
        }

        $estadisticas = [
            'total' => $total_alertas,
            'proximas' => $alertas_proximas,
            'vencidas' => $alertas_vencidas
        ];

        // Flags para ventana emergente
        $hay_stock_bajo = false;
        $hay_vencidas = $alertas_vencidas > 0;
        // Considerar próximas (por defecto dentro de 7 días)
        $umbral_proximas_dias = 7;
        $hay_proximas = false;
        foreach ($alertas as $alerta) {
            $fc = $alerta['fecha_caducidad'] ?? null;
            if ($fc) {
                $dias = (strtotime($fc) - strtotime($fecha_actual)) / (60*60*24);
                if ($dias >= 0 && $dias <= $umbral_proximas_dias) { $hay_proximas = true; break; }
            }
        }
        foreach ($alertas as $alerta) {
            $tipo = $alerta['tipo_alerta'] ?? '';
            if ($tipo === 'stock' || $tipo === 'stock_bajo') {
                $hay_stock_bajo = true; break;
            }
        }
        
        // Obtener tipos dinámicamente de las alertas registradas
        $tipos_disponibles = array();
        
        $sql_tipos = "SELECT DISTINCT tipo_alerta FROM alertas WHERE tipo_alerta IS NOT NULL AND tipo_alerta != ''";
        $result = $conn->query($sql_tipos);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tipos_disponibles[] = $row['tipo_alerta'];
            }
        }
        
        // Para prioridad y estado, usamos valores fijos ya que no existen en la BD
        $prioridades_disponibles = array('Alta', 'Media', 'Baja');
        $estados_disponibles = array('Activo', 'Vencida', 'Crítica', 'Próxima', 'Normal');
        
        include __DIR__ . '/../views/alertas/index.php';
    }

    public function create() {
        // Iniciar sesión si no está activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        global $conn;
        $productos = Product::getAll($conn);
        $proveedores = Provider::getAll($conn);
        $mensaje = '';
        // Permitir preselección del tipo de alerta desde la URL (stock | expiration)
        $preselected_tipo = isset($_GET['tipo']) && in_array($_GET['tipo'], ['stock','expiration'])
            ? $_GET['tipo']
            : 'stock';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_producto = isset($_POST['id_productos']) ? (int)$_POST['id_productos'] : 0;
            $cantidad_minima = isset($_POST['cantidad_minima']) ? (int)$_POST['cantidad_minima'] : 0;
            $fecha_caducidad = $_POST['fecha_caducidad'] ?? '';
            $id_proveedor = isset($_POST['id_proveedores']) ? (int)$_POST['id_proveedores'] : 0;
            $tipo_alerta = $_POST['alert_type'] ?? 'stock'; // 'stock' o 'expiration'
            $estado_alerta = $_POST['estado_alerta'] ?? 'Activo'; // Nuevo campo de estado

            // Validación diferenciada según tipo de alerta
            $campos_validos = false;
            
            if ($tipo_alerta === 'stock') {
                // Para Stock Bajo: producto, cantidad_minima y proveedor son obligatorios
                // fecha_caducidad NO es obligatoria
                $campos_validos = ($id_producto && $cantidad_minima && $id_proveedor);
            } else {
                // Para Vencimiento: producto, fecha_caducidad y proveedor son obligatorios
                // cantidad_minima NO es obligatoria
                $campos_validos = ($id_producto && $fecha_caducidad && $id_proveedor);
            }

            if ($campos_validos) {
                // Validar existencia en BD
                $prod = Product::getById($conn, $id_producto);
                $prov = Provider::getById($conn, $id_proveedor);
                if (!$prod) {
                    $_SESSION['error'] = 'Producto no válido.';
                } elseif (!$prov) {
                    $_SESSION['error'] = 'Proveedor no válido.';
                } else {
                    try {
                        $resultado = Alert::create($conn, $id_producto, $cantidad_minima, $fecha_caducidad, $id_proveedor, $tipo_alerta, $estado_alerta);
                        if ($resultado) {
                            $_SESSION['success'] = '¡Alerta creada exitosamente!';
                            header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
                            exit();
                        } else {
                            $_SESSION['error'] = 'Error al crear la alerta.';
                        }
                    } catch (Throwable $e) {
                        $_SESSION['error'] = 'Error al crear la alerta: ' . $e->getMessage();
                    }
                }
            } else {
                $_SESSION['error'] = 'Por favor, completa todos los campos obligatorios.';
            }
        }
        // Pasar variable de preselección a la vista
        $alert_type_default = $preselected_tipo;
        include __DIR__ . '/../views/alertas/create.php';
    }

    public function edit() {
        // Iniciar sesión si no está activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        global $conn;
        $id = $_GET['id'] ?? 0;
        $errors = [];
        $success = '';
        $debug_info = '';
        
        $alerta = Alert::getById($conn, $id);
        if (!$alerta) {
            header('Location: /RMIE/app/controllers/AlertController.php?action=index');
            exit();
        }
        $productos = Product::getAll($conn);
        $proveedores = Provider::getAll($conn);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug: mostrar qué se recibió
            $debug_info = "POST recibido. Datos: " . json_encode($_POST);
            
            $alerta['id_productos'] = isset($_POST['id_productos']) ? (int)$_POST['id_productos'] : 0;
            $alerta['cantidad_minima'] = isset($_POST['cantidad_minima']) ? (int)$_POST['cantidad_minima'] : 0;
            $alerta['fecha_caducidad'] = $_POST['fecha_caducidad'] ?? '';
            $alerta['id_proveedores'] = isset($_POST['id_proveedores']) ? (int)$_POST['id_proveedores'] : 0;
            $alerta['estado'] = $_POST['estado_alerta'] ?? 'Activo';
            
            $debug_info .= "\n Estado en POST: " . (isset($_POST['estado_alerta']) ? $_POST['estado_alerta'] : 'NO ENVIADO');

            if (empty($alerta['id_productos'])) $errors[] = 'El producto es obligatorio';
            if (empty($alerta['cantidad_minima']) || $alerta['cantidad_minima'] < 0) $errors[] = 'La cantidad mínima debe ser mayor o igual a 0';
            if (empty($alerta['fecha_caducidad'])) $errors[] = 'La fecha de caducidad es obligatoria';
            if (empty($alerta['id_proveedores'])) $errors[] = 'El proveedor es obligatorio';

            if (empty($errors)) {
                // Validar existencia en BD
                if (!Product::getById($conn, $alerta['id_productos'])) {
                    $errors[] = 'Producto no válido';
                }
                if (!Provider::getById($conn, $alerta['id_proveedores'])) {
                    $errors[] = 'Proveedor no válido';
                }
            }

            if (empty($errors)) {
                $result = Alert::update($conn, (int)$id, $alerta);
                if ($result) {
                    $_SESSION['success'] = '¡Alerta actualizada exitosamente!';
                    // Agregar log para debug
                    error_log("Alert actualizada: ID=" . $id . ", Estado=" . $alerta['estado']);
                    header('Location: /RMIE/app/controllers/AlertController.php?accion=index&success=updated');
                    exit();
                } else {
                    $errors[] = 'Error al actualizar la alerta: ' . $conn->error;
                    error_log("Error al actualizar alerta: " . $conn->error);
                }
            }
        }
        include __DIR__ . '/../views/alertas/edit.php';
    }

    public function delete() {
        // Verificar sesión activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para realizar esta acción';
            header('Location: /RMIE/index.php');
            exit();
        }
        
        // Verificar si el rol es coordinador y restringir eliminación
        if ($_SESSION['rol'] === 'coordinador') {
            $_SESSION['error'] = 'El rol de coordinador no tiene permisos para eliminar alertas por políticas de seguridad';
            header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
            exit();
        }
        
        global $conn;
        $id = $_GET['id'] ?? null;
        
        // Si no hay POST, mostrar página de confirmación
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Validar que el id sea un número válido
            if (!is_numeric($id) || $id <= 0) {
                $_SESSION['error'] = 'ID de alerta inválido';
                header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
                exit();
            }
            
            // Verificar que la alerta existe
            $alerta = Alert::getById($conn, $id);
            if (!$alerta) {
                $_SESSION['error'] = 'La alerta no existe';
                header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
                exit();
            }
            
            // Mostrar página de confirmación
            $errors = [];
            $success = '';
            include __DIR__ . '/../views/alertas/delete.php';
            return;
        }
        
        // Procesar la eliminación si viene por POST
        if (isset($_POST['confirmar_eliminar'])) {
            // Validar ID nuevamente
            if (!is_numeric($id) || $id <= 0) {
                $_SESSION['error'] = 'ID de alerta inválido';
                header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
                exit();
            }
            
            $result = Alert::moveToTrash($conn, $id, 'Usuario eliminó manualmente');
            if ($result) {
                $_SESSION['success'] = 'Alerta eliminada exitosamente y movida a papelera';
                header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
                exit();
            } else {
                $_SESSION['error'] = 'Error al eliminar la alerta: ' . $conn->error;
                header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
                exit();
            }
        }
        
        // Si llega aquí, redirigir a index
        header('Location: /RMIE/app/controllers/AlertController.php?accion=index');
        exit();
    }

    public function trash() {
        // Iniciar sesión si es necesario
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        global $conn;
        
        // Obtener alertas en papelera
        $alertas_papelera = Alert::getTrash($conn);
        
        // Si se solicita restaurar
        if (isset($_GET['restaurar'])) {
            $id_papelera = (int)$_GET['restaurar'];
            if (Alert::restoreFromTrash($conn, $id_papelera)) {
                $_SESSION['success'] = 'Alerta restaurada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al restaurar la alerta';
            }
            header('Location: /RMIE/app/controllers/AlertController.php?accion=trash');
            exit();
        }
        
        include __DIR__ . '/../views/alertas/trash.php';
    }

    public function notifications() {
        // Iniciar sesión si es necesario
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        global $conn;
        
        // Obtener notificaciones no leídas
        $notificaciones = Alert::getUnreadNotifications($conn);
        
        // Marcar como leídas si se solicita
        if (isset($_GET['marcar_leida'])) {
            $id_notificacion = (int)$_GET['marcar_leida'];
            Alert::markNotificationAsRead($conn, $id_notificacion);
            header('Location: /RMIE/app/controllers/AlertController.php?accion=notifications');
            exit();
        }
        
        include __DIR__ . '/../views/alertas/notifications.php';
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
            case 'trash':
                $this->trash();
                break;
            case 'notifications':
                $this->notifications();
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
