<?php
/**
 * Endpoint de prueba para verificar funcionalidad de planificación semanal
 */

require_once __DIR__ . '/../config/db.php';

echo "<h1>🧪 Test de Planificación Semanal - RMIE</h1>";

try {
    // Verificar conexión a BD
    echo "<h2>✅ Conexión a Base de Datos</h2>";
    echo "<p>Estado: " . ($conn->ping() ? "✅ Conectado" : "❌ Desconectado") . "</p>";

    // Verificar tablas
    echo "<h2>📋 Verificación de Tablas</h2>";
    
    $tablas = ['ruta_clientes_semanales', 'rutas_semanales', 'clientes'];
    foreach ($tablas as $tabla) {
        $result = $conn->query("SHOW TABLES LIKE '$tabla'");
        echo "<p>Tabla '$tabla': " . ($result && $result->num_rows > 0 ? "✅ Existe" : "❌ No existe") . "</p>";
    }

    // Verificar datos de prueba
    echo "<h2>📊 Datos de Prueba</h2>";
    
    $result = $conn->query("SELECT COUNT(*) as total FROM ruta_clientes_semanales");
    $total = $result->fetch_assoc()['total'];
    echo "<p>Asignaciones semanales: <strong>$total</strong></p>";

    $result = $conn->query("SELECT COUNT(*) as total FROM clientes");
    $total = $result->fetch_assoc()['total'];
    echo "<p>Clientes totales: <strong>$total</strong></p>";

    // Mostrar datos por día
    echo "<h2>📅 Planificación por Día (Usuario ID: 1)</h2>";
    $result = $conn->query("
        SELECT 
            rcs.dia_semana,
            rcs.cliente_id,
            rcs.orden,
            c.nombre as cliente_nombre
        FROM ruta_clientes_semanales rcs
        LEFT JOIN clientes c ON c.id_clientes = rcs.cliente_id
        WHERE rcs.usuario_id = 1
        ORDER BY rcs.dia_semana, rcs.orden
    ");

    $dias = [];
    while ($row = $result->fetch_assoc()) {
        $dias[$row['dia_semana']][] = $row;
    }

    if (empty($dias)) {
        echo "<p>❌ No hay datos de planificación</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Día</th><th>Clientes Asignados</th></tr>";
        
        foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia) {
            echo "<tr>";
            echo "<td><strong>$dia</strong></td>";
            echo "<td>";
            
            if (isset($dias[$dia])) {
                foreach ($dias[$dia] as $asignacion) {
                    echo "• " . ($asignacion['cliente_nombre'] ?: 'Cliente #' . $asignacion['cliente_id']) . "<br>";
                }
            } else {
                echo "<em>Sin asignaciones</em>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // Test API endpoints
    echo "<h2>🔗 Test de APIs</h2>";
    
    $apis = [
        'get_route_info.php?dia=lunes' => 'Información del Lunes',
        'get_available_clients.php' => 'Clientes Disponibles',
        'save_planificacion.php' => 'Guardar Planificación'
    ];

    foreach ($apis as $endpoint => $descripcion) {
        $url = "http://localhost/RMIE/app/api/$endpoint";
        echo "<p><strong>$descripcion:</strong> <a href='$url' target='_blank'>$url</a></p>";
    }

    // Mostrar formulario de prueba
    echo "<h2>🧪 Formulario de Prueba</h2>";
    echo "<p><a href='/RMIE/rutas.php?accion=index' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
        🚀 Ir al Módulo de Rutas
    </a></p>";

    echo "<p><a href='/RMIE/rutas.php?accion=create' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
        ➕ Crear Nueva Ruta
    </a></p>";

} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>