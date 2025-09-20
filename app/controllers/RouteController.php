<?php
require_once __DIR__ . '/../models/Route.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../../config/db.php';

class RouteController {
    public function index() {
        global $conn;
        $reportes = Report::getFiltered($conn);
        $ventas = Sale::getFiltered($conn);
        $filtro_reporte = isset($_GET['reporte']) ? $_GET['reporte'] : '';
        $filtro_venta = isset($_GET['venta']) ? $_GET['venta'] : '';
        $rutas = Route::getFiltered($conn, $filtro_reporte, $filtro_venta);
        include __DIR__ . '/../views/rutas/index.php';
    }
}
?>
