<?php
class Route {
    public static function getAll($conn) {
        $sql = "SELECT * FROM rutas";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function create($conn, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas) {
        try {
            $sql = "INSERT INTO rutas (direccion, nombre_local, nombre_cliente, id_clientes, id_ventas) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            $stmt->bind_param('sssii', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            echo "<p>Ruta creada exitosamente en el modelo.</p>";
        } catch (Exception $e) {
            echo "<p>Error en el modelo: " . $e->getMessage() . "</p>";
            throw new Exception("Error al crear la ruta: " . $e->getMessage());
        }
    }

    public static function getById($conn, $id) {
        $sql = "SELECT * FROM rutas WHERE id_ruta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function update($conn, $id, $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas) {
        $sql = "UPDATE rutas SET direccion = ?, nombre_local = ?, nombre_cliente = ?, id_clientes = ?, = ?, id_ventas = ? WHERE id_ruta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssiiii', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $id);
        $stmt->execute();
    }

    public static function delete($conn, $id) {
        $sql = "DELETE FROM rutas WHERE id_ruta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function getFiltered($conn, $venta = '') {
        $sql = "SELECT * FROM rutas WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($venta)) {
            $sql .= " AND id_ventas = ?";
            $params[] = $venta;
            $types .= 'i';
        }

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
