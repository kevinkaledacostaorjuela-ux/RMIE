<?php
// Script para aplicar migraciones de base de datos
// Ejecutar para permitir múltiples proveedores por producto

require_once 'config/db.php';

echo "=== MIGRACION: Múltiples proveedores por producto ===\n";

try {
    // Crear tabla intermedia si no existe
    echo "1. Creando tabla intermedia proveedores_productos...\n";
    $sql = "CREATE TABLE IF NOT EXISTS proveedores_productos (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        id_proveedor INT NOT NULL,
        id_producto INT NOT NULL,
        fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
        activo TINYINT(1) DEFAULT 1,
        FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedores) ON DELETE CASCADE,
        FOREIGN KEY (id_producto) REFERENCES productos(id_productos) ON DELETE CASCADE,
        UNIQUE KEY unique_proveedor_producto (id_proveedor, id_producto)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Tabla proveedores_productos creada exitosamente\n";
    } else {
        echo "✗ Error al crear tabla: " . $conn->error . "\n";
    }

    // Migrar datos existentes
    echo "2. Migrando datos existentes...\n";
    $sql = "INSERT IGNORE INTO proveedores_productos (id_proveedor, id_producto)
            SELECT id_proveedores, id_productos 
            FROM productos 
            WHERE id_proveedores IS NOT NULL AND id_proveedores > 0";
    
    if ($conn->query($sql) === TRUE) {
        $affected = $conn->affected_rows;
        echo "✓ Migrados $affected productos a la nueva estructura\n";
    } else {
        echo "✗ Error migrando datos: " . $conn->error . "\n";
    }

    // Hacer opcional el campo id_proveedores
    echo "3. Haciendo opcional el campo id_proveedores...\n";
    $sql = "ALTER TABLE productos MODIFY id_proveedores INT NULL";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Campo id_proveedores ahora es opcional\n";
    } else {
        echo "✗ Error modificando campo: " . $conn->error . "\n";
    }

    echo "\n=== MIGRACIÓN COMPLETADA EXITOSAMENTE ===\n";
    echo "Ahora puedes asignar el mismo producto a múltiples proveedores.\n";

} catch (Exception $e) {
    echo "✗ Error durante la migración: " . $e->getMessage() . "\n";
}

$conn->close();
?>