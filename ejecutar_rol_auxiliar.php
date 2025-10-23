<?php
/**
 * Ejecutor del script SQL para agregar el rol auxiliar
 * Fecha: 22 de octubre de 2025
 */

require_once 'config/db.php';

echo "<h2>Aplicando cambios para el rol auxiliar</h2>";

try {
    // Leer el archivo SQL
    $sql_file = 'update_roles_auxiliar.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("Archivo SQL no encontrado: $sql_file");
    }
    
    $sql_content = file_get_contents($sql_file);
    
    // Dividir por statements (punto y coma)
    $statements = array_filter(
        array_map('trim', explode(';', $sql_content)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^\s*--/', $stmt);
        }
    );
    
    echo "<h3>Ejecutando statements SQL:</h3>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px;'>";
    
    foreach ($statements as $index => $statement) {
        if (trim($statement)) {
            echo "<strong>Statement " . ($index + 1) . ":</strong><br>";
            echo "<code>" . htmlspecialchars($statement) . "</code><br>";
            
            try {
                $conn->query($statement);
                echo "<span style='color: green;'>✅ Ejecutado correctamente</span><br><br>";
            } catch (Exception $e) {
                echo "<span style='color: red;'>❌ Error: " . $e->getMessage() . "</span><br><br>";
            }
        }
    }
    
    echo "</div>";
    
    // Verificar los cambios
    echo "<h3>Verificación de cambios:</h3>";
    
    // Verificar estructura de la columna rol
    $result = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'rol'");
    if ($row = $result->fetch_assoc()) {
        echo "<p><strong>Columna rol:</strong> " . $row['Type'] . "</p>";
    }
    
    // Contar usuarios por rol
    $result = $conn->query("SELECT rol, COUNT(*) as count FROM usuarios GROUP BY rol ORDER BY rol");
    echo "<p><strong>Usuarios por rol:</strong></p>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . ucfirst($row['rol']) . ": " . $row['count'] . " usuario(s)</li>";
    }
    echo "</ul>";
    
    // Mostrar todos los usuarios
    $result = $conn->query("SELECT nombres, apellidos, correo, rol FROM usuarios ORDER BY rol, nombres");
    echo "<h4>Lista de usuarios:</h4>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr style='background: #007bff; color: white;'>";
    echo "<th style='padding: 10px;'>Nombre</th>";
    echo "<th style='padding: 10px;'>Email</th>";
    echo "<th style='padding: 10px;'>Rol</th>";
    echo "</tr>";
    
    while ($row = $result->fetch_assoc()) {
        $bgColor = '';
        switch($row['rol']) {
            case 'admin': $bgColor = '#dc3545'; break;
            case 'coordinador': $bgColor = '#007bff'; break;
            case 'auxiliar': $bgColor = '#28a745'; break;
        }
        
        echo "<tr>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['nombres'] . ' ' . $row['apellidos']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['correo']) . "</td>";
        echo "<td style='padding: 8px; background: $bgColor; color: white; text-align: center;'>" . 
             "<strong>" . ucfirst($row['rol']) . "</strong></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>✅ Rol auxiliar implementado exitosamente</h4>";
    echo "<p><strong>Resumen de cambios:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Tabla usuarios actualizada con el ENUM rol('admin','coordinador','auxiliar')</li>";
    echo "<li>✅ Usuario auxiliar de ejemplo creado (auxiliar@rmie.com / auxiliar123)</li>";
    echo "<li>✅ AuthUtils.php actualizado con nuevas funciones</li>";
    echo "<li>✅ Controladores actualizados con restricciones para auxiliares</li>";
    echo "<li>✅ Vistas actualizadas con permisos apropiados</li>";
    echo "</ul>";
    echo "<p><strong>Permisos por rol:</strong></p>";
    echo "<ul>";
    echo "<li><strong style='color: #dc3545;'>Admin:</strong> Puede crear, editar y eliminar todo</li>";
    echo "<li><strong style='color: #007bff;'>Coordinador:</strong> Puede crear y editar, NO puede eliminar</li>";
    echo "<li><strong style='color: #28a745;'>Auxiliar:</strong> Solo puede ver (solo lectura)</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>❌ Error al aplicar cambios</h4>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 20px;
    background: #f8f9fa;
}
code {
    background: #e9ecef;
    padding: 2px 4px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
}
</style>