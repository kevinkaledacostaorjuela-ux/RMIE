<?php
/**
 * API Endpoint - Obtener rutas recientes
 * Devuelve las rutas más recientes creadas (últimas 10)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/db.php';

try {
    // Obtener las rutas más recientes (últimas 10)
    $sql = "SELECT 
                id_ruta,
                direccion,
                nombre_local,
                nombre_cliente,
                id_clientes,
                id_clientes_json,
                estado
            FROM rutas 
            ORDER BY id_ruta DESC 
            LIMIT 10";
    
    $result = $conn->query($sql);
    
    $rutas = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Procesar clientes JSON si existe
            $clientes_info = '';
            if (!empty($row['id_clientes_json'])) {
                $clientes_ids = json_decode($row['id_clientes_json'], true);
                if (is_array($clientes_ids) && count($clientes_ids) > 1) {
                    $clientes_info = $row['nombre_cliente'] . ' (+' . (count($clientes_ids) - 1) . ' más)';
                } else {
                    $clientes_info = $row['nombre_cliente'];
                }
            } else {
                $clientes_info = $row['nombre_cliente'];
            }

            $rutas[] = [
                'id_ruta' => (int)$row['id_ruta'],
                'direccion' => $row['direccion'] ?: 'Sin dirección',
                'nombre_local' => $row['nombre_local'] ?: 'Sin local',
                'nombre_cliente' => $clientes_info,
                'id_clientes' => (int)$row['id_clientes'],
                'clientes_json' => $row['id_clientes_json'],
                'estado' => $row['estado'] ?: 'activa'
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'rutas' => $rutas,
        'total' => count($rutas)
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