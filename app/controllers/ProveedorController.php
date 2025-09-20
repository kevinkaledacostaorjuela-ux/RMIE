<?php
// app/controllers/ProveedorController.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
echo '<h2>Gestión de Proveedores (en construcción)</h2>';
?>