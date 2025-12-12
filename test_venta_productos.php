<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Sale.php';

$id_venta = 61;

echo "<h2>Test de productos de venta #$id_venta</h2>";

$productos = Sale::getProductos($conn, $id_venta);

echo "<pre>";
echo "Total de productos: " . count($productos) . "\n\n";

foreach ($productos as $p) {
    echo "Producto:\n";
    echo "  - ID: " . ($p->id_productos ?? 'N/A') . "\n";
    echo "  - Nombre: " . ($p->nombre ?? 'N/A') . "\n";
    echo "  - Cantidad: " . ($p->cantidad ?? 'N/A') . "\n";
    echo "  - Precio Unitario: " . ($p->precio_unitario ?? 'N/A') . "\n";
    echo "  - Subtotal: " . ($p->subtotal ?? 'N/A') . "\n";
    echo "  - Stock: " . ($p->stock ?? 'N/A') . "\n";
    echo "\n";
}

echo "</pre>";

echo "<h3>Objeto completo:</h3>";
echo "<pre>";
print_r($productos);
echo "</pre>";
?>
