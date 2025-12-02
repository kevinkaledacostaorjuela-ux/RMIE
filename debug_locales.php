<?php
// Test debug del modelo Local
require_once 'config/db.php';
require_once 'app/models/Local.php';

echo "<h1>Debug del modelo Local</h1>\n";

// Probar la consulta directamente
$locales = Local::getAll($conn);

echo "<h2>Resultados:</h2>\n";
echo "<p>Total de locales encontrados: " . count($locales) . "</p>\n";

foreach ($locales as $i => $local) {
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<h3>Local " . ($i + 1) . "</h3>";
    echo "<p><strong>ID:</strong> " . $local->id_locales . "</p>";
    echo "<p><strong>Nombre:</strong> " . htmlspecialchars($local->nombre_local) . "</p>";
    echo "<p><strong>Total clientes:</strong> " . $local->total_clientes . "</p>";
    echo "<p><strong>Nombres clientes:</strong> " . ($local->nombres_clientes ?: 'NULL') . "</p>";
    echo "<p><strong>Estado:</strong> " . $local->estado . "</p>";
    echo "</div>";
}

// Probar también la consulta SQL directamente
echo "<h2>Consulta SQL directa:</h2>\n";
$sql = "SELECT l.*, 
               COUNT(lc.id_clientes) as total_clientes,
               GROUP_CONCAT(c.nombre SEPARATOR ', ') as nombres_clientes
        FROM locales l
        LEFT JOIN locales_clientes lc ON l.id_locales = lc.id_locales
        LEFT JOIN clientes c ON lc.id_clientes = c.id_clientes
        WHERE 1=1
        GROUP BY l.id_locales 
        ORDER BY l.fecha_creacion DESC";

$result = $conn->query($sql);
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Total Clientes</th><th>Nombres Clientes</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id_locales'] . "</td>";
    echo "<td>" . htmlspecialchars($row['nombre_local']) . "</td>";
    echo "<td>" . $row['total_clientes'] . "</td>";
    echo "<td>" . ($row['nombres_clientes'] ?: 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";
?>