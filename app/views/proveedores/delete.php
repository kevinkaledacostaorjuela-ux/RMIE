<?php
// Este archivo elimina un proveedor por id
require_once '../../app/controllers/ProviderController.php';

if (isset($_GET['id'])) {
    $controller = new ProviderController();
    $controller->delete($_GET['id']);
}
header('Location: index.php');
exit();
?>
