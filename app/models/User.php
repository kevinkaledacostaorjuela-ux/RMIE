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

    public function __construct($num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol, $fecha_creacion = null) {
        $this->num_doc = $num_doc;
        $this->tipo_doc = $tipo_doc;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->num_cel = $num_cel;
        $this->rol = $rol;
        $this->fecha_creacion = $fecha_creacion;
    }

    // Obtener todos los usuarios con filtros opcionales
    public static function getAll($conn, $filtro_rol = '', $filtro_tipo_doc = '', $buscar = '') {
        $sql = "SELECT * FROM usuarios WHERE 1=1";
        $params = [];
        $types = "";

        // Aplicar filtros
        if (!empty($filtro_rol)) {
            $sql .= " AND rol = ?";
            $params[] = $filtro_rol;
            $types .= "s";
        }

        if (!empty($filtro_tipo_doc)) {
            $sql .= " AND tipo_doc = ?";
            $params[] = $filtro_tipo_doc;
            $types .= "s";
        }

        if (!empty($buscar)) {
            $sql .= " AND (nombres LIKE ? OR apellidos LIKE ? OR correo LIKE ? OR num_doc LIKE ?)";
            $buscarParam = "%$buscar%";
            $params[] = $buscarParam;
            $params[] = $buscarParam;
            $params[] = $buscarParam;
            $params[] = $buscarParam;
            $types .= "ssss";
        }

        $sql .= " ORDER BY nombres, apellidos";

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
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
                $row['fecha_creacion'] ?? null
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
                $row['fecha_creacion'] ?? null
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
                $row['fecha_creacion'] ?? null
            );
        }
        return null;
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
