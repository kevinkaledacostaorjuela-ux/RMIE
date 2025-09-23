<?php
require_once __DIR__ . '/../models/Local.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'listar_locales';

switch ($action) {
    case 'crear_local':
        require_once __DIR__ . '/../views/locales/locales_crear.php';
        break;
    case 'guardar_local':
        $nombre_local = $_POST['nombre_local'];
        $direccion = $_POST['direccion'];
        $cel_local = $_POST['cel_local'];
        $estado = $_POST['estado'];
        $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : '';
        $barrio = isset($_POST['barrio']) ? $_POST['barrio'] : '';
        Local::crear($nombre_local, $direccion, $cel_local, $estado, $localidad, $barrio);
        header('Location: /RMIE/app/controllers/LocalController.php?action=listar_locales');
        exit;
    case 'editar_local':
        $id = $_GET['id'];
        $local = Local::obtenerPorId($id);
        require_once __DIR__ . '/../views/locales/locales_editar.php';
        break;
    case 'actualizar_local':
        $id_locales = $_POST['id_locales'];
        $nombre_local = $_POST['nombre_local'];
        $direccion = $_POST['direccion'];
        $cel_local = $_POST['cel_local'];
        $estado = $_POST['estado'];
        $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : '';
        $barrio = isset($_POST['barrio']) ? $_POST['barrio'] : '';
        Local::actualizar($id_locales, $nombre_local, $direccion, $cel_local, $estado, $localidad, $barrio);
        header('Location: /RMIE/app/controllers/LocalController.php?action=listar_locales');
        exit;
    case 'eliminar_local':
        $id_locales = $_GET['id'];
        Local::eliminar($id_locales);
        header('Location: /RMIE/app/controllers/LocalController.php?action=listar_locales');
        exit;
    case 'filtrar_locales':
        $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
        $locales = array_filter(Local::getAll(), function($local) use ($nombre, $estado) {
            $nombreMatch = $nombre === '' || (isset($local['nombre_local']) && stripos($local['nombre_local'], $nombre) !== false);
            $estadoMatch = $estado === '' || (isset($local['estado']) && $local['estado'] === $estado);
            return $nombreMatch && $estadoMatch;
        });
        require_once __DIR__ . '/../views/locales/locales_listar.php';
        break;
    case 'listar_locales':
    default:
        $locales = Local::getAll();
        require_once __DIR__ . '/../views/locales/locales_listar.php';
        break;
}
