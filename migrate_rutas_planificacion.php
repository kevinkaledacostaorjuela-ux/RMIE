<?php
require_once __DIR__ . '/config/db.php';

echo "=== Migración tabla rutas_planificacion ===\n";

$check = $conn->query("SHOW TABLES LIKE 'rutas_planificacion'");
if ($check && $check->num_rows > 0) {
    echo "✓ Tabla 'rutas_planificacion' ya existe.\n";
    exit; 
}

$sql = "CREATE TABLE rutas_planificacion (\n    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,\n    num_doc_usuario INT NOT NULL,\n    dia VARCHAR(15) NOT NULL,\n    id_cliente INT NOT NULL,\n    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n    UNIQUE KEY unq_usuario_dia_cliente (num_doc_usuario, dia, id_cliente),\n    INDEX idx_usuario (num_doc_usuario),\n    INDEX idx_dia (dia)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql)) {
    echo "✓ Tabla 'rutas_planificacion' creada correctamente.\n";
} else {
    echo "✗ Error creando tabla: " . $conn->error . "\n";
}
?>