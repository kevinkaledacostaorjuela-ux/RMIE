<?php
require_once 'config/db.php';
$result = $conn->query('DESCRIBE reportes');
echo "Estructura de la tabla reportes:\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}
?>