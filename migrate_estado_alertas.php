<?php
require_once __DIR__ . '/config/db.php';

echo "=== AGREGAR COLUMNA 'estado' A TABLA 'alertas' ===\n\n";

// Verificar si la columna ya existe
$check_column = $conn->query("SHOW COLUMNS FROM alertas LIKE 'estado'");
if ($check_column && $check_column->num_rows > 0) {
    echo "✓ La columna 'estado' ya existe en la tabla alertas\n";
    exit;
}

// Agregar la columna
$sql = "ALTER TABLE alertas ADD COLUMN estado VARCHAR(50) DEFAULT 'Activo' AFTER tipo_alerta";

if ($conn->query($sql)) {
    echo "✓ Columna 'estado' agregada exitosamente\n";
    echo "  Columna: estado (VARCHAR(50), DEFAULT: 'Activo')\n\n";
    
    // Verificar la nueva estructura
    echo "Nueva estructura de la tabla alertas:\n";
    $result = $conn->query("DESCRIBE alertas");
    while ($row = $result->fetch_assoc()) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "✗ Error al agregar columna: " . $conn->error . "\n";
}

?>
