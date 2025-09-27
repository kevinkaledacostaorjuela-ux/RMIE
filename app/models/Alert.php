<?php
class Alert {
    public $id_alertas;
    public $cliente_no_disponible;
    public $id_clientes;
    public $id_productos;
    public $cantidad_minima;
    public $fecha_caducidad;

    public function __construct($id_alertas, $cliente_no_disponible, $id_clientes, $id_productos, $cantidad_minima = null, $fecha_caducidad = null) {
        $this->id_alertas = $id_alertas;
        $this->cliente_no_disponible = $cliente_no_disponible;
        $this->id_clientes = $id_clientes;
        $this->id_productos = $id_productos;
        $this->cantidad_minima = $cantidad_minima;
        $this->fecha_caducidad = $fecha_caducidad;
    }

    public static function create($conn, $id_productos, $cantidad_minima, $fecha_caducidad, $id_clientes) {
        $sql = "INSERT INTO alertas (id_productos, cantidad_minima, fecha_caducidad, id_clientes) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error en prepare(): ' . $conn->error);
        }
        if (!$stmt->bind_param('iisi', $id_productos, $cantidad_minima, $fecha_caducidad, $id_clientes)) {
            throw new Exception('Error en bind_param(): ' . $stmt->error);
        }
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar INSERT de alerta: ' . $stmt->error);
        }
        return true;
    }

    public static function getById($conn, $id) {
        $sql = "SELECT * FROM alertas WHERE id_alertas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function getAll($conn) {
        $sql = "SELECT a.*, p.nombre AS producto_nombre FROM alertas a JOIN productos p ON a.id_productos = p.id_productos";
        $result = $conn->query($sql);
        $alertas = [];
        while ($row = $result->fetch_assoc()) {
            $alertas[] = $row;
        }
        return $alertas;
    }

    public static function update($conn, $id, $data) {
        $sql = "UPDATE alertas SET id_productos = ?, cantidad_minima = ?, fecha_caducidad = ?, id_clientes = ? WHERE id_alertas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisii', $data['id_productos'], $data['cantidad_minima'], $data['fecha_caducidad'], $data['id_clientes'], $id);
        return $stmt->execute();
    }

    public static function delete($conn, $id) {
        $sql = "DELETE FROM alertas WHERE id_alertas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function getFiltered($conn, $filters = []) {
        $sql = "SELECT a.*, p.nombre AS producto_nombre, c.nombre AS cliente_nombre 
                FROM alertas a 
                JOIN productos p ON a.id_productos = p.id_productos 
                LEFT JOIN clientes c ON a.id_clientes = c.id_clientes";
        
        $params = [];
        $types = '';
        $where = [];
        
        // Filtro por producto ID
        if (!empty($filters['producto'])) {
            $where[] = "a.id_productos = ?";
            $params[] = $filters['producto'];
            $types .= 'i';
        }
        
        // Filtro por nombre de producto
        if (!empty($filters['nombre_producto'])) {
            $where[] = "p.nombre LIKE ?";
            $params[] = '%' . $filters['nombre_producto'] . '%';
            $types .= 's';
        }
        
        // Filtro por cantidad mínima
        if (!empty($filters['cantidad_min'])) {
            $where[] = "a.cantidad_minima >= ?";
            $params[] = $filters['cantidad_min'];
            $types .= 'i';
        }
        
        if (!empty($filters['cantidad_max'])) {
            $where[] = "a.cantidad_minima <= ?";
            $params[] = $filters['cantidad_max'];
            $types .= 'i';
        }
        
        // Filtro por fecha de caducidad
        if (!empty($filters['fecha_desde'])) {
            $where[] = "a.fecha_caducidad >= ?";
            $params[] = $filters['fecha_desde'];
            $types .= 's';
        }
        
        if (!empty($filters['fecha_hasta'])) {
            $where[] = "a.fecha_caducidad <= ?";
            $params[] = $filters['fecha_hasta'];
            $types .= 's';
        }
        
        // Construir consulta
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY a.fecha_caducidad ASC, p.nombre ASC";
        
        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        $alertas = [];
        while ($row = $result->fetch_assoc()) {
            $alertas[] = $row;
        }
        return $alertas;
    }
}
?>