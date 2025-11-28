<?php
require_once __DIR__ . '/config/db.php';

// Verificar el estado guardado en la BD para alerta 48
$result = $conn->query("SELECT id_alertas, estado, fecha_caducidad FROM alertas WHERE id_alertas = 48");
$alerta = $result->fetch_assoc();

echo "=== VERIFICACIÓN DE ESTADO ===\n\n";
echo "Alerta ID 48:\n";
echo "  Estado en BD: " . ($alerta['estado'] ?? 'NULL') . "\n";
echo "  Fecha caducidad: " . $alerta['fecha_caducidad'] . "\n";

// Calcular lo que sería sin el override
$fecha_actual = date('Y-m-d');
$dias_restantes = (strtotime($alerta['fecha_caducidad']) - strtotime($fecha_actual)) / (60 * 60 * 24);
echo "  Días restantes: " . number_format($dias_restantes, 1) . "\n";

// Mostrar qué estado se calcularía sin el override
if ($dias_restantes < 0) {
    $estado_calculado = 'Vencida';
} elseif ($dias_restantes <= 7) {
    $estado_calculado = 'Crítica';
} elseif ($dias_restantes <= 30) {
    $estado_calculado = 'Próxima';
} else {
    $estado_calculado = 'Normal';
}

echo "  Estado calculado (sin override): " . $estado_calculado . "\n";

echo "\n✓ Con el nuevo código:\n";
if (!empty($alerta['estado'])) {
    echo "  -> Se usará el estado de BD: " . $alerta['estado'] . "\n";
} else {
    echo "  -> Se calculará: " . $estado_calculado . "\n";
}

?>
