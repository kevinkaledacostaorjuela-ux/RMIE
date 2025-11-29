<?php
/**
 * API para obtener rutas del día específico - Integrada con sistema RMIE existente
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

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

// Incluir archivos del sistema existente
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/models/RouteSchedule.php';
require_once __DIR__ . '/../app/models/Route.php';

try {
    $usuarioActual = isset($_SESSION['user']) ? intval($_SESSION['user']) : null;
    
    // Si se solicita un día específico
    if (isset($_GET['dia'])) {
        $dia = $_GET['dia'];
        
        // Validar día
        $diasValidos = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
        if (!in_array($dia, $diasValidos)) {
            echo json_encode([
                'success' => false,
                'message' => 'Día inválido'
            ]);
            exit;
        }
        
        // Obtener asignaciones del día directamente de la nueva tabla
        $dia_formateado = ucfirst(strtolower($dia));
        
        $clientesDelDia = [];
        if ($usuarioActual) {
            $stmt = $conn->prepare("SELECT cliente_id, orden FROM ruta_clientes_semanales WHERE usuario_id = ? AND dia_semana = ? ORDER BY orden");
            if ($stmt) {
                $stmt->bind_param('is', $usuarioActual, $dia_formateado);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    $clientesDelDia[] = $row['cliente_id'];
                }
                $stmt->close();
            }
        }
        
        // Obtener rutas existentes para ese día
        $rutasExistentes = Route::getAll($conn);
        $rutasDelDia = [];
        
        foreach ($rutasExistentes as $ruta) {
            // Verificar si la ruta tiene clientes del día seleccionado
            if (!empty($ruta['id_clientes_json'])) {
                $clientesRuta = json_decode($ruta['id_clientes_json'], true);
                if (is_array($clientesRuta) && !empty(array_intersect($clientesRuta, $clientesDelDia))) {
                    $rutasDelDia[] = $ruta;
                }
            } elseif (isset($ruta['id_clientes']) && in_array(intval($ruta['id_clientes']), $clientesDelDia)) {
                $rutasDelDia[] = $ruta;
            }
        }
        
        echo json_encode([
            'success' => true,
            'dia' => $dia,
            'clientes_asignados' => $clientesDelDia,
            'rutas_existentes' => $rutasDelDia,
            'total_clientes' => count($clientesDelDia),
            'total_rutas' => count($rutasDelDia)
        ]);
        exit;
    }
    
    // Si se solicitan todas las asignaciones
    if (isset($_GET['asignaciones'])) {
        $asignaciones = RouteSchedule::getAssignmentsByUser($conn, $usuarioActual);
        
        echo json_encode([
            'success' => true,
            'asignaciones' => $asignaciones,
            'usuario' => $usuarioActual
        ]);
        exit;
    }
    
    // Si se solicitan clientes disponibles
    if (isset($_GET['clientes'])) {
        $clientes = Route::getAvailableClients($conn);
        
        echo json_encode([
            'success' => true,
            'clientes' => $clientes
        ]);
        exit;
    }
    
    // Por defecto, obtener información del día actual
    $diaActual = date('N'); // 1=Lunes, 2=Martes, etc.
    $nombres_dias = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    $diaHoy = $nombres_dias[$diaActual] ?? 'Lunes';
    
    $asignaciones = RouteSchedule::getAssignmentsByUser($conn, $usuarioActual);
    $clientesHoy = $asignaciones[$diaHoy] ?? [];
    
    echo json_encode([
        'success' => true,
        'dia_actual' => $diaHoy,
        'clientes_hoy' => $clientesHoy,
        'todas_asignaciones' => $asignaciones
    ]);
    
} catch (Exception $e) {
    error_log("Error en get_route_info.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>