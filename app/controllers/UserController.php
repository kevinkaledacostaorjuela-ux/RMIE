<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/db.php';

class UserController {
    public function index() {
        global $conn;
        $usuarios = User::getAll($conn);
        include __DIR__ . '/../views/usuarios/index.php';
    }
}
?>
