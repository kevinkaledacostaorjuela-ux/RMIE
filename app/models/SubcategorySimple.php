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

    // Obtener por ID sin fecha_creacion (devuelve objeto)
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

    // Obtener por ID sin fecha_creacion (devuelve array)
    public static function getById($conn, $id_subcategoria) {
        $sql = "SELECT s.*, c.nombre AS categoria_nombre 
                FROM subcategorias s 
                LEFT JOIN categorias c ON s.id_categoria = c.id_categoria 
                WHERE s.id_subcategoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_subcategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row;
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

    // Verificar si la subcategoría tiene dependencias
    public static function checkDependencies($conn, $id_subcategoria) {
        $dependencies = [];
        
        // Verificar productos asociados
        $sqlProductos = "SELECT COUNT(*) as count FROM productos WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sqlProductos);
        $stmt->bind_param("i", $id_subcategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_assoc()['count'];
        
        if ($productos > 0) {
            $dependencies['productos'] = $productos;
            
            // Verificar ventas relacionadas con productos de esta subcategoría
            $sqlVentas = "SELECT COUNT(DISTINCT v.id_ventas) as count 
                         FROM ventas v 
                         INNER JOIN productos p ON v.id_productos = p.id_productos 
                         WHERE p.id_subcategoria = ?";
            $stmt = $conn->prepare($sqlVentas);
            $stmt->bind_param("i", $id_subcategoria);
            $stmt->execute();
            $result = $stmt->get_result();
            $ventas = $result->fetch_assoc()['count'];
            
            if ($ventas > 0) {
                $dependencies['ventas'] = $ventas;
            }
        }
        
        return $dependencies;
    }

    // Eliminar con manejo de dependencias
    public static function delete($conn, $id_subcategoria, $force = false) {
        // Verificar dependencias
        $dependencies = self::checkDependencies($conn, $id_subcategoria);
        
        if (!empty($dependencies) && !$force) {
            return ['error' => 'dependencies', 'data' => $dependencies];
        }
        
        try {
            // Iniciar transacción
            $conn->autocommit(false);
            
            if ($force && !empty($dependencies)) {
                // Si hay ventas, no permitir eliminación forzada
                if (isset($dependencies['ventas'])) {
                    $conn->rollback();
                    return ['error' => 'has_sales', 'data' => $dependencies];
                }
                
                // Actualizar productos para que no tengan subcategoría (NULL)
                if (isset($dependencies['productos'])) {
                    $sqlProductos = "UPDATE productos SET id_subcategoria = NULL WHERE id_subcategoria = ?";
                    $stmt = $conn->prepare($sqlProductos);
                    $stmt->bind_param("i", $id_subcategoria);
                    $stmt->execute();
                }
            }
            
            // Eliminar la subcategoría
            $sql = "DELETE FROM subcategorias WHERE id_subcategoria = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_subcategoria);
            $result = $stmt->execute();
            
            if ($result) {
                $conn->commit();
                return ['success' => true];
            } else {
                $conn->rollback();
                return ['error' => 'delete_failed'];
            }
            
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            return ['error' => 'exception', 'message' => $e->getMessage()];
        } finally {
            $conn->autocommit(true);
        }
    }

    // Eliminar simple (mantenido para compatibilidad)
    public static function deleteSimple($conn, $id_subcategoria) {
        try {
            $sql = "DELETE FROM subcategorias WHERE id_subcategoria = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_subcategoria);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            return ['error' => 'exception', 'message' => $e->getMessage()];
        }
    }
}
?>