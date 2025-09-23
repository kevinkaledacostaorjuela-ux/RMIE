<?php
class Client {
    public $id_clientes;
    public $nombre;
    public $descripcion;
    public $cel_cliente;
    public $correo;
    public $estado;
    public $id_locales;
    public $fecha_creacion;
    public $local_nombre;

    public function __construct($id_clientes, $nombre, $descripcion, $cel_cliente, $correo, $estado, $id_locales, $fecha_creacion = null, $local_nombre = null) {
        $this->id_clientes = $id_clientes;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cel_cliente = $cel_cliente;
        $this->correo = $correo;
        $this->estado = $estado;
        $this->id_locales = $id_locales;
        $this->fecha_creacion = $fecha_creacion;
        $this->local_nombre = $local_nombre;
    }

    public static function getAll($conn, $filtros = []) {
        $sql = "SELECT c.*, l.nombre_local AS local_nombre, 
                       c.fecha_creacion
                FROM clientes c 
                LEFT JOIN locales l ON c.id_locales = l.id_locales";
        
        $params = [];
        $types = '';
        $where = [];
        
        // Filtro por local
        if (!empty($filtros['local'])) {
            $where[] = "c.id_locales = ?";
            $params[] = $filtros['local'];
            $types .= 'i';
        }
        
        // Filtro por estado
        if (!empty($filtros['estado'])) {
            $where[] = "c.estado = ?";
            $params[] = $filtros['estado'];
            $types .= 's';
        }
        
        // Filtro por búsqueda de texto
        if (!empty($filtros['busqueda'])) {
            $where[] = "(c.nombre LIKE ? OR c.correo LIKE ? OR c.cel_cliente LIKE ?)";
            $searchTerm = '%' . $filtros['busqueda'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'sss';
        }
        
        // Filtro por fecha de creación
        if (!empty($filtros['fecha_desde'])) {
            $where[] = "DATE(c.fecha_creacion) >= ?";
            $params[] = $filtros['fecha_desde'];
            $types .= 's';
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $where[] = "DATE(c.fecha_creacion) <= ?";
            $params[] = $filtros['fecha_hasta'];
            $types .= 's';
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY c.fecha_creacion DESC";
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = new Client(
                $row['id_clientes'],
                $row['nombre'],
                $row['descripcion'],
                $row['cel_cliente'],
                $row['correo'],
                $row['estado'],
                $row['id_locales'],
                $row['fecha_creacion'],
                $row['local_nombre']
            );
        }
        return $clientes;
    }

    public static function getById($conn, $id) {
        $sql = "SELECT c.*, l.nombre_local AS local_nombre 
                FROM clientes c 
                LEFT JOIN locales l ON c.id_locales = l.id_locales 
                WHERE c.id_clientes = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new Client(
                $row['id_clientes'],
                $row['nombre'],
                $row['descripcion'],
                $row['cel_cliente'],
                $row['correo'],
                $row['estado'],
                $row['id_locales'],
                $row['fecha_creacion'] ?? null,
                $row['local_nombre']
            );
        }
        return null;
    }

    public static function getByEmail($conn, $correo) {
        $sql = "SELECT * FROM clientes WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new Client(
                $row['id_clientes'],
                $row['nombre'],
                $row['descripcion'],
                $row['cel_cliente'],
                $row['correo'],
                $row['estado'],
                $row['id_locales'],
                $row['fecha_creacion'] ?? null
            );
        }
        return null;
    }

    public static function create($conn, $data) {
        $sql = "INSERT INTO clientes (nombre, descripcion, cel_cliente, correo, estado, id_locales, fecha_creacion) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', 
            $data['nombre'], 
            $data['descripcion'], 
            $data['cel_cliente'], 
            $data['correo'], 
            $data['estado'], 
            $data['id_locales']
        );
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        return false;
    }

    public static function update($conn, $id, $data) {
        $sql = "UPDATE clientes SET 
                nombre = ?, 
                descripcion = ?, 
                cel_cliente = ?, 
                correo = ?, 
                estado = ?, 
                id_locales = ? 
                WHERE id_clientes = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssii', 
            $data['nombre'], 
            $data['descripcion'], 
            $data['cel_cliente'], 
            $data['correo'], 
            $data['estado'], 
            $data['id_locales'],
            $id
        );
        
        return $stmt->execute();
    }

    public static function delete($conn, $id) {
        $sql = "DELETE FROM clientes WHERE id_clientes = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function getStats($conn) {
        $stats = [];
        
        // Total de clientes
        $sql = "SELECT COUNT(*) as total FROM clientes";
        $result = $conn->query($sql);
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Clientes activos
        $sql = "SELECT COUNT(*) as activos FROM clientes WHERE estado = 'activo'";
        $result = $conn->query($sql);
        $stats['activos'] = $result->fetch_assoc()['activos'];
        
        // Clientes inactivos
        $sql = "SELECT COUNT(*) as inactivos FROM clientes WHERE estado = 'inactivo'";
        $result = $conn->query($sql);
        $stats['inactivos'] = $result->fetch_assoc()['inactivos'];
        
        // Nuevos clientes este mes
        $sql = "SELECT COUNT(*) as nuevos FROM clientes 
                WHERE MONTH(fecha_creacion) = MONTH(CURRENT_DATE()) 
                AND YEAR(fecha_creacion) = YEAR(CURRENT_DATE())";
        $result = $conn->query($sql);
        $stats['nuevos_mes'] = $result->fetch_assoc()['nuevos'];
        
        return $stats;
    }

    public static function getClientStats($conn, $clientId) {
        $stats = [];
        
        // Ventas realizadas por el cliente
        $sql = "SELECT COUNT(*) as total_ventas, 
                       COALESCE(SUM(total), 0) as total_monto 
                FROM ventas WHERE id_clientes = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stats['ventas_realizadas'] = $row['total_ventas'];
        $stats['total_ventas'] = $row['total_monto'];
        
        return $stats;
    }

    public static function getFiltered($conn, $local = '') {
        $sql = "SELECT c.*, l.nombre_local AS local_nombre FROM clientes c JOIN locales l ON c.id_locales = l.id_locales";
        $params = [];
        $types = '';
        $where = [];
        if ($local) {
            $where[] = "c.id_locales = ?";
            $params[] = $local;
            $types .= 'i';
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = [
                'obj' => new Client($row['id_clientes'], $row['nombre'], $row['descripcion'], $row['cel_cliente'], $row['correo'], $row['estado'], $row['id_locales']),
                'local_nombre' => $row['local_nombre']
            ];
        }
        return $clientes;
    }
}
?>
