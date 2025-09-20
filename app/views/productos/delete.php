<?php
// Este archivo elimina un producto por id
require_once '../../app/controllers/ProductController.php';

if (isset($_GET['id'])) {
    $controller = new ProductController();
    $controller->delete($_GET['id']);
}
header('Location: index.php');
exit();
?>
