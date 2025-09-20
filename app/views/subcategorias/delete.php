<?php
// Este archivo elimina una subcategoría por id
require_once '../../app/controllers/SubcategoryController.php';

if (isset($_GET['id'])) {
    $controller = new SubcategoryController();
    $controller->delete($_GET['id']);
}
header('Location: index.php');
exit();
?>
