<?php
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../../config/db.php';

class AlertController {
    public function index() {
        global $conn;
        $productos = Product::getAll($conn);
        $filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
        $alertas = Alert::getFiltered($conn, $filtro_producto);
        include __DIR__ . '/../views/alertas/index.php';
    }
}
?>
