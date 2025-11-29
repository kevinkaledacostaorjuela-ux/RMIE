<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Si es una petición OPTIONS, terminar aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configuración de la base de datos
require_once 'config/db.php';

try {
    // Consulta para obtener todos los locales disponibles
    $sql = "SELECT id_locales, nombre_local, direccion, estado 
            FROM locales 
            WHERE estado = 'activo' 
            ORDER BY nombre_local ASC";
    
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("Error en la consulta: " . $conn->error);
    }
    
    $locals = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $locals[] = [
                'id' => (int)$row['id_locales'],
                'nombre' => $row['nombre_local'],
                'direccion' => $row['direccion'] ?: 'Sin dirección especificada',
                'estado' => $row['estado']
            ];
        }
    }
    
    // Respuesta exitosa
    $response = [
        'success' => true,
        'locals' => $locals,
        'total' => count($locals),
        'message' => 'Locales cargados correctamente'
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Respuesta de error
    $response = [
        'success' => false,
        'error' => $e->getMessage(),
        'locals' => [],
        'total' => 0
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// Cerrar conexión
if (isset($conn)) {
    $conn->close();
}
?>