<?php
require 'config/db.php';

echo "<h2>Diagnóstico de estructura de tablas</h2>";
echo "<hr>";

$tables_to_check = [
    'alertas_papelera',
    'alertas',
    'reportes',
    'ventas',
    'ventas_productos',
    'proveedores_productos',
    'notificaciones_alertas',
    'productos'
];

foreach ($tables_to_check as $table) {
    echo "<h3>Tabla: $table</h3>";
    
    $query = "DESCRIBE $table";
    $result = $conn->query($query);
    
    if (!$result) {
        echo "❌ Tabla no existe o error: " . $conn->error . "<br>";
        continue;
    }
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<br>";
}
?>
