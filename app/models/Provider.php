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
            'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['activo', 'inactivo', 'bloqueado']]],
            'email' => ['type' => 'email'],
            'ubicacion' => ['type' => 'text', 'options' => ['max_length' => 200]],
            'celular' => ['type' => 'text', 'options' => ['max_length' => 20]],
            'fecha_desde' => ['type' => 'date'],
            'fecha_hasta' => ['type' => 'date'],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
        ];
        
        // Procesar filtros
        $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
        
        // Mapeo de campos a columnas SQL
        $mapping = [
            'nombre' => ['column' => 'nombre_distribuidor', 'operator' => 'LIKE', 'type' => 's'],
            'estado' => ['column' => 'estado', 'operator' => '=', 'type' => 's'],
            'email' => ['column' => 'correo', 'operator' => 'LIKE', 'type' => 's'],
            'ubicacion' => ['column' => 'ubicacion', 'operator' => 'LIKE', 'type' => 's'],
            'celular' => ['column' => 'cel_proveedor', 'operator' => 'LIKE', 'type' => 's'],
            'fecha_desde' => ['column' => 'DATE(fecha_creacion)', 'operator' => '>=', 'type' => 's'],
            'fecha_hasta' => ['column' => 'DATE(fecha_creacion)', 'operator' => '<=', 'type' => 's'],
            'buscar' => [
                'columns' => ['nombre_distribuidor', 'correo', 'ubicacion', 'cel_proveedor'],
                'operator' => 'MULTIPLE_LIKE'
            ]
        ];
        
        // Construir consulta base
        $sql = "SELECT * FROM proveedores WHERE 1=1";
        
        // Construir WHERE con filtros
        $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
        
        if (!empty($whereData['where'])) {
            $sql .= " AND " . implode(" AND ", $whereData['where']);
        }
        
        $sql .= " ORDER BY nombre_distribuidor";
        
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
        return $stmt->execute();
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
}
?>
