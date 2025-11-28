<?php
session_start();
$_SESSION['user'] = 'test@test.com';
$_SESSION['user_id'] = 1;
$_SESSION['rol'] = 'admin';

require_once __DIR__ . '/config/db.php';

echo "=== TEST DE FORMULARIO POST PARA EDICIÓN DE ALERTA ===\n\n";

// Simular un POST request como si viniera del formulario
$_GET['id'] = 48;
$_GET['action'] = 'edit';
$_SERVER['REQUEST_METHOD'] = 'POST';

// Datos del formulario
$_POST['id_productos'] = 40;
$_POST['cantidad_minima'] = 10;
$_POST['fecha_caducidad'] = '2025-12-06';
$_POST['id_proveedores'] = 36;
$_POST['estado_alerta'] = 'Próxima';  // Cambiar a Próxima

echo "1. SIMULAR POST CON DATOS DEL FORMULARIO\n";
echo "   ID: 48\n";
echo "   Producto: 40\n";
echo "   Cantidad: 10\n";
echo "   Fecha: 2025-12-06\n";
echo "   Proveedor: 36\n";
echo "   Estado: Próxima (del formulario)\n\n";

// Incluir el controlador
require_once __DIR__ . '/app/models/Alert.php';
require_once __DIR__ . '/app/models/Product.php';
require_once __DIR__ . '/app/models/Provider.php';

// Simulación de lo que hace el controlador
$id = $_GET['id'] ?? 0;
echo "2. PROCESANDO COMO CONTROLADOR\n";
echo "   ID extraído del GET: " . $id . "\n";

$alerta = Alert::getById($conn, $id);
if ($alerta) {
    echo "   ✓ Alerta cargada\n";
    echo "   Estado en BD antes: " . ($alerta['estado'] ?? 'NULL') . "\n";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "\n3. CAPTURANDO DATOS POST\n";
    
    $alerta['id_productos'] = isset($_POST['id_productos']) ? (int)$_POST['id_productos'] : 0;
    $alerta['cantidad_minima'] = isset($_POST['cantidad_minima']) ? (int)$_POST['cantidad_minima'] : 0;
    $alerta['fecha_caducidad'] = $_POST['fecha_caducidad'] ?? '';
    $alerta['id_proveedores'] = isset($_POST['id_proveedores']) ? (int)$_POST['id_proveedores'] : 0;
    $alerta['estado'] = $_POST['estado_alerta'] ?? 'Activo';
    
    echo "   Estado capturado del POST: " . $alerta['estado'] . "\n";
    echo "   Array completo preparado para update:\n";
    echo "   - id_productos: " . $alerta['id_productos'] . "\n";
    echo "   - cantidad_minima: " . $alerta['cantidad_minima'] . "\n";
    echo "   - fecha_caducidad: " . $alerta['fecha_caducidad'] . "\n";
    echo "   - id_proveedores: " . $alerta['id_proveedores'] . "\n";
    echo "   - estado: " . $alerta['estado'] . "\n";
    
    echo "\n4. LLAMANDO Alert::update()\n";
    try {
        $result = Alert::update($conn, (int)$id, $alerta);
        if ($result) {
            echo "   ✓ Update ejecutado exitosamente\n";
            
            $alerta_updated = Alert::getById($conn, $id);
            echo "   Estado en BD después: " . ($alerta_updated['estado'] ?? 'NULL') . "\n";
            
            if ($alerta_updated['estado'] === 'Próxima') {
                echo "\n✓✓✓ ÉXITO: El estado se actualizó a 'Próxima'\n";
            } else {
                echo "\n✗ FALLO: El estado no es 'Próxima', es: " . ($alerta_updated['estado'] ?? 'NULL') . "\n";
            }
        } else {
            echo "   ✗ Execute retornó false: " . $conn->error . "\n";
        }
    } catch (Exception $e) {
        echo "   ✗ Excepción: " . $e->getMessage() . "\n";
    }
}

?>
