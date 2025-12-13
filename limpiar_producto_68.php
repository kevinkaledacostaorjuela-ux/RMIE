<?php
require 'config/db.php';

echo "<h2>Reparación completa de restricciones de clave foránea</h2>";
echo "<hr>";

try {
    // Paso 1: Desactivar restricciones temporalmente
    $conn->query("SET FOREIGN_KEY_CHECKS=0");
    echo "✓ Desactivadas restricciones<br><br>";
    
    // Paso 2: Limpiar datos huérfanos del producto 68
    echo "<strong>Limpiando datos del producto 68:</strong><br>";
    
    $tables = [
        'alertas_papelera' => 'id_productos',
        'alertas' => 'id_productos',
        'ventas_productos' => 'id_productos',
        'ventas' => 'id_productos',
        'reportes' => 'id_productos',
        'proveedores_productos' => 'id_producto'
    ];
    
    foreach ($tables as $table => $column) {
        $query = "DELETE FROM $table WHERE $column = 68";
        $result = $conn->query($query);
        $affected = $conn->affected_rows;
        echo "  ✓ Eliminados $affected registros de $table<br>";
    }
    
    // Paso 3: Eliminar el producto
    echo "<br><strong>Eliminando producto 68:</strong><br>";
    $query_producto = "DELETE FROM productos WHERE id_productos = 68";
    $result = $conn->query($query_producto);
    echo "  ✓ Producto eliminado<br>";
    
    // Paso 4: Reactivar restricciones
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    echo "<br>✓ Restricciones reactivadas<br>";
    
    echo "<br><hr>";
    echo "<h3 style='color: green;'>✓✓✓ PRODUCTO 68 ELIMINADO EXITOSAMENTE</h3>";
    
} catch (Exception $e) {
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    echo "<h3 style='color: red;'>✗ Error: " . $e->getMessage() . "</h3>";
}

echo "<hr>";
echo "<p><a href='/RMIE/app/controllers/ProductController.php?accion=index' class='btn btn-primary'>Volver a productos</a></p>";
?>
