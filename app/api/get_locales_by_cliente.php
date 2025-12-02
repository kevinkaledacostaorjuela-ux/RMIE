<?php
// API para obtener locales por cliente - RMIE
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['cliente_id']) || empty($_GET['cliente_id'])) {
    echo json_encode([]);
    exit;
}

$cliente_id = intval($_GET['cliente_id']);
$search = isset($_GET['q']) ? $_GET['q'] : '';

try {
    require_once __DIR__ . '/../../config/db.php';
    

    
    $sql = "SELECT 
                l.id_locales as id, 
                CONCAT(l.nombre_local, ' - ', l.direccion) as text,
                l.nombre_local,
                l.direccion,
                l.cel_local as telefono
            FROM locales l 
            LEFT JOIN clientes_locales cl ON l.id_locales = cl.id_locales
            WHERE (cl.id_clientes = ? OR l.id_clientes = ?) AND l.estado = 'activo'";
    
    $params = [$cliente_id, $cliente_id];
    
    // Si hay término de búsqueda, agregarlo al filtro
    if (!empty($search)) {
        $sql .= " AND (l.nombre_local LIKE ? OR l.direccion LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
    }
    
    $sql .= " ORDER BY l.nombre_local ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->get_result();
    
    $locales = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $locales[] = [
                'id' => $row['id'],
                'text' => $row['text'],
                'nombre_local' => $row['nombre_local'],
                'direccion' => $row['direccion'],
                'telefono' => $row['telefono']
            ];
        }
    }
    

    
    echo json_encode($locales);
    
} catch (Exception $e) {
    error_log("Error en get_locales_by_cliente: " . $e->getMessage());
    echo json_encode([]);
}
?>