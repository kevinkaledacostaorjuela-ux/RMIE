<?php
class User {
    public $id;
    public $username;
    public $password;

    public static function all($pdo) {
        try {
            $stmt = $pdo->query('SELECT * FROM users ORDER BY id DESC');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function find($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function create($pdo, $username, $password) {
        if (empty($username) || empty($password)) return false;
        try {
            $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
            return $stmt->execute([
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update($pdo, $id, $username, $password = null) {
        if (empty($username)) return false;
        try {
            if ($password) {
                $stmt = $pdo->prepare('UPDATE users SET username = :username, password = :password WHERE id = :id');
                return $stmt->execute([
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'id' => $id
                ]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET username = :username WHERE id = :id');
                return $stmt->execute([
                    'username' => $username,
                    'id' => $id
                ]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete($pdo, $id) {
        try {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function verify($pdo, $username, $password) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
