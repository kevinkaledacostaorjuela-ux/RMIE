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
            $reportes[] = new self(
                $row['id_reportes'], 
                $row['nombre'], 
                $row['descripcion'], 
                $row['fecha'], 
                $row['estado'], 
                $row['id_ventas']
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
        $fecha = date('Y-m-d H:i:s');
        $sql = "INSERT INTO reportes (nombre, descripcion, fecha, estado, id_ventas) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        return $stmt->bind_param("ssss", $data['nombre'], $data['descripcion'], $fecha, $data['estado']) && $stmt->execute();
    }

    public static function update($conn, $id, $data) {
        $sql = "UPDATE reportes SET nombre = ?, descripcion = ?, estado = ? WHERE id_reportes = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->bind_param("sssi", $data['nombre'], $data['descripcion'], $data['estado'], $id) && $stmt->execute();
    }

    public static function delete($conn, $id) {
        $sql = "DELETE FROM reportes WHERE id_reportes = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function getStats($conn) {
        $stats = [];
        
        // Total de reportes
        $result = $conn->query("SELECT COUNT(*) as total FROM reportes");
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Reportes activos
        $result = $conn->query("SELECT COUNT(*) as activos FROM reportes WHERE estado = 'activo'");
        $stats['activos'] = $result->fetch_assoc()['activos'];
        
        // Reportes inactivos
        $result = $conn->query("SELECT COUNT(*) as inactivos FROM reportes WHERE estado = 'inactivo'");
        $stats['inactivos'] = $result->fetch_assoc()['inactivos'];
        
        // Reportes del mes actual
        $result = $conn->query("SELECT COUNT(*) as mes_actual FROM reportes WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())");
        $stats['mes_actual'] = $result->fetch_assoc()['mes_actual'];
        
        return $stats;
    }

    public static function generateData($conn, $id) {
        $reporte = self::getById($conn, $id);
        if (!$reporte) {
            return [];
        }
        
        $data = [];
        
        // Generar datos básicos del reporte
        $data['reporte'] = $reporte;
        $data['fecha_generacion'] = date('Y-m-d H:i:s');
        
        // Ejemplo de datos generados (esto se puede personalizar según el tipo de reporte)
        $data['ventas'] = [];
        $data['productos'] = [];
        $data['clientes'] = [];
        
        // Obtener datos de ventas relacionadas si existe id_ventas
        if ($reporte['id_ventas'] > 0) {
            $sql = "SELECT v.*, c.nombre as cliente_nombre, p.nombre as producto_nombre 
                    FROM ventas v 
                    LEFT JOIN clientes c ON v.id_clientes = c.id_clientes 
                    LEFT JOIN productos p ON v.id_productos = p.id_productos 
                    WHERE v.id_ventas = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $reporte['id_ventas']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $data['ventas'][] = $row;
            }
        }
        
        return $data;
    }

    public static function getFiltered($conn, $filtro_producto) {
        $sql = "SELECT * FROM reportes";
        if (!empty($filtro_producto)) {
            $sql .= " WHERE id_ventas IN (SELECT id_ventas FROM ventas WHERE id_productos = ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $filtro_producto);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = new self($row['id_reportes'], $row['nombre'], $row['descripcion'], $row['fecha'], $row['estado'], $row['id_ventas']);
        }
        return $reportes;
    }

    public static function getRecentReports($conn, $limit = 5) {
        $sql = "SELECT * FROM reportes ORDER BY fecha DESC LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = $row;
        }
        return $reportes;
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
}
?>