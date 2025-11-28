<?php
session_start();
$_SESSION['user'] = 'test@test.com';
$_SESSION['user_id'] = 1;
$_SESSION['rol'] = 'admin';

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Alert.php';

echo "=== TEST DE EDICIÓN DE ALERTA CON ESTADO ===\n\n";

// Obtener alerta con ID 48
$alerta = Alert::getById($conn, 48);
if ($alerta) {
    echo "✓ Alerta obtenida correctamente\n";
    echo "  ID: " . $alerta['id_alertas'] . "\n";
    echo "  Producto ID: " . $alerta['id_productos'] . "\n";
    echo "  Cantidad Mínima: " . $alerta['cantidad_minima'] . "\n";
    echo "  Fecha Caducidad: " . $alerta['fecha_caducidad'] . "\n";
    echo "  Proveedor ID: " . $alerta['id_proveedores'] . "\n";
    echo "  Estado actual: " . (isset($alerta['estado']) ? $alerta['estado'] : 'NO DEFINIDO') . "\n";
    
    echo "\n✓ Preparando actualización de estado a 'Crítica'...\n";
    
    $data = [
        'id_productos' => $alerta['id_productos'],
        'cantidad_minima' => $alerta['cantidad_minima'],
        'fecha_caducidad' => $alerta['fecha_caducidad'],
        'id_proveedores' => $alerta['id_proveedores'],
        'estado' => 'Crítica'
    ];
    
    $result = Alert::update($conn, 48, $data);
    if ($result) {
        echo "✓ Alerta actualizada exitosamente\n";
        
        // Verificar cambio
        $alerta_updated = Alert::getById($conn, 48);
        echo "  Nuevo estado: " . (isset($alerta_updated['estado']) ? $alerta_updated['estado'] : 'NO DEFINIDO') . "\n";
    } else {
        echo "✗ Error al actualizar: " . $conn->error . "\n";
    }
} else {
    echo "✗ Alerta no encontrada\n";
}

?>
