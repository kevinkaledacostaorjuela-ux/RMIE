<?php
require 'config/db.php';

$id_producto = 68;

echo "<h2>Limpieza de datos vinculados al Producto ID: $id_producto</h2>";
echo "<hr>";

try {
    // 1. Eliminar de alertas_papelera
    $query1 = "DELETE FROM alertas_papelera WHERE id_productos = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("i", $id_producto);
    $stmt1->execute();
    $deleted1 = $stmt1->affected_rows;
    echo "✓ Eliminados $deleted1 registro(s) de alertas_papelera<br>";
    
    // 2. Eliminar de alertas (si existen)
    $query2 = "DELETE FROM alertas WHERE id_productos = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("i", $id_producto);
    $stmt2->execute();
    $deleted2 = $stmt2->affected_rows;
    echo "✓ Eliminados $deleted2 registro(s) de alertas<br>";
    
    // 3. Eliminar de ventas_productos
    $query3 = "DELETE FROM ventas_productos WHERE id_productos = ?";
    $stmt3 = $conn->prepare($query3);
    $stmt3->bind_param("i", $id_producto);
    $stmt3->execute();
    $deleted3 = $stmt3->affected_rows;
    echo "✓ Eliminados $deleted3 registro(s) de ventas_productos<br>";
    
    // 4. Eliminar de ventas
    $query4 = "DELETE FROM ventas WHERE id_productos = ?";
    $stmt4 = $conn->prepare($query4);
    $stmt4->bind_param("i", $id_producto);
    $stmt4->execute();
    $deleted4 = $stmt4->affected_rows;
    echo "✓ Eliminados $deleted4 registro(s) de ventas<br>";
    
    // 5. Eliminar de proveedores_productos
    $query5 = "DELETE FROM proveedores_productos WHERE id_producto = ?";
    $stmt5 = $conn->prepare($query5);
    $stmt5->bind_param("i", $id_producto);
    $stmt5->execute();
    $deleted5 = $stmt5->affected_rows;
    echo "✓ Eliminados $deleted5 registro(s) de proveedores_productos<br>";
    
    // 6. Finalmente, eliminar el producto
    $query6 = "DELETE FROM productos WHERE id_productos = ?";
    $stmt6 = $conn->prepare($query6);
    $stmt6->bind_param("i", $id_producto);
    $stmt6->execute();
    
    if ($stmt6->affected_rows > 0) {
        echo "<br><h3 style='color: green;'>✓ Producto ID $id_producto eliminado exitosamente</h3>";
    } else {
        echo "<br><h3 style='color: orange;'>⚠ El producto ya no existe</h3>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>✗ Error: " . $e->getMessage() . "</h3>";
}

echo "<hr>";
echo "<p><a href='/RMIE/app/controllers/ProductController.php?accion=index' class='btn btn-primary'>Volver a productos</a></p>";
?>
