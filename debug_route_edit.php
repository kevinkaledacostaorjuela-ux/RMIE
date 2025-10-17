<?php
// Test simple para el controlador de rutas
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>DEBUG - RouteController Edit</h1>";
echo "<p>Método HTTP: " . $_SERVER['REQUEST_METHOD'] . "</p>";

echo "<h2>Datos POST:</h2>";
echo "<pre>";
var_dump($_POST);
echo "</pre>";

echo "<h2>Datos GET:</h2>";
echo "<pre>";
var_dump($_GET);
echo "</pre>";

echo "<h2>URL actual:</h2>";
echo "<p>" . $_SERVER['REQUEST_URI'] . "</p>";

echo "<h2>Datos de sesión:</h2>";
session_start();
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

// Si hay un ID en GET, intentar cargar la ruta
if (isset($_GET['id'])) {
    require_once 'config/db.php';
    require_once 'app/models/Route.php';
    
    $id = intval($_GET['id']);
    echo "<h2>Intentando cargar ruta ID: $id</h2>";
    
    $route = Route::getById($conn, $id);
    if ($route) {
        echo "<p>✓ Ruta encontrada</p>";
        echo "<pre>";
        var_dump($route);
        echo "</pre>";
    } else {
        echo "<p>✗ Ruta no encontrada</p>";
    }
}

echo "<h2>Ahora redirigiendo al RouteController real...</h2>";
echo '<p><a href="/RMIE/app/controllers/RouteController.php?accion=edit&id=' . ($_GET['id'] ?? '1') . '">Ir al controlador real</a></p>';
?>