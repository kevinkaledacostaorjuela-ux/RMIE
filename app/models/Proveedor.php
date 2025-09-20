<?php
require_once __DIR__ . '/../../config/db.php';

class Proveedor {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM proveedores");
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM proveedores WHERE id_proveedores = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear($nombre, $correo, $cel, $estado, $productos = []) {
        $stmt = $this->pdo->prepare("INSERT INTO proveedores (nombre_distribuidor, correo, cel_proveedor, estado) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $correo, $cel, $estado]);
        $id_proveedor = $this->pdo->lastInsertId();
        $this->asignarProductos($id_proveedor, $productos);
        return $id_proveedor;
    }

    public function actualizar($id, $nombre, $correo, $cel, $estado, $productos = []) {
        $stmt = $this->pdo->prepare("UPDATE proveedores SET nombre_distribuidor = ?, correo = ?, cel_proveedor = ?, estado = ? WHERE id_proveedores = ?");
        $stmt->execute([$nombre, $correo, $cel, $estado, $id]);
        $this->asignarProductos($id, $productos, true);
        return true;
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM proveedores WHERE id_proveedores = ?");
        $stmt->execute([$id]);
        $this->eliminarProductosProveedor($id);
        return true;
    }

    public function obtenerProductos() {
        $stmt = $this->pdo->query("SELECT * FROM productos");
        return $stmt->fetchAll();
    }

    public function obtenerProductosPorProveedor($id_proveedor) {
        $stmt = $this->pdo->prepare("SELECT p.* FROM productos p JOIN proveedor_producto pp ON p.id_productos = pp.id_productos WHERE pp.id_proveedores = ?");
        $stmt->execute([$id_proveedor]);
        return $stmt->fetchAll();
    }

    private function asignarProductos($id_proveedor, $productos, $eliminarPrevios = false) {
        if ($eliminarPrevios) {
            $this->eliminarProductosProveedor($id_proveedor);
        }
        if (!empty($productos)) {
            $stmt = $this->pdo->prepare("INSERT INTO proveedor_producto (id_proveedores, id_productos) VALUES (?, ?)");
            foreach ($productos as $id_producto) {
                $stmt->execute([$id_proveedor, $id_producto]);
            }
        }
    }

    private function eliminarProductosProveedor($id_proveedor) {
        $stmt = $this->pdo->prepare("DELETE FROM proveedor_producto WHERE id_proveedores = ?");
        $stmt->execute([$id_proveedor]);
    }
}