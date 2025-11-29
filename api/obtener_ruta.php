<?php
/**
 * obtener_ruta.php - Devuelve la ruta y clientes de un día en JSON
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación (opcional, depende de tus requisitos)
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no autenticado'
    ]);
    exit;
}

// Incluir configuración de base de datos y controlador
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/controllers/RouteControllerWeekly.php';

try {
    $routeController = new RouteControllerWeekly($conn);
    
    // Verificar si se solicitan todas las rutas
    if (isset($_GET['todas']) && $_GET['todas'] == '1') {
        $rutas = $routeController->getTodasRutas();
        
        echo json_encode([
            'success' => true,
            'rutas' => $rutas,
            'total' => count($rutas)
        ]);
        exit;
    }
    
    // Obtener ruta por día específico
    if (isset($_GET['dia'])) {
        $dia = $_GET['dia'];
        
        // Validar día
        $diasValidos = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        if (!in_array($dia, $diasValidos)) {
            echo json_encode([
                'success' => false,
                'message' => 'Día inválido. Debe ser: ' . implode(', ', $diasValidos)
            ]);
            exit;
        }
        
        $ruta = $routeController->getRutaPorDia($dia);
        
        if ($ruta) {
            echo json_encode([
                'success' => true,
                'ruta' => $ruta,
                'message' => 'Ruta encontrada'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontró ruta para el día ' . $dia,
                'ruta' => null
            ]);
        }
        exit;
    }
    
    // Si se solicita ruta del día actual
    $ruta = $routeController->getRutaDelDia();
    
    if ($ruta) {
        echo json_encode([
            'success' => true,
            'ruta' => $ruta,
            'message' => 'Ruta del día actual encontrada'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No hay ruta configurada para hoy',
            'ruta' => null
        ]);
    }
    
} catch (Exception $e) {
    error_log("Error en obtener_ruta.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>