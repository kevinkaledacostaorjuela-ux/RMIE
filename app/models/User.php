<?php
class User {
    private static function getPDO() {
        require __DIR__ . '/../../config/db.php';
        return $pdo;
    }

    public static function getAll() {
        $pdo = self::getPDO();
        $stmt = $pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll();
    }

    public static function getById($num_doc) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE num_doc = ?");
        $stmt->execute([$num_doc]);
        return $stmt->fetch();
    }

    public static function create($num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("INSERT INTO usuarios (num_doc, tipo_doc, nombres, apellidos, correo, contrasena, num_cel, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol]);
    }

    public static function update($num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("UPDATE usuarios SET tipo_doc = ?, nombres = ?, apellidos = ?, correo = ?, contrasena = ?, num_cel = ?, rol = ? WHERE num_doc = ?");
        $stmt->execute([$tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol, $num_doc]);
    }

    public static function delete($num_doc) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE num_doc = ?");
        $stmt->execute([$num_doc]);
    }
}
