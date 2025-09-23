<?php
class SubcategorySimple {
    public $id_subcategoria;
    public $nombre;
    public $descripcion;
    public $id_categoria;

    public function __construct($id_subcategoria, $nombre, $descripcion, $id_categoria) {
        $this->id_subcategoria = $id_subcategoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id_categoria = $id_categoria;
    }

    // Versión simple del create sin fecha_creacion
    public static function createSimple($conn, $nombre, $descripcion, $id_categoria) {
        // Validar datos
        if (empty($nombre) || empty($descripcion) || empty($id_categoria)) {
            echo '<pre>Error: Todos los campos son obligatorios.</pre>';
            return false;
        }
        
        if (strlen($nombre) < 3 || strlen($nombre) > 45) {
            echo '<pre>Error: El nombre debe tener entre 3 y 45 caracteres.</pre>';
            return false;
        }
        
        if (strlen($descripcion) < 3 || strlen($descripcion) > 45) {
            echo '<pre>Error: La descripción debe tener entre 3 y 45 caracteres.</pre>';
            return false;
        }
        
        // SQL simple sin fecha_creacion
        $sql = "INSERT INTO subcategorias (nombre, descripcion, id_categoria) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<pre>Error al preparar el statement: ' . $conn->error . '</pre>';
            return false;
        }
        
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_categoria);
        $result = $stmt->execute();
        
        if (!$result) {
            echo '<pre>Error al ejecutar el statement: ' . $stmt->error . '</pre>';
            echo '<pre>SQL: ' . $sql . '</pre>';
            echo '<pre>Parámetros: nombre=' . $nombre . ', descripcion=' . $descripcion . ', id_categoria=' . $id_categoria . '</pre>';
        } else {
            echo '<pre>Subcategoría creada correctamente.</pre>';
        }
        
        return $result;
    }

    // Obtener todas las subcategorías sin fecha_creacion
    public static function getAllSimple($conn) {
        $sql = "SELECT s.*, c.nombre AS categoria_nombre FROM subcategorias s JOIN categorias c ON s.id_categoria = c.id_categoria";
        $result = $conn->query($sql);
        $subcategorias = [];
        
        if ($result === false) {
            echo '<pre>Error en la consulta: ' . $conn->error . '</pre>';
            return $subcategorias;
        }
        
        if ($result->num_rows === 0) {
            echo '<pre>No hay subcategorías en la base de datos.</pre>';
            return $subcategorias;
        }
        
        while ($row = $result->fetch_assoc()) {
            $subcategorias[] = [
                'obj' => new SubcategorySimple($row['id_subcategoria'], $row['nombre'], $row['descripcion'], $row['id_categoria']),
                'categoria_nombre' => $row['categoria_nombre']
            ];
        }
        return $subcategorias;
    }

    // Obtener por ID sin fecha_creacion
    public static function getByIdSimple($conn, $id_subcategoria) {
        $sql = "SELECT * FROM subcategorias WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_subcategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new SubcategorySimple($row['id_subcategoria'], $row['nombre'], $row['descripcion'], $row['id_categoria']);
        }
        return null;
    }

    // Actualizar sin fecha_creacion
    public static function updateSimple($conn, $id_subcategoria, $nombre, $descripcion, $id_categoria) {
        if (empty($nombre) || empty($descripcion) || empty($id_categoria)) {
            echo '<pre>Error: Todos los campos son obligatorios.</pre>';
            return false;
        }
        
        if (strlen($nombre) < 3 || strlen($nombre) > 45) {
            echo '<pre>Error: El nombre debe tener entre 3 y 45 caracteres.</pre>';
            return false;
        }
        
        if (strlen($descripcion) < 3 || strlen($descripcion) > 45) {
            echo '<pre>Error: La descripción debe tener entre 3 y 45 caracteres.</pre>';
            return false;
        }
        
        $sql = "UPDATE subcategorias SET nombre = ?, descripcion = ?, id_categoria = ? WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<pre>Error al preparar el statement: ' . $conn->error . '</pre>';
            return false;
        }
        
        $stmt->bind_param("ssii", $nombre, $descripcion, $id_categoria, $id_subcategoria);
        $result = $stmt->execute();
        
        if (!$result) {
            echo '<pre>Error al ejecutar el statement: ' . $stmt->error . '</pre>';
        } else {
            echo '<pre>Subcategoría actualizada correctamente.</pre>';
        }
        
        return $result;
    }

    // Eliminar
    public static function deleteSimple($conn, $id_subcategoria) {
        $sql = "DELETE FROM subcategorias WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_subcategoria);
        return $stmt->execute();
    }
}
?>