<?php
/**
 * Test API Endpoint - Información de rutas del día
 * Versión simplificada para pruebas sin autenticación
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Route.php';
require_once __DIR__ . '/../models/RouteSchedule.php';

try {
    // Validar parámetro día
    $dia = $_GET['dia'] ?? '';
    
    $dias_validos = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes'];
    if (!in_array(strtolower($dia), $dias_validos)) {
        echo json_encode([
            'success' => false,
            'message' => 'Día inválido. Debe ser: ' . implode(', ', $dias_validos)
        ]);
        exit;
    }

    // Datos de prueba - simular usuario ID 1
    $user_id = 1;
    
    // Obtener asignaciones del día directamente de la nueva tabla
    $dia_formateado = ucfirst(strtolower($dia));
    $stmt = $conn->prepare("SELECT cliente_id, orden FROM ruta_clientes_semanales WHERE usuario_id = ? AND dia_semana = ? ORDER BY orden");
    $stmt->bind_param('is', $user_id, $dia_formateado);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $asignaciones = [];
    while ($row = $result->fetch_assoc()) {
        $asignaciones[] = $row['cliente_id'];
    }
    $stmt->close();
    
    // Obtener todas las rutas para el día
    $rutas = Route::getAll($conn);
    $ruta_del_dia = null;
    
    // Buscar si hay una ruta específica para este día
    foreach ($rutas as $ruta) {
        if (isset($ruta['dia_planificado']) && strtolower($ruta['dia_planificado']) === strtolower($dia)) {
            $ruta_del_dia = $ruta;
            break;
        }
    }

    // Procesar clientes sugeridos
    $clientes_sugeridos = [];
    if (!empty($asignaciones)) {
        foreach ($asignaciones as $cliente_id) {
            // Obtener nombre del cliente desde la base de datos
            $stmt = $conn->prepare("SELECT nombre FROM clientes WHERE id_clientes = ?");
            $stmt->bind_param('i', $cliente_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $cliente = $result->fetch_assoc();
            
            $clientes_sugeridos[] = [
                'id' => $cliente_id,
                'nombre' => $cliente ? $cliente['nombre'] : 'Cliente #' . $cliente_id
            ];
            
            $stmt->close();
        }
    }

    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'data' => [
            'dia' => $dia,
            'user_id' => $user_id,
            'ruta_actual' => $ruta_del_dia ? [
                'id' => $ruta_del_dia['id'],
                'nombre' => $ruta_del_dia['nombre'],
                'descripcion' => $ruta_del_dia['descripcion'] ?? ''
            ] : null,
            'clientes_sugeridos' => $clientes_sugeridos,
            'total_asignaciones' => count($asignaciones)
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>