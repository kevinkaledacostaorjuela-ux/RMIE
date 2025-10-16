<?php
// Configuración de conexión a la base de datos usando mysqli
$host = 'localhost';
$db = 'rmie';
$user = 'root';
$pass = '';

// Establecer la zona horaria de Colombia
date_default_timezone_set('America/Bogota');

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Configurar la zona horaria en MySQL también
$conn->query("SET time_zone = '-05:00'");
?>