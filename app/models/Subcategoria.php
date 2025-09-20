<?php
require_once __DIR__ . '/../../config/db.php';

class Subcategoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas($filtros = []) {
        $sql = "SELECT s.*, c.nombre AS categoria_nombre FROM subcategorias s JOIN categorias c ON s.id_categoria = c.id_categoria WHERE 1=1";
        $params = [];
        if (!empty($filtros['nombre'])) {
            $sql .= " AND s.nombre LIKE ?";
            $params[] = '%' . $filtros['nombre'] . '%';
        }
        if (!empty($filtros['categoria'])) {
            $sql .= " AND s.id_categoria = ?";
            $params[] = $filtros['categoria'];
        }
        $orden = 'ASC';
        if (!empty($filtros['orden']) && $filtros['orden'] === 'desc') {
            $orden = 'DESC';
        }
        $sql .= " ORDER BY s.nombre $orden";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM subcategorias WHERE id_subcategoria = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear($nombre, $descripcion, $id_categoria) {
        $stmt = $this->pdo->prepare("INSERT INTO subcategorias (nombre, descripcion, id_categoria) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $id_categoria]);
    }

    public function actualizar($id, $nombre, $descripcion, $id_categoria) {
        $stmt = $this->pdo->prepare("UPDATE subcategorias SET nombre = ?, descripcion = ?, id_categoria = ? WHERE id_subcategoria = ?");
        return $stmt->execute([$nombre, $descripcion, $id_categoria, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM subcategorias WHERE id_subcategoria = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerCategorias() {
        $stmt = $this->pdo->query("SELECT * FROM categorias");
        return $stmt->fetchAll();
    }
}
