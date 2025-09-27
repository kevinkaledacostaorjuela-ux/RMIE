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
        try {
            $sql = "UPDATE rutas SET direccion = ?, nombre_local = ?, nombre_cliente = ?, id_clientes = ?, id_ventas = ? WHERE id_ruta = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            $stmt->bind_param('sssiii', $direccion, $nombre_local, $nombre_cliente, $id_clientes, $id_ventas, $id);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            echo "<p>Ruta actualizada exitosamente.</p>";
        } catch (Exception $e) {
            echo "<p>Error en el modelo: " . $e->getMessage() . "</p>";
            throw new Exception("Error al actualizar la ruta: " . $e->getMessage());
        }
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

    // Métodos para obtener datos relacionados
    public static function getAvailableClients($conn) {
        try {
            $sql = "SELECT id_clientes, nombre FROM clientes ORDER BY nombre";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Error al obtener clientes: " . $conn->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAvailableClients: " . $e->getMessage());
            return [];
        }
    }

    public static function getAvailableSales($conn) {
        try {
            $sql = "SELECT id_ventas, CONCAT('Venta #', id_ventas, ' - $', total) as descripcion FROM ventas ORDER BY id_ventas DESC";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Error al obtener ventas: " . $conn->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAvailableSales: " . $e->getMessage());
            return [];
        }
    }

    // Validar si existe un cliente
    public static function clientExists($conn, $id_cliente) {
        try {
            $sql = "SELECT COUNT(*) as count FROM clientes WHERE id_clientes = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id_cliente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Error en clientExists: " . $e->getMessage());
            return false;
        }
    }

    // Validar si existe una venta
    public static function saleExists($conn, $id_venta) {
        try {
            $sql = "SELECT COUNT(*) as count FROM ventas WHERE id_ventas = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id_venta);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Error en saleExists: " . $e->getMessage());
            return false;
        }
    }
}
?>
