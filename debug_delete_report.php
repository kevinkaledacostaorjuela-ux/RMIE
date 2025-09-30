<?php
session_start();
require_once 'config/db.php';
require_once 'app/models/Report.php';

echo "<h2>🔧 Debug Eliminación de Reportes</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px; margin: 10px 0;'>";

// Mostrar información de debugging
echo "<h3>Información del Sistema:</h3>";
echo "<strong>Archivo actual:</strong> " . __FILE__ . "<br>";
echo "<strong>Directorio actual:</strong> " . __DIR__ . "<br>";
echo "<strong>URL solicitada:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";
echo "<strong>Método:</strong> " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "<strong>GET:</strong> " . print_r($_GET, true) . "<br>";
echo "<strong>POST:</strong> " . print_r($_POST, true) . "<br>";

echo "</div>";

// Listar reportes disponibles
$sql = "SELECT id_reportes, nombre, descripcion FROM reportes ORDER BY id_reportes DESC LIMIT 5";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<h3>📋 Reportes Disponibles:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr style='background: #e9ecef;'><th>ID</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id_reportes']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars(substr($row['descripcion'], 0, 50)) . "...</td>";
        echo "<td>";
        echo "<a href='app/controllers/ReportController.php?action=delete&id=" . $row['id_reportes'] . "' style='background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin: 2px;'>Eliminar via Controller</a>";
        echo "<a href='debug_delete_report.php?action=direct_delete&id=" . $row['id_reportes'] . "' style='background: #fd7e14; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin: 2px;' onclick='return confirm(\"¿Eliminar directamente?\")'>Eliminar Directo</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>❌ No hay reportes disponibles o error en consulta: " . $conn->error . "</p>";
}

// Procesamiento de eliminación directa (para testing)
if (isset($_GET['action']) && $_GET['action'] === 'direct_delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    echo "<hr>";
    echo "<h3>🗑️ Procesando Eliminación Directa del Reporte ID: $id</h3>";
    
    $stmt = $conn->prepare("DELETE FROM reportes WHERE id_reportes = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<div style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724; margin: 10px 0;'>";
            echo "✅ Reporte eliminado exitosamente (Filas afectadas: " . $stmt->affected_rows . ")";
            echo "</div>";
            echo "<p><a href='debug_delete_report.php' style='background: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;'>🔄 Actualizar Lista</a></p>";
        } else {
            echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; color: #721c24; margin: 10px 0;'>";
            echo "❌ Error al eliminar: " . $stmt->error;
            echo "</div>";
        }
        $stmt->close();
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; color: #721c24; margin: 10px 0;'>";
        echo "❌ Error preparando consulta: " . $conn->error;
        echo "</div>";
    }
}

echo "<hr>";
echo "<h3>🔗 Enlaces de Navegación:</h3>";
echo "<a href='app/controllers/ReportController.php?action=index' style='background: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>📊 Volver a Reportes</a>";
echo "<a href='app/views/dashboard.php' style='background: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>🏠 Dashboard</a>";
echo "<a href='debug_delete_report.php' style='background: #17a2b8; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>🔄 Recargar Debug</a>";

$conn->close();
?>