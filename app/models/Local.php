<?php
class Local {
    public $id_locales;
    public $direccion;
    public $nombre_local;
    public $cel_local;
    public $estado;
    public $localidad;
    public $barrio;
    public $fecha_creacion;

    public function __construct($id_locales, $direccion, $nombre_local, $cel_local, $estado, $localidad, $barrio, $fecha_creacion = null) {
        $this->id_locales = $id_locales;
        $this->direccion = $direccion;
        $this->nombre_local = $nombre_local;
        $this->cel_local = $cel_local;
        $this->estado = $estado;
        $this->localidad = $localidad;
        $this->barrio = $barrio;
        $this->fecha_creacion = $fecha_creacion;
    }

    public static function getAll($conn, $filtros = []) {
        $sql = "SELECT * FROM locales WHERE 1=1";
        $params = [];
        $types = "";
        
        // Aplicar filtros
        if (!empty($filtros['nombre'])) {
            $sql .= " AND nombre_local LIKE ?";
            $params[] = "%" . $filtros['nombre'] . "%";
            $types .= "s";
        }
        
        if (!empty($filtros['localidad'])) {
            $sql .= " AND localidad LIKE ?";
            $params[] = "%" . $filtros['localidad'] . "%";
            $types .= "s";
        }
        
        if (!empty($filtros['estado'])) {
            $sql .= " AND estado = ?";
            $params[] = $filtros['estado'];
            $types .= "s";
        }
        
        if (!empty($filtros['barrio'])) {
            $sql .= " AND barrio LIKE ?";
            $params[] = "%" . $filtros['barrio'] . "%";
            $types .= "s";
        }
        
        $sql .= " ORDER BY fecha_creacion DESC";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $locales = [];
        while ($row = $result->fetch_assoc()) {
            $locales[] = new Local(
                $row['id_locales'],
                $row['direccion'],
                $row['nombre_local'],
                $row['cel_local'],
                $row['estado'],
                $row['localidad'],
                $row['barrio'],
                $row['fecha_creacion']
            );
        }
        
        return $locales;
    }
    
    public static function getById($conn, $id) {
        $sql = "SELECT * FROM locales WHERE id_locales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new Local(
                $row['id_locales'],
                $row['direccion'],
                $row['nombre_local'],
                $row['cel_local'],
                $row['estado'],
                $row['localidad'],
                $row['barrio'],
                $row['fecha_creacion']
            );
        }
        
        return null;
    }
    
    public static function getByName($conn, $nombre) {
        $sql = "SELECT * FROM locales WHERE nombre_local = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new Local(
                $row['id_locales'],
                $row['direccion'],
                $row['nombre_local'],
                $row['cel_local'],
                $row['estado'],
                $row['localidad'],
                $row['barrio'],
                $row['fecha_creacion']
            );
        }
        
        return null;
    }
    
    public static function create($conn, $data) {
        $sql = "INSERT INTO locales (direccion, nombre_local, cel_local, estado, localidad, barrio) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", 
            $data['direccion'],
            $data['nombre_local'],
            $data['cel_local'],
            $data['estado'],
            $data['localidad'],
            $data['barrio']
        );
        
        return $stmt->execute();
    }
    
    public static function update($conn, $id, $data) {
        $sql = "UPDATE locales SET direccion = ?, nombre_local = ?, cel_local = ?, estado = ?, localidad = ?, barrio = ? WHERE id_locales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi",
            $data['direccion'],
            $data['nombre_local'],
            $data['cel_local'],
            $data['estado'],
            $data['localidad'],
            $data['barrio'],
            $id
        );
        
        return $stmt->execute();
    }
    
    public static function delete($conn, $id) {
        $sql = "DELETE FROM locales WHERE id_locales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public static function canDelete($conn, $id) {
        // Verificar si hay clientes asociados a este local
        $sql = "SELECT COUNT(*) as count FROM clientes WHERE id_locales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            return false;
        }
        
        // Verificar si hay productos asociados a este local
        $sql = "SELECT COUNT(*) as count FROM productos WHERE id_locales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            return false;
        }
        
        // Verificar si hay ventas asociadas a este local
        $sql = "SELECT COUNT(*) as count FROM ventas WHERE id_locales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            return false;
        }
        
        return true;
    }
    
    public static function getStats($conn) {
        $stats = [];
        
        // Total de locales
        $sql = "SELECT COUNT(*) as total FROM locales";
        $result = $conn->query($sql);
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Locales activos
        $sql = "SELECT COUNT(*) as activos FROM locales WHERE estado = 'Activo'";
        $result = $conn->query($sql);
        $stats['activos'] = $result->fetch_assoc()['activos'];
        
        // Locales inactivos
        $sql = "SELECT COUNT(*) as inactivos FROM locales WHERE estado = 'Inactivo'";
        $result = $conn->query($sql);
        $stats['inactivos'] = $result->fetch_assoc()['inactivos'];
        
        // Locales por localidad
        $sql = "SELECT localidad, COUNT(*) as count FROM locales GROUP BY localidad ORDER BY count DESC LIMIT 5";
        $result = $conn->query($sql);
        $stats['por_localidad'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['por_localidad'][] = $row;
        }
        
        // Locales creados este mes
        $sql = "SELECT COUNT(*) as este_mes FROM locales WHERE MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())";
        $result = $conn->query($sql);
        $stats['este_mes'] = $result->fetch_assoc()['este_mes'];
        
        return $stats;
    }
    
    public static function getLocalStats($conn) {
        $stats = [];
        
        // Clientes por local
        $sql = "SELECT l.nombre_local, COUNT(c.id_clientes) as clientes 
                FROM locales l 
                LEFT JOIN clientes c ON l.id_locales = c.id_locales 
                GROUP BY l.id_locales, l.nombre_local 
                ORDER BY clientes DESC";
        $result = $conn->query($sql);
        $stats['clientes_por_local'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['clientes_por_local'][] = $row;
        }
        
        // Productos por local
        $sql = "SELECT l.nombre_local, COUNT(p.id_productos) as productos 
                FROM locales l 
                LEFT JOIN productos p ON l.id_locales = p.id_locales 
                GROUP BY l.id_locales, l.nombre_local 
                ORDER BY productos DESC";
        $result = $conn->query($sql);
        $stats['productos_por_local'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['productos_por_local'][] = $row;
        }
        
        // Ventas por local
        $sql = "SELECT l.nombre_local, COUNT(v.id_ventas) as ventas, SUM(v.total) as total_ventas
                FROM locales l 
                LEFT JOIN ventas v ON l.id_locales = v.id_locales 
                GROUP BY l.id_locales, l.nombre_local 
                ORDER BY total_ventas DESC";
        $result = $conn->query($sql);
        $stats['ventas_por_local'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['ventas_por_local'][] = $row;
        }
        
        return $stats;
    }
}
?>
