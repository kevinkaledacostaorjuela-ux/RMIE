<?php
class Alert {
    public $id_alertas;
    public $cliente_no_disponible;
    public $id_clientes;
    public $id_producto;
    public $cantidad_minima;
    public $fecha_caducidad;

    public function __construct($id_alertas, $cliente_no_disponible, $id_clientes, $id_producto, $cantidad_minima = null, $fecha_caducidad = null) {
        $this->id_alertas = $id_alertas;
        $this->cliente_no_disponible = $cliente_no_disponible;
        $this->id_clientes = $id_clientes;
        $this->id_producto = $id_producto;
        $this->cantidad_minima = $cantidad_minima;
        $this->fecha_caducidad = $fecha_caducidad;
    }

    public static function create($conn, $id_producto, $cantidad_minima, $fecha_caducidad, $id_clientes) {
        $sql = "INSERT INTO alertas (id_producto, cantidad_minima, fecha_caducidad, id_clientes) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisi', $id_producto, $cantidad_minima, $fecha_caducidad, $id_clientes);
        return $stmt->execute();
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
        $sql = "SELECT a.*, p.nombre AS producto_nombre FROM alertas a JOIN productos p ON a.id_producto = p.id_productos";
        $result = $conn->query($sql);
        $alertas = [];
        while ($row = $result->fetch_assoc()) {
            $alertas[] = $row;
        }
        return $alertas;
    }

    public static function update($conn, $id, $data) {
        $sql = "UPDATE alertas SET id_producto = ?, cantidad_minima = ?, fecha_caducidad = ?, id_clientes = ? WHERE id_alertas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisii', $data['id_producto'], $data['cantidad_minima'], $data['fecha_caducidad'], $data['id_clientes'], $id);
        return $stmt->execute();
    }

    public static function delete($conn, $id) {
        $sql = "DELETE FROM alertas WHERE id_alertas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function getFiltered($conn, $producto = '') {
        $sql = "SELECT a.*, p.nombre AS producto_nombre FROM alertas a JOIN productos p ON a.id_producto = p.id_productos";
        $params = [];
        $types = '';
        $where = [];
        if ($producto) {
            $where[] = "a.id_producto = ?";
            $params[] = $producto;
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
        $alertas = [];
        while ($row = $result->fetch_assoc()) {
            $alertas[] = $row;
        }
        return $alertas;
    }
}
?>
