<?php
// Test completo de actualización de rutas
require_once 'config/db.php';
require_once 'app/models/Route.php';

echo "<h1>DIAGNÓSTICO COMPLETO DE ACTUALIZACIÓN DE RUTAS</h1>";

// 1. Verificar conexión a base de datos
echo "<h2>1. Verificación de conexión</h2>";
if ($conn) {
    echo "✓ Conexión establecida<br>";
    echo "Host: " . $conn->host_info . "<br>";
    echo "Versión MySQL: " . $conn->server_info . "<br>";
    echo "Base de datos actual: " . $conn->query("SELECT DATABASE()")->fetch_row()[0] . "<br>";
} else {
    echo "✗ Error de conexión<br>";
    exit;
}

// 2. Verificar tabla rutas existe
echo "<h2>2. Verificación de tabla rutas</h2>";
$result = $conn->query("SHOW TABLES LIKE 'rutas'");
if ($result->num_rows > 0) {
    echo "✓ Tabla 'rutas' existe<br>";
    
    // Ver estructura de la tabla
    $columns = $conn->query("DESCRIBE rutas");
    echo "<strong>Columnas de la tabla:</strong><br>";
    while ($col = $columns->fetch_assoc()) {
        echo "- " . $col['Field'] . " (" . $col['Type'] . ")<br>";
    }
} else {
    echo "✗ Tabla 'rutas' no existe<br>";
    exit;
}

// 3. Verificar registros existentes
echo "<h2>3. Registros actuales en rutas</h2>";
$routes = $conn->query("SELECT * FROM rutas ORDER BY id_ruta");
if ($routes->num_rows > 0) {
    echo "Total de rutas: " . $routes->num_rows . "<br>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Dirección</th><th>Local</th><th>Cliente</th><th>ID Cliente</th><th>ID Ventas</th><th>ID Reportes</th></tr>";
    while ($route = $routes->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $route['id_ruta'] . "</td>";
        echo "<td>" . $route['direccion'] . "</td>";
        echo "<td>" . $route['nombre_local'] . "</td>";
        echo "<td>" . $route['nombre_cliente'] . "</td>";
        echo "<td>" . $route['id_clientes'] . "</td>";
        echo "<td>" . $route['id_ventas'] . "</td>";
        echo "<td>" . ($route['id_reportes'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay rutas en la tabla<br>";
}

// 4. Test de actualización directa
echo "<h2>4. Test de actualización directa</h2>";
$test_id = 1; // Cambiar por un ID que exista

// Obtener datos actuales
$current = $conn->query("SELECT * FROM rutas WHERE id_ruta = $test_id")->fetch_assoc();
if ($current) {
    echo "Datos actuales del registro $test_id:<br>";
    echo "- Dirección: " . $current['direccion'] . "<br>";
    echo "- Local: " . $current['nombre_local'] . "<br>";
    echo "- Cliente: " . $current['nombre_cliente'] . "<br>";
    
    // Hacer una actualización de prueba
    $test_direccion = "TEST ACTUALIZACIÓN " . date('H:i:s');
    $stmt = $conn->prepare("UPDATE rutas SET direccion = ? WHERE id_ruta = ?");
    $stmt->bind_param("si", $test_direccion, $test_id);
    
    if ($stmt->execute()) {
        echo "✓ Query ejecutada correctamente<br>";
        echo "Filas afectadas: " . $stmt->affected_rows . "<br>";
        
        // Verificar el cambio
        $updated = $conn->query("SELECT direccion FROM rutas WHERE id_ruta = $test_id")->fetch_assoc();
        if ($updated['direccion'] === $test_direccion) {
            echo "✓ Actualización confirmada. Nueva dirección: " . $updated['direccion'] . "<br>";
            
            // Restaurar valor original
            $restore_stmt = $conn->prepare("UPDATE rutas SET direccion = ? WHERE id_ruta = ?");
            $restore_stmt->bind_param("si", $current['direccion'], $test_id);
            $restore_stmt->execute();
            echo "✓ Valor original restaurado<br>";
        } else {
            echo "✗ La actualización no se reflejó en la base de datos<br>";
        }
    } else {
        echo "✗ Error en la ejecución: " . $stmt->error . "<br>";
    }
} else {
    echo "No se encontró el registro con ID $test_id<br>";
}

// 5. Test usando el modelo Route
echo "<h2>5. Test usando el modelo Route</h2>";

// Probar obtener una ruta
$test_route = Route::getById($conn, $test_id);
if ($test_route) {
    echo "✓ Modelo puede obtener rutas<br>";
    echo "Datos obtenidos:<br>";
    foreach ($test_route as $key => $value) {
        echo "- $key: " . ($value ?? 'NULL') . "<br>";
    }
    
    // Probar actualización usando el modelo
    $test_direccion_modelo = 'TEST MODELO ' . date('H:i:s');
    
    echo "<h3>Intentando actualización con modelo:</h3>";
    echo "Datos a actualizar:<br>";
    echo "- direccion: $test_direccion_modelo<br>";
    echo "- nombre_local: " . $test_route['nombre_local'] . "<br>";
    echo "- nombre_cliente: " . $test_route['nombre_cliente'] . "<br>";
    echo "- id_clientes: " . $test_route['id_clientes'] . "<br>";
    echo "- id_ventas: " . $test_route['id_ventas'] . "<br>";
    echo "- id_reportes: " . ($test_route['id_reportes'] ?? 'NULL') . "<br>";
    
    try {
        $result = Route::update(
            $conn, 
            $test_id, 
            $test_direccion_modelo,
            $test_route['nombre_local'],
            $test_route['nombre_cliente'],
            $test_route['id_clientes'],
            $test_route['id_ventas'],
            $test_route['id_reportes']
        );
        
        if ($result) {
            echo "✓ Modelo reporta actualización exitosa<br>";
            
            // Verificar en base de datos
            $verify = $conn->query("SELECT direccion FROM rutas WHERE id_ruta = $test_id")->fetch_assoc();
            if ($verify['direccion'] === $test_direccion_modelo) {
                echo "✓ Actualización confirmada en BD<br>";
                
                // Restaurar valor original
                Route::update(
                    $conn, 
                    $test_id, 
                    $current['direccion'],
                    $test_route['nombre_local'],
                    $test_route['nombre_cliente'],
                    $test_route['id_clientes'],
                    $test_route['id_ventas'],
                    $test_route['id_reportes']
                );
                echo "✓ Valor original restaurado<br>";
            } else {
                echo "✗ Actualización NO confirmada en BD<br>";
                echo "Esperado: " . $test_direccion_modelo . "<br>";
                echo "Actual: " . $verify['direccion'] . "<br>";
            }
        } else {
            echo "✗ Modelo reporta fallo en actualización<br>";
        }
    } catch (Exception $e) {
        echo "✗ Error en actualización con modelo: " . $e->getMessage() . "<br>";
    }
} else {
    echo "✗ Modelo no puede obtener la ruta<br>";
}

// 6. Verificar configuración de MySQL
echo "<h2>6. Verificación de configuración MySQL</h2>";
$autocommit_result = $conn->query("SELECT @@autocommit")->fetch_row();
echo "Autocommit: " . ($autocommit_result[0] ? 'ACTIVADO' : 'DESACTIVADO') . "<br>";

$isolation_result = $conn->query("SELECT @@transaction_isolation")->fetch_row();
echo "Isolation level: " . $isolation_result[0] . "<br>";

echo "<h2>DIAGNÓSTICO COMPLETADO</h2>";
?>