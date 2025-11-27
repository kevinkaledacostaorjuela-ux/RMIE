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

    // Método mejorado para obtener todas las categorías con filtros
    public static function getAll($conn, $filtros = []) {
        try {
            require_once __DIR__ . '/../utils/FilterHelper.php';
            
            // Verificar qué columnas existen
            $columns_query = "SHOW COLUMNS FROM categorias";
            $columns_result = $conn->query($columns_query);
            $has_fecha_creacion = false;
            
            if ($columns_result) {
                while ($column = $columns_result->fetch_assoc()) {
                    if ($column['Field'] == 'fecha_creacion') {
                        $has_fecha_creacion = true;
                        break;
                    }
                }
            }
            
            // Si no hay filtros, usar método simple
            if (empty($filtros)) {
                $sql = $has_fecha_creacion 
                    ? "SELECT id_categoria, nombre, descripcion, fecha_creacion FROM categorias ORDER BY nombre ASC"
                    : "SELECT id_categoria, nombre, descripcion FROM categorias ORDER BY nombre ASC";
                    
                $result = $conn->query($sql);
                $categorias = [];
                
                if ($result === false) {
                    error_log('[Category::getAll] SQL Error: ' . $conn->error);
                    return $categorias; // Devolver array vacío en caso de error
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
            
            // Definir reglas de validación para filtros
            $filterRules = [
                'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
                'descripcion' => ['type' => 'text', 'options' => ['max_length' => 255]],
                'fecha_desde' => ['type' => 'date'],
                'fecha_hasta' => ['type' => 'date'],
                'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
            ];
            
            // Procesar filtros
            $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
            
            // Mapeo de campos a columnas SQL
            $mapping = [
                'nombre' => ['column' => 'nombre', 'operator' => 'LIKE', 'type' => 's'],
                'descripcion' => ['column' => 'descripcion', 'operator' => 'LIKE', 'type' => 's'],
                'buscar' => [
                    'columns' => ['nombre', 'descripcion'],
                    'operator' => 'MULTIPLE_LIKE'
                ]
            ];
            
            // Solo agregar filtros de fecha si la columna existe
            if ($has_fecha_creacion) {
                $mapping['fecha_desde'] = ['column' => 'DATE(fecha_creacion)', 'operator' => '>=', 'type' => 's'];
                $mapping['fecha_hasta'] = ['column' => 'DATE(fecha_creacion)', 'operator' => '<=', 'type' => 's'];
            }
            
            // Construir consulta base
            $sql = $has_fecha_creacion 
                ? "SELECT id_categoria, nombre, descripcion, fecha_creacion FROM categorias WHERE 1=1"
                : "SELECT id_categoria, nombre, descripcion FROM categorias WHERE 1=1";
            
            // Construir WHERE con filtros
            $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
            
            if (!empty($whereData['where'])) {
                $sql .= " AND " . implode(" AND ", $whereData['where']);
            }
            
            $sql .= " ORDER BY nombre ASC";
            
            $stmt = $conn->prepare($sql);
            if (!empty($whereData['params'])) {
                $stmt->bind_param($whereData['types'], ...$whereData['params']);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $categorias = [];
            
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
        } catch (Exception $e) {
            error_log('[Category::getAll] Exception: ' . $e->getMessage());
            return [];
        }
    }

    // Método para crear una nueva categoría
    public static function create($conn, $nombre, $descripcion) {
        // Validar entrada
        if (empty(trim($nombre))) {
            return false;
        }
        
        // Intentar primero con fecha_creacion
        try {
            $sql = "INSERT INTO categorias (nombre, descripcion, fecha_creacion) VALUES (?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception($conn->error);
            }
            $stmt->bind_param("ss", $nombre, $descripcion);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            // Si falla, intentar sin fecha_creacion
            try {
                $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    error_log('[Category::create] Prepare error: ' . $conn->error);
                    return false;
                }
                $stmt->bind_param("ss", $nombre, $descripcion);
                $result = $stmt->execute();
                if (!$result) {
                    error_log('[Category::create] Execute error: ' . $stmt->error);
                }
                $stmt->close();
                return $result;
            } catch (Exception $e2) {
                error_log('[Category::create] Exception: ' . $e2->getMessage());
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
        
        // Verificar subcategorías (las cuales pertenecen a esta categoría)
        $sqlSubcategorias = "SELECT COUNT(*) as count FROM subcategorias WHERE id_categoria = ?";
        $stmt = $conn->prepare($sqlSubcategorias);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $subcategorias = $result->fetch_assoc()['count'];
        
        if ($subcategorias > 0) {
            $dependencies['subcategorias'] = $subcategorias;
        }
        
        // Verificar productos relacionados con subcategorías de esta categoría
        $sqlProductos = "SELECT COUNT(*) as count FROM productos p 
                        INNER JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria 
                        WHERE s.id_categoria = ?";
        $stmt = $conn->prepare($sqlProductos);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_assoc()['count'];
        
        if ($productos > 0) {
            $dependencies['productos'] = $productos;
            
            // Verificar ventas relacionadas con productos de subcategorías de esta categoría
            $sqlVentas = "SELECT COUNT(DISTINCT v.id_ventas) as count 
                         FROM ventas v 
                         INNER JOIN productos p ON v.id_productos = p.id_productos 
                         INNER JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria 
                         WHERE s.id_categoria = ?";
            $stmt = $conn->prepare($sqlVentas);
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $result = $stmt->get_result();
            $ventas = $result->fetch_assoc()['count'];
            
            if ($ventas > 0) {
                $dependencies['ventas'] = $ventas;
            }
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
