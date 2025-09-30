<?php
session_start();
require_once 'config/db.php';

echo "<h2>Test de eliminación de reportes</h2>";

// Listar reportes disponibles
$sql = "SELECT id_reportes, nombre, descripcion FROM reportes LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Reportes disponibles:</h3>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "ID: " . $row['id_reportes'] . " - " . $row['nombre'];
        echo " <a href='test_delete_report.php?action=delete&id=" . $row['id_reportes'] . "' onclick=\"return confirm('¿Eliminar?')\">Eliminar</a>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No hay reportes disponibles</p>";
}

// Procesar eliminación
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    echo "<hr>";
    echo "<h3>Procesando eliminación del reporte ID: $id</h3>";
    
    $stmt = $conn->prepare("DELETE FROM reportes WHERE id_reportes = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<div style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;'>";
        echo "✅ Reporte eliminado exitosamente";
        echo "</div>";
        echo "<p><a href='test_delete_report.php'>Ver lista actualizada</a></p>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; color: #721c24;'>";
        echo "❌ Error al eliminar: " . $conn->error;
        echo "</div>";
    }
}

echo "<hr>";
echo "<p><a href='app/controllers/ReportController.php?action=index'>Volver a reportes</a></p>";
?>