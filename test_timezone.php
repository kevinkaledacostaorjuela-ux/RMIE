<!DOCTYPE html>
<html>
<head>
    <title>Test Zona Horaria</title>
    <style>
        body { font-family: monospace; margin: 20px; }
        .result { background: #f0f0f0; padding: 10px; margin: 10px 0; border-left: 3px solid #007bff; }
        .error { border-left-color: #dc3545; }
        .success { border-left-color: #28a745; }
    </style>
</head>
<body>
    <h2>🕒 Prueba de Zona Horaria - Colombia</h2>
    
<?php
try {
    echo '<div class="result">';
    echo "<strong>=== PRUEBA DE ZONA HORARIA ===</strong><br>";
    echo "Fecha/hora del servidor sin configuración: " . date('Y-m-d H:i:s') . "<br>";
    echo "Zona horaria del servidor: " . date_default_timezone_get() . "<br>";

    // Configurar zona horaria de Colombia
    date_default_timezone_set('America/Bogota');
    echo "Fecha/hora después de configurar Colombia: " . date('Y-m-d H:i:s') . "<br>";
    echo "Nueva zona horaria: " . date_default_timezone_get() . "<br>";

    // Probar con DateTime
    $datetime = new DateTime('now', new DateTimeZone('America/Bogota'));
    echo "Usando DateTime con zona Colombia: " . $datetime->format('Y-m-d H:i:s') . "<br>";
    echo '</div>';

    // Incluir configuración de base de datos para probar
    require_once 'config/db.php';
    echo '<div class="result">';
    $result = $conn->query("SELECT NOW() as tiempo_mysql");
    if ($result && $row = $result->fetch_assoc()) {
        echo "Tiempo de MySQL: " . $row['tiempo_mysql'] . "<br>";
    }
    echo '</div>';

    // Crear un reporte de prueba para verificar la fecha
    echo '<div class="result">';
    echo "<strong>=== PRUEBA DE CREACIÓN DE REPORTE ===</strong><br>";
    require_once 'app/models/Report.php';

    $data_prueba = [
        'nombre' => 'Reporte de Prueba Timezone ' . date('H:i:s'),
        'descripcion' => 'Prueba para verificar zona horaria',
        'tipo' => 'general',
        'estado' => 'activo',
        'parametros' => '{}',
        'fecha_creacion' => '' // Dejar vacío para que use fecha actual
    ];

    echo "Intentando crear reporte con fecha automática...<br>";
    $resultado = Report::create($conn, $data_prueba);
    echo "Resultado: " . ($resultado ? '<span style="color: green">SUCCESS ✓</span>' : '<span style="color: red">FAILED ✗</span>') . "<br>";

    // Obtener el último reporte creado para ver su fecha
    $ultimo_reporte = $conn->query("SELECT * FROM reportes ORDER BY id_reportes DESC LIMIT 1");
    if ($ultimo_reporte && $row = $ultimo_reporte->fetch_assoc()) {
        echo "Fecha del último reporte creado: <strong>" . $row['fecha'] . "</strong><br>";
        echo "ID del reporte: " . $row['id_reportes'] . "<br>";
        echo "Nombre: " . $row['nombre'] . "<br>";
    }
    echo '</div>';

} catch (Exception $e) {
    echo '<div class="result error">';
    echo "<strong>ERROR:</strong> " . htmlspecialchars($e->getMessage());
    echo '</div>';
}
?>

<p><a href="http://localhost/RMIE/app/controllers/ReportController.php?action=create">← Volver al formulario de reportes</a></p>

</body>
</html>
