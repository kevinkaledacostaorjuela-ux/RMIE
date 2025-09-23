<?php
require_once __DIR__ . '/../models/User.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'listar';

switch ($action) {
    case 'crear':
        require_once __DIR__ . '/../views/users/create.php';
        break;
    case 'guardar':
        $num_doc = $_POST['num_doc'];
        $tipo_doc = $_POST['tipo_doc'];
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];
        $num_cel = $_POST['num_cel'];
        $rol = $_POST['rol'];
        User::create($num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol);
        header('Location: /RMIE/app/controllers/UserController.php?action=listar');
        exit;
    case 'editar':
        $num_doc = $_GET['num_doc'];
        $usuario = User::getById($num_doc);
        require_once __DIR__ . '/../views/users/edit.php';
        break;
    case 'actualizar':
        $num_doc = $_POST['num_doc'];
        $tipo_doc = $_POST['tipo_doc'];
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];
        $num_cel = $_POST['num_cel'];
        $rol = $_POST['rol'];
        User::update($num_doc, $tipo_doc, $nombres, $apellidos, $correo, $contrasena, $num_cel, $rol);
        header('Location: /RMIE/app/controllers/UserController.php?action=listar');
        exit;
    case 'eliminar':
        $num_doc = $_GET['num_doc'];
        User::delete($num_doc);
        header('Location: /RMIE/app/controllers/UserController.php?action=listar');
        exit;
    case 'filtrar':
        $nombres = isset($_GET['nombres']) ? $_GET['nombres'] : '';
        $rol = isset($_GET['rol']) ? $_GET['rol'] : '';
        $usuarios = array_filter(User::getAll(), function($user) use ($nombres, $rol) {
            $nombreMatch = $nombres === '' || (isset($user['nombres']) && stripos($user['nombres'], $nombres) !== false);
            $rolMatch = $rol === '' || (isset($user['rol']) && $user['rol'] === $rol);
            return $nombreMatch && $rolMatch;
        });
        require_once __DIR__ . '/../views/users/list.php';
        break;
    case 'listar':
    default:
        $usuarios = User::getAll();
        require_once __DIR__ . '/../views/users/list.php';
        break;
}
