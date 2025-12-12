<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Sale.php';
require_once __DIR__ . '/app/models/Client.php';
require_once __DIR__ . '/app/models/User.php';

$id = 61;

echo "<h2>Debug Venta #$id</h2>";

// Cargar venta
$venta = Sale::getById($conn, $id);

echo "<h3>Datos de la venta:</h3>";
echo "<pre>";
print_r($venta);
echo "</pre>";

// Cargar productos
$venta->productos_asignados = Sale::getProductos($conn, $id);

echo "<h3>Productos asignados (" . count($venta->productos_asignados) . "):</h3>";
echo "<pre>";
print_r($venta->productos_asignados);
echo "</pre>";

// Mostrar tabla como en la vista
echo "<h3>Tabla de productos:</h3>";
echo '<table border="1" cellpadding="5">';
echo '<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>';

if (!empty($venta->productos_asignados)) {
    foreach ($venta->productos_asignados as $p) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($p->nombre ?? 'SIN NOMBRE') . '</td>';
        echo '<td>' . htmlspecialchars($p->cantidad ?? 1) . '</td>';
        echo '<td>$' . number_format(floatval($p->precio_unitario ?? 0), 2) . '</td>';
        echo '<td>$' . number_format(floatval($p->subtotal ?? 0), 2) . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">Sin productos asignados</td></tr>';
}

echo '</table>';

// Cliente
$cliente = Client::getById($conn, $venta->id_clientes);
echo "<h3>Cliente:</h3>";
echo "<pre>";
print_r($cliente);
echo "</pre>";

// Usuario
$usuario = User::getById($conn, $venta->num_doc);
echo "<h3>Usuario:</h3>";
echo "<pre>";
print_r($usuario);
echo "</pre>";
?>
