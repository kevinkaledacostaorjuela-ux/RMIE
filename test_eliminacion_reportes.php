<?php
session_start();
require_once 'config/db.php';

echo "<!DOCTYPE html><html><head><title>Test Eliminación Reportes</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;}</style>";
echo "</head><body>";

echo "<h1>Test de Eliminación de Reportes - RMIE</h1>";

// Mostrar mensajes de sesión
if (isset($_SESSION['success'])) {
    echo "<div class='success'>✅ " . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='error'>❌ " . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}

echo "<h2>Reportes Disponibles</h2>";

$sql = "SELECT id_reportes, nombre, descripcion, estado FROM reportes ORDER BY id_reportes DESC LIMIT 10";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_reportes'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
        echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
        echo "<td>";
        echo "<button onclick=\"eliminarReporte(" . $row['id_reportes'] . ")\" style='background:#dc3545;color:white;border:none;padding:5px 10px;cursor:pointer;'>Eliminar</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay reportes disponibles</p>";
}

echo "<hr>";
echo "<h2>Enlaces de navegación</h2>";
echo "<p><a href='app/controllers/ReportController.php?action=index'>Ir al módulo oficial de reportes</a></p>";
echo "<p><a href='test_eliminacion_reportes.php'>Recargar esta página</a></p>";

echo "<script>";
echo "function eliminarReporte(id) {";
echo "    if (confirm('¿Está seguro de eliminar este reporte?\\n\\nEsta acción no se puede deshacer.')) {";
echo "        window.location.href = 'app/controllers/ReportController.php?action=delete&id=' + id + '&confirm=yes';";
echo "    }";
echo "}";
echo "</script>";

echo "</body></html>";
?>