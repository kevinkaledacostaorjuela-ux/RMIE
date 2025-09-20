
<?php
require_once __DIR__ . '/../../config/db.php';

class Producto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT p.*, s.nombre AS subcategoria_nombre, s.id_subcategoria, c.nombre AS categoria_nombre, c.id_categoria FROM productos p JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria JOIN categorias c ON s.id_categoria = c.id_categoria");
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id_productos = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear($nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria) {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, fecha_entrada, fecha_fabricacion, fecha_caducidad, id_subcategoria) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria]);
    }

    public function actualizar($id, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria) {
        $stmt = $this->pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, fecha_entrada = ?, fecha_fabricacion = ?, fecha_caducidad = ?, id_subcategoria = ? WHERE id_productos = ?");
        return $stmt->execute([$nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $id_subcategoria, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id_productos = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerSubcategorias() {
        $stmt = $this->pdo->query("SELECT s.*, c.nombre AS categoria_nombre FROM subcategorias s JOIN categorias c ON s.id_categoria = c.id_categoria");
        return $stmt->fetchAll();
    }

    public function obtenerCategorias() {
        $stmt = $this->pdo->query("SELECT * FROM categorias");
        return $stmt->fetchAll();
    }

    public function obtenerSubcategoriasPorCategoria($id_categoria) {
        $stmt = $this->pdo->prepare("SELECT * FROM subcategorias WHERE id_categoria = ?");
        $stmt->execute([$id_categoria]);
        return $stmt->fetchAll();
    }
}
