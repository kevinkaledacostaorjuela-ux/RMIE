<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';

echo "<h1>🔍 Diagnóstico de Tabla Rutas</h1>";

try {
    // Verificar estructura de la tabla
    echo "<h2>📋 Estructura de la tabla 'rutas':</h2>";
    $result = $conn->query("DESCRIBE rutas");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>Error: No se pudo obtener la estructura de la tabla: " . $conn->error . "</p>";
    }

    // Mostrar algunas rutas de ejemplo
    echo "<h2>📊 Datos de ejemplo (primeras 3 rutas):</h2>";
    $result = $conn->query("SELECT * FROM rutas LIMIT 3");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        $firstRow = true;
        while ($row = $result->fetch_assoc()) {
            if ($firstRow) {
                echo "<tr style='background: #f0f0f0;'>";
                foreach (array_keys($row) as $column) {
                    echo "<th>" . htmlspecialchars($column) . "</th>";
                }
                echo "</tr>";
                $firstRow = false;
            }
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>No hay rutas en la tabla o error al consultar: " . $conn->error . "</p>";
    }

    // Probar un UPDATE de prueba
    echo "<h2>🧪 Prueba de UPDATE:</h2>";
    
    // Buscar la primera ruta disponible
    $testResult = $conn->query("SELECT * FROM rutas LIMIT 1");
    if ($testResult && $testResult->num_rows > 0) {
        $testRoute = $testResult->fetch_assoc();
        $testId = $testRoute['id_ruta'];
        
        echo "<p><strong>Ruta de prueba ID:</strong> " . $testId . "</p>";
        echo "<p><strong>Datos actuales:</strong></p>";
        echo "<pre>" . print_r($testRoute, true) . "</pre>";
        
        // Intentar un update con los mismos datos (no debería cambiar nada)
        $updateSql = "UPDATE rutas SET direccion = ? WHERE id_ruta = ?";
        $stmt = $conn->prepare($updateSql);
        if ($stmt) {
            $currentDireccion = $testRoute['direccion'];
            $stmt->bind_param('si', $currentDireccion, $testId);
            
            if ($stmt->execute()) {
                $affected = $stmt->affected_rows;
                echo "<p style='color: green;'>✅ UPDATE ejecutado exitosamente</p>";
                echo "<p><strong>Filas afectadas:</strong> $affected (debería ser 0 porque no cambió nada)</p>";
            } else {
                echo "<p style='color: red;'>❌ Error en UPDATE: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Error al preparar UPDATE: " . $conn->error . "</p>";
        }
        
        // Ahora intentar un cambio real
        echo "<h3>🔄 Prueba con cambio real:</h3>";
        $newDireccion = $currentDireccion . " [MODIFICADO " . date('H:i:s') . "]";
        $updateSql2 = "UPDATE rutas SET direccion = ? WHERE id_ruta = ?";
        $stmt2 = $conn->prepare($updateSql2);
        if ($stmt2) {
            $stmt2->bind_param('si', $newDireccion, $testId);
            
            if ($stmt2->execute()) {
                $affected2 = $stmt2->affected_rows;
                echo "<p style='color: green;'>✅ UPDATE con cambio ejecutado exitosamente</p>";
                echo "<p><strong>Filas afectadas:</strong> $affected2 (debería ser 1)</p>";
                
                // Verificar el cambio
                $verifyResult = $conn->query("SELECT direccion FROM rutas WHERE id_ruta = $testId");
                if ($verifyResult) {
                    $verifyData = $verifyResult->fetch_assoc();
                    echo "<p><strong>Nueva dirección:</strong> " . htmlspecialchars($verifyData['direccion']) . "</p>";
                    
                    // Restaurar valor original
                    $restoreSql = "UPDATE rutas SET direccion = ? WHERE id_ruta = ?";
                    $restoreStmt = $conn->prepare($restoreSql);
                    $restoreStmt->bind_param('si', $currentDireccion, $testId);
                    $restoreStmt->execute();
                    echo "<p style='color: blue;'>🔄 Valor original restaurado</p>";
                }
            } else {
                echo "<p style='color: red;'>❌ Error en UPDATE con cambio: " . $stmt2->error . "</p>";
            }
        }
        
    } else {
        echo "<p style='color: red;'>❌ No se encontraron rutas para probar</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general: " . $e->getMessage() . "</p>";
}

echo "<br><hr><p><em>Diagnóstico completado a las " . date('Y-m-d H:i:s') . "</em></p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>