<?php
session_start();
require_once 'config/db.php';

echo "<!DOCTYPE html><html><head><title>Test Edición Clientes - RMIE</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;}</style>";
echo "</head><body>";

echo "<h1>Test de Edición de Clientes - RMIE</h1>";

echo "<h2>Clientes Disponibles para Editar</h2>";

// Listar clientes disponibles
$sql = "SELECT id_clientes, nombres, apellidos, correo FROM clientes LIMIT 5";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Nombres</th><th>Apellidos</th><th>Correo</th><th>Acciones de Test</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_clientes'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
        echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
        echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
        echo "<td>";
        
        // Enlace corregido para editar
        echo "<a href='/RMIE/app/controllers/ClientController.php?accion=edit&id=" . $row['id_clientes'] . "' ";
        echo "style='background: #007bff; color: white; padding: 5px 10px; text-decoration: none; margin: 2px; border-radius: 3px;'>Editar</a>";
        
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay clientes disponibles para editar</p>";
}

echo "<hr>";
echo "<h2>Pruebas de Enlaces</h2>";

echo "<div class='info'>";
echo "<h3>Enlaces Corregidos:</h3>";
echo "<p>✅ Formulario de edición: <code>/RMIE/app/controllers/ClientController.php?accion=edit&id=X</code></p>";
echo "<p>✅ Breadcrumb: <code>/RMIE/app/controllers/ClientController.php?accion=index</code></p>";
echo "<p>✅ Botón cancelar: <code>/RMIE/app/controllers/ClientController.php?accion=index</code></p>";
echo "</div>";

echo "<div class='error'>";
echo "<h3>URLs Problemáticas (ya corregidas):</h3>";
echo "<p>❌ <s>../../controllers/ClientController.php</s> → Causaba error 404</p>";
echo "<p>❌ <s>/RMIE/controllers/ClientController.php</s> → Falta 'app' en la ruta</p>";
echo "</div>";

echo "<hr>";
echo "<h2>Estado de Sesión</h2>";
if (isset($_SESSION['user'])) {
    echo "<p class='success'>✅ Usuario logueado: " . htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') . "</p>";
} else {
    echo "<p class='error'>❌ No hay usuario logueado - <a href='index.php'>Login</a></p>";
}

echo "<hr>";
echo "<h2>Enlaces de Navegación</h2>";
echo "<p><a href='/RMIE/app/controllers/ClientController.php?accion=index'>Ir al módulo oficial de clientes</a></p>";
echo "<p><a href='test_enlaces_clientes.php'>Recargar este test</a></p>";
echo "<p><a href='app/views/dashboard.php'>Volver al Dashboard</a></p>";

echo "</body></html>";
?>