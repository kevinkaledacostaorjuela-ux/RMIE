<?php
/**
 * API Endpoint - Crear rutas automáticamente desde planificación semanal
 * Convierte las asignaciones semanales en rutas reales en la tabla rutas
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/db.php';

try {
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

    $planificacion = $input['planificacion'];
    $usuario_id = 1; // Usuario de prueba
    
    // Iniciar transacción
    $conn->begin_transaction();

    try {
        $rutas_creadas = 0;
        
        // Preparar statement para insertar rutas
        $insert_ruta_sql = "INSERT INTO rutas (direccion, nombre_local, nombre_cliente, id_clientes, id_clientes_json, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_ruta_sql);

        foreach ($planificacion as $dia => $clientes_data) {
            if (empty($clientes_data) || !is_array($clientes_data)) {
                continue;
            }

            // Extraer información de los clientes desde los objetos
            $clientes_ids = [];
            $nombres_clientes = [];
            $locales_info = [];
            
            foreach ($clientes_data as $cliente_obj) {
                if (isset($cliente_obj['id_clientes'])) {
                    $clientes_ids[] = (int)$cliente_obj['id_clientes'];
                    $nombres_clientes[] = $cliente_obj['nombre'] ?? 'Cliente';
                    
                    // Información del local
                    if (isset($cliente_obj['local'])) {
                        $locales_info[] = $cliente_obj['local']['nombre'] ?? 'Local';
                    } else {
                        $locales_info[] = 'Local Principal';
                    }
                }
            }

            if (empty($clientes_ids)) {
                continue;
            }

            // Crear la ruta para el día
            $direccion_ruta = "Ruta planificada para $dia - " . date('d/m/Y');
            $nombre_local_ruta = implode(', ', array_slice($locales_info, 0, 2)) . (count($locales_info) > 2 ? ' y ' . (count($locales_info) - 2) . ' más' : '');
            $nombre_cliente_ruta = implode(', ', array_slice($nombres_clientes, 0, 2)) . (count($nombres_clientes) > 2 ? ' y ' . (count($nombres_clientes) - 2) . ' más' : '');
            $primer_cliente_id = $clientes_ids[0]; // Primer cliente como principal
            $clientes_json = json_encode($clientes_ids);
            $estado = 'activa';

            $insert_stmt->bind_param('sssiss', 
                $direccion_ruta, 
                $nombre_local_ruta, 
                $nombre_cliente_ruta, 
                $primer_cliente_id,
                $clientes_json, 
                $estado
            );

            if ($insert_stmt->execute()) {
                $rutas_creadas++;
            }
        }

        $insert_stmt->close();

        // Confirmar transacción
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Rutas creadas exitosamente desde planificación',
            'rutas_creadas' => $rutas_creadas,
            'planificacion_procesada' => $planificacion
        ]);

    } catch (Exception $e) {
        // Rollback en caso de error
        $conn->rollback();
        throw $e;
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear rutas: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>