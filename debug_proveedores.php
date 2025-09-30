<?php
require_once 'config/db.php';

echo "<h1>Debug - Estructura de la tabla proveedores</h1>";

// Mostrar estructura de la tabla
echo "<h2>1. Estructura de la tabla</h2>";
$sql = "DESCRIBE proveedores";
$result = $conn->query($sql);

if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

// Mostrar algunos registros de ejemplo
echo "<h2>2. Registros de ejemplo (primeros 3)</h2>";
$sql = "SELECT * FROM proveedores LIMIT 3";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    
    // Encabezados
    $first_row = $result->fetch_assoc();
    echo "<tr>";
    foreach($first_row as $key => $value) {
        echo "<th>$key</th>";
    }
    echo "</tr>";
    
    // Primera fila
    echo "<tr>";
    foreach($first_row as $value) {
        echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
    }
    echo "</tr>";
    
    // Resto de filas
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay registros o error: " . $conn->error;
}

// Mostrar modelo Provider actual
echo "<h2>3. Propiedades del modelo Provider actual</h2>";
echo "<ul>";
echo "<li>id_proveedores</li>";
echo "<li>nombre_distribuidor</li>";
echo "<li>correo</li>";
echo "<li>cel_proveedor</li>";
echo "<li>estado</li>";
echo "</ul>";

$conn->close();
?>