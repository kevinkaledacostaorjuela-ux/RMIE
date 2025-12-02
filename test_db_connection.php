<?php
// Test de conectividad y datos - RMIE
require_once __DIR__ . '/config/db.php';

echo "<h2>Test de Conexión a Base de Datos RMIE</h2>";

// Test de conexión
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $conn->connect_error . "</p>";
    exit;
} else {
    echo "<p style='color: green;'>✅ Conexión exitosa a la base de datos</p>";
}

// Test de tabla locales
echo "<h3>Test tabla 'locales':</h3>";
$sql = "SHOW TABLES LIKE 'locales'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Tabla 'locales' existe</p>";
    
    // Contar registros
    $sql = "SELECT COUNT(*) as total FROM locales";
    $result = $conn->query($sql);
    $total = $result->fetch_assoc()['total'];
    echo "<p>Total de locales: $total</p>";
    
    // Mostrar algunos registros
    $sql = "SELECT id_locales, nombre_local, direccion, estado FROM locales LIMIT 5";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Nombre</th><th>Dirección</th><th>Estado</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['id_locales']}</td><td>{$row['nombre_local']}</td><td>{$row['direccion']}</td><td>{$row['estado']}</td></tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Tabla 'locales' no existe</p>";
}

// Test de tabla clientes
echo "<h3>Test tabla 'clientes':</h3>";
$sql = "SHOW TABLES LIKE 'clientes'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Tabla 'clientes' existe</p>";
    
    // Contar registros
    $sql = "SELECT COUNT(*) as total FROM clientes";
    $result = $conn->query($sql);
    $total = $result->fetch_assoc()['total'];
    echo "<p>Total de clientes: $total</p>";
    
    // Mostrar algunos registros
    $sql = "SELECT id_clientes, nombre, cel_cliente, estado FROM clientes LIMIT 5";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Nombre</th><th>Teléfono</th><th>Estado</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['id_clientes']}</td><td>{$row['nombre']}</td><td>{$row['cel_cliente']}</td><td>{$row['estado']}</td></tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Tabla 'clientes' no existe</p>";
}

// Probar APIs directamente
echo "<h3>Test de APIs:</h3>";

echo "<h4>API get_locales.php:</h4>";
$url = 'http://localhost/RMIE/app/api/get_locales.php';
$context = stream_context_create([
    'http' => [
        'timeout' => 10
    ]
]);
$response = file_get_contents($url, false, $context);
if ($response) {
    $data = json_decode($response, true);
    echo "<p style='color: green;'>✅ API locales responde: " . count($data) . " registros</p>";
    echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "<p style='color: red;'>❌ Error al consultar API locales</p>";
}

echo "<h4>API get_clientes.php:</h4>";
$url = 'http://localhost/RMIE/app/api/get_clientes.php';
$response = file_get_contents($url, false, $context);
if ($response) {
    $data = json_decode($response, true);
    echo "<p style='color: green;'>✅ API clientes responde: " . count($data) . " registros</p>";
    echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "<p style='color: red;'>❌ Error al consultar API clientes</p>";
}

$conn->close();
?>