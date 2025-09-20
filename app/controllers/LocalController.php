<?php
require_once __DIR__ . '/../models/Local.php';
require_once __DIR__ . '/../../config/db.php';

class LocalController {
    public function index() {
        global $conn;
        $locales = Local::getAll($conn);
        include __DIR__ . '/../views/locales/index.php';
    }
}
?>
