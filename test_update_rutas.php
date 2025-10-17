<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/db.php';
require_once 'app/models/Route.php';

echo "<h1>🧪 Prueba de UPDATE de Rutas</h1>";

try {
    // Obtener datos actuales de la ruta 14
    echo "<h2>📋 Datos actuales de la ruta ID 14:</h2>";
    $route = Route::getById($conn, 14);
    if ($route) {
        echo "<pre>" . print_r($route, true) . "</pre>";
        
        // Probar update con un cambio pequeño
        echo "<h2>🔄 Intentando UPDATE con nueva dirección...</h2>";
        $nuevaDireccion = $route['direccion'] . " [EDITADO " . date('H:i:s') . "]";
        
        $success = Route::update(
            $conn, 
            14, 
            $nuevaDireccion, 
            $route['nombre_local'], 
            $route['nombre_cliente'], 
            $route['id_clientes'], 
            $route['id_ventas'], 
            $route['id_reportes']
        );
        
        if ($success) {
            echo "<p style='color: green;'>✅ UPDATE exitoso!</p>";
            
            // Verificar el cambio
            $updatedRoute = Route::getById($conn, 14);
            echo "<h3>📊 Datos después del UPDATE:</h3>";
            echo "<pre>" . print_r($updatedRoute, true) . "</pre>";
            
            // Restaurar valor original
            Route::update(
                $conn, 
                14, 
                $route['direccion'], 
                $route['nombre_local'], 
                $route['nombre_cliente'], 
                $route['id_clientes'], 
                $route['id_ventas'], 
                $route['id_reportes']
            );
            echo "<p style='color: blue;'>🔄 Valor original restaurado</p>";
            
        } else {
            echo "<p style='color: red;'>❌ UPDATE falló</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ No se encontró la ruta ID 14</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr><p><em>Prueba completada</em></p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
</style>