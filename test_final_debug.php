<?php
// Simular exactamente lo que hace el navegador

session_start();
$_SESSION['user'] = 'admin@test.com';
$_SESSION['user_id'] = 1;
$_SESSION['rol'] = 'admin';

// URL: http://localhost/RMIE/app/controllers/AlertController.php?action=edit&id=48
// Método: POST
// Datos del formulario

$_GET = ['action' => 'edit', 'id' => '48'];
$_SERVER['REQUEST_METHOD'] = 'POST';

// Simular lo que envía el navegador desde el formulario
$_POST = [
    'id_productos' => '40',
    'cantidad_minima' => '10',
    'fecha_caducidad' => '2025-12-06',
    'id_proveedores' => '36',
    'estado_alerta' => 'Normal'  // El usuario cambió a Normal
];

echo "=== PRUEBA DE ENVÍO DE FORMULARIO ===\n\n";

// Capturar salida
ob_start();

// Incluir el controlador
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Alert.php';
require_once __DIR__ . '/app/models/Product.php';
require_once __DIR__ . '/app/models/Provider.php';

// Simular exactamente lo que hace el controlador
$id = $_GET['id'] ?? 0;
$errors = [];
$success = '';
$debug_info = '';

$alerta = Alert::getById($conn, $id);
echo "1. Alerta cargada - Estado actual en BD: " . ($alerta['estado'] ?? 'NULL') . "\n";

$productos = Product::getAll($conn);
$proveedores = Provider::getAll($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "2. POST recibido\n";
    
    // Debug: mostrar qué se recibió
    $debug_info = "POST recibido. Datos: " . json_encode($_POST);
    
    $alerta['id_productos'] = isset($_POST['id_productos']) ? (int)$_POST['id_productos'] : 0;
    $alerta['cantidad_minima'] = isset($_POST['cantidad_minima']) ? (int)$_POST['cantidad_minima'] : 0;
    $alerta['fecha_caducidad'] = $_POST['fecha_caducidad'] ?? '';
    $alerta['id_proveedores'] = isset($_POST['id_proveedores']) ? (int)$_POST['id_proveedores'] : 0;
    $alerta['estado'] = $_POST['estado_alerta'] ?? 'Activo';
    
    $debug_info .= "\nEstado capturado: " . $alerta['estado'];
    
    echo "3. Datos extraídos:\n";
    echo "   - id_productos: " . $alerta['id_productos'] . "\n";
    echo "   - cantidad_minima: " . $alerta['cantidad_minima'] . "\n";
    echo "   - fecha_caducidad: " . $alerta['fecha_caducidad'] . "\n";
    echo "   - id_proveedores: " . $alerta['id_proveedores'] . "\n";
    echo "   - estado: " . $alerta['estado'] . "\n";

    if (empty($alerta['id_productos'])) $errors[] = 'El producto es obligatorio';
    if (empty($alerta['cantidad_minima']) || $alerta['cantidad_minima'] < 0) $errors[] = 'La cantidad mínima debe ser mayor o igual a 0';
    if (empty($alerta['fecha_caducidad'])) $errors[] = 'La fecha de caducidad es obligatoria';
    if (empty($alerta['id_proveedores'])) $errors[] = 'El proveedor es obligatorio';

    echo "4. Validaciones: " . (count($errors) > 0 ? count($errors) . " errores" : "OK") . "\n";
    
    if (empty($errors)) {
        // Validar existencia en BD
        if (!Product::getById($conn, $alerta['id_productos'])) {
            $errors[] = 'Producto no válido';
        }
        if (!Provider::getById($conn, $alerta['id_proveedores'])) {
            $errors[] = 'Proveedor no válido';
        }
    }

    echo "5. Validaciones extendidas: " . (count($errors) > 0 ? count($errors) . " errores" : "OK") . "\n";

    if (empty($errors)) {
        echo "6. Llamando Alert::update...\n";
        
        try {
            $result = Alert::update($conn, (int)$id, $alerta);
            
            echo "7. Resultado del update: " . ($result ? "TRUE" : "FALSE") . "\n";
            
            if ($result) {
                echo "8. Actualización exitosa. Verificando BD...\n";
                
                $alerta_check = Alert::getById($conn, $id);
                echo "9. Estado en BD después del update: " . ($alerta_check['estado'] ?? 'NULL') . "\n";
                
                if ($alerta_check['estado'] === 'Normal') {
                    echo "\n✓✓✓ ÉXITO: El estado se actualizó correctamente a 'Normal'\n";
                } else {
                    echo "\n✗✗✗ FALLO: El estado no cambió. Estado actual: " . ($alerta_check['estado'] ?? 'NULL') . "\n";
                }
            } else {
                echo "\n✗✗✗ FALLO: Alert::update() retornó false\n";
                echo "Error de BD: " . $conn->error . "\n";
            }
        } catch (Exception $e) {
            echo "\n✗✗✗ EXCEPCIÓN: " . $e->getMessage() . "\n";
        }
    } else {
        echo "\n✗ Validaciones fallidas:\n";
        foreach ($errors as $err) {
            echo "  - $err\n";
        }
    }
}

ob_end_clean();

?>
