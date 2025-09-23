<?php
class Venta {
    private static function getPDO() {
        require __DIR__ . '/../../config/db.php';
        return $pdo;
    }

    public static function getAll() {
        $pdo = self::getPDO();
        $sql = "SELECT v.*, c.nombre AS nombre_cliente FROM ventas v LEFT JOIN clientes c ON v.id_clientes = c.id_clientes";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function obtenerPorId($id_ventas) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM ventas WHERE id_ventas = ?");
        $stmt->execute([$id_ventas]);
        return $stmt->fetch();
    }

    public static function crear($nombre, $direccion, $cantidad, $fecha_venta, $id_clientes) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("INSERT INTO ventas (nombre, direccion, cantidad, fecha_venta, id_clientes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $direccion, $cantidad, $fecha_venta, $id_clientes]);
    }

    public static function actualizar($id_ventas, $nombre, $direccion, $cantidad, $fecha_venta, $id_clientes) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("UPDATE ventas SET nombre = ?, direccion = ?, cantidad = ?, fecha_venta = ?, id_clientes = ? WHERE id_ventas = ?");
        $stmt->execute([$nombre, $direccion, $cantidad, $fecha_venta, $id_clientes, $id_ventas]);
    }

    public static function eliminar($id_ventas) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("DELETE FROM ventas WHERE id_ventas = ?");
        $stmt->execute([$id_ventas]);
    }
}
