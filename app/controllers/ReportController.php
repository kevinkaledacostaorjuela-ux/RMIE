<?php
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../../config/db.php';

class ReportController {
    public function index() {
        global $conn;
        $productos = Product::getAll($conn);
        $filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
        $reportes = Report::getFiltered($conn, $filtro_producto);
        include __DIR__ . '/../views/reportes/index.php';
    }

    public function create() {
        global $conn;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $id_productos = $_POST['id_productos'];
            Report::create($conn, $nombre, $descripcion, $id_productos);
            header('Location: index.php');
            exit();
        }
        include __DIR__ . '/../views/reportes/create.php';
    }
}
?>
