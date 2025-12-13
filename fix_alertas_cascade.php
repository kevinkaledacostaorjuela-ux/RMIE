<?php
require 'config/db.php';

echo "<h2>Modificar tabla alertas_papelera para eliminar automáticamente registros</h2>";
echo "<hr>";

try {
    // 1. Eliminar la clave foránea existente
    $query1 = "ALTER TABLE alertas_papelera DROP FOREIGN KEY alertas_papelera_ibfk_1";
    $conn->query($query1);
    echo "✓ Eliminada clave foránea antigua<br>";
    
    // 2. Agregar clave foránea con ON DELETE CASCADE
    $query2 = "ALTER TABLE alertas_papelera ADD CONSTRAINT alertas_papelera_ibfk_1 
               FOREIGN KEY (id_productos) REFERENCES productos(id_productos) ON DELETE CASCADE";
    $conn->query($query2);
    echo "✓ Agregada clave foránea con ON DELETE CASCADE<br>";
    
    echo "<br><h3 style='color: green;'>✓ Tabla modificada correctamente</h3>";
    echo "<p>Ahora cuando elimines un producto, las alertas en papelera se eliminarán automáticamente.</p>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>✗ Error: " . $e->getMessage() . "</h3>";
}

echo "<hr>";
echo "<p><a href='/RMIE/app/controllers/ProductController.php?accion=index' class='btn btn-primary'>Ir a productos</a></p>";
?>
