<?php
// Script para agregar campos de último acceso y fecha de creación a usuarios
require_once 'config/db.php';

echo "=== MIGRACIÓN: Campos de último acceso para usuarios ===\n";

try {
    // Agregar campo de fecha de creación
    echo "1. Agregando campo fecha_creacion...\n";
    $sql = "ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Campo fecha_creacion agregado exitosamente\n";
    } else {
        echo "✗ Error o campo ya existe: " . $conn->error . "\n";
    }

    // Agregar campo de último acceso
    echo "2. Agregando campo ultimo_acceso...\n";
    $sql = "ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS ultimo_acceso DATETIME NULL";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Campo ultimo_acceso agregado exitosamente\n";
    } else {
        echo "✗ Error o campo ya existe: " . $conn->error . "\n";
    }

    // Actualizar usuarios existentes con fecha de creación
    echo "3. Actualizando fecha_creacion para usuarios existentes...\n";
    $sql = "UPDATE usuarios SET fecha_creacion = CURRENT_TIMESTAMP WHERE fecha_creacion IS NULL";
    
    if ($conn->query($sql) === TRUE) {
        $affected = $conn->affected_rows;
        echo "✓ Actualizados $affected usuarios con fecha_creacion\n";
    } else {
        echo "✗ Error actualizando fechas: " . $conn->error . "\n";
    }

    echo "\n=== MIGRACIÓN COMPLETADA EXITOSAMENTE ===\n";
    echo "Campos agregados:\n";
    echo "- fecha_creacion: Fecha de registro del usuario\n";
    echo "- ultimo_acceso: Última vez que el usuario accedió al sistema\n";

} catch (Exception $e) {
    echo "✗ Error durante la migración: " . $e->getMessage() . "\n";
}

$conn->close();
?>