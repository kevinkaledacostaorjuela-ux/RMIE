<?php
// Este script simula exactamente lo que hace el navegador al enviar el formulario

// Paso 1: Preparar ambiente como si fuera una sesión real
session_start();
$_SESSION['user'] = 'admin@test.com';
$_SESSION['user_id'] = 1;
$_SESSION['rol'] = 'admin';

// Paso 2: Simular GET y POST
// Cuando hace POST a: /RMIE/app/controllers/AlertController.php?action=edit&id=48
$_GET = ['action' => 'edit', 'id' => '48'];
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/RMIE/app/controllers/AlertController.php?action=edit&id=48';

// Paso 3: Datos POST del formulario (lo que envía el navegador)
$_POST = [
    'id_productos' => '40',
    'cantidad_minima' => '10',
    'fecha_caducidad' => '2025-12-06',
    'id_proveedores' => '36',
    'estado_alerta' => 'Crítica'  // ESTE ES EL CAMBIO DE ESTADO QUE EL USUARIO QUIERE
];

echo "=== SIMULACIÓN COMPLETA DE ENVÍO DE FORMULARIO ===\n\n";
echo "URL: /RMIE/app/controllers/AlertController.php?action=edit&id=48\n";
echo "Método: POST\n";
echo "Datos POST:\n";
foreach ($_POST as $key => $val) {
    echo "  - $key = $val\n";
}

// Paso 4: Ejecutar el controlador como lo hace index.php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Alert.php';
require_once __DIR__ . '/app/models/Product.php';
require_once __DIR__ . '/app/models/Provider.php';

echo "\n=== EJECUTANDO CONTROLADOR ===\n\n";

// Simular el método edit() del controlador
$id = $_GET['id'] ?? 0;
$errors = [];

echo "1. Obteniendo alerta ID: $id\n";
$alerta = Alert::getById($conn, $id);
echo "   ✓ Alerta obtenida. Estado actual: " . ($alerta['estado'] ?? 'NULL') . "\n";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "\n2. Procesando POST\n";
    
    $alerta['id_productos'] = isset($_POST['id_productos']) ? (int)$_POST['id_productos'] : 0;
    $alerta['cantidad_minima'] = isset($_POST['cantidad_minima']) ? (int)$_POST['cantidad_minima'] : 0;
    $alerta['fecha_caducidad'] = $_POST['fecha_caducidad'] ?? '';
    $alerta['id_proveedores'] = isset($_POST['id_proveedores']) ? (int)$_POST['id_proveedores'] : 0;
    $alerta['estado'] = $_POST['estado_alerta'] ?? 'Activo';
    
    echo "   Estado recibido del formulario: " . $alerta['estado'] . "\n";
    
    // Validaciones
    if (empty($alerta['id_productos'])) $errors[] = 'El producto es obligatorio';
    if (empty($alerta['cantidad_minima']) || $alerta['cantidad_minima'] < 0) $errors[] = 'La cantidad mínima debe ser mayor o igual a 0';
    if (empty($alerta['fecha_caducidad'])) $errors[] = 'La fecha de caducidad es obligatoria';
    if (empty($alerta['id_proveedores'])) $errors[] = 'El proveedor es obligatorio';
    
    if (empty($errors)) {
        echo "\n3. Validaciones pasadas. Actualizando...\n";
        
        $result = Alert::update($conn, (int)$id, $alerta);
        if ($result) {
            echo "   ✓ Alert::update() retornó true\n";
            
            // Verificar en BD
            $alerta_check = Alert::getById($conn, $id);
            echo "   ✓ Estado en BD ahora: " . ($alerta_check['estado'] ?? 'NULL') . "\n";
            
            $_SESSION['success'] = '¡Alerta actualizada exitosamente!';
            echo "\n✓✓✓ ÉXITO TOTAL: Estado actualizado a '" . $alerta_check['estado'] . "'\n";
        } else {
            echo "   ✗ Alert::update() retornó false\n";
            echo "   Error: " . $conn->error . "\n";
        }
    } else {
        echo "   ✗ Validación fallida:\n";
        foreach ($errors as $err) {
            echo "     - $err\n";
        }
    }
}

?>
