<?php
class Product {
    public static function getAll($conn) {
        $sql = "SELECT p.*, 
                       COALESCE(s.nombre, '') AS subcategoria_nombre, 
                       c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria";
        $result = $conn->query($sql);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = new Product(
                $row['id_productos'], $row['nombre'], $row['descripcion'], $row['fecha_entrada'], 
                $row['fecha_fabricacion'], $row['fecha_caducidad'], $row['stock'], $row['precio_unitario'], 
                $row['precio_por_mayor'], $row['valor_unitario'], $row['marca'], $row['id_subcategoria'], 
                $row['id_categoria'], $row['id_proveedores'] ?? null, $row['num_doc'] ?? null
            );
            $productos[count($productos) - 1]->categoria_nombre = $row['categoria_nombre'];
            $productos[count($productos) - 1]->subcategoria_nombre = $row['subcategoria_nombre'];
        }
        return $productos;
    }
    public $id_productos;
    public $nombre;
    public $nombre_producto;
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
        // Compatibilidad: algunos lugares usan nombre_producto
        $this->nombre_producto = $nombre;
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

    // Método mejorado con filtros avanzados
    public static function getFiltered($conn, $categoria = '', $subcategoria = '', $proveedor = '', $usuario = '') {
        // Si el primer parámetro es un array, usar nueva implementación
        if (is_array($categoria)) {
            $filtros = $categoria;
        } else {
            // Retrocompatibilidad: parámetros por separado
            $filtros = [
                'categoria' => $categoria,
                'subcategoria' => $subcategoria,
                'proveedor' => $proveedor,
                'usuario' => $usuario
            ];
        }
        
        require_once __DIR__ . '/../utils/FilterHelper.php';
        
        // Definir reglas de validación para filtros
        $filterRules = [
            'categoria' => ['type' => 'int', 'options' => ['min' => 1]],
            'subcategoria' => ['type' => 'int', 'options' => ['min' => 1]],
            'proveedor' => ['type' => 'int', 'options' => ['min' => 1]],
            'usuario' => ['type' => 'text'],
            'nombre' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'marca' => ['type' => 'text', 'options' => ['max_length' => 50]],
            'precio_min' => ['type' => 'float', 'options' => ['min' => 0]],
            'precio_max' => ['type' => 'float', 'options' => ['min' => 0]],
            'stock_min' => ['type' => 'int', 'options' => ['min' => 0]],
            'stock_max' => ['type' => 'int', 'options' => ['min' => 0]],
            'fecha_entrada_desde' => ['type' => 'date'],
            'fecha_entrada_hasta' => ['type' => 'date'],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]]
        ];
        
        // Procesar filtros
        $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
        
        // Mapeo de campos a columnas SQL
        $mapping = [
            'categoria' => ['column' => 'p.id_categoria', 'operator' => '=', 'type' => 'i'],
            'subcategoria' => ['column' => 'p.id_subcategoria', 'operator' => '=', 'type' => 'i'],
            'proveedor' => ['column' => 'p.id_proveedores', 'operator' => '=', 'type' => 'i'],
            'usuario' => ['column' => 'p.num_doc', 'operator' => '=', 'type' => 's'],
            'nombre' => ['column' => 'p.nombre', 'operator' => 'LIKE', 'type' => 's'],
            'marca' => ['column' => 'p.marca', 'operator' => 'LIKE', 'type' => 's'],
            'precio_min' => ['column' => 'p.precio_unitario', 'operator' => '>=', 'type' => 'd'],
            'precio_max' => ['column' => 'p.precio_unitario', 'operator' => '<=', 'type' => 'd'],
            'stock_min' => ['column' => 'p.stock', 'operator' => '>=', 'type' => 'i'],
            'stock_max' => ['column' => 'p.stock', 'operator' => '<=', 'type' => 'i'],
            'fecha_entrada_desde' => ['column' => 'DATE(p.fecha_entrada)', 'operator' => '>=', 'type' => 's'],
            'fecha_entrada_hasta' => ['column' => 'DATE(p.fecha_entrada)', 'operator' => '<=', 'type' => 's'],
            'buscar' => [
                'columns' => ['p.nombre', 'p.descripcion', 'p.marca'],
                'operator' => 'MULTIPLE_LIKE'
            ]
        ];
        
        // Construir consulta base
        $sql = "SELECT p.*, 
                   COALESCE(s.nombre, '') AS subcategoria_nombre, 
                   c.nombre AS categoria_nombre, 
                   pr.nombre_distribuidor AS proveedor_nombre, 
                   u.nombres AS usuario_nombre 
            FROM productos p 
            LEFT JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
            LEFT JOIN proveedores pr ON p.id_proveedores = pr.id_proveedores 
            LEFT JOIN usuarios u ON p.num_doc = u.num_doc 
            WHERE 1=1";
        
        // Construir WHERE con filtros
        $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
        
        if (!empty($whereData['where'])) {
            $sql .= " AND " . implode(" AND ", $whereData['where']);
        }
        
        $sql .= " ORDER BY p.nombre, p.fecha_entrada DESC";
        
        $stmt = $conn->prepare($sql);
        if (!empty($whereData['params'])) {
            $stmt->bind_param($whereData['types'], ...$whereData['params']);
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
        // Si no se proporciona proveedor, usar proveedor genérico
        if ($id_proveedores === null || $id_proveedores === '') {
            $id_proveedores = self::getOrCreateGenericProvider($conn);
        }
        
        // Convertir subcategoría vacía a NULL y validar que existe
        if ($id_subcategoria === '' || $id_subcategoria === '0') {
            $id_subcategoria = null;
        } elseif ($id_subcategoria !== null) {
            // Verificar que la subcategoría existe
            $check_subcat = $conn->prepare("SELECT id_subcategoria FROM subcategorias WHERE id_subcategoria = ?");
            $check_subcat->bind_param("i", $id_subcategoria);
            $check_subcat->execute();
            $result = $check_subcat->get_result();
            if ($result->num_rows == 0) {
                $id_subcategoria = null; // Si no existe, usar NULL
            }
            $check_subcat->close();
        }
        
        $sql = "INSERT INTO productos (nombre, descripcion, fecha_entrada, fecha_fabricacion, fecha_caducidad, stock, precio_unitario, precio_por_mayor, valor_unitario, marca, id_subcategoria, id_categoria, id_proveedores, num_doc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssdddssiiis", $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc);
        return $stmt->execute();
    }

    // Asignar un producto a un proveedor (actualiza solo id_proveedores)
    public static function assignToProvider($conn, $id_productos, $id_proveedores) {
        try {
            $sql = "UPDATE productos SET id_proveedores = ? WHERE id_productos = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_proveedores, $id_productos);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log('Product::assignToProvider error: ' . $e->getMessage());
            return false;
        }
    }

    private static function getOrCreateGenericProvider($conn) {
        // Buscar si existe un proveedor genérico
        $sql = "SELECT id_proveedores FROM proveedores WHERE nombre_distribuidor = 'Sin Proveedor' OR nombre_distribuidor = 'Genérico' LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row['id_proveedores'];
        }
        
        // Si no existe, crear uno genérico
        $sql = "INSERT INTO proveedores (nombre_distribuidor, correo, cel_proveedor, estado) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $nombre = "Sin Proveedor";
        $correo = "sin.proveedor@sistema.local";
        $telefono = "0000000000";
        $estado = "activo";
        
        $stmt->bind_param("ssss", $nombre, $correo, $telefono, $estado);
        
        if ($stmt->execute()) {
            return $conn->insert_id;
        }
        
        // Si falla, intentar encontrar cualquier proveedor existente
        $sql = "SELECT id_proveedores FROM proveedores LIMIT 1";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['id_proveedores'];
        }
        
        // Como último recurso, retornar 1
        return 1;
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

    // Obtener producto por ID como array (para páginas de eliminación)
    public static function getByIdArray($conn, $id_productos) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, s.nombre AS subcategoria_nombre, pr.nombre_distribuidor AS proveedor_nombre, u.nombres AS usuario_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                LEFT JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria  
                LEFT JOIN proveedores pr ON p.id_proveedores = pr.id_proveedores
                LEFT JOIN usuarios u ON p.num_doc = u.num_doc
                WHERE p.id_productos = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_productos);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public static function update($conn, $id_productos, $nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores, $num_doc) {
        // Debug prints removed to avoid showing internal logs in the UI
        
        // Si no se proporciona proveedor, usar proveedor genérico
        if ($id_proveedores === null || $id_proveedores === '') {
            $id_proveedores = self::getOrCreateGenericProvider($conn);
        }
        
        // Convertir subcategoría vacía a NULL y validar que existe
        if ($id_subcategoria === '' || $id_subcategoria === '0') {
            $id_subcategoria = null;
        } elseif ($id_subcategoria !== null) {
            // Verificar que la subcategoría existe
            $check_subcat = $conn->prepare("SELECT id_subcategoria FROM subcategorias WHERE id_subcategoria = ?");
            $check_subcat->bind_param("i", $id_subcategoria);
            $check_subcat->execute();
            $result = $check_subcat->get_result();
            if ($result->num_rows == 0) {
                $id_subcategoria = null; // Si no existe, usar NULL
            }
            $check_subcat->close();
        }
        
        // Validar que la categoría existe (requerida)
        if ($id_categoria === '' || $id_categoria === '0' || $id_categoria === null) {
            error_log('Product::update error: La categoría es obligatoria');
            return false;
        } else {
            // Verificar que la categoría existe
            $check_cat = $conn->prepare("SELECT id_categoria FROM categorias WHERE id_categoria = ?");
            $check_cat->bind_param("i", $id_categoria);
            $check_cat->execute();
            $result = $check_cat->get_result();
                if ($result->num_rows == 0) {
                    error_log('Product::update error: La categoría seleccionada no existe');
                    return false;
                }
            $check_cat->close();
        }
        // Final category/subcategory values validated
        
        // Validar num_doc: si está vacío o no existe en usuarios, usar NULL
        if ($num_doc === '' || $num_doc === null) {
            $num_doc = null;
            error_log('Product::update notice: num_doc vacío — se usará NULL');
        } else {
            // Comprobar existencia en la tabla usuarios
            try {
                $check_user_sql = "SELECT num_doc FROM usuarios WHERE num_doc = ? LIMIT 1";
                $check_user = $conn->prepare($check_user_sql);
                    if ($check_user) {
                    // elegir tipo de bind según si es numérico
                    if (is_numeric($num_doc)) {
                        $check_user->bind_param("i", $num_doc);
                    } else {
                        $check_user->bind_param("s", $num_doc);
                    }
                    $check_user->execute();
                    $res = $check_user->get_result();
                    if ($res->num_rows == 0) {
                        // Usuario no existe -> asignar NULL para evitar violación de FK
                        error_log('Product::update notice: num_doc ' . $num_doc . ' no existe en usuarios — se usará NULL');
                        $num_doc = null;
                    }
                    $check_user->close();
                } else {
                    // Si no se pudo preparar la consulta, dejar num_doc como NULL por seguridad
                    $num_doc = null;
                }
            } catch (mysqli_sql_exception $e) {
                error_log('Product::update check user error: ' . $e->getMessage());
                $num_doc = null;
            }
        }
        
        // Construir UPDATE dinámico: si num_doc es NULL, asignar NULL directamente en la consulta
        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, fecha_entrada = ?, fecha_fabricacion = ?, fecha_caducidad = ?, stock = ?, precio_unitario = ?, precio_por_mayor = ?, valor_unitario = ?, marca = ?, id_subcategoria = ?, id_categoria = ?, id_proveedores = ?";

        $types = "sssssdddssiii"; // tipos por defecto (sin num_doc ni id_productos)
        $params = [$nombre, $descripcion, $fecha_entrada, $fecha_fabricacion, $fecha_caducidad, $stock, $precio_unitario, $precio_por_mayor, $valor_unitario, $marca, $id_subcategoria, $id_categoria, $id_proveedores];

        if ($num_doc !== null) {
            $sql .= ", num_doc = ?";
            $types .= "s";
            $params[] = $num_doc;
        } else {
            // No cambiar el valor de num_doc si no se proporcionó uno válido
            // Así evitamos potenciales problemas con la constraint o columnas NOT NULL
            // Para ello, removemos la asignación a num_doc del SET construído.
            // Como el SQL ya fue creado sin num_doc, simplemente no hacemos nada aquí.
        }

        $sql .= " WHERE id_productos = ?";
        $types .= "i";
        $params[] = $id_productos;

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log('Product::update error: No se pudo preparar la consulta: ' . $conn->error);
            return false;
        }

        // bind_param requiere variables, no valores directos, por eso usamos referencias
        $bind_names = [];
        $bind_names[] = $types;
        foreach ($params as $key => $value) {
            // Necesitamos pasar por referencia
            $bind_names[] = &$params[$key];
        }

        call_user_func_array([$stmt, 'bind_param'], $bind_names);

        $result = $stmt->execute();
        
        if (!$result) {
            error_log('Product::update error: No se pudo ejecutar la actualización: ' . $stmt->error);
        } else {
            error_log('Product::update success: Producto actualizado. Filas afectadas: ' . $stmt->affected_rows);
        }
        
        return $result;
    }

    // Verificar si el producto tiene dependencias
    public static function checkDependencies($conn, $id_productos) {
        $dependencies = [];
        
        // Verificar ventas asociadas al producto
        $sqlVentas = "SELECT COUNT(*) as count FROM ventas WHERE id_productos = ?";
        $stmt = $conn->prepare($sqlVentas);
        $stmt->bind_param("i", $id_productos);
        $stmt->execute();
        $result = $stmt->get_result();
        $ventas = $result->fetch_assoc()['count'];
        
        if ($ventas > 0) {
            $dependencies['ventas'] = $ventas;
        }
        
        return $dependencies;
    }

    // Eliminar con manejo de dependencias
    public static function deleteWithDependencies($conn, $id_productos, $force = false) {
        // Verificar dependencias
        $dependencies = self::checkDependencies($conn, $id_productos);
        
        if (!empty($dependencies) && !$force) {
            return ['error' => 'dependencies', 'data' => $dependencies];
        }
        
        try {
            // Si hay ventas y se intenta forzar, no permitir
            if (isset($dependencies['ventas']) && $force) {
                return ['error' => 'has_sales', 'data' => $dependencies];
            }
            
            // Si no hay dependencias o no hay ventas, proceder con eliminación
            $sql = "DELETE FROM productos WHERE id_productos = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_productos);
            $result = $stmt->execute();
            
            if ($result) {
                return ['success' => true];
            } else {
                return ['error' => 'delete_failed'];
            }
            
        } catch (mysqli_sql_exception $e) {
            return ['error' => 'exception', 'message' => $e->getMessage()];
        }
    }

    // Método de eliminación simple (mantenido para compatibilidad)
    public static function delete($conn, $id_productos) {
        try {
            $sql = "DELETE FROM productos WHERE id_productos = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_productos);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            return ['error' => 'exception', 'message' => $e->getMessage()];
        }
    }

    // Método para remover proveedor de todos los productos
    public static function removeProviderFromProducts($conn, $providerId) {
        try {
            $sql = "UPDATE productos SET id_proveedores = NULL WHERE id_proveedores = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $providerId);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Error removing provider from products: " . $e->getMessage());
            return false;
        }
    }

    // Método para asignar proveedor a un producto específico
    public static function assignProvider($conn, $productId, $providerId) {
        try {
            $sql = "UPDATE productos SET id_proveedores = ? WHERE id_productos = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $providerId, $productId);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Error assigning provider to product: " . $e->getMessage());
            return false;
        }
    }
}
?>
