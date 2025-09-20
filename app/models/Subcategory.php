<?php
class Subcategory {
    public $id_subcategoria;
    public $nombre;
    public $descripcion;
    public $fecha_creacion;
    public $id_categoria;

    public function __construct($id_subcategoria, $nombre, $descripcion, $fecha_creacion, $id_categoria) {
        $this->id_subcategoria = $id_subcategoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fecha_creacion = $fecha_creacion;
        $this->id_categoria = $id_categoria;
    }

    public static function getAll($conn) {
        $sql = "SELECT s.*, c.nombre AS categoria_nombre FROM subcategorias s JOIN categorias c ON s.id_categoria = c.id_categoria";
        $result = $conn->query($sql);
        $subcategorias = [];
        while ($row = $result->fetch_assoc()) {
            $subcategorias[] = [
                'obj' => new Subcategory($row['id_subcategoria'], $row['nombre'], $row['descripcion'], $row['fecha_creacion'], $row['id_categoria']),
                'categoria_nombre' => $row['categoria_nombre']
            ];
        }
        return $subcategorias;
    }

    public static function create($conn, $nombre, $descripcion, $id_categoria) {
        $sql = "INSERT INTO subcategorias (nombre, descripcion, fecha_creacion, id_categoria) VALUES (?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_categoria);
        return $stmt->execute();
    }

    public static function getById($conn, $id_subcategoria) {
        $sql = "SELECT * FROM subcategorias WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_subcategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Subcategory($row['id_subcategoria'], $row['nombre'], $row['descripcion'], $row['fecha_creacion'], $row['id_categoria']);
        }
        return null;
    }

    public static function update($conn, $id_subcategoria, $nombre, $descripcion, $id_categoria) {
        $sql = "UPDATE subcategorias SET nombre = ?, descripcion = ?, id_categoria = ? WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $nombre, $descripcion, $id_categoria, $id_subcategoria);
        return $stmt->execute();
    }

    public static function delete($conn, $id_subcategoria) {
        $sql = "DELETE FROM subcategorias WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_subcategoria);
        return $stmt->execute();
    }
}
?>
