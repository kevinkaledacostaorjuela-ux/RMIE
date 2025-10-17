<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>DIAGNÓSTICO DE SESIÓN Y ERROR</h1>";

echo "<h2>1. Información básica</h2>";
echo "Método HTTP: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "URL: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Timestamp: " . date('Y-m-d H:i:s') . "<br>";

echo "<h2>2. Contenido de \$_SESSION</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>3. Datos GET</h2>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

echo "<h2>4. Datos POST</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Limpiar sesión completamente
echo "<h2>5. Limpiar sesión</h2>";
session_unset();
session_destroy();
echo "Sesión limpiada completamente.<br>";

// Iniciar nueva sesión
session_start();
echo "Nueva sesión iniciada.<br>";
echo "ID de sesión: " . session_id() . "<br>";

echo "<h2>6. Contenido de nueva sesión</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>7. Test directo del controlador</h2>";
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    echo "ID recibido: $id<br>";
    
    // Simular llamada al método edit
    echo "<h3>Simulando RouteController->edit($id)</h3>";
    
    require_once 'config/db.php';
    require_once 'app/models/Route.php';
    
    $route = Route::getById($conn, $id);
    if ($route) {
        echo "✓ Ruta encontrada:<br>";
        echo "<pre>";
        print_r($route);
        echo "</pre>";
        
        echo "<h3>Ahora intentemos ir al edit real:</h3>";
        echo '<a href="/RMIE/app/controllers/RouteController.php?accion=edit&id=' . $id . '" style="background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">IR AL EDIT REAL</a>';
    } else {
        echo "✗ Ruta no encontrada<br>";
    }
} else {
    echo "No se proporcionó ID. <a href='?id=11'>Probar con ID 11</a><br>";
}
?>