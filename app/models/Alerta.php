<?php
require_once __DIR__ . '/../../config/db.php';

class Alerta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT a.*, c.nombre AS cliente_nombre, p.nombre AS producto_nombre FROM alertas a JOIN clientes c ON a.id_clientes = c.id_clientes JOIN productos p ON a.id_productos = p.id_productos");
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM alertas WHERE id_alertas = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear($cliente_no_disponible, $id_clientes, $id_productos) {
        $stmt = $this->pdo->prepare("INSERT INTO alertas (cliente_no_disponible, id_clientes, id_productos) VALUES (?, ?, ?)");
        return $stmt->execute([$cliente_no_disponible, $id_clientes, $id_productos]);
    }

    public function actualizar($id, $cliente_no_disponible, $id_clientes, $id_productos) {
        $stmt = $this->pdo->prepare("UPDATE alertas SET cliente_no_disponible = ?, id_clientes = ?, id_productos = ? WHERE id_alertas = ?");
        return $stmt->execute([$cliente_no_disponible, $id_clientes, $id_productos, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM alertas WHERE id_alertas = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerProductos() {
        $stmt = $this->pdo->query("SELECT * FROM productos");
        return $stmt->fetchAll();
    }

    public function obtenerClientes() {
        $stmt = $this->pdo->query("SELECT * FROM clientes");
        return $stmt->fetchAll();
    }
}
