<?php
class Route {
    public $id_ruta;
    public $direccion;
    public $nombre_local;
    public $nombre_cliente;
    public $id_clientes;
    public $id_reportes;
    public $id_ventas;

    public function __construct($id_ruta, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_reportes, $id_ventas) {
        $this->id_ruta = $id_ruta;
        $this->direccion = $direccion;
        $this->nombre_local = $nombre_local;
        $this->nombre_cliente = $nombre_cliente;
        $this->id_clientes = $id_clientes;
        $this->id_reportes = $id_reportes;
        $this->id_ventas = $id_ventas;
    }

    public static function getFiltered($conn, $reporte = '', $venta = '') {
        $sql = "SELECT r.*, rep.nombre AS reporte_nombre, v.nombre AS venta_nombre FROM rutas r LEFT JOIN reportes rep ON r.id_reportes = rep.id_reportes LEFT JOIN ventas v ON r.id_ventas = v.id_ventas";
        $params = [];
        $types = '';
        $where = [];
        if ($reporte) {
            $where[] = "r.id_reportes = ?";
            $params[] = $reporte;
            $types .= 'i';
        }
        if ($venta) {
            $where[] = "r.id_ventas = ?";
            $params[] = $venta;
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
        $rutas = [];
        while ($row = $result->fetch_assoc()) {
            $rutas[] = [
                'obj' => new Route($row['id_ruta'], $row['direccion'], $row['nombre_local'], $row['nombre_cliente'], $row['id_clientes'], $row['id_reportes'], $row['id_ventas']),
                'reporte_nombre' => $row['reporte_nombre'],
                'venta_nombre' => $row['venta_nombre']
            ];
        }
        return $rutas;
    }
}
?>
