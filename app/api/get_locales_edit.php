<?php
// API para obtener lista de locales para edición de rutas
header('Content-Type: application/json');

try {
    // Incluir configuración de base de datos
    require_once __DIR__ . '/../../config/db.php';
    
    $busqueda = $_GET['q'] ?? '';
    $id_cliente = $_GET['id_cliente'] ?? '';
    
    // Consulta base usando ambas relaciones (tabla relaciones y directa) para compatibilidad
    $sql = "SELECT DISTINCT l.id_locales, l.nombre_local, l.direccion, l.cel_local, l.localidad, l.barrio, 
                   COALESCE(c1.nombre, c2.nombre) as nombre_cliente
            FROM locales l
            LEFT JOIN clientes_locales cl ON l.id_locales = cl.id_locales
            LEFT JOIN clientes c1 ON cl.id_clientes = c1.id_clientes
            LEFT JOIN clientes c2 ON l.id_clientes = c2.id_clientes
            WHERE l.estado = 'activo'";
    
    // Filtro por cliente específico si se proporciona
    if (!empty($id_cliente)) {
        $sql .= " AND (cl.id_clientes = ? OR l.id_clientes = ?)";
    }
    
    // Agregar filtro de búsqueda si existe
    if (!empty($busqueda)) {
        $sql .= " AND (l.nombre_local LIKE ? OR l.direccion LIKE ? OR l.localidad LIKE ? OR l.barrio LIKE ?)";
    }
    
    $sql .= " ORDER BY l.nombre_local LIMIT 50";
    
    $stmt = $conn->prepare($sql);
    
    // Preparar parámetros
    $params = [];
    $types = '';
    
    if (!empty($id_cliente)) {
        $params[] = $id_cliente;
        $params[] = $id_cliente;  // Para ambas condiciones
        $types .= 'ii';
    }
    
    if (!empty($busqueda)) {
        $searchTerm = '%' . $busqueda . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ssss';
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $locales = [];
    while ($row = $result->fetch_assoc()) {
        $locales[] = [
            'id' => $row['id_locales'],
            'text' => $row['nombre_local'] . ' - ' . $row['direccion'],
            'nombre_local' => $row['nombre_local'],
            'direccion' => $row['direccion'],
            'telefono' => $row['cel_local'],
            'localidad' => $row['localidad'],
            'barrio' => $row['barrio'],
            'cliente' => $row['nombre_cliente']
        ];
    }
    
    echo json_encode([
        'results' => $locales,
        'pagination' => ['more' => false]
    ]);
    
} catch (Exception $e) {
    error_log('Error en get_locales_edit.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error interno del servidor',
        'results' => [],
        'pagination' => ['more' => false]
    ]);
}
?>