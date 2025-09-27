<?php
class Report {
    public $id_reportes;
    public $nombre;
    public $descripcion;
    public $fecha;
    public $estado;
    public $id_ventas;
    public $tipo;
    public $parametros;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($id_reportes, $nombre, $descripcion, $fecha, $estado, $id_ventas, $tipo = 'general', $parametros = null) {
        $this->id_reportes = $id_reportes;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->id_ventas = $id_ventas;
        $this->tipo = $tipo;
        $this->parametros = $parametros;
    }

    public static function getAll($conn, $filtros = []) {
        $sql = "SELECT * FROM reportes WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($filtros['buscar'])) {
            $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
            $search = "%" . $filtros['buscar'] . "%";
            $params[] = $search;
            $params[] = $search;
            $types .= "ss";
        }
        
        if (!empty($filtros['estado'])) {
            $sql .= " AND estado = ?";
            $params[] = $filtros['estado'];
            $types .= "s";
        }
        
        if (!empty($filtros['fecha_inicio'])) {
            $sql .= " AND fecha >= ?";
            $params[] = $filtros['fecha_inicio'];
            $types .= "s";
        }
        
        if (!empty($filtros['fecha_fin'])) {
            $sql .= " AND fecha <= ?";
            $params[] = $filtros['fecha_fin'];
            $types .= "s";
        }
        
        if (!empty($filtros['tipo'])) {
            $sql .= " AND tipo = ?";
            $params[] = $filtros['tipo'];
            $types .= "s";
        }
        
        $sql .= " ORDER BY fecha DESC";
        
        if (!empty($params)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = new Report(
                $row['id_reportes'], 
                $row['nombre'], 
                $row['descripcion'], 
                $row['fecha'], 
                $row['estado'], 
                $row['id_ventas'] ?? 0,
                $row['tipo'] ?? 'general',
                $row['parametros'] ?? '{}'
            );
        }
        return $reportes;
    }

    public static function getById($conn, $id) {
        $sql = "SELECT * FROM reportes WHERE id_reportes = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public static function create($conn, $data) {
        try {
            $fecha = date('Y-m-d H:i:s');
            
            // Verificar si la tabla tiene las columnas nuevas
            $checkColumns = $conn->query("SHOW COLUMNS FROM reportes LIKE 'tipo'");
            $hasNewColumns = $checkColumns->num_rows > 0;
            
            if ($hasNewColumns) {
                $sql = "INSERT INTO reportes (nombre, descripcion, fecha, estado, tipo, parametros, id_ventas, fecha_creacion, fecha_actualizacion) 
                        VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), NOW())";
                $stmt = $conn->prepare($sql);
                $parametros = $data['parametros'] ?? '{}';
                $tipo = $data['tipo'] ?? 'general';
                $result = $stmt->bind_param("ssssss", 
                    $data['nombre'], 
                    $data['descripcion'], 
                    $fecha, 
                    $data['estado'], 
                    $tipo, 
                    $parametros
                );
            } else {
                // Fallback para la estructura antigua
                $sql = "INSERT INTO reportes (nombre, descripcion, fecha, estado, id_ventas) VALUES (?, ?, ?, ?, 0)";
                $stmt = $conn->prepare($sql);
                $result = $stmt->bind_param("ssss", 
                    $data['nombre'], 
                    $data['descripcion'], 
                    $fecha, 
                    $data['estado']
                );
            }
            
            return $result && $stmt->execute();
        } catch (Exception $e) {
            error_log("Error creating report: " . $e->getMessage());
            return false;
        }
    }

    public static function update($conn, $id, $data) {
        try {
            // Verificar si la tabla tiene las columnas nuevas
            $checkColumns = $conn->query("SHOW COLUMNS FROM reportes LIKE 'tipo'");
            $hasNewColumns = $checkColumns->num_rows > 0;
            
            if ($hasNewColumns) {
                $sql = "UPDATE reportes SET 
                        nombre = ?, 
                        descripcion = ?, 
                        estado = ?, 
                        tipo = ?, 
                        parametros = ?, 
                        fecha_actualizacion = NOW() 
                        WHERE id_reportes = ?";
                
                $stmt = $conn->prepare($sql);
                $parametros = $data['parametros'] ?? '{}';
                $tipo = $data['tipo'] ?? 'general';
                $result = $stmt->bind_param("sssssi", 
                    $data['nombre'], 
                    $data['descripcion'], 
                    $data['estado'], 
                    $tipo, 
                    $parametros,
                    $id
                );
            } else {
                // Fallback para la estructura antigua
                $sql = "UPDATE reportes SET nombre = ?, descripcion = ?, estado = ? WHERE id_reportes = ?";
                $stmt = $conn->prepare($sql);
                $result = $stmt->bind_param("sssi", 
                    $data['nombre'], 
                    $data['descripcion'], 
                    $data['estado'], 
                    $id
                );
            }
            
            return $result && $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating report: " . $e->getMessage());
            return false;
        }
    }

    public static function delete($conn, $id) {
        try {
            $sql = "DELETE FROM reportes WHERE id_reportes = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting report: " . $e->getMessage());
            return false;
        }
    }

    public static function getStats($conn) {
        $stats = [
            'total' => 0,
            'activos' => 0,
            'inactivos' => 0,
            'borradores' => 0
        ];

        try {
            // Total de reportes
            $sql = "SELECT COUNT(*) as total FROM reportes";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $stats['total'] = $row['total'];

            // Por estado
            $sql = "SELECT estado, COUNT(*) as count FROM reportes GROUP BY estado";
            $result = $conn->query($sql);
            
            while ($row = $result->fetch_assoc()) {
                switch ($row['estado']) {
                    case 'activo':
                        $stats['activos'] = $row['count'];
                        break;
                    case 'inactivo':
                        $stats['inactivos'] = $row['count'];
                        break;
                    case 'borrador':
                        $stats['borradores'] = $row['count'];
                        break;
                }
            }
        } catch (Exception $e) {
            error_log("Error getting stats: " . $e->getMessage());
        }

        return $stats;
    }

    public static function getReportsByStatus($conn, $status) {
        $sql = "SELECT * FROM reportes WHERE estado = ? ORDER BY fecha DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = $row;
        }
        return $reportes;
    }

    // Método para generar reportes específicos
    public static function generateReportData($conn, $tipo, $parametros = []) {
        $data = [];
        
        try {
            switch ($tipo) {
                case 'ventas':
                    $sql = "SELECT v.*, c.nombre as cliente_nombre, u.nombres as usuario_nombre 
                            FROM ventas v 
                            LEFT JOIN clientes c ON v.id_clientes = c.id_clientes 
                            LEFT JOIN usuarios u ON v.num_doc = u.num_doc";
                    
                    $conditions = [];
                    $params = [];
                    $types = "";
                    
                    if (!empty($parametros['fecha_inicio'])) {
                        $conditions[] = "v.fecha >= ?";
                        $params[] = $parametros['fecha_inicio'];
                        $types .= "s";
                    }
                    
                    if (!empty($parametros['fecha_fin'])) {
                        $conditions[] = "v.fecha <= ?";
                        $params[] = $parametros['fecha_fin'];
                        $types .= "s";
                    }
                    
                    if (!empty($parametros['include_canceled']) && $parametros['include_canceled'] == '0') {
                        $conditions[] = "v.estado != 'cancelada'";
                    }
                    
                    if (!empty($conditions)) {
                        $sql .= " WHERE " . implode(" AND ", $conditions);
                    }
                    
                    $sql .= " ORDER BY v.fecha DESC";
                    
                    if (!empty($params)) {
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param($types, ...$params);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $result = $conn->query($sql);
                    }
                    
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    break;
                    
                case 'productos':
                    $sql = "SELECT p.*, c.nombre_categoria, sc.nombre_subcategoria 
                            FROM productos p 
                            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
                            LEFT JOIN subcategorias sc ON p.id_subcategoria = sc.id_subcategoria";
                    
                    $conditions = [];
                    $params = [];
                    $types = "";
                    
                    if (!empty($parametros['stock_minimo'])) {
                        $conditions[] = "p.stock <= ?";
                        $params[] = $parametros['stock_minimo'];
                        $types .= "i";
                    }
                    
                    if (!empty($conditions)) {
                        $sql .= " WHERE " . implode(" AND ", $conditions);
                    }
                    
                    $sql .= " ORDER BY p.nombre_producto";
                    
                    if (!empty($params)) {
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param($types, ...$params);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $result = $conn->query($sql);
                    }
                    
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    break;
                    
                case 'clientes':
                    $sql = "SELECT * FROM clientes ORDER BY nombre";
                    $result = $conn->query($sql);
                    
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    break;
                    
                default:
                    // Reporte general
                    $data = [
                        'resumen' => 'Reporte general del sistema RMIE',
                        'fecha_generacion' => date('Y-m-d H:i:s')
                    ];
                    break;
            }
        } catch (Exception $e) {
            error_log("Error generating report data: " . $e->getMessage());
        }
        
        return $data;
    }
}
?>