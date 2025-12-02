<?php
// API para obtener lista de clientes para edición de rutas
header('Content-Type: application/json');

try {
    // Incluir configuración de base de datos
    require_once __DIR__ . '/../../config/db.php';
    
    $busqueda = $_GET['q'] ?? '';
    
    // Consulta base
    $sql = "SELECT id_clientes, nombre, descripcion, cel_cliente, correo 
            FROM clientes 
            WHERE estado = 'activo'";
    
    // Agregar filtro de búsqueda si existe
    if (!empty($busqueda)) {
        $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
    }
    
    $sql .= " ORDER BY nombre LIMIT 50";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($busqueda)) {
        $searchTerm = '%' . $busqueda . '%';
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = [
            'id' => $row['id_clientes'],
            'text' => $row['nombre'],
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'telefono' => $row['cel_cliente'],
            'correo' => $row['correo']
        ];
    }
    
    echo json_encode([
        'results' => $clientes,
        'pagination' => ['more' => false]
    ]);
    
} catch (Exception $e) {
    error_log('Error en get_clientes_edit.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error interno del servidor',
        'results' => [],
        'pagination' => ['more' => false]
    ]);
}
?>