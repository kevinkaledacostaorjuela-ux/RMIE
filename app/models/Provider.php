<?php
class Provider {
    public $id_proveedores;
    public $nombre_distribuidor;
    public $correo;
    public $cel_proveedor;
    public $estado;

    public function __construct($id_proveedores, $nombre_distribuidor, $correo, $cel_proveedor, $estado) {
        $this->id_proveedores = $id_proveedores;
        $this->nombre_distribuidor = $nombre_distribuidor;
        $this->correo = $correo;
        $this->cel_proveedor = $cel_proveedor;
        $this->estado = $estado;
    }

    public static function getAll($conn) {
        $sql = "SELECT * FROM proveedores";
        $result = $conn->query($sql);
        $proveedores = [];
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = new Provider($row['id_proveedores'], $row['nombre_distribuidor'], $row['correo'], $row['cel_proveedor'], $row['estado']);
        }
        return $proveedores;
    }

    public static function create($conn, $nombre_distribuidor, $correo, $cel_proveedor, $estado) {
        $sql = "INSERT INTO proveedores (nombre_distribuidor, correo, cel_proveedor, estado) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre_distribuidor, $correo, $cel_proveedor, $estado);
        return $stmt->execute();
    }

    public static function getById($conn, $id_proveedores) {
        $sql = "SELECT * FROM proveedores WHERE id_proveedores = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedores);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Provider($row['id_proveedores'], $row['nombre_distribuidor'], $row['correo'], $row['cel_proveedor'], $row['estado']);
        }
        return null;
    }

    public static function update($conn, $id_proveedores, $nombre_distribuidor, $correo, $cel_proveedor, $estado) {
        $sql = "UPDATE proveedores SET nombre_distribuidor = ?, correo = ?, cel_proveedor = ?, estado = ? WHERE id_proveedores = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombre_distribuidor, $correo, $cel_proveedor, $estado, $id_proveedores);
        return $stmt->execute();
    }

    public static function delete($conn, $id_proveedores) {
        $sql = "DELETE FROM proveedores WHERE id_proveedores = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedores);
        return $stmt->execute();
    }
}
?>
