<?php
// Script de diagnóstico para limpiar OPcache (si está disponible) y mostrar propiedades de Product
header('Content-Type: text/html; charset=utf-8');

echo "<pre>OPcache diagnostic:\n";
if (function_exists('opcache_reset')) {
    try {
        $res = @opcache_reset();
        echo "opcache_reset() -> ";
        var_export($res);
        echo "\n";
    } catch (Throwable $e) {
        echo "opcache_reset() threw: " . $e->getMessage() . "\n";
    }
} else {
    echo "opcache_reset not available\n";
}

require_once __DIR__ . '/app/models/Product.php';
$p = new Product(1, 'Prueba', 'desc', date('Y-m-d'), null, null, 10, 5.0, 0, 0, 'Marca', 1, 1);

echo "Property 'nombre_producto' exists? ";
var_export(property_exists($p, 'nombre_producto'));
echo "\n\nObject dump:\n";
print_r($p);

echo "</pre>";

?>