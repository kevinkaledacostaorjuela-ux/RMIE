<?php
class Alert {
    public $id_alertas;
    public $cliente_no_disponible;
    public $id_clientes;
    public $id_productos;

    public function __construct($id_alertas, $cliente_no_disponible, $id_clientes, $id_productos) {
        $this->id_alertas = $id_alertas;
        $this->cliente_no_disponible = $cliente_no_disponible;
        $this->id_clientes = $id_clientes;
        $this->id_productos = $id_productos;
    }

    public static function getFiltered($conn, $producto = '') {
        $sql = "SELECT a.*, p.nombre AS producto_nombre FROM alertas a JOIN productos p ON a.id_productos = p.id_productos";
        $params = [];
        $types = '';
        $where = [];
        if ($producto) {
            $where[] = "a.id_productos = ?";
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
            $alertas[] = [
                'obj' => new Alert($row['id_alertas'], $row['cliente_no_disponible'], $row['id_clientes'], $row['id_productos']),
                'producto_nombre' => $row['producto_nombre']
            ];
        }
        return $alertas;
    }
}
?>
