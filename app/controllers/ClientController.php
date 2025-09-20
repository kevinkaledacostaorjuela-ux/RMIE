<?php
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Local.php';
require_once __DIR__ . '/../../config/db.php';

class ClientController {
    public function index() {
        global $conn;
        $locales = Local::getAll($conn);
        $filtro_local = isset($_GET['local']) ? $_GET['local'] : '';
        $clientes = Client::getFiltered($conn, $filtro_local);
        include __DIR__ . '/../views/clientes/index.php';
    }
}
?>
