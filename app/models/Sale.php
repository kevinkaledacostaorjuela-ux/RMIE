<?php
class Sale {
    public $id_ventas;
    public $nombre;
    public $direccion;
    public $cantidad;
    public $fecha_venta;
    public $id_clientes;
    public $id_reportes;
    public $id_ruta;
    public $id_productos;
    public $precio_unitario;
    public $total;
    public $estado;
    public $num_doc;
    
    // Propiedades adicionales para mostrar información relacionada
    public $producto_nombre;
    public $cliente_nombre;
    public $usuario_nombre;

    public function __construct($id_ventas, $nombre, $direccion, $cantidad, $fecha_venta, $id_clientes, $id_reportes = null, $id_ruta = null, $id_productos = null, $precio_unitario = null, $total = null, $estado = 'pendiente', $num_doc = null) {
        $this->id_ventas = $id_ventas;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->cantidad = $cantidad;
        $this->fecha_venta = $fecha_venta;
        $this->id_clientes = $id_clientes;
        $this->id_reportes = $id_reportes;
        $this->id_ruta = $id_ruta;
        $this->id_productos = $id_productos;
        $this->precio_unitario = $precio_unitario;
        $this->total = $total;
        $this->estado = $estado;
        $this->num_doc = $num_doc;
    }

    public static function getAll($conn) {
        $sql = "SELECT v.*, p.nombre AS producto_nombre, c.nombre AS cliente_nombre, u.nombres AS usuario_nombre 
                FROM ventas v 
                LEFT JOIN productos p ON v.id_productos = p.id_productos 
                LEFT JOIN clientes c ON v.id_clientes = c.id_clientes 
                LEFT JOIN usuarios u ON v.num_doc = u.num_doc 
                ORDER BY v.fecha_venta DESC";
        
        $result = $conn->query($sql);
        $ventas = [];
        
        while ($row = $result->fetch_assoc()) {
            $venta = new Sale(
                $row['id_ventas'], 
                $row['nombre'], 
                $row['direccion'], 
                $row['cantidad'], 
                $row['fecha_venta'], 
                $row['id_clientes'], 
                $row['id_reportes'], 
                $row['id_ruta'], 
                $row['id_productos'], 
                $row['precio_unitario'], 
                $row['total'], 
                $row['estado'], 
                $row['num_doc']
            );
            
            // Agregar información relacionada
            $venta->producto_nombre = $row['producto_nombre'];
            $venta->cliente_nombre = $row['cliente_nombre'];
            $venta->usuario_nombre = $row['usuario_nombre'];
            
            $ventas[] = $venta;
        }
        
        return $ventas;
    }

    public static function create($conn, $id_productos, $id_clientes, $fecha_venta, $cantidad, $precio_unitario, $total, $estado, $num_doc, $nombre = '', $direccion = '') {
        $sql = "INSERT INTO ventas (nombre, direccion, cantidad, fecha_venta, id_clientes, id_productos, precio_unitario, total, estado, num_doc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiiidsi", $nombre, $direccion, $cantidad, $fecha_venta, $id_clientes, $id_productos, $precio_unitario, $total, $estado, $num_doc);
        return $stmt->execute();
    }

    public static function getById($conn, $id_ventas) {
        $sql = "SELECT v.*, p.nombre AS producto_nombre, c.nombre AS cliente_nombre, u.nombres AS usuario_nombre 
                FROM ventas v 
                LEFT JOIN productos p ON v.id_productos = p.id_productos 
                LEFT JOIN clientes c ON v.id_clientes = c.id_clientes 
                LEFT JOIN usuarios u ON v.num_doc = u.num_doc 
                WHERE v.id_ventas = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_ventas);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $venta = new Sale(
                $row['id_ventas'], 
                $row['nombre'], 
                $row['direccion'], 
                $row['cantidad'], 
                $row['fecha_venta'], 
                $row['id_clientes'], 
                $row['id_reportes'], 
                $row['id_ruta'], 
                $row['id_productos'], 
                $row['precio_unitario'], 
                $row['total'], 
                $row['estado'], 
                $row['num_doc']
            );
            
            // Agregar información relacionada
            $venta->producto_nombre = $row['producto_nombre'];
            $venta->cliente_nombre = $row['cliente_nombre'];
            $venta->usuario_nombre = $row['usuario_nombre'];
            
            return $venta;
        }
        
        return null;
    }

    public static function update($conn, $id_ventas, $id_productos, $id_clientes, $fecha_venta, $cantidad, $precio_unitario, $total, $estado, $num_doc, $nombre = '', $direccion = '') {
        $sql = "UPDATE ventas SET nombre = ?, direccion = ?, cantidad = ?, fecha_venta = ?, id_clientes = ?, id_productos = ?, precio_unitario = ?, total = ?, estado = ?, num_doc = ? WHERE id_ventas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiiidsii", $nombre, $direccion, $cantidad, $fecha_venta, $id_clientes, $id_productos, $precio_unitario, $total, $estado, $num_doc, $id_ventas);
        return $stmt->execute();
    }

    public static function delete($conn, $id_ventas) {
        $sql = "DELETE FROM ventas WHERE id_ventas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_ventas);
        return $stmt->execute();
    }

    public static function getFiltered($conn, $producto = '', $cliente = '') {
        $sql = "SELECT v.*, p.nombre AS producto_nombre, c.nombre AS cliente_nombre, u.nombres AS usuario_nombre 
                FROM ventas v 
                LEFT JOIN productos p ON v.id_productos = p.id_productos 
                LEFT JOIN clientes c ON v.id_clientes = c.id_clientes 
                LEFT JOIN usuarios u ON v.num_doc = u.num_doc";
        
        $params = [];
        $types = '';
        $where = [];
        
        if ($producto) {
            $where[] = "v.id_productos = ?";
            $params[] = $producto;
            $types .= 'i';
        }
        if ($cliente) {
            $where[] = "v.id_clientes = ?";
            $params[] = $cliente;
            $types .= 'i';
        }
        
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY v.fecha_venta DESC";
        
        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $ventas = [];
        
        while ($row = $result->fetch_assoc()) {
            $venta = new Sale(
                $row['id_ventas'], 
                $row['nombre'], 
                $row['direccion'], 
                $row['cantidad'], 
                $row['fecha_venta'], 
                $row['id_clientes'], 
                $row['id_reportes'], 
                $row['id_ruta'], 
                $row['id_productos'], 
                $row['precio_unitario'], 
                $row['total'], 
                $row['estado'], 
                $row['num_doc']
            );
            
            // Agregar información relacionada
            $venta->producto_nombre = $row['producto_nombre'];
            $venta->cliente_nombre = $row['cliente_nombre'];
            $venta->usuario_nombre = $row['usuario_nombre'];
            
            $ventas[] = $venta;
        }
        
        return $ventas;
    }
}
?>
