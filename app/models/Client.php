<?php
class Client {
    public static function getAll($conn) {
        $sql = "SELECT * FROM clientes";
        $result = $conn->query($sql);
        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = new Client(
                $row['id_clientes'],
                $row['nombre'],
                $row['descripcion'],
                $row['cel_cliente'],
                $row['correo'],
                $row['estado'],
                $row['id_locales']
            );
        }
        return $clientes;
    }
    public $id_clientes;
    public $nombre;
    public $descripcion;
    public $cel_cliente;
    public $correo;
    public $estado;
    public $id_locales;

    public function __construct($id_clientes, $nombre, $descripcion, $cel_cliente, $correo, $estado, $id_locales) {
        $this->id_clientes = $id_clientes;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cel_cliente = $cel_cliente;
        $this->correo = $correo;
        $this->estado = $estado;
        $this->id_locales = $id_locales;
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
