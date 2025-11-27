<?php
require_once 'config/db.php';

echo "=== Prueba: Crear cliente sin local ===\n\n";

// Simular los datos del formulario
$nombre = "Cliente Prueba " . date('His');
$correo = "cliente_prueba_" . time() . "@example.com";
$cel = "3201234567";
$descripcion = "Cliente de prueba sin local asignado";

echo "Datos a insertar:\n";
echo "  Nombre: " . $nombre . "\n";
echo "  Correo: " . $correo . "\n";
echo "  Celular: " . $cel . "\n";
echo "  Descripción: " . $descripcion . "\n\n";

// Insertar cliente
$sql = "INSERT INTO clientes (nombre, descripcion, cel_cliente, correo, estado, fecha_creacion) 
        VALUES (?, ?, ?, ?, 'activo', NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $nombre, $descripcion, $cel, $correo);

if ($stmt->execute()) {
    $cliente_id = $conn->insert_id;
    echo "✓ Cliente creado exitosamente\n";
    echo "  ID: " . $cliente_id . "\n\n";
    
    // Verificar que se creó sin local asignado
    $sql = "SELECT c.id_clientes, c.nombre, c.correo, COUNT(cl.id_locales) as total_locales 
            FROM clientes c 
            LEFT JOIN clientes_locales cl ON c.id_clientes = cl.id_clientes 
            WHERE c.id_clientes = ? 
            GROUP BY c.id_clientes";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo "Verificación:\n";
        echo "  Nombre: " . htmlspecialchars($row['nombre']) . "\n";
        echo "  Correo: " . htmlspecialchars($row['correo']) . "\n";
        echo "  Locales asignados: " . $row['total_locales'] . "\n\n";
        
        if ($row['total_locales'] == 0) {
            echo "✓ El cliente se creó correctamente SIN locales asignados\n";
        } else {
            echo "⚠ El cliente tiene " . $row['total_locales'] . " local(es) asignado(s)\n";
        }
    }
} else {
    echo "✗ Error al crear cliente: " . $conn->error . "\n";
}
?>
