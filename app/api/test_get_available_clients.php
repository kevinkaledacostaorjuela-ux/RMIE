<?php
/**
 * API Endpoint - Obtener clientes disponibles (versión de prueba sin autenticación)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir archivos necesarios
require_once __DIR__ . '/../../config/db.php';

try {
    // Obtener todos los clientes disponibles
    $sql = "SELECT id_clientes as id, nombre, descripcion, cel_cliente, correo, estado 
            FROM clientes 
            WHERE estado = 'activo' OR estado IS NULL
            ORDER BY nombre ASC";
    
    $result = $conn->query($sql);
    
    $clients = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clients[] = [
                'id' => (int)$row['id'],
                'nombre' => $row['nombre'] ?: 'Cliente #' . $row['id'],
                'descripcion' => $row['descripcion'],
                'telefono' => $row['cel_cliente'],
                'correo' => $row['correo'],
                'estado' => $row['estado']
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'clients' => $clients,
        'total' => count($clients)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>