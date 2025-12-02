<?php
// API para obtener lista de locales - RMIE
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    require_once __DIR__ . '/../../config/db.php';
    
    $sql = "SELECT id_locales, nombre_local, direccion, cel_local, estado 
            FROM locales 
            WHERE estado = 'activo' 
            ORDER BY nombre_local ASC";
    
    $result = $conn->query($sql);
    $locales = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $locales[] = [
                'id' => $row['id_locales'],
                'id_locales' => $row['id_locales'],
                'nombre' => $row['nombre_local'],
                'nombre_local' => $row['nombre_local'],
                'direccion' => $row['direccion'] ?? '',
                'telefono' => $row['cel_local'] ?? '',
                'estado' => $row['estado']
            ];
        }
    }
    
    echo json_encode($locales);
    
} catch (Exception $e) {
    error_log('Error en get_locales.php: ' . $e->getMessage());
    
    // Respuesta de fallback en caso de error
    $fallback = [
        ['id' => 1, 'id_locales' => 1, 'nombre' => 'Local Centro', 'nombre_local' => 'Local Centro', 'direccion' => 'Carrera 10 #15-30', 'telefono' => '', 'estado' => 'activo'],
        ['id' => 2, 'id_locales' => 2, 'nombre' => 'Local Norte', 'nombre_local' => 'Local Norte', 'direccion' => 'Calle 80 #25-40', 'telefono' => '', 'estado' => 'activo'],
        ['id' => 3, 'id_locales' => 3, 'nombre' => 'Local Sur', 'nombre_local' => 'Local Sur', 'direccion' => 'Carrera 30 #40-50', 'telefono' => '', 'estado' => 'activo']
    ];
    
    echo json_encode($fallback);
}
?>