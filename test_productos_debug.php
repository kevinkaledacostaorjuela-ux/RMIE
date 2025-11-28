<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Product.php';

echo "Probando conexión y productos...\n";

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . $conn->connect_error);
}

echo "Conexión OK\n";

// Obtener productos
$productos = Product::getAll($conn);

echo "Tipo de \$productos: " . gettype($productos) . "\n";
echo "Cantidad de productos: " . (is_array($productos) ? count($productos) : 'N/A') . "\n";

if (is_array($productos) && count($productos) > 0) {
    echo "Primeros 5 productos:\n";
    for ($i = 0; $i < min(5, count($productos)); $i++) {
        echo "  " . ($i + 1) . ". " . $productos[$i]->nombre . " (ID: " . $productos[$i]->id_productos . ")\n";
    }
} else {
    echo "NO HAY PRODUCTOS O ERROR\n";
    // Hacer query directa
    echo "\nIntentando query directa...\n";
    $result = $conn->query("SELECT COUNT(*) as total FROM productos");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Total de productos en BD: " . $row['total'] . "\n";
    } else {
        echo "Error en query: " . $conn->error . "\n";
    }
}
?>
