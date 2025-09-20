<?php
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../../config/db.php';

class SaleController {
    public function index() {
        global $conn;
            $producto = isset($_GET['producto']) ? $_GET['producto'] : '';
            $cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';
            $ventas = Sale::getFiltered($conn, $producto, $cliente);
            $productos = Product::getAll($conn);
            $clientes = Client::getAll($conn);
        include __DIR__ . '/../views/ventas/index.php';
    }
}
?>
