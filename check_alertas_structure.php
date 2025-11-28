<?php
require_once __DIR__ . '/config/db.php';

echo "Columnas de la tabla alertas:\n";
$result = $conn->query('DESCRIBE alertas');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . "\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

echo "\n\nDatos de ejemplo:\n";
$result = $conn->query('SELECT * FROM alertas LIMIT 1');
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    print_r(array_keys($row));
}
?>
