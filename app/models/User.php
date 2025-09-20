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

    public function __construct($num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol) {
        $this->num_doc = $num_doc;
        $this->tipo_doc = $tipo_doc;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->num_cel = $num_cel;
        $this->rol = $rol;
    }

    public static function getAll($conn) {
        $sql = "SELECT * FROM usuarios";
        $result = $conn->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = new User($row['num_doc'], $row['tipo_doc'], $row['nombres'], $row['apellidos'], $row['correo'], $row['contrasena'], $row['num_cel'], $row['rol']);
        }
        return $usuarios;
    }

    public static function getById($conn, $num_doc) {
        $sql = "SELECT * FROM usuarios WHERE num_doc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $num_doc);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User($row['num_doc'], $row['tipo_doc'], $row['nombres'], $row['apellidos'], $row['correo'], $row['contrasena'], $row['num_cel'], $row['rol']);
        }
        return null;
    }
}
?>
