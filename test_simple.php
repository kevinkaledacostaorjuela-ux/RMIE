<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Alert.php';

session_start();
$_SESSION['user'] = 'admin@test.com';

$_GET = ['id' => '48'];
$_POST = [
    'id_productos' => '40',
    'cantidad_minima' => '10',
    'fecha_caducidad' => '2025-12-06',
    'id_proveedores' => '36',
    'estado_alerta' => 'Activo'
];

echo "TEST: Verificar el update\n";
echo "ID Producto: " . $_POST['id_productos'] . "\n";
echo "Estado: " . $_POST['estado_alerta'] . "\n";

$id = 48;
$data = [
    'id_productos' => 40,
    'cantidad_minima' => 10,
    'fecha_caducidad' => '2025-12-06',
    'id_proveedores' => 36,
    'estado' => 'Activo'
];

echo "\nAntes: ";
$before = Alert::getById($conn, $id);
echo $before['estado'] . "\n";

$result = Alert::update($conn, $id, $data);
echo "Update: " . ($result ? "OK" : "FAIL") . "\n";

echo "Después: ";
$after = Alert::getById($conn, $id);
echo $after['estado'] . "\n";

?>
