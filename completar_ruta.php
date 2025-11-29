<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Si es una petición OPTIONS, terminar aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no autenticado'
    ]);
    exit;
}

// Incluir archivos necesarios
require_once 'config/db.php';

try {
    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
        exit;
    }

    // Obtener datos JSON o de formulario
    $input = json_decode(file_get_contents('php://input'), true);
    $id_ruta = null;
    
    // Intentar obtener de JSON primero, luego de POST
    if ($input && isset($input['id_ruta'])) {
        $id_ruta = (int)$input['id_ruta'];
    } elseif (isset($_POST['id_ruta'])) {
        $id_ruta = (int)$_POST['id_ruta'];
    }
    
    if (!$id_ruta) {
        echo json_encode([
            'success' => false,
            'message' => 'ID de ruta requerido'
        ]);
        exit;
    }

    // Verificar que la ruta existe y está activa
    $verificar_sql = "SELECT id_ruta, estado FROM rutas WHERE id_ruta = ?";
    $verificar_stmt = $conn->prepare($verificar_sql);
    $verificar_stmt->bind_param("i", $id_ruta);
    $verificar_stmt->execute();
    $resultado = $verificar_stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ruta no encontrada'
        ]);
        exit;
    }

    $ruta = $resultado->fetch_assoc();
    
    if ($ruta['estado'] !== 'activa') {
        echo json_encode([
            'success' => false,
            'message' => 'Solo se pueden completar rutas que estén activas'
        ]);
        exit;
    }

    // Actualizar el estado a completada
    $actualizar_sql = "UPDATE rutas SET estado = 'completada' WHERE id_ruta = ?";
    $actualizar_stmt = $conn->prepare($actualizar_sql);
    $actualizar_stmt->bind_param("i", $id_ruta);

    if ($actualizar_stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Ruta marcada como completada exitosamente',
            'id_ruta' => $id_ruta
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el estado de la ruta'
        ]);
    }

    $actualizar_stmt->close();
    $verificar_stmt->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}

// Cerrar conexión
if (isset($conn)) {
    $conn->close();
}
?>