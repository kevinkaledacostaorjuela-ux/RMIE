<?php
class Sale {
    public $id_ventas;
    public $nombre;
    public $direccion;
    public $cantidad;
    public $fecha_venta;
    public $id_clientes;
    public $id_reportes;
    public $id_ruta;
    public $id_productos;

    public function __construct($id_ventas, $nombre, $direccion, $cantidad, $fecha_venta, $id_clientes, $id_reportes, $id_ruta, $id_productos) {
        $this->id_ventas = $id_ventas;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->cantidad = $cantidad;
        $this->fecha_venta = $fecha_venta;
        $this->id_clientes = $id_clientes;
        $this->id_reportes = $id_reportes;
        $this->id_ruta = $id_ruta;
        $this->id_productos = $id_productos;
    }

    public static function getFiltered($conn, $producto = '', $cliente = '') {
        $sql = "SELECT v.*, p.nombre AS producto_nombre, c.nombre AS cliente_nombre FROM ventas v JOIN productos p ON v.id_productos = p.id_productos JOIN clientes c ON v.id_clientes = c.id_clientes";
        $params = [];
        $types = '';
        $where = [];
        if ($producto) {
            $where[] = "v.id_productos = ?";
            $params[] = $producto;
            $types .= 'i';
        }
        if ($cliente) {
            $where[] = "v.id_clientes = ?";
            $params[] = $cliente;
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
        $ventas = [];
        while ($row = $result->fetch_assoc()) {
            $ventas[] = [
                'obj' => new Sale($row['id_ventas'], $row['nombre'], $row['direccion'], $row['cantidad'], $row['fecha_venta'], $row['id_clientes'], $row['id_reportes'], $row['id_ruta'], $row['id_productos']),
                'producto_nombre' => $row['producto_nombre'],
                'cliente_nombre' => $row['cliente_nombre']
            ];
        }
        return $ventas;
    }
}
?>
