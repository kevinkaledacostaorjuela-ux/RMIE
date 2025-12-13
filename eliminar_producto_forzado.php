<?php
require 'config/db.php';

$id_producto = 68;

echo "<h2>Eliminación forzada del Producto ID: $id_producto</h2>";
echo "<hr>";

try {
    // Desactivar restricciones de clave foránea temporalmente
    $conn->query("SET FOREIGN_KEY_CHECKS=0");
    echo "✓ Desactivadas restricciones de clave foránea<br>";
    
    // 1. Ver qué registros hay relacionados
    $query_check = "SELECT * FROM alertas_papelera WHERE id_productos = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("i", $id_producto);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $count_papelera = $result_check->num_rows;
    echo "✓ Encontrados $count_papelera registro(s) en alertas_papelera<br>";
    
    // 2. Eliminar de alertas_papelera
    $query1 = "DELETE FROM alertas_papelera WHERE id_productos = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("i", $id_producto);
    $stmt1->execute();
    $deleted1 = $stmt1->affected_rows;
    echo "✓ Eliminados $deleted1 registro(s) de alertas_papelera<br>";
    
    // 3. Eliminar de alertas
    $query2 = "DELETE FROM alertas WHERE id_productos = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("i", $id_producto);
    $stmt2->execute();
    $deleted2 = $stmt2->affected_rows;
    echo "✓ Eliminados $deleted2 registro(s) de alertas<br>";
    
    // 4. Eliminar de notificaciones_alertas
    $query_notif = "DELETE FROM notificaciones_alertas WHERE id_productos = ?";
    $stmt_notif = $conn->prepare($query_notif);
    $stmt_notif->bind_param("i", $id_producto);
    $stmt_notif->execute();
    $deleted_notif = $stmt_notif->affected_rows;
    echo "✓ Eliminados $deleted_notif registro(s) de notificaciones_alertas<br>";
    
    // 5. Eliminar de ventas_productos
    $query3 = "DELETE FROM ventas_productos WHERE id_productos = ?";
    $stmt3 = $conn->prepare($query3);
    $stmt3->bind_param("i", $id_producto);
    $stmt3->execute();
    $deleted3 = $stmt3->affected_rows;
    echo "✓ Eliminados $deleted3 registro(s) de ventas_productos<br>";
    
    // 6. Eliminar de ventas
    $query4 = "DELETE FROM ventas WHERE id_productos = ?";
    $stmt4 = $conn->prepare($query4);
    $stmt4->bind_param("i", $id_producto);
    $stmt4->execute();
    $deleted4 = $stmt4->affected_rows;
    echo "✓ Eliminados $deleted4 registro(s) de ventas<br>";
    
    // 7. Eliminar de proveedores_productos
    $query5 = "DELETE FROM proveedores_productos WHERE id_producto = ?";
    $stmt5 = $conn->prepare($query5);
    $stmt5->bind_param("i", $id_producto);
    $stmt5->execute();
    $deleted5 = $stmt5->affected_rows;
    echo "✓ Eliminados $deleted5 registro(s) de proveedores_productos<br>";
    
    // 8. Finalmente, eliminar el producto
    $query6 = "DELETE FROM productos WHERE id_productos = ?";
    $stmt6 = $conn->prepare($query6);
    $stmt6->bind_param("i", $id_producto);
    $stmt6->execute();
    $deleted6 = $stmt6->affected_rows;
    echo "✓ Eliminados $deleted6 registro(s) de productos<br>";
    
    // Reactivar restricciones de clave foránea
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    echo "<br>✓ Reactivadas restricciones de clave foránea<br>";
    
    if ($deleted6 > 0) {
        echo "<br><h3 style='color: green;'>✓✓✓ Producto ID $id_producto eliminado exitosamente</h3>";
    } else {
        echo "<br><h3 style='color: orange;'>⚠ El producto ya no existe</h3>";
    }
    
} catch (Exception $e) {
    // Asegurar que se reactive las restricciones en caso de error
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    echo "<h3 style='color: red;'>✗ Error: " . $e->getMessage() . "</h3>";
}

echo "<hr>";
echo "<p><a href='/RMIE/app/controllers/ProductController.php?accion=index' class='btn btn-primary'>Volver a productos</a></p>";
?>
