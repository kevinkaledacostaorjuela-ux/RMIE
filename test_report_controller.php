<?php
// Archivo de prueba para ReportController
require_once __DIR__ . '/app/controllers/ReportController.php';

echo "Probando ReportController...\n";

try {
    $controller = new ReportController();
    echo "✓ ReportController se puede instanciar\n";
    
    // Simular una petición
    $_GET['accion'] = 'create';
    $_SESSION['user'] = ['id' => 1, 'nombre' => 'test'];
    
    ob_start();
    $controller->handleRequest();
    $output = ob_get_clean();
    
    if (strpos($output, 'Crear Reporte') !== false || strpos($output, 'create.php') !== false) {
        echo "✓ Método create() funciona correctamente\n";
    } else {
        echo "✗ Problema con método create()\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "Prueba completada.\n";
?>