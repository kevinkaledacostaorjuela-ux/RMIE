<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Sale.php';

$id_venta = 61;

echo "<h1>Prueba de Sale::getProductos($id_venta)</h1>";

$productos = Sale::getProductos($conn, $id_venta);

echo "<p><strong>Total de productos retornados:</strong> " . count($productos) . "</p>";

if (count($productos) > 0) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #667eea; color: white;'>";
    echo "<th>ID Producto</th><th>Nombre</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th><th>Stock</th>";
    echo "</tr>";
    
    foreach ($productos as $p) {
        echo "<tr>";
        echo "<td>" . ($p->id_productos ?? 'N/A') . "</td>";
        echo "<td>" . ($p->nombre ?? '<span style="color:red;">SIN NOMBRE</span>') . "</td>";
        echo "<td>" . ($p->cantidad ?? 'N/A') . "</td>";
        echo "<td>$" . number_format($p->precio_unitario ?? 0, 2) . "</td>";
        echo "<td>$" . number_format($p->subtotal ?? 0, 2) . "</td>";
        echo "<td>" . ($p->stock ?? 'N/A') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<h2>Objeto completo del primer producto:</h2>";
    echo "<pre>";
    var_dump($productos[0]);
    echo "</pre>";
    
} else {
    echo "<p style='color: red; font-weight: bold;'>⚠️ NO SE RETORNARON PRODUCTOS</p>";
}

// Verificar query directamente
echo "<h2>Query directa a la base de datos:</h2>";
$sql = "SELECT vp.*, p.nombre, p.descripcion, p.precio_unitario as precio_producto, p.stock 
        FROM ventas_productos vp
        INNER JOIN productos p ON vp.id_productos = p.id_productos
        WHERE vp.id_ventas = $id_venta
        ORDER BY p.nombre";

$result = $conn->query($sql);

if ($result) {
    echo "<p><strong>Filas encontradas:</strong> " . $result->num_rows . "</p>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #764ba2; color: white;'>";
        echo "<th>Columnas disponibles</th></tr>";
        
        $first_row = $result->fetch_assoc();
        foreach ($first_row as $key => $value) {
            echo "<tr><td><strong>$key:</strong> $value</td></tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p style='color: red;'>Error en query: " . $conn->error . "</p>";
}
?>
