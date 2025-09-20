<?php
class Local {
    public $id_locales;
    public $direccion;
    public $nombre_local;
    public $cel_local;
    public $estado;
    public $localidad;
    public $barrio;

    public function __construct($id_locales, $direccion, $nombre_local, $cel_local, $estado, $localidad, $barrio) {
        $this->id_locales = $id_locales;
        $this->direccion = $direccion;
        $this->nombre_local = $nombre_local;
        $this->cel_local = $cel_local;
        $this->estado = $estado;
        $this->localidad = $localidad;
        $this->barrio = $barrio;
    }

    public static function getAll($conn) {
        $sql = "SELECT * FROM locales";
        $result = $conn->query($sql);
        $locales = [];
        while ($row = $result->fetch_assoc()) {
            $locales[] = new Local($row['id_locales'], $row['direccion'], $row['nombre_local'], $row['cel_local'], $row['estado'], $row['localidad'], $row['barrio']);
        }
        return $locales;
    }
}
?>
