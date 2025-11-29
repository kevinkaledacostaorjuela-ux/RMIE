<?php
/**
 * API Endpoint - Obtener local asignado a un cliente
 * Devuelve la información del local asignado a un cliente específico
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/db.php';

try {
    // Verificar que se proporcione el ID del cliente
    if (!isset($_GET['cliente_id']) || empty($_GET['cliente_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID de cliente no proporcionado'
        ]);
        exit;
    }

    $cliente_id = (int)$_GET['cliente_id'];

    // Obtener información del cliente y su local asignado
    $sql = "SELECT 
                c.id_clientes,
                c.nombre as cliente_nombre,
                c.descripcion as cliente_descripcion,
                c.id_locales,
                l.id_locales as local_id,
                l.nombre_local as local_nombre,
                l.direccion as local_direccion,
                l.cel_local as local_telefono,
                l.localidad,
                l.barrio,
                l.estado as local_estado
            FROM clientes c
            LEFT JOIN locales l ON l.id_locales = c.id_locales
            WHERE c.id_clientes = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $response = [
            'success' => true,
            'cliente' => [
                'id' => (int)$row['id_clientes'],
                'nombre' => $row['cliente_nombre'],
                'descripcion' => $row['cliente_descripcion']
            ]
        ];

        // Si tiene local asignado
        if ($row['local_id']) {
            $direccion_completa = trim(($row['local_direccion'] ?: '') . ' ' . ($row['barrio'] ?: '') . ' ' . ($row['localidad'] ?: ''));
            
            $response['local'] = [
                'id' => (int)$row['local_id'],
                'nombre' => $row['local_nombre'] ?: 'Local sin nombre',
                'direccion' => $direccion_completa ?: 'Sin dirección',
                'telefono' => $row['local_telefono'] ?: 'Sin teléfono',
                'barrio' => $row['barrio'] ?: '',
                'localidad' => $row['localidad'] ?: '',
                'estado' => $row['local_estado'] ?: 'activo'
            ];
        } else {
            $response['local'] = null;
        }

        echo json_encode($response);
        
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Cliente no encontrado'
        ]);
    }

    $stmt->close();

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