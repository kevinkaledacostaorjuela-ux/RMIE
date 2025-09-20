<?php
// Este archivo elimina una categoría por id
require_once '../../app/controllers/CategoryController.php';

if (isset($_GET['id'])) {
    $controller = new CategoryController();
    $controller->delete($_GET['id']);
}
header('Location: index.php');
exit();
?>
