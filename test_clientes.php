<?php
// test_clientes.php - Archivo de prueba para el módulo de clientes
session_start();

// Simular usuario logueado para prueba
$_SESSION['user'] = 'admin';
$_SESSION['rol'] = 'administrador';

// Simular parámetro GET
$_GET['accion'] = 'index';

// Incluir dependencias
require_once 'config/db.php';
require_once 'app/controllers/ClientController.php';

try {
    echo "Iniciando prueba del ClientController...\n";
    
    // Probar conexión a la base de datos
    global $conn;
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    echo "✅ Conexión a la base de datos exitosa\n";
    
    // Probar el controlador
    $controller = new ClientController();
    echo "✅ ClientController creado exitosamente\n";
    
    // Ejecutar el método
    ob_start(); // Capturar la salida
    $controller->handleRequest();
    $output = ob_get_clean();
    
    echo "✅ handleRequest() ejecutado exitosamente\n";
    echo "Longitud de la salida: " . strlen($output) . " caracteres\n";
    
    // Mostrar los primeros 500 caracteres de la salida
    echo "Primeros 500 caracteres de la salida:\n";
    echo substr($output, 0, 500) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
}
?>