<?php
require_once __DIR__ . '/config/db.php';

echo "=== VERIFICACIÓN DE TABLA ALERTAS ===\n\n";

// 1. Verificar estructura
echo "1. ESTRUCTURA DE LA TABLA:\n";
$result = $conn->query("DESCRIBE alertas");
while ($row = $result->fetch_assoc()) {
    echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

// 2. Verificar datos
echo "\n2. CANTIDAD DE REGISTROS: ";
$result = $conn->query("SELECT COUNT(*) as total FROM alertas");
$row = $result->fetch_assoc();
echo $row['total'] . "\n";

// 3. Ver tipos_alerta disponibles
echo "\n3. TIPOS DE ALERTA DISPONIBLES:\n";
$result = $conn->query("SELECT DISTINCT tipo_alerta FROM alertas WHERE tipo_alerta IS NOT NULL AND tipo_alerta != ''");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "   - " . $row['tipo_alerta'] . "\n";
    }
} else {
    echo "   (Ninguno)\n";
}

// 4. Ver un ejemplo de registro
echo "\n4. EJEMPLO DE REGISTRO:\n";
$result = $conn->query("SELECT a.*, p.nombre as producto_nombre FROM alertas a LEFT JOIN productos p ON a.id_productos = p.id_productos LIMIT 1");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    foreach ($row as $key => $value) {
        echo "   " . $key . ": " . ($value ?? 'NULL') . "\n";
    }
} else {
    echo "   (No hay registros)\n";
}

echo "\n✓ Verificación completada\n";
?>
