<?php
class User {
    public $num_doc;
    public $tipo_doc;
    public $nombres;
    public $apellidos;
    public $correo;
    public $contrasena;
    public $num_cel;
    public $rol;
    public $fecha_creacion;
    public $ultimo_acceso;

    public function __construct($num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol, $fecha_creacion = null, $ultimo_acceso = null) {
        $this->num_doc = $num_doc;
        $this->tipo_doc = $tipo_doc;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->num_cel = $num_cel;
        $this->rol = $rol;
        $this->fecha_creacion = $fecha_creacion;
        $this->ultimo_acceso = $ultimo_acceso;
    }

    // Obtener todos los usuarios con filtros opcionales
    public static function getAll($conn, $filtro_rol = '', $filtro_tipo_doc = '', $buscar = '') {
        // Si el primer parámetro es un array, usar nueva implementación
        if (is_array($filtro_rol)) {
            $filtros = $filtro_rol;
        } else {
            // Retrocompatibilidad: parámetros por separado
            $filtros = [
                'rol' => $filtro_rol,
                'tipo_doc' => $filtro_tipo_doc,
                'buscar' => $buscar
            ];
        }
        
        require_once __DIR__ . '/../utils/FilterHelper.php';
        
        // Definir reglas de validación para filtros
        $filterRules = [
            'rol' => ['type' => 'select', 'options' => ['allowed_values' => ['administrador', 'empleado', 'vendedor']]],
            'tipo_doc' => ['type' => 'select', 'options' => ['allowed_values' => ['CC', 'CE', 'TI', 'PP']]],
            'buscar' => ['type' => 'text', 'options' => ['max_length' => 100]],
            'num_cel' => ['type' => 'text', 'options' => ['max_length' => 20]],
            'fecha_desde' => ['type' => 'date'],
            'fecha_hasta' => ['type' => 'date'],
            'estado' => ['type' => 'select', 'options' => ['allowed_values' => ['activo', 'inactivo']]]
        ];
        
        // Procesar filtros
        $filtrosProcesados = FilterHelper::processFilters($filtros, $filterRules);
        
        // Mapeo de campos a columnas SQL
        $mapping = [
            'rol' => ['column' => 'rol', 'operator' => '=', 'type' => 's'],
            'tipo_doc' => ['column' => 'tipo_doc', 'operator' => '=', 'type' => 's'],
            'estado' => ['column' => 'estado', 'operator' => '=', 'type' => 's'],
            'num_cel' => ['column' => 'num_cel', 'operator' => 'LIKE', 'type' => 's'],
            'fecha_desde' => ['column' => 'DATE(fecha_creacion)', 'operator' => '>=', 'type' => 's'],
            'fecha_hasta' => ['column' => 'DATE(fecha_creacion)', 'operator' => '<=', 'type' => 's'],
            'buscar' => [
                'columns' => ['nombres', 'apellidos', 'correo', 'num_doc'],
                'operator' => 'MULTIPLE_LIKE'
            ]
        ];
        
        // Construir consulta base
        $sql = "SELECT * FROM usuarios WHERE 1=1";
        
        // Construir WHERE con filtros
        $whereData = FilterHelper::buildWhereClause($filtrosProcesados, $mapping);
        
        if (!empty($whereData['where'])) {
            $sql .= " AND " . implode(" AND ", $whereData['where']);
        }
        
        $sql .= " ORDER BY nombres, apellidos";

        $stmt = $conn->prepare($sql);
        if (!empty($whereData['params'])) {
            $stmt->bind_param($whereData['types'], ...$whereData['params']);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = new User(
                $row['num_doc'], 
                $row['tipo_doc'], 
                $row['nombres'], 
                $row['apellidos'], 
                $row['correo'], 
                $row['contrasena'], 
                $row['num_cel'], 
                $row['rol'],
                $row['fecha_creacion'] ?? null,
                $row['ultimo_acceso'] ?? null
            );
        }
        return $usuarios;
    }

    // Obtener usuario por ID
    public static function getById($conn, $num_doc) {
        $sql = "SELECT * FROM usuarios WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new User(
                $row['num_doc'], 
                $row['tipo_doc'], 
                $row['nombres'], 
                $row['apellidos'], 
                $row['correo'], 
                $row['contrasena'], 
                $row['num_cel'], 
                $row['rol'],
                $row['fecha_creacion'] ?? null,
                $row['ultimo_acceso'] ?? null
            );
        }
        return null;
    }

    // Obtener usuario por correo electrónico
    public static function getByEmail($conn, $correo) {
        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new User(
                $row['num_doc'], 
                $row['tipo_doc'], 
                $row['nombres'], 
                $row['apellidos'], 
                $row['correo'], 
                $row['contrasena'], 
                $row['num_cel'], 
                $row['rol'],
                $row['fecha_creacion'] ?? null,
                $row['ultimo_acceso'] ?? null
            );
        }
        return null;
    }

    // Obtener usuario por identificador (num_doc, correo o nombre)
    public static function getByIdentifier($conn, $identifier) {
        // Si es numérico, buscar por num_doc
        if (is_numeric($identifier)) {
            $sql = "SELECT * FROM usuarios WHERE num_doc = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $identifier);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        // Buscar por correo exacto
        $sql = "SELECT * FROM usuarios WHERE correo = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }

        // Buscar por nombre o "nombres apellidos"
        $sql = "SELECT * FROM usuarios WHERE nombres = ? OR CONCAT(nombres, ' ', apellidos) = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Crear nuevo usuario
    public static function create($conn, $data) {
        $sql = "INSERT INTO usuarios (num_doc, tipo_doc, nombres, apellidos, correo, contrasena, num_cel, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Hash de la contraseña
        $hashedPassword = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        
        $stmt->bind_param(
            "isssssss",
            $data['num_doc'],
            $data['tipo_doc'],
            $data['nombres'],
            $data['apellidos'],
            $data['correo'],
            $hashedPassword,
            $data['num_cel'],
            $data['rol']
        );
        
        return $stmt->execute();
    }

    // Actualizar usuario
    public static function update($conn, $num_doc, $data) {
        if (!empty($data['contrasena'])) {
            // Si se proporciona nueva contraseña
            $sql = "UPDATE usuarios SET tipo_doc = ?, nombres = ?, apellidos = ?, correo = ?, contrasena = ?, num_cel = ?, rol = ? WHERE num_doc = ?";
            $stmt = $conn->prepare($sql);
            
            $hashedPassword = password_hash($data['contrasena'], PASSWORD_DEFAULT);
            
            $stmt->bind_param(
                "sssssssi",
                $data['tipo_doc'],
                $data['nombres'],
                $data['apellidos'],
                $data['correo'],
                $hashedPassword,
                $data['num_cel'],
                $data['rol'],
                $num_doc
            );
        } else {
            // Sin cambiar contraseña
            $sql = "UPDATE usuarios SET tipo_doc = ?, nombres = ?, apellidos = ?, correo = ?, num_cel = ?, rol = ? WHERE num_doc = ?";
            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param(
                "ssssssi",
                $data['tipo_doc'],
                $data['nombres'],
                $data['apellidos'],
                $data['correo'],
                $data['num_cel'],
                $data['rol'],
                $num_doc
            );
        }
        
        return $stmt->execute();
    }

    // Eliminar usuario
    public static function delete($conn, $num_doc) {
        $sql = "DELETE FROM usuarios WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        return $stmt->execute();
    }

    // Actualizar último acceso
    public static function updateLastAccess($conn, $num_doc) {
        $sql = "UPDATE usuarios SET ultimo_acceso = CURRENT_TIMESTAMP WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        return $stmt->execute();
    }

    // Verificar si el usuario tiene ventas asociadas
    public static function hasAssociatedSales($conn, $num_doc) {
        $sql = "SELECT COUNT(*) as total FROM ventas WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Verificar si el usuario tiene productos asociados
    public static function hasAssociatedProducts($conn, $num_doc) {
        $sql = "SELECT COUNT(*) as total FROM productos WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Verificar todas las relaciones del usuario
    public static function getAssociatedRecords($conn, $num_doc) {
        $relations = [];
        
        // Verificar productos
        $sql = "SELECT COUNT(*) as total FROM productos WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        $stmt->execute();
        $result = $stmt->get_result();
        $relations['productos'] = $result->fetch_assoc()['total'];
        
        // Verificar ventas
        $sql = "SELECT COUNT(*) as total FROM ventas WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        $stmt->execute();
        $result = $stmt->get_result();
        $relations['ventas'] = $result->fetch_assoc()['total'];
        
        return $relations;
    }

    // Obtener estadísticas de usuarios
    public static function getStats($conn) {
        $stats = [];
        
        // Total de usuarios
        $sql = "SELECT COUNT(*) as total FROM usuarios";
        $result = $conn->query($sql);
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Usuarios por rol
        $sql = "SELECT rol, COUNT(*) as total FROM usuarios GROUP BY rol";
        $result = $conn->query($sql);
        $stats['por_rol'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['por_rol'][$row['rol']] = $row['total'];
        }
        
        // Usuarios por tipo de documento
        $sql = "SELECT tipo_doc, COUNT(*) as total FROM usuarios WHERE tipo_doc IS NOT NULL AND tipo_doc != '' GROUP BY tipo_doc ORDER BY total DESC";
        $result = $conn->query($sql);
        $stats['por_tipo_doc'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['por_tipo_doc'][$row['tipo_doc']] = $row['total'];
        }
        
        // Usuarios con más ventas
        $sql = "SELECT u.num_doc, u.nombres, u.apellidos, COUNT(v.id_ventas) as total_ventas 
                FROM usuarios u 
                LEFT JOIN ventas v ON u.num_doc = v.num_doc 
                GROUP BY u.num_doc, u.nombres, u.apellidos 
                ORDER BY total_ventas DESC 
                LIMIT 5";
        $result = $conn->query($sql);
        $stats['top_vendedores'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['top_vendedores'][] = $row;
        }
        
        return $stats;
    }

    // Obtener tipos de documento únicos
    public static function getTiposDocumento($conn) {
        $sql = "SELECT DISTINCT tipo_doc FROM usuarios WHERE tipo_doc IS NOT NULL AND tipo_doc != '' ORDER BY tipo_doc";
        $result = $conn->query($sql);
        
        $tipos = [];
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row['tipo_doc'];
        }
        return $tipos;
    }

    // Validar credenciales para login
    public static function validateCredentials($conn, $correo, $contrasena) {
        $usuario = self::getByEmail($conn, $correo);
        
        if ($usuario && password_verify($contrasena, $usuario->contrasena)) {
            return $usuario;
        }
        
        return null;
    }

    // Obtener nombre completo
    public function getNombreCompleto() {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    // Verificar si es administrador
    public function isAdmin() {
        return $this->rol === 'admin';
    }

    // Verificar si es coordinador
    public function isCoordinador() {
        return $this->rol === 'coordinador';
    }
}
?>
