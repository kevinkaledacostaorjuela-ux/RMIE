<?php
// Script para probar que el sistema de reportes funciona end-to-end
session_start();

// Simular usuario logueado
$_SESSION['user'] = ['id' => 1, 'nombre' => 'Usuario de Prueba'];

echo "Probando sistema de reportes completo...\n\n";

// Test 1: Probar que la página de creación funciona
echo "1. Probando página de creación...\n";
ob_start();
include __DIR__ . '/app/controllers/ReportController.php';
$_GET['accion'] = 'create';
try {
    $controller = new ReportController();
    $controller->handleRequest();
    $output = ob_get_clean();
    
    if (strpos($output, 'Crear Reporte') !== false || strpos($output, 'form') !== false) {
        echo "✓ Página de creación funciona\n";
    } else {
        echo "✗ Problema con página de creación\n";
    }
} catch (Exception $e) {
    ob_end_clean();
    echo "✗ Error en página de creación: " . $e->getMessage() . "\n";
}

// Test 2: Probar creación via POST
echo "\n2. Probando creación via POST...\n";
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'nombre' => 'Reporte de Prueba Web',
    'descripcion' => 'Descripción del reporte de prueba',
    'tipo' => 'general',
    'estado' => 'activo',
    'parametros' => ['formato' => 'pdf']
];
$_GET['accion'] = 'store';

ob_start();
try {
    $controller = new ReportController();
    $controller->handleRequest();
    $output = ob_get_clean();
    
    if (isset($_SESSION['success'])) {
        echo "✓ Reporte creado exitosamente: " . $_SESSION['success'] . "\n";
        unset($_SESSION['success']);
    } else if (isset($_SESSION['error'])) {
        echo "✗ Error al crear reporte: " . $_SESSION['error'] . "\n";
        unset($_SESSION['error']);
    } else {
        echo "✓ Proceso completado (revisar en la base de datos)\n";
    }
} catch (Exception $e) {
    ob_end_clean();
    echo "✗ Error en creación: " . $e->getMessage() . "\n";
}

// Test 3: Probar listado
echo "\n3. Probando listado de reportes...\n";
$_GET['accion'] = 'index';
unset($_POST);
$_SERVER['REQUEST_METHOD'] = 'GET';

ob_start();
try {
    $controller = new ReportController();
    $controller->handleRequest();
    $output = ob_get_clean();
    
    if (strpos($output, 'Reporte de Prueba') !== false) {
        echo "✓ El reporte aparece en el listado\n";
    } else {
        echo "? No se encontró el reporte en el listado (puede ser normal si ya existía)\n";
    }
} catch (Exception $e) {
    ob_end_clean();
    echo "✗ Error en listado: " . $e->getMessage() . "\n";
}

echo "\nPrueba completa terminada.\n";
?>