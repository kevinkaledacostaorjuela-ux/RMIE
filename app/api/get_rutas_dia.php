<?php
// API para obtener rutas de un día específico para mostrar en el mapa
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Incluir configuración de base de datos
    require_once __DIR__ . '/../../config/db.php';
    
    $dia = $_GET['dia'] ?? '';
    
    if (empty($dia)) {
        throw new Exception("Día no especificado");
    }
    
    // Consulta simplificada para obtener solo rutas PENDIENTES del día (excluir completadas y eliminadas)
    // Priorizar datos de la tabla rutas que ya contiene la información correcta
    $sql = "SELECT r.*, 
                   c.nombre as cliente_nombre_real,
                   r.nombre_local as local_nombre_real,
                   r.direccion as direccion_real,
                   '' as telefono_local,
                   '' as localidad,
                   '' as barrio
            FROM rutas r 
            LEFT JOIN clientes c ON r.id_clientes = c.id_clientes 
            WHERE r.dia_semana = ? AND r.estado != 'eliminado' AND r.estado != 'completada'
            ORDER BY r.id_ruta ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $dia);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rutas = [];
    while ($row = $result->fetch_assoc()) {
        // Limpiar y formatear datos
        $ruta = [
            'id_ruta' => $row['id_ruta'],
            'nombre_cliente' => $row['cliente_nombre_real'] ?: $row['nombre_cliente'],
            'nombre_local' => $row['local_nombre_real'] ?: $row['nombre_local'],
            'direccion' => $row['direccion_real'] ?: $row['direccion'],
            'telefono_local' => $row['telefono_local'] ?: '',
            'localidad' => $row['localidad'] ?: '',
            'barrio' => $row['barrio'] ?: '',
            'estado' => $row['estado'],
            'dia_semana' => $row['dia_semana']
        ];
        
        // Solo agregar rutas con dirección válida
        if (!empty($ruta['direccion']) && $ruta['direccion'] !== 'Dirección no especificada') {
            $rutas[] = $ruta;
        }
    }
    
    echo json_encode([
        'success' => true,
        'dia' => $dia,
        'total' => count($rutas),
        'rutas' => $rutas
    ]);
    
} catch (Exception $e) {
    error_log('Error en get_rutas_dia.php: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'rutas' => []
    ]);
}
?>