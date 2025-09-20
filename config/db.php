<?php
// Configuración de conexión a la base de datos usando mysqli
$host = 'localhost';
$db = 'rmie';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}
?>