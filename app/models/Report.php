<?php
class Report {
    public static function create($conn, $nombre, $descripcion, $id_productos) {
        $sql = "INSERT INTO reportes (nombre, descripcion, fecha, estado, id_productos) VALUES (?, ?, NOW(), 'activo', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_productos);
        return $stmt->execute();
    }
    public $id_reportes;
    public $nombre;
    public $descripcion;
    public $fecha;
    public $estado;
    public $id_ventas;
    public $id_productos;

    public function __construct($id_reportes, $nombre, $descripcion, $fecha, $estado, $id_ventas, $id_productos) {
        $this->id_reportes = $id_reportes;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->id_ventas = $id_ventas;
        $this->id_productos = $id_productos;
    }

    public static function getFiltered($conn, $producto = '') {
        $sql = "SELECT r.*, p.nombre AS producto_nombre FROM reportes r JOIN productos p ON r.id_productos = p.id_productos";
        $params = [];
        $types = '';
        $where = [];
        if ($producto) {
            $where[] = "r.id_productos = ?";
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
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = [
                'obj' => new Report($row['id_reportes'], $row['nombre'], $row['descripcion'], $row['fecha'], $row['estado'], $row['id_ventas'], $row['id_productos']),
                'producto_nombre' => $row['producto_nombre']
            ];
        }
        return $reportes;
    }
}
?>
