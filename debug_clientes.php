<?php
// debug_clientes.php - Diagnóstico detallado del problema de clientes
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DIAGNÓSTICO DEL MÓDULO CLIENTES ===\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";

// 1. Verificar archivos necesarios
echo "1. VERIFICACIÓN DE ARCHIVOS:\n";
$files_to_check = [
    'config/db.php',
    'app/controllers/ClientController.php',
    'app/models/Client.php',
    'app/views/clientes/index.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ $file (existe)\n";
    } else {
        echo "❌ $file (NO EXISTE)\n";
    }
}

// 2. Verificar conexión a la base de datos
echo "\n2. CONEXIÓN A BASE DE DATOS:\n";
try {
    require_once 'config/db.php';
    global $conn;
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    echo "✅ Conexión exitosa\n";
    
    // Verificar tabla clientes
    $result = $conn->query("SHOW TABLES LIKE 'clientes'");
    if ($result->num_rows > 0) {
        echo "✅ Tabla 'clientes' existe\n";
        
        // Contar registros
        $count_result = $conn->query("SELECT COUNT(*) as total FROM clientes");
        $count = $count_result->fetch_assoc()['total'];
        echo "✅ Registros en tabla clientes: $count\n";
    } else {
        echo "❌ Tabla 'clientes' NO existe\n";
    }
} catch (Exception $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
}

// 3. Verificar controlador
echo "\n3. CONTROLADOR:\n";
try {
    require_once 'app/controllers/ClientController.php';
    $controller = new ClientController();
    echo "✅ ClientController creado exitosamente\n";
} catch (Exception $e) {
    echo "❌ Error en ClientController: " . $e->getMessage() . "\n";
}

// 4. Simulación de petición web
echo "\n4. SIMULACIÓN DE PETICIÓN WEB:\n";
try {
    // Simular sesión
    session_start();
    $_SESSION['user'] = 'admin';
    $_SESSION['rol'] = 'administrador';
    
    // Simular GET
    $_GET['accion'] = 'index';
    
    echo "✅ Sesión iniciada\n";
    echo "✅ Parámetros GET configurados\n";
    
    // Capturar errores y salida
    ob_start();
    error_reporting(E_ALL);
    
    $controller = new ClientController();
    $controller->handleRequest();
    
    $output = ob_get_clean();
    
    if (strlen($output) > 0) {
        echo "✅ Salida generada: " . strlen($output) . " caracteres\n";
        
        // Buscar errores comunes en la salida
        if (strpos($output, 'Fatal error') !== false) {
            echo "❌ Fatal error detectado en la salida\n";
        } elseif (strpos($output, 'Warning') !== false) {
            echo "⚠️ Warnings detectados en la salida\n";
        } elseif (strpos($output, 'Notice') !== false) {
            echo "ℹ️ Notices detectados en la salida\n";
        } else {
            echo "✅ Sin errores aparentes en la salida\n";
        }
        
        // Mostrar las primeras líneas para diagnóstico
        $lines = explode("\n", $output);
        echo "Primeras 5 líneas de salida:\n";
        for ($i = 0; $i < min(5, count($lines)); $i++) {
            echo "  " . ($i+1) . ": " . trim($lines[$i]) . "\n";
        }
        
    } else {
        echo "❌ No se generó salida\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error en simulación: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
}

// 5. Verificar permisos
echo "\n5. PERMISOS DE ARCHIVOS:\n";
$permissions_to_check = [
    'app/controllers/ClientController.php',
    'app/views/clientes/index.php'
];

foreach ($permissions_to_check as $file) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        $readable = is_readable($file) ? "✅" : "❌";
        echo "$readable $file (permisos: " . sprintf('%o', $perms) . ")\n";
    }
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
?>