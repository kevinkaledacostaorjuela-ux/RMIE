<?php
// Script de diagnóstico para verificar el acceso a edit
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>DIAGNÓSTICO ACCESO A EDITAR RUTA</h1>";

echo "<h2>Información de la Request:</h2>";
echo "METHOD: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "QUERY_STRING: " . $_SERVER['QUERY_STRING'] . "<br>";

echo "<h2>Parámetros GET:</h2>";
var_dump($_GET);

echo "<h2>Parámetros POST:</h2>";
var_dump($_POST);

echo "<h2>Verificación de ID:</h2>";
$id = $_GET['id'] ?? 'NO DEFINIDO';
echo "ID recibido: " . $id . "<br>";
echo "ID es numérico: " . (is_numeric($id) ? 'SÍ' : 'NO') . "<br>";

if (is_numeric($id)) {
    require_once 'config/db.php';
    require_once 'app/models/Route.php';
    
    echo "<h2>Verificación en Base de Datos:</h2>";
    $route = Route::getById($conn, $id);
    if ($route) {
        echo "✓ Ruta encontrada:<br>";
        echo "- ID: " . $route['id_ruta'] . "<br>";
        echo "- Dirección: " . htmlspecialchars($route['direccion']) . "<br>";
        echo "- Local: " . htmlspecialchars($route['nombre_local']) . "<br>";
        echo "- Cliente: " . htmlspecialchars($route['nombre_cliente']) . "<br>";
        
        echo "<h2>Longitud de campos:</h2>";
        echo "- Dirección: " . strlen($route['direccion']) . " caracteres<br>";
        echo "- Local: " . strlen($route['nombre_local']) . " caracteres<br>";
        echo "- Cliente: " . strlen($route['nombre_cliente']) . " caracteres<br>";
        
        if (strlen($route['direccion']) < 5) {
            echo "<div style='background:red;color:white;padding:10px;margin:10px 0;'>";
            echo "⚠️ PROBLEMA: La dirección actual tiene menos de 5 caracteres!";
            echo "</div>";
        }
    } else {
        echo "✗ Ruta no encontrada en la base de datos<br>";
    }
}

echo "<h2>Mensajes de Sesión:</h2>";
echo "Error: " . ($_SESSION['error'] ?? 'Ninguno') . "<br>";
echo "Success: " . ($_SESSION['success'] ?? 'Ninguno') . "<br>";

echo "<h2>Test de URL correcta:</h2>";
echo '<a href="/RMIE/app/controllers/RouteController.php?accion=edit&id=11">Link correcto para editar ruta 11</a><br>';
echo '<a href="/RMIE/app/controllers/RouteController.php?accion=index">Volver al índice</a>';
?>