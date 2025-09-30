<?php
session_start();
require_once 'config/db.php';
require_once 'app/models/Report.php';

echo "<!DOCTYPE html><html><head><title>Debug Reportes</title></head><body>";
echo "<h1>Debug Eliminación de Reportes</h1>";

echo "<h2>1. Verificando conexión a la base de datos</h2>";
if ($conn->connect_error) {
    echo "❌ Error de conexión: " . $conn->connect_error;
} else {
    echo "✅ Conexión exitosa a la base de datos";
}

echo "<h2>2. Listando reportes existentes</h2>";
$sql = "SELECT id_reportes, nombre, descripcion FROM reportes LIMIT 10";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_reportes'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
        echo "<td>";
        
        // Enlace directo al controlador
        echo "<a href='app/controllers/ReportController.php?action=delete&id=" . $row['id_reportes'] . "' ";
        echo "style='background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; margin: 2px;'>Eliminar</a>";
        
        // Enlace a nuestra página de debug
        echo "<a href='debug_reportes.php?action=test_delete&id=" . $row['id_reportes'] . "' ";
        echo "style='background: #007bff; color: white; padding: 5px 10px; text-decoration: none; margin: 2px;'>Test Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay reportes en la base de datos</p>";
}

// Procesar test de eliminación
if (isset($_GET['action']) && $_GET['action'] === 'test_delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    echo "<hr><h2>3. Procesando eliminación de prueba para ID: $id</h2>";
    
    echo "<p><strong>Método:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
    echo "<p><strong>URL actual:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
    echo "<p><strong>Script actual:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
    
    // Verificar que el reporte existe
    $report = Report::getById($conn, $id);
    if ($report) {
        echo "<p>✅ Reporte encontrado: " . htmlspecialchars($report['nombre']) . "</p>";
        
        // Intentar eliminar
        echo "<p>Intentando eliminar...</p>";
        $result = Report::delete($conn, $id);
        
        if ($result) {
            echo "<p style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb;'>✅ Reporte eliminado exitosamente</p>";
            echo "<p><a href='debug_reportes.php'>Actualizar lista</a></p>";
        } else {
            echo "<p style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;'>❌ Error al eliminar el reporte</p>";
        }
    } else {
        echo "<p style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;'>❌ Reporte no encontrado</p>";
    }
}

echo "<hr>";
echo "<h2>4. Enlaces de navegación</h2>";
echo "<p><a href='app/controllers/ReportController.php?action=index'>Ver módulo de reportes oficial</a></p>";
echo "<p><a href='debug_reportes.php'>Recargar esta página</a></p>";
echo "<p><a href='app/views/dashboard.php'>Ir al dashboard</a></p>";

echo "</body></html>";
?>