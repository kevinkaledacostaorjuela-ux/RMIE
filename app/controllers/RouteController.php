<?php
require_once __DIR__ . '/../models/Route.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../../config/db.php';

class RouteController {
    public function index() {
        global $conn;
        $filtro_reporte = isset($_GET['reporte']) ? $_GET['reporte'] : '';
        $filtro_venta = isset($_GET['venta']) ? $_GET['venta'] : '';
        $reportes = Report::getFiltered($conn, $filtro_reporte);
        $ventas = Sale::getFiltered($conn);
        $rutas = Route::getFiltered($conn, $filtro_reporte, $filtro_venta);

        // Asegurarse de que $rutas sea un array válido
        $routes = is_array($rutas) ? $rutas : [];

        include __DIR__ . '/../views/rutas/index.php';
    }

    public function create() {
        global $conn;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $direccion = $_POST['direccion'];
            $nombre_local = $_POST['nombre_local'];
            $nombre_cliente = $_POST['nombre_cliente'];
            $id_clientes = $_POST['id_clientes'];
            $id_reportes = $_POST['id_reportes'];
            $id_ventas = $_POST['id_ventas'];

            Route::create($conn, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_reportes, $id_ventas);
            header('Location: /app/views/rutas/index.php');
            exit;
        }
        include __DIR__ . '/../views/rutas/create.php';
    }

    public function edit($id) {
        global $conn;
        $route = Route::getById($conn, $id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $direccion = $_POST['direccion'];
            $nombre_local = $_POST['nombre_local'];
            $nombre_cliente = $_POST['nombre_cliente'];
            $id_clientes = $_POST['id_clientes'];
            $id_reportes = $_POST['id_reportes'];
            $id_ventas = $_POST['id_ventas'];

            Route::update($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_reportes, $id_ventas);
            header('Location: /app/views/rutas/index.php');
            exit;
        }
        include __DIR__ . '/../views/rutas/edit.php';
    }

    public function delete($id) {
        global $conn;
        Route::delete($conn, $id);
        header('Location: /app/views/rutas/index.php');
        exit;
    }
}
?>
