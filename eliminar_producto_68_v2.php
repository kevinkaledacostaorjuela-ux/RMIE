<?php
require 'config/db.php';

$id_producto = 68;

echo "<h2>Eliminación del Producto ID: $id_producto</h2>";
echo "<hr>";

try {
    // Desactivar restricciones
    $conn->query("SET FOREIGN_KEY_CHECKS=0");
    
    // Ejecutar eliminaciones en orden correcto
    $queries = [
        ["DELETE FROM alertas_papelera WHERE id_productos = ?", "alertas_papelera"],
        ["DELETE FROM alertas WHERE id_productos = ?", "alertas"],
        ["DELETE FROM reportes WHERE id_productos = ?", "reportes"],
        ["DELETE FROM ventas WHERE id_productos = ?", "ventas"],
        ["DELETE FROM ventas_productos WHERE id_productos = ?", "ventas_productos"],
        ["DELETE FROM proveedores_productos WHERE id_producto = ?", "proveedores_productos"],
        ["DELETE FROM productos WHERE id_productos = ?", "productos"]
    ];
    
    foreach ($queries as $query_data) {
        $query = $query_data[0];
        $table = $query_data[1];
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo "❌ Error en preparación de $table: " . $conn->error . "<br>";
            continue;
        }
        
        $stmt->bind_param("i", $id_producto);
        if (!$stmt->execute()) {
            echo "❌ Error ejecutando en $table: " . $stmt->error . "<br>";
        } else {
            $affected = $stmt->affected_rows;
            echo "✓ $table: $affected registros eliminados<br>";
        }
        $stmt->close();
    }
    
    // Reactivar restricciones
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    
    echo "<br><h3 style='color: green;'>✓✓✓ PRODUCTO ELIMINADO</h3>";
    
} catch (Exception $e) {
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    echo "<h3 style='color: red;'>Error: " . $e->getMessage() . "</h3>";
}

echo "<hr>";
echo "<p><a href='/RMIE/app/controllers/ProductController.php?accion=index' class='btn btn-primary'>Volver a productos</a></p>";
?>
