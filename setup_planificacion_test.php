<?php
/**
 * Script para insertar datos de prueba para el sistema de planificación semanal
 */

require_once __DIR__ . '/config/db.php';

echo "<h1>Insertando datos de prueba para planificación semanal</h1>";

try {
    // Verificar si ya existen tablas
    $result = $conn->query("SHOW TABLES LIKE 'ruta_clientes_semanales'");
    if ($result->num_rows == 0) {
        echo "<p>❌ Las tablas de planificación semanal no existen. Ejecuta primero el archivo de migración.</p>";
        exit;
    }

    // Limpiar datos existentes
    $conn->query("DELETE FROM ruta_clientes_semanales");
    echo "<p>✅ Datos existentes limpiados</p>";

    // Obtener algunos clientes existentes
    $result = $conn->query("SELECT id, nombre FROM clientes LIMIT 10");
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    if (empty($clientes)) {
        echo "<p>❌ No hay clientes en la base de datos. Agrega algunos clientes primero.</p>";
        exit;
    }

    echo "<p>📋 Clientes encontrados: " . count($clientes) . "</p>";

    // Usuario de prueba (ID 1)
    $usuario_id = 1;

    // Planificar clientes para cada día de la semana
    $planificacion = [
        'Lunes' => array_slice($clientes, 0, 3),
        'Martes' => array_slice($clientes, 2, 4),
        'Miércoles' => array_slice($clientes, 1, 3),
        'Jueves' => array_slice($clientes, 3, 4),
        'Viernes' => array_slice($clientes, 0, 2)
    ];

    $stmt = $conn->prepare("INSERT INTO ruta_clientes_semanales (usuario_id, dia_semana, cliente_id, orden) VALUES (?, ?, ?, ?)");

    foreach ($planificacion as $dia => $clientesDia) {
        foreach ($clientesDia as $index => $cliente) {
            $orden = $index + 1;
            $stmt->bind_param('isii', $usuario_id, $dia, $cliente['id'], $orden);
            $stmt->execute();
            echo "<p>✅ Asignado: {$cliente['nombre']} para $dia (orden $orden)</p>";
        }
    }

    echo "<h2>🎉 Datos de prueba insertados correctamente!</h2>";
    echo "<h3>Planificación creada:</h3>";
    echo "<ul>";
    foreach ($planificacion as $dia => $clientesDia) {
        echo "<li><strong>$dia:</strong> ";
        $nombres = array_map(function($c) { return $c['nombre']; }, $clientesDia);
        echo implode(', ', $nombres);
        echo "</li>";
    }
    echo "</ul>";

    echo "<p><a href='rutas.php?accion=create' class='btn btn-primary'>Probar formulario de creación de rutas</a></p>";

} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
} finally {
    $conn->close();
}
?>