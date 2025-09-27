<?php
// acceso_directo_clientes.php - Acceso directo al módulo de clientes para pruebas
session_start();

// Establecer sesión de usuario automáticamente para prueba
$_SESSION['user'] = 'admin';
$_SESSION['rol'] = 'administrador';

// Redirigir al módulo de clientes
header('Location: app/controllers/ClientController.php?accion=index');
exit();
?>