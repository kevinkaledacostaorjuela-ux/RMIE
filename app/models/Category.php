<?php
class Category {
    public $id_categoria;
    public $nombre;
    public $descripcion;
    public $fecha_creacion;

    public function __construct($id_categoria = null, $nombre = null, $descripcion = null, $fecha_creacion = null) {
        $this->id_categoria = $id_categoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fecha_creacion = $fecha_creacion;
    }

    // Método para obtener todas las categorías
    public static function getAll($conn) {
        // Verificar qué columnas existen
        $columns_query = "SHOW COLUMNS FROM categorias";
        $columns_result = $conn->query($columns_query);
        $has_fecha_creacion = false;
        
        while ($column = $columns_result->fetch_assoc()) {
            if ($column['Field'] == 'fecha_creacion') {
                $has_fecha_creacion = true;
                break;
            }
        }
        
        $sql = $has_fecha_creacion 
            ? "SELECT id_categoria, nombre, descripcion, fecha_creacion FROM categorias ORDER BY nombre ASC"
            : "SELECT id_categoria, nombre, descripcion FROM categorias ORDER BY nombre ASC";
            
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
            $fecha_creacion = $has_fecha_creacion ? ($row['fecha_creacion'] ?? null) : null;
            $categorias[] = new Category(
                $row['id_categoria'], 
                $row['nombre'], 
                $row['descripcion'], 
                $fecha_creacion
            );
        }
        return $categorias;
    }

    // Método para crear una nueva categoría
    public static function create($conn, $nombre, $descripcion) {
        // Intentar primero con fecha_creacion
        try {
            $sql = "INSERT INTO categorias (nombre, descripcion, fecha_creacion) VALUES (?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception($conn->error);
            }
            $stmt->bind_param("ss", $nombre, $descripcion);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception($stmt->error);
            }
            echo '<pre>Categoría creada correctamente.</pre>';
            return $result;
        } catch (Exception $e) {
            // Si falla, intentar sin fecha_creacion
            try {
                $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";
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
                    echo '<pre>Categoría creada correctamente (sin fecha).</pre>';
                }
                return $result;
            } catch (Exception $e2) {
                echo '<pre>Error al crear categoría: ' . $e2->getMessage() . '</pre>';
                return false;
            }
        }
    }

    // Método para obtener una categoría por ID
    public static function getById($conn, $id_categoria) {
        // Verificar qué columnas existen
        $columns_query = "SHOW COLUMNS FROM categorias";
        $columns_result = $conn->query($columns_query);
        $has_fecha_creacion = false;
        
        while ($column = $columns_result->fetch_assoc()) {
            if ($column['Field'] == 'fecha_creacion') {
                $has_fecha_creacion = true;
                break;
            }
        }
        
        $sql = $has_fecha_creacion 
            ? "SELECT id_categoria, nombre, descripcion, fecha_creacion FROM categorias WHERE id_categoria = ?"
            : "SELECT id_categoria, nombre, descripcion FROM categorias WHERE id_categoria = ?";
            
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fecha_creacion = $has_fecha_creacion ? ($row['fecha_creacion'] ?? null) : null;
            return new Category(
                $row['id_categoria'], 
                $row['nombre'], 
                $row['descripcion'], 
                $fecha_creacion
            );
        }
        return null;
    }

    // Método para actualizar una categoría
    public static function update($conn, $id_categoria, $nombre, $descripcion) {
        $sql = "UPDATE categorias SET nombre = ?, descripcion = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_categoria);
        return $stmt->execute();
    }    // Verificar si la categoría tiene dependencias
    public static function checkDependencies($conn, $id_categoria) {
        $dependencies = [];
        
        // Verificar productos
        $sqlProductos = "SELECT COUNT(*) as count FROM productos WHERE id_categoria = ?";
        $stmt = $conn->prepare($sqlProductos);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_assoc()['count'];
        
        if ($productos > 0) {
            $dependencies['productos'] = $productos;
            
            // Verificar ventas relacionadas con productos de esta categoría
            $sqlVentas = "SELECT COUNT(DISTINCT v.id_ventas) as count 
                         FROM ventas v 
                         INNER JOIN productos p ON v.id_productos = p.id_productos 
                         WHERE p.id_categoria = ?";
            $stmt = $conn->prepare($sqlVentas);
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $result = $stmt->get_result();
            $ventas = $result->fetch_assoc()['count'];
            
            if ($ventas > 0) {
                $dependencies['ventas'] = $ventas;
            }
        }
        
        // Verificar subcategorías
        $sqlSubcategorias = "SELECT COUNT(*) as count FROM subcategorias WHERE id_categoria = ?";
        $stmt = $conn->prepare($sqlSubcategorias);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $subcategorias = $result->fetch_assoc()['count'];
        
        if ($subcategorias > 0) {
            $dependencies['subcategorias'] = $subcategorias;
        }
        
        return $dependencies;
    }

    public static function delete($conn, $id_categoria, $force = false) {
        // Verificar dependencias
        $dependencies = self::checkDependencies($conn, $id_categoria);
        
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
                
                // Eliminar subcategorías asociadas
                if (isset($dependencies['subcategorias'])) {
                    $sqlSubcategorias = "DELETE FROM subcategorias WHERE id_categoria = ?";
                    $stmt = $conn->prepare($sqlSubcategorias);
                    $stmt->bind_param("i", $id_categoria);
                    $stmt->execute();
                }
                
                // Actualizar productos para que no tengan categoría (NULL)
                if (isset($dependencies['productos'])) {
                    $sqlProductos = "UPDATE productos SET id_categoria = NULL WHERE id_categoria = ?";
                    $stmt = $conn->prepare($sqlProductos);
                    $stmt->bind_param("i", $id_categoria);
                    $stmt->execute();
                }
            }
            
            // Eliminar la categoría
            $sql = "DELETE FROM categorias WHERE id_categoria = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_categoria);
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

    // Método básico para eliminar una categoría (mantenido para compatibilidad)
    public static function deleteBasic($conn, $id_categoria) {
        $sql = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_categoria);
        try {
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            return $e;
        }
    }
}
?>
