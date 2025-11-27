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
    public $productos_asignados = [];

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

    // Método mejorado con filtros avanzados
    public static function getFiltered($conn, $producto = '', $cliente = '') {
        // Si el primer parámetro es un array, usar nueva implementación
        if (is_array($producto)) {
            $filtros = $producto;
        } else {
            // Retrocompatibilidad: parámetros por separado
            $filtros = [
                'producto' => $producto,
                'cliente' => $cliente
            ];
        }
        
        require_once __DIR__ . '/../utils/FilterHelper.php';
        
        // Definir reglas de validación para filtros
        $filterRules = [
            'producto' => ['type' => 'int', 'options' => ['min' => 1]],
            'cliente' => ['type' => 'int', 'options' => ['min' => 1]],
            'usuario' => ['type' => 'text'],
            'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['pendiente', 'completada', 'cancelada', 'en_proceso']]],
            'precio_min' => ['type' => 'float', 'options' => ['min' => 0]],
            'precio_max' => ['type' => 'float', 'options' => ['min' => 0]],
            'cantidad_min' => ['type' => 'int', 'options' => ['min' => 1]],
            'cantidad_max' => ['type' => 'int', 'options' => ['min' => 1]],
            'fecha_desde' => ['type' => 'date'],
            'fecha_hasta' => ['type' => 'date'],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
        ];
        
        // Procesar filtros
        $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
        
        // Mapeo de campos a columnas SQL
        $mapping = [
            'producto' => ['column' => 'v.id_productos', 'operator' => '=', 'type' => 'i'],
            'cliente' => ['column' => 'v.id_clientes', 'operator' => '=', 'type' => 'i'],
            'usuario' => ['column' => 'v.num_doc', 'operator' => '=', 'type' => 's'],
            'estado' => ['column' => 'v.estado', 'operator' => '=', 'type' => 's'],
            'precio_min' => ['column' => 'v.total', 'operator' => '>=', 'type' => 'd'],
            'precio_max' => ['column' => 'v.total', 'operator' => '<=', 'type' => 'd'],
            'cantidad_min' => ['column' => 'v.cantidad', 'operator' => '>=', 'type' => 'i'],
            'cantidad_max' => ['column' => 'v.cantidad', 'operator' => '<=', 'type' => 'i'],
            'fecha_desde' => ['column' => 'DATE(v.fecha_venta)', 'operator' => '>=', 'type' => 's'],
            'fecha_hasta' => ['column' => 'DATE(v.fecha_venta)', 'operator' => '<=', 'type' => 's'],
            'buscar' => [
                'columns' => ['v.nombre', 'p.nombre', 'c.nombre'],
                'operator' => 'MULTIPLE_LIKE'
            ]
        ];
        
        // Construir consulta base
        $sql = "SELECT v.*, 
                       p.nombre AS producto_nombre, 
                       c.nombre AS cliente_nombre, 
                       u.nombres AS usuario_nombre 
                FROM ventas v 
                LEFT JOIN productos p ON v.id_productos = p.id_productos 
                LEFT JOIN clientes c ON v.id_clientes = c.id_clientes 
                LEFT JOIN usuarios u ON v.num_doc = u.num_doc 
                WHERE 1=1";
        
        // Construir WHERE con filtros
        $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
        
        if (!empty($whereData['where'])) {
            $sql .= " AND " . implode(" AND ", $whereData['where']);
        }
        
        $sql .= " ORDER BY v.fecha_venta DESC";
        
        $stmt = $conn->prepare($sql);
        if (!empty($whereData['params'])) {
            $stmt->bind_param($whereData['types'], ...$whereData['params']);
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

    // Obtener productos asignados a una venta
    public static function getProductos($conn, $id_ventas) {
        $sql = "SELECT vp.*, p.nombre, p.descripcion, p.precio_unitario as precio_producto, p.stock 
                FROM ventas_productos vp
                INNER JOIN productos p ON vp.id_productos = p.id_productos
                WHERE vp.id_ventas = ?
                ORDER BY p.nombre";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_ventas);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = (object) $row;
        }
        
        return $productos;
    }

    // Actualizar productos de una venta
    public static function updateProductos($conn, $id_ventas, $productos_data) {
        // Eliminar productos actuales
        $sql = "DELETE FROM ventas_productos WHERE id_ventas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_ventas);
        $stmt->execute();
        
        // Insertar nuevos productos
        if (!empty($productos_data)) {
            $sql = "INSERT INTO ventas_productos (id_ventas, id_productos, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            foreach ($productos_data as $producto) {
                $stmt->bind_param("iiidd", 
                    $id_ventas, 
                    $producto['id_productos'], 
                    $producto['cantidad'], 
                    $producto['precio_unitario'], 
                    $producto['subtotal']
                );
                $stmt->execute();
            }
        }
        
        return true;
    }
}
?>
