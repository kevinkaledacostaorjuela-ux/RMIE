<?php
// app/controllers/AlertaController.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
echo '<h2>Gestión de Alertas (en construcción)</h2>';
?>