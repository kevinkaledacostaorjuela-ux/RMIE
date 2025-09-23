<?php
class Producto {
    public $id;
    public $nombre;
    public $categoria_id;

    public static function all($pdo) {
        try {
            $stmt = $pdo->query('SELECT * FROM productos ORDER BY id DESC');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function find($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function create($pdo, $nombre, $categoria_id) {
        if (empty($nombre) || empty($categoria_id)) return false;
        try {
            $stmt = $pdo->prepare('INSERT INTO productos (nombre, categoria_id) VALUES (:nombre, :categoria_id)');
            return $stmt->execute(['nombre' => $nombre, 'categoria_id' => $categoria_id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update($pdo, $id, $nombre, $categoria_id) {
        if (empty($nombre) || empty($categoria_id)) return false;
        try {
            $stmt = $pdo->prepare('UPDATE productos SET nombre = :nombre, categoria_id = :categoria_id WHERE id = :id');
            return $stmt->execute(['nombre' => $nombre, 'categoria_id' => $categoria_id, 'id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete($pdo, $id) {
        try {
            $stmt = $pdo->prepare('DELETE FROM productos WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
