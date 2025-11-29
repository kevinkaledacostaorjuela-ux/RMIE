<?php
/**
 * API Endpoint - Guardar planificación semanal
 * Guarda las asignaciones de clientes por día de la semana
 */

// Iniciar sesión solo si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/db.php';

try {
    // Verificar autenticación
    if (!isset($_SESSION['user'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no autenticado'
        ]);
        exit;
    }

    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
        exit;
    }

    // Obtener datos JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['planificacion'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos de planificación no válidos'
        ]);
        exit;
    }

    $usuario_id = (int)$_SESSION['user'];
    $planificacion = $input['planificacion'];
    
    $dias_validos = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Limpiar asignaciones existentes del usuario
        $delete_sql = "DELETE FROM ruta_clientes_semanales WHERE usuario_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param('i', $usuario_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        // Preparar insert para nuevas asignaciones
        $insert_sql = "INSERT INTO ruta_clientes_semanales (usuario_id, dia_semana, cliente_id, orden) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);

        $total_asignaciones = 0;

        // Insertar nuevas asignaciones
        foreach ($planificacion as $dia => $clientes) {
            if (!in_array($dia, $dias_validos)) continue;
            
            if (is_array($clientes) && !empty($clientes)) {
                foreach ($clientes as $index => $cliente_id) {
                    $cliente_id = (int)$cliente_id;
                    $orden = $index + 1;
                    
                    if ($cliente_id > 0) {
                        $insert_stmt->bind_param('isii', $usuario_id, $dia, $cliente_id, $orden);
                        $insert_stmt->execute();
                        $total_asignaciones++;
                    }
                }
            }
        }

        $insert_stmt->close();

        // Confirmar transacción
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Planificación guardada exitosamente',
            'total_asignaciones' => $total_asignaciones
        ]);

    } catch (Exception $e) {
        // Rollback en caso de error
        $conn->rollback();
        throw $e;
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar la planificación: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>