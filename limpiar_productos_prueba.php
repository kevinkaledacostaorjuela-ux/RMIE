<?php
require 'config/db.php';

// IDs de los productos de prueba que quieres eliminar
$productos_a_eliminar = [204, 207];

echo "<h2>Limpieza de datos de prueba</h2>";
echo "<hr>";

foreach ($productos_a_eliminar as $id_producto) {
    echo "<h3>Procesando Producto ID: $id_producto</h3>";
    
    // 1. Ver qué ventas tiene este producto
    $query = "SELECT id_ventas FROM ventas WHERE id_productos = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $ventas_encontradas = [];
    while ($row = $result->fetch_assoc()) {
        $ventas_encontradas[] = $row['id_ventas'];
    }
    
    if (!empty($ventas_encontradas)) {
        echo "✓ Encontradas " . count($ventas_encontradas) . " venta(s): " . implode(", ", $ventas_encontradas) . "<br>";
        
        // 2. Eliminar registros en ventas_productos (tabla intermedia si existe)
        $query_intermedia = "DELETE FROM ventas_productos WHERE id_ventas IN (" . implode(",", $ventas_encontradas) . ")";
        $conn->query($query_intermedia);
        echo "  ✓ Eliminados registros de ventas_productos<br>";
        
        // 3. Eliminar las ventas
        $query_delete = "DELETE FROM ventas WHERE id_productos = ?";
        $stmt_delete = $conn->prepare($query_delete);
        $stmt_delete->bind_param("i", $id_producto);
        $stmt_delete->execute();
        echo "  ✓ Eliminadas las ventas<br>";
    } else {
        echo "ℹ No hay ventas asociadas<br>";
    }
    
    echo "<br>";
}

echo "<hr>";
echo "<h3 style='color: green;'>✓ Limpieza completada</h3>";
echo "<p>Ahora puedes eliminar los productos desde el panel de administración sin problemas.</p>";
echo "<p><a href='/RMIE/app/controllers/ProductController.php?accion=index' class='btn btn-primary'>Volver a productos</a></p>";
?>
