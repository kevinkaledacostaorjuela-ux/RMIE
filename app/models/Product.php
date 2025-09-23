<?php
class Product {
    public static function getAll($conn) {
        $sql = "SELECT * FROM productos";
        $result = $conn->query($sql);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = new Product($row['id_productos'], $row['nombre'], $row['descripcion'], $row['fecha_entrada'], $row['fecha_fabricacion'], $row['fecha_caducidad'], $row['stock'], $row['precio_unitario'], $row['precio_por_mayor'], $row['valor_unitario'], $row['marca'], $row['id_subcategoria'], $row['id_categoria'], $row['id_proveedores'] ?? null, $row['num_doc'] ?? null);
        }
        return $productos;
    }
    public $id_productos;
    public $nombre;
    public $descripcion;
    public $fecha_entrada;
    public $fecha_fabricacion;
    public $fecha_caducidad;
    public $stock;
    public $precio_unitario;
    public $precio_por_mayor;
    public $valor_unitario;
    public $marca;
    public $id_subcategoria;
    public $id_categoria;
    public $id_proveedores;
    public $num_doc;

    public function __construct($id_productos, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores = null, $num_doc = null) {
        $this->id_productos = $id_productos;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fecha_entrada = $fecha_entrada;
        $this->fecha_fabricacion = $fecha_fabricacion;
        $this->fecha_caducidad = $fecha_caducidad;
        $this->stock = $stock;
        $this->precio_unitario = $precio_unitario;
        $this->precio_por_mayor = $precio_por_mayor;
        $this->valor_unitario = $valor_unitario;
        $this->marca = $marca;
        $this->id_subcategoria = $id_subcategoria;
        $this->id_categoria = $id_categoria;
        $this->id_proveedores = $id_proveedores;
        $this->num_doc = $num_doc;
    }

    public static function getFiltered($conn, $categoria = '', $subcategoria = '', $proveedor = '', $usuario = '') {
        $sql = "SELECT p.*, s.nombre AS subcategoria_nombre, c.nombre AS categoria_nombre, pr.nombre_distribuidor AS proveedor_nombre, u.nombres AS usuario_nombre FROM productos p JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria JOIN categorias c ON p.id_categoria = c.id_categoria JOIN proveedores pr ON p.id_proveedores = pr.id_proveedores JOIN usuarios u ON p.num_doc = u.num_doc";
        $params = [];
        $types = '';
        $where = [];
        if ($categoria) {
            $where[] = "p.id_categoria = ?";
            $params[] = $categoria;
            $types .= 'i';
        }
        if ($subcategoria) {
            $where[] = "p.id_subcategoria = ?";
            $params[] = $subcategoria;
            $types .= 'i';
        }
        if ($proveedor) {
            $where[] = "p.id_proveedores = ?";
            $params[] = $proveedor;
            $types .= 'i';
        }
        if ($usuario) {
            $where[] = "p.num_doc = ?";
            $params[] = $usuario;
            $types .= 'i';
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = [
                'obj' => new Product($row['id_productos'], $row['nombre'], $row['descripcion'], $row['fecha_entrada'], $row['fecha_fabricacion'], $row['fecha_caducidad'], $row['stock'], $row['precio_unitario'], $row['precio_por_mayor'], $row['valor_unitario'], $row['marca'], $row['id_subcategoria'], $row['id_categoria']),
                'subcategoria_nombre' => $row['subcategoria_nombre'],
                'categoria_nombre' => $row['categoria_nombre'],
                'proveedor_nombre' => $row['proveedor_nombre'],
                'usuario_nombre' => $row['usuario_nombre']
            ];
        }
        return $productos;
    }

    public static function create($conn, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc) {
        $sql = "INSERT INTO productos (nombre, descripcion, fecha_entrada, fecha_fabricacion, fecha_caducidad, stock, precio_unitario, precio_por_mayor, valor_unitario, marca, id_subcategoria, id_categoria, id_proveedores, num_doc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssiiii", $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc);
        return $stmt->execute();
    }

    public static function getById($conn, $id_productos) {
        $sql = "SELECT * FROM productos WHERE id_productos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_productos);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Product($row['id_productos'], $row['nombre'], $row['descripcion'], $row['fecha_entrada'], $row['fecha_fabricacion'], $row['fecha_caducidad'], $row['stock'], $row['precio_unitario'], $row['precio_por_mayor'], $row['valor_unitario'], $row['marca'], $row['id_subcategoria'], $row['id_categoria'], $row['id_proveedores'] ?? null, $row['num_doc'] ?? null);
        }
        return null;
    }

    public static function update($conn, $id_productos, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc) {
        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, fecha_entrada = ?, fecha_fabricacion = ?, fecha_caducidad = ?, stock = ?, precio_unitario = ?, precio_por_mayor = ?, valor_unitario = ?, marca = ?, id_subcategoria = ?, id_categoria = ?, id_proveedores = ?, num_doc = ? WHERE id_productos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssiiiii", $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc, $id_productos);
        return $stmt->execute();
    }

    public static function delete($conn, $id_productos) {
        $sql = "DELETE FROM productos WHERE id_productos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_productos);
        return $stmt->execute();
    }
}
?>
