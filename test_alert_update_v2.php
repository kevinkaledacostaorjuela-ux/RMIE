<?php
session_start();
$_SESSION['user'] = 'test@test.com';
$_SESSION['user_id'] = 1;
$_SESSION['rol'] = 'admin';

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Alert.php';

echo "=== TEST DE ACTUALIZACIÓN DE ALERTA - MÉTODO CORREGIDO ===\n\n";

// 1. Obtener alerta inicial
echo "1. OBTENIENDO ALERTA #48...\n";
$alerta = Alert::getById($conn, 48);
if ($alerta) {
    echo "   ✓ Alerta obtenida\n";
    echo "   Estado inicial: " . ($alerta['estado'] ?? 'NULL') . "\n";
} else {
    echo "   ✗ Alerta no encontrada\n";
    exit;
}

// 2. Preparar datos para actualización
echo "\n2. PREPARANDO DATOS PARA ACTUALIZACIÓN...\n";
$data = [
    'id_productos' => $alerta['id_productos'],
    'cantidad_minima' => $alerta['cantidad_minima'],
    'fecha_caducidad' => $alerta['fecha_caducidad'],
    'id_proveedores' => $alerta['id_proveedores'],
    'estado' => 'Normal'
];
echo "   Estado nuevo: " . $data['estado'] . "\n";
echo "   ID Productos: " . $data['id_productos'] . " (int)\n";
echo "   Cantidad Mín: " . $data['cantidad_minima'] . " (int)\n";
echo "   Fecha: " . $data['fecha_caducidad'] . " (string)\n";
echo "   ID Proveedores: " . $data['id_proveedores'] . " (int)\n";

// 3. Intentar actualizar
echo "\n3. ACTUALIZANDO ALERTA...\n";
try {
    $result = Alert::update($conn, 48, $data);
    if ($result) {
        echo "   ✓ Actualización exitosa\n";
    } else {
        echo "   ✗ Error en execute(): " . $conn->error . "\n";
        exit;
    }
} catch (Exception $e) {
    echo "   ✗ Excepción: " . $e->getMessage() . "\n";
    exit;
}

// 4. Verificar cambio
echo "\n4. VERIFICANDO CAMBIO EN BD...\n";
$alerta_updated = Alert::getById($conn, 48);
if ($alerta_updated) {
    echo "   ✓ Alerta consultada después de actualización\n";
    echo "   Estado ahora: " . ($alerta_updated['estado'] ?? 'NULL') . "\n";
    
    if ($alerta_updated['estado'] === 'Normal') {
        echo "\n✓✓✓ ÉXITO: El estado se actualizó correctamente a 'Normal'\n";
    } else {
        echo "\n✗ FALLO: El estado no cambió, sigue siendo: " . ($alerta_updated['estado'] ?? 'NULL') . "\n";
    }
} else {
    echo "   ✗ Error al obtener alerta después de actualización\n";
}

// 5. Probar con otro estado
echo "\n5. PRUEBA ADICIONAL: Cambiar a 'Vencida'...\n";
$data['estado'] = 'Vencida';
try {
    $result = Alert::update($conn, 48, $data);
    if ($result) {
        $alerta_final = Alert::getById($conn, 48);
        echo "   ✓ Actualizado a: " . ($alerta_final['estado'] ?? 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

?>
