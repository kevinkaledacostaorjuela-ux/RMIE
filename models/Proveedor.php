<?php
class Proveedor {
    public $id;
    public $nombre;

    public static function all($pdo) {
        try {
            $stmt = $pdo->query('SELECT * FROM proveedores ORDER BY id DESC');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function find($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM proveedores WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function create($pdo, $nombre) {
        if (empty($nombre)) return false;
        try {
            $stmt = $pdo->prepare('INSERT INTO proveedores (nombre) VALUES (:nombre)');
            return $stmt->execute(['nombre' => $nombre]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update($pdo, $id, $nombre) {
        if (empty($nombre)) return false;
        try {
            $stmt = $pdo->prepare('UPDATE proveedores SET nombre = :nombre WHERE id = :id');
            return $stmt->execute(['nombre' => $nombre, 'id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete($pdo, $id) {
        try {
            $stmt = $pdo->prepare('DELETE FROM proveedores WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
