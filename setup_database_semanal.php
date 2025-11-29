<?php
/**
 * Script para crear las tablas de planificación semanal si no existen
 */

require_once __DIR__ . '/config/db.php';

echo "<h1>Configurando base de datos para planificación semanal</h1>";

try {
    // Verificar si la tabla ruta_clientes_semanales existe
    $result = $conn->query("SHOW TABLES LIKE 'ruta_clientes_semanales'");
    
    if ($result->num_rows == 0) {
        echo "<p>📝 Creando tabla ruta_clientes_semanales...</p>";
        
        $sql = "
        CREATE TABLE IF NOT EXISTS ruta_clientes_semanales (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NOT NULL,
            dia_semana VARCHAR(20) NOT NULL,
            cliente_id INT NOT NULL,
            orden INT DEFAULT 1,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_usuario_dia (usuario_id, dia_semana),
            INDEX idx_cliente (cliente_id),
            FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        if ($conn->query($sql)) {
            echo "<p>✅ Tabla ruta_clientes_semanales creada correctamente</p>";
        } else {
            echo "<p>❌ Error creando tabla: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>✅ Tabla ruta_clientes_semanales ya existe</p>";
    }

    // Verificar si la tabla rutas_semanales existe
    $result = $conn->query("SHOW TABLES LIKE 'rutas_semanales'");
    
    if ($result->num_rows == 0) {
        echo "<p>📝 Creando tabla rutas_semanales...</p>";
        
        $sql = "
        CREATE TABLE IF NOT EXISTS rutas_semanales (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NOT NULL,
            dia_semana VARCHAR(20) NOT NULL,
            nombre VARCHAR(100) NOT NULL,
            descripcion TEXT,
            estado ENUM('activa', 'completada', 'cancelada') DEFAULT 'activa',
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_usuario_dia (usuario_id, dia_semana),
            INDEX idx_estado (estado),
            INDEX idx_fecha (fecha_creacion)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        if ($conn->query($sql)) {
            echo "<p>✅ Tabla rutas_semanales creada correctamente</p>";
        } else {
            echo "<p>❌ Error creando tabla: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>✅ Tabla rutas_semanales ya existe</p>";
    }

    echo "<h2>🎉 Base de datos configurada correctamente!</h2>";
    echo "<p><a href='setup_planificacion_test.php'>Continuar con datos de prueba</a></p>";

} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
} finally {
    $conn->close();
}
?>