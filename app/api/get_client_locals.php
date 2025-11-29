<?php
header('Content-Type: application/json; charset=utf-8');

// Incluir la configuración de la base de datos
require_once __DIR__ . '/../config/db.php';

try {
    // Verificar que se recibió el parámetro cliente_id
    if (!isset($_GET['cliente_id'])) {
        throw new Exception('ID del cliente es requerido');
    }

    $cliente_id = intval($_GET['cliente_id']);
    
    if ($cliente_id <= 0) {
        throw new Exception('ID del cliente inválido');
    }

    // Consultar locales del cliente específico
    $sql = "SELECT id_locales as id, nombre_local, direccion 
            FROM locales 
            WHERE id_clientes = ? 
            AND estado = 'activo'
            ORDER BY nombre_local";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $conn->error);
    }
    
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $locales = [];
    while ($row = $result->fetch_assoc()) {
        $locales[] = $row;
    }
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'locals' => $locales,
        'count' => count($locales),
        'cliente_id' => $cliente_id
    ]);

} catch (Exception $e) {
    // Respuesta de error
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'locals' => []
    ]);
}

// Cerrar la conexión
if (isset($conn)) {
    $conn->close();
}
?>