<?php
class Local {
    private static function getPDO() {
        require __DIR__ . '/../../config/db.php';
        return $pdo;
    }

    public static function getAll() {
        $pdo = self::getPDO();
        $stmt = $pdo->query("SELECT * FROM locales");
        return $stmt->fetchAll();
    }

    public static function crear($nombre_local, $direccion, $cel_local, $estado, $localidad = '', $barrio = '') {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("INSERT INTO locales (nombre_local, direccion, cel_local, estado, localidad, barrio) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre_local, $direccion, $cel_local, $estado, $localidad, $barrio]);
    }

    public static function obtenerPorId($id_locales) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM locales WHERE id_locales = ?");
        $stmt->execute([$id_locales]);
        return $stmt->fetch();
    }

    public static function actualizar($id_locales, $nombre_local, $direccion, $cel_local, $estado, $localidad = '', $barrio = '') {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("UPDATE locales SET nombre_local = ?, direccion = ?, cel_local = ?, estado = ?, localidad = ?, barrio = ? WHERE id_locales = ?");
        $stmt->execute([$nombre_local, $direccion, $cel_local, $estado, $localidad, $barrio, $id_locales]);
    }

    public static function eliminar($id_locales) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("DELETE FROM locales WHERE id_locales = ?");
        $stmt->execute([$id_locales]);
    }
}
