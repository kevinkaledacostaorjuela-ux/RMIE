<?php
// Script para actualizar la tabla de categorías agregando la columna fecha_creacion si no existe
require_once 'config/db.php';

echo "<h2>🔧 Actualización de Tabla Categorías</h2>";
echo "<p>Verificando y actualizando la estructura de la tabla categorías...</p>";

try {
    // Verificar si la columna fecha_creacion existe
    $query = "SHOW COLUMNS FROM categorias LIKE 'fecha_creacion'";
    $result = $conn->query($query);
    
    if ($result->num_rows == 0) {
        // La columna no existe, la agregamos
        echo "<p>❌ La columna 'fecha_creacion' no existe.</p>";
        echo "<p>➕ Agregando columna 'fecha_creacion'...</p>";
        
        $alterQuery = "ALTER TABLE categorias ADD COLUMN fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP";
        if ($conn->query($alterQuery) === TRUE) {
            echo "<p>✅ Columna 'fecha_creacion' agregada exitosamente.</p>";
            
            // Actualizar registros existentes con fecha actual
            $updateQuery = "UPDATE categorias SET fecha_creacion = NOW() WHERE fecha_creacion IS NULL";
            if ($conn->query($updateQuery) === TRUE) {
                echo "<p>✅ Registros existentes actualizados con fecha actual.</p>";
            } else {
                echo "<p>⚠️ Error al actualizar registros existentes: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>❌ Error al agregar columna: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>✅ La columna 'fecha_creacion' ya existe en la tabla.</p>";
    }
    
    // Mostrar estructura actual de la tabla
    echo "<h3>📋 Estructura actual de la tabla categorías:</h3>";
    $describeQuery = "DESCRIBE categorias";
    $result = $conn->query($describeQuery);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th style='padding: 8px;'>Campo</th>";
    echo "<th style='padding: 8px;'>Tipo</th>";
    echo "<th style='padding: 8px;'>Null</th>";
    echo "<th style='padding: 8px;'>Key</th>";
    echo "<th style='padding: 8px;'>Default</th>";
    echo "<th style='padding: 8px;'>Extra</th>";
    echo "</tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Mostrar conteo de categorías
    $countQuery = "SELECT COUNT(*) as total FROM categorias";
    $result = $conn->query($countQuery);
    $count = $result->fetch_assoc()['total'];
    
    echo "<h3>📊 Estadísticas:</h3>";
    echo "<p>Total de categorías en la base de datos: <strong>$count</strong></p>";
    
    if ($count > 0) {
        echo "<h3>📝 Primeras 5 categorías:</h3>";
        $sampleQuery = "SELECT * FROM categorias LIMIT 5";
        $result = $conn->query($sampleQuery);
        
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th style='padding: 8px;'>ID</th>";
        echo "<th style='padding: 8px;'>Nombre</th>";
        echo "<th style='padding: 8px;'>Descripción</th>";
        if ($result->field_count > 3) {
            echo "<th style='padding: 8px;'>Fecha Creación</th>";
        }
        echo "</tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($row['id_categoria']) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($row['descripcion'] ?? 'Sin descripción') . "</td>";
            if (isset($row['fecha_creacion'])) {
                echo "<td style='padding: 8px;'>" . htmlspecialchars($row['fecha_creacion'] ?? 'No disponible') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h3>🎯 Resultado:</h3>";
    echo "<p>✅ La actualización se ha completado exitosamente.</p>";
    echo "<p>🔗 <a href='/RMIE/app/controllers/CategoryController.php?accion=index'>Ir a Gestión de Categorías</a></p>";
    echo "<p>🏠 <a href='/RMIE/app/views/dashboard.php'>Volver al Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error durante la actualización: " . $e->getMessage() . "</p>";
}

$conn->close();
?>