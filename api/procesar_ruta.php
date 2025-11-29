<?php
/**
 * procesar_ruta.php - Procesa la creación o modificación de rutas
 * Determina automáticamente si crear o modificar según si ruta_id está vacío o no
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido. Use POST.'
    ]);
    exit;
}

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no autenticado'
    ]);
    exit;
}

// Incluir configuración de base de datos y controlador
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/controllers/RouteControllerWeekly.php';

try {
    $routeController = new RouteControllerWeekly($conn);
    
    // Validar datos requeridos
    $dia = $_POST['dia'] ?? '';
    $nombreRuta = trim($_POST['nombre_ruta'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = $_POST['estado'] ?? 'activa';
    $clientesStr = trim($_POST['clientes'] ?? '');
    $rutaId = $_POST['ruta_id'] ?? '';
    
    // Validaciones básicas
    if (empty($dia)) {
        echo json_encode([
            'success' => false,
            'message' => 'El día de la semana es requerido'
        ]);
        exit;
    }
    
    if (empty($nombreRuta)) {
        echo json_encode([
            'success' => false,
            'message' => 'El nombre de la ruta es requerido'
        ]);
        exit;
    }
    
    // Validar día
    $diasValidos = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    if (!in_array($dia, $diasValidos)) {
        echo json_encode([
            'success' => false,
            'message' => 'Día inválido. Debe ser: ' . implode(', ', $diasValidos)
        ]);
        exit;
    }
    
    // Procesar clientes (convertir string a array)
    $clientes = [];
    if (!empty($clientesStr)) {
        $clientesArray = explode(',', $clientesStr);
        foreach ($clientesArray as $cliente) {
            $clienteId = trim($cliente);
            if (is_numeric($clienteId) && $clienteId > 0) {
                $clientes[] = (int)$clienteId;
            }
        }
    }
    
    // Preparar datos de la ruta
    $rutaData = [
        'nombre_ruta' => $nombreRuta,
        'descripcion' => $descripcion,
        'estado' => $estado
    ];
    
    // Determinar si es creación o modificación según ruta_id
    if (empty($rutaId)) {
        // CREAR NUEVA RUTA
        try {
            $nuevoId = $routeController->crearRuta($dia, $rutaData, $clientes);
            
            echo json_encode([
                'success' => true,
                'message' => 'Ruta creada exitosamente',
                'ruta_id' => $nuevoId,
                'accion' => 'crear'
            ]);
            
        } catch (Exception $e) {
            // Si ya existe una ruta para ese día, intentar modificarla
            if (strpos($e->getMessage(), 'Ya existe una ruta') !== false) {
                // Obtener la ruta existente
                $rutaExistente = $routeController->getRutaPorDia($dia);
                
                if ($rutaExistente) {
                    // Modificar la ruta existente
                    $resultado = $routeController->modificarRuta($rutaExistente['id'], $dia, $rutaData, $clientes);
                    
                    if ($resultado) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Ruta actualizada exitosamente (ya existía una ruta para este día)',
                            'ruta_id' => $rutaExistente['id'],
                            'accion' => 'actualizar_automatico'
                        ]);
                    } else {
                        throw new Exception('Error actualizando la ruta existente');
                    }
                } else {
                    throw $e;
                }
            } else {
                throw $e;
            }
        }
        
    } else {
        // MODIFICAR RUTA EXISTENTE
        $rutaId = (int)$rutaId;
        
        $resultado = $routeController->modificarRuta($rutaId, $dia, $rutaData, $clientes);
        
        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Ruta actualizada exitosamente',
                'ruta_id' => $rutaId,
                'accion' => 'actualizar'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error actualizando la ruta'
            ]);
        }
    }
    
} catch (Exception $e) {
    error_log("Error en procesar_ruta.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Error procesando la ruta: ' . $e->getMessage()
    ]);
}
?>