<?php
require_once __DIR__ . '/../models/Provider.php';
require_once __DIR__ . '/../../config/db.php';

class ProviderController {
    public function index() {
        global $conn;
        $proveedores = Provider::getAll($conn);
        include __DIR__ . '/../views/proveedores/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $conn;
            $nombre_distribuidor = $_POST['nombre_distribuidor'];
            $correo = $_POST['correo'];
            $cel_proveedor = $_POST['cel_proveedor'];
            $estado = $_POST['estado'];
            Provider::create($conn, $nombre_distribuidor, $correo, $cel_proveedor, $estado);
            header('Location: index.php');
            exit();
        }
        include __DIR__ . '/../views/proveedores/create.php';
    }

    public function edit($id) {
        global $conn;
        $proveedor = Provider::getById($conn, $id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_distribuidor = $_POST['nombre_distribuidor'];
            $correo = $_POST['correo'];
            $cel_proveedor = $_POST['cel_proveedor'];
            $estado = $_POST['estado'];
            Provider::update($conn, $id, $nombre_distribuidor, $correo, $cel_proveedor, $estado);
            header('Location: index.php');
            exit();
        }
        include __DIR__ . '/../views/proveedores/edit.php';
    }

    public function delete($id) {
        global $conn;
        Provider::delete($conn, $id);
        header('Location: index.php');
        exit();
    }
}
?>
