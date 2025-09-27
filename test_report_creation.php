<?php
// Prueba directa de creación de reportes
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/models/Report.php';

echo "Probando creación de reportes...\n";

try {
    // Datos de prueba
    $data = [
        'nombre' => 'Reporte de Prueba',
        'descripcion' => 'Este es un reporte de prueba',
        'tipo' => 'general',
        'estado' => 'activo',
        'parametros' => json_encode(['test' => 'value'])
    ];
    
    echo "Datos a insertar:\n";
    print_r($data);
    
    $result = Report::create($conn, $data);
    
    if ($result) {
        echo "✓ Reporte creado exitosamente\n";
        
        // Verificar que se insertó
        $reportes = Report::getAll($conn);
        echo "Total de reportes en la BD: " . count($reportes) . "\n";
        
        // Mostrar el último reporte
        if (!empty($reportes)) {
            $ultimoReporte = $reportes[0];
            echo "Último reporte creado: " . $ultimoReporte->nombre . "\n";
        }
    } else {
        echo "✗ Error al crear el reporte\n";
    }
    
} catch (Exception $e) {
    echo "✗ Excepción: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\nPrueba completada.\n";
?>