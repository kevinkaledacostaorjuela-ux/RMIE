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

// Verificar que se proporcione el ID del cliente
if (!isset($_GET['id_cliente']) || empty($_GET['id_cliente'])) {
    $response = [
        'success' => false,
        'error' => 'ID de cliente requerido',
        'locals' => [],
        'total' => 0
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$idCliente = (int)$_GET['id_cliente'];

try {
    // Consulta para obtener los locales asignados a este cliente usando la tabla intermedia
    $sql = "SELECT l.id_locales, l.nombre_local, l.direccion, l.estado, l.barrio, l.localidad
            FROM locales l 
            INNER JOIN clientes_locales cl ON l.id_locales = cl.id_locales 
            WHERE cl.id_clientes = ? AND l.estado = 'activo' 
            ORDER BY l.nombre_local ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $result = $stmt->get_result();
    
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
                'barrio' => $row['barrio'] ?: '',
                'localidad' => $row['localidad'] ?: '',
                'estado' => $row['estado']
            ];
        }
    }
    
    // Respuesta exitosa
    $response = [
        'success' => true,
        'locals' => $locals,
        'total' => count($locals),
        'message' => count($locals) > 0 ? 'Locales del cliente cargados correctamente' : 'Este cliente no tiene locales asignados',
        'id_cliente' => $idCliente
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Respuesta de error
    $response = [
        'success' => false,
        'error' => $e->getMessage(),
        'locals' => [],
        'total' => 0,
        'id_cliente' => $idCliente
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// Cerrar conexión
if (isset($stmt)) {
    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
}
?>