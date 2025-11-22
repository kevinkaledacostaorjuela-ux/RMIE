<?php
class Provider {
    public $id_proveedores;
    public $nombre_distribuidor;
    public $correo;
    public $cel_proveedor;
    public $estado;
    public $ubicacion;

    public function __construct($id_proveedores, $nombre_distribuidor, $correo, $cel_proveedor, $estado, $ubicacion = null) {
        $this->id_proveedores = $id_proveedores;
        $this->nombre_distribuidor = $nombre_distribuidor;
        $this->correo = $correo;
        $this->cel_proveedor = $cel_proveedor;
        $this->estado = $estado;
        $this->ubicacion = $ubicacion;
    }

    // Método mejorado con filtros
    public static function getAll($conn, $filtros = []) {
        require_once __DIR__ . '/../utils/FilterHelper.php';
          // Definir reglas de validación para filtros
        $filterRules = [
            'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['activo', 'inactivo', 'pendiente']]],
            'correo' => ['type' => 'email'],
            'telefono' => ['type' => 'text', 'options' => ['max_length' => 20]],
            'ubicacion' => ['type' => 'text', 'options' => ['max_length' => 200]],
            'fecha_desde' => ['type' => 'date'],
            'fecha_hasta' => ['type' => 'date'],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
        ];
        
        // Procesar filtros
        $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
        
        // Mapeo de campos a columnas SQL
        $mapping = [
            'nombre' => ['column' => 'p.nombre_distribuidor', 'operator' => 'LIKE', 'type' => 's'],
            'estado' => ['column' => 'p.estado', 'operator' => '=', 'type' => 's'],
            'correo' => ['column' => 'p.correo', 'operator' => 'LIKE', 'type' => 's'],
            'telefono' => ['column' => 'p.cel_proveedor', 'operator' => 'LIKE', 'type' => 's'],
            'producto' => ['column' => 'pr.nombre', 'operator' => 'LIKE', 'type' => 's'],
            'fecha_desde' => ['column' => 'DATE(p.fecha_creacion)', 'operator' => '>=', 'type' => 's'],
            'fecha_hasta' => ['column' => 'DATE(p.fecha_creacion)', 'operator' => '<=', 'type' => 's'],
            'buscar' => [
                'columns' => ['p.nombre_distribuidor', 'p.correo', 'p.cel_proveedor', 'pr.nombre'],
                'operator' => 'MULTIPLE_LIKE'
            ]
        ];
        
        // Construir consulta base con LEFT JOIN directo a productos
        $sql = "SELECT DISTINCT p.* FROM proveedores p 
                LEFT JOIN productos pr ON p.id_proveedores = pr.id_proveedores 
                WHERE 1=1";
        
        // Construir WHERE con filtros
        $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
        
        if (!empty($whereData['where'])) {
            $sql .= " AND " . implode(" AND ", $whereData['where']);
        }
        
        $sql .= " ORDER BY p.nombre_distribuidor";
        
        $stmt = $conn->prepare($sql);
        if (!empty($whereData['params'])) {
            $stmt->bind_param($whereData['types'], ...$whereData['params']);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $proveedores = [];
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = new Provider(
                $row['id_proveedores'], 
                $row['nombre_distribuidor'], 
                $row['correo'], 
                $row['cel_proveedor'], 
                $row['estado'], 
                $row['ubicacion'] ?? null
            );
        }
        return $proveedores;
    }

    public static function create($conn, $nombre_distribuidor, $correo, $cel_proveedor, $estado, $ubicacion = null) {
        $sql = "INSERT INTO proveedores (nombre_distribuidor, correo, cel_proveedor, estado, ubicacion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre_distribuidor, $correo, $cel_proveedor, $estado, $ubicacion);
        if ($stmt->execute()) {
            return $conn->insert_id; // retornar id del proveedor creado
        }
        return false;
    }

    public static function getById($conn, $id_proveedores) {
        $sql = "SELECT * FROM proveedores WHERE id_proveedores = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedores);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Provider($row['id_proveedores'], $row['nombre_distribuidor'], $row['correo'], $row['cel_proveedor'], $row['estado'], $row['ubicacion'] ?? null);
        }
        return null;
    }

    public static function update($conn, $id_proveedores, $nombre_distribuidor, $correo, $cel_proveedor, $estado, $ubicacion = null) {
        $sql = "UPDATE proveedores SET nombre_distribuidor = ?, correo = ?, cel_proveedor = ?, estado = ?, ubicacion = ? WHERE id_proveedores = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre_distribuidor, $correo, $cel_proveedor, $estado, $ubicacion, $id_proveedores);
        return $stmt->execute();
    }

    public static function delete($conn, $id_proveedores) {
        $sql = "DELETE FROM proveedores WHERE id_proveedores = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedores);
        return $stmt->execute();
    }

    // Obtener productos de un proveedor específico
    public static function getProductosByProveedor($conn, $id_proveedor) {
        $sql = "SELECT p.* 
                FROM productos p 
                WHERE p.id_proveedores = ?
                ORDER BY p.nombre";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedor);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = (object) $row;
        }
        return $productos;
    }

    // Asignar producto a proveedor
    public static function assignProducto($conn, $id_proveedor, $id_producto) {
        $sql = "UPDATE productos SET id_proveedores = ? WHERE id_productos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_proveedor, $id_producto);
        return $stmt->execute();
    }

    // Remover producto de proveedor (establecer a NULL o a un proveedor por defecto)
    public static function removeProducto($conn, $id_proveedor, $id_producto) {
        // Opción 1: Establecer a NULL si la columna lo permite
        // Opción 2: Establecer a un proveedor por defecto (ID 13 = "Sin Proveedor")
        $sql = "UPDATE productos SET id_proveedores = 13 WHERE id_proveedores = ? AND id_productos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_proveedor, $id_producto);
        return $stmt->execute();
    }

    // Remover todos los productos de un proveedor (establecer a proveedor por defecto)
    public static function removeAllProductos($conn, $id_proveedor) {
        // Establecer todos los productos de este proveedor al proveedor por defecto (ID 13)
        $sql = "UPDATE productos SET id_proveedores = 13 WHERE id_proveedores = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedor);
        return $stmt->execute();
    }

    // Actualizar productos de un proveedor (reemplaza todos)
    public static function updateProductos($conn, $id_proveedor, $productos_ids) {
        // Iniciar transacción
        $conn->begin_transaction();
        
        try {
            // Remover todas las asignaciones actuales
            self::removeAllProductos($conn, $id_proveedor);
            
            // Agregar las nuevas asignaciones
            if (!empty($productos_ids)) {
                foreach ($productos_ids as $id_producto) {
                    if (!empty($id_producto)) {
                        self::assignProducto($conn, $id_proveedor, $id_producto);
                    }
                }
            }
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error updating provider products: " . $e->getMessage());
            return false;
        }
    }
}
?>
