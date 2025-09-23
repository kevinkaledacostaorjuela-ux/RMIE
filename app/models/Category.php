<?php
class Category {
    public $id_categoria;
    public $nombre;
    public $descripcion;
    public $fecha_creacion;

    public function __construct($id_categoria, $nombre, $descripcion, $fecha_creacion) {
        $this->id_categoria = $id_categoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fecha_creacion = $fecha_creacion;
    }

    public static function getAll($conn) {
        $sql = "SELECT * FROM categorias";
        $result = $conn->query($sql);
        $categorias = [];
        
        if ($result === false) {
            echo '<pre>Error en la consulta: ' . $conn->error . '</pre>';
            return $categorias; // Devolver array vacío en caso de error
        }
        
        if ($result->num_rows === 0) {
            echo '<pre>No hay categorías en la base de datos.</pre>';
            return $categorias; // Devolver array vacío si no hay registros
        }
        
        while ($row = $result->fetch_assoc()) {
            $categorias[] = new Category($row['id_categoria'], $row['nombre'], $row['descripcion'], $row['fecha_creacion'] ?? null);
        }
        return $categorias;
    }

    public static function create($conn, $nombre, $descripcion) {
        $sql = "INSERT INTO categorias (nombre, descripcion, fecha_creacion) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo '<pre>Error al preparar el statement: ' . $conn->error . '</pre>';
            return false;
        }
        $stmt->bind_param("ss", $nombre, $descripcion);
        $result = $stmt->execute();
        if (!$result) {
            echo '<pre>Error al ejecutar el statement: ' . $stmt->error . '</pre>';
        } else {
            echo '<pre>Statement ejecutado correctamente.</pre>';
        }
        return $result;
    }

    public static function getById($conn, $id_categoria) {
        $sql = "SELECT * FROM categorias WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Category($row['id_categoria'], $row['nombre'], $row['descripcion'], $row['fecha_creacion'] ?? null);
        }
        return null;
    }

    public static function update($conn, $id_categoria, $nombre, $descripcion) {
        $sql = "UPDATE categorias SET nombre = ?, descripcion = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_categoria);
        return $stmt->execute();
    }

    public static function delete($conn, $id_categoria) {
        $sql = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_categoria);
        return $stmt->execute();
    }
}
?>
