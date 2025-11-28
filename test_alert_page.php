<?php
// Iniciar sesión y verificar acceso a la página de alertas
session_start();

// Verificar si la sesión existe (si no, se redirigirá a login)
if (!isset($_SESSION['user_id'])) {
    echo "Sesión no iniciada. Necesitas hacer login primero.";
    exit;
}

// Incluir el controlador de alertas
require_once __DIR__ . '/app/controllers/AlertController.php';

try {
    $alertController = new AlertController();
    echo "✓ AlertController cargado exitosamente\n";
    
    // Obtener tipos disponibles manualmente para prueba
    require_once __DIR__ . '/config/db.php';
    
    $sql_tipos = "SELECT DISTINCT tipo_alerta FROM alertas WHERE tipo_alerta IS NOT NULL AND tipo_alerta != ''";
    $result = $conn->query($sql_tipos);
    
    if ($result) {
        echo "✓ Consulta de tipos ejecutada correctamente\n";
        echo "  Tipos encontrados: ";
        $tipos = [];
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row['tipo_alerta'];
        }
        echo implode(", ", $tipos) ?: "Ninguno";
        echo "\n";
    } else {
        echo "✗ Error en consulta de tipos: " . $conn->error . "\n";
    }
    
    echo "\n✓ La página de alertas debería cargarse sin errores fatales.\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
