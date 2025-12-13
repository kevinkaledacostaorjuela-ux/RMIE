<?php
require 'config/db.php';

echo "\n=== VERIFICANDO TABLA VENTAS ===\n";

// Ver cuántos registros hay en ventas
$query = "SELECT COUNT(*) as total FROM ventas";
$result = $conn->query($query);
$count = $result->fetch_assoc();
echo "Total de registros en VENTAS: " . $count['total'] . "\n";

// Ver productos que tienen ventas asociadas
$query2 = "SELECT DISTINCT id_productos FROM ventas LIMIT 20";
$result2 = $conn->query($query2);
echo "\nProductos con ventas asociadas:\n";
while($row = $result2->fetch_assoc()) {
    echo "  - ID Producto: " . $row['id_productos'] . "\n";
}

// Ver todas las ventas
$query3 = "SELECT id_ventas, id_productos FROM ventas LIMIT 10";
$result3 = $conn->query($query3);
echo "\nPrimeros 10 registros de ventas:\n";
while($row = $result3->fetch_assoc()) {
    echo "  Venta ID: " . $row['id_ventas'] . ", Producto ID: " . $row['id_productos'] . "\n";
}

echo "\n";
?>
