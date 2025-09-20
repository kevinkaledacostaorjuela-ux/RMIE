<?php
require_once __DIR__ . '/../../config/db.php';

class Categoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT * FROM categorias");
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear($nombre, $descripcion) {
        $stmt = $this->pdo->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function actualizar($id, $nombre, $descripcion) {
        $stmt = $this->pdo->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE id_categoria = ?");
        return $stmt->execute([$nombre, $descripcion, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        return $stmt->execute([$id]);
    }
}
