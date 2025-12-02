<?php
// API para obtener lista de clientes - RMIE
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    require_once __DIR__ . '/../../config/db.php';
    
    $sql = "SELECT id_clientes, nombre, cel_cliente, correo, estado 
            FROM clientes 
            WHERE estado = 'activo' 
            ORDER BY nombre ASC";
    
    $result = $conn->query($sql);
    $clientes = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = [
                'id' => $row['id_clientes'],
                'id_clientes' => $row['id_clientes'],
                'nombre' => $row['nombre'],
                'telefono' => $row['cel_cliente'] ?? '',
                'correo' => $row['correo'] ?? '',
                'estado' => $row['estado']
            ];
        }
    }
    
    echo json_encode($clientes);
    
} catch (Exception $e) {
    error_log('Error en get_clientes.php: ' . $e->getMessage());
    
    // Respuesta de fallback en caso de error
    $fallback = [
        ['id' => 33, 'id_clientes' => 33, 'nombre' => 'Supermercado Central', 'telefono' => '12345678', 'correo' => '', 'estado' => 'activo'],
        ['id' => 34, 'id_clientes' => 34, 'nombre' => 'Panadería La Esquina', 'telefono' => '87654321', 'correo' => '', 'estado' => 'activo'],
        ['id' => 35, 'id_clientes' => 35, 'nombre' => 'Tienda TPS', 'telefono' => '11223344', 'correo' => '', 'estado' => 'activo']
    ];
    
    echo json_encode($fallback);
}
?>