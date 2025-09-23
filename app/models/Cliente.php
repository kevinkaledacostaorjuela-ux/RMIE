<?php
// No incluir la conexión aquí, se inyecta por el constructor

class Cliente {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT c.*, l.nombre_local FROM clientes c JOIN locales l ON c.id_locales = l.id_locales");
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE id_clientes = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear($nombre, $descripcion, $cel_cliente, $correo, $estado, $id_locales) {
        $stmt = $this->pdo->prepare("INSERT INTO clientes (nombre, descripcion, cel_cliente, correo, estado, id_locales) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $cel_cliente, $correo, $estado, $id_locales]);
    }

    public function actualizar($id, $nombre, $descripcion, $cel_cliente, $correo, $estado, $id_locales) {
        $stmt = $this->pdo->prepare("UPDATE clientes SET nombre = ?, descripcion = ?, cel_cliente = ?, correo = ?, estado = ?, id_locales = ? WHERE id_clientes = ?");
        return $stmt->execute([$nombre, $descripcion, $cel_cliente, $correo, $estado, $id_locales, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id_clientes = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerLocales() {
        $stmt = $this->pdo->query("SELECT * FROM locales");
        return $stmt->fetchAll();
    }
}
