<?php
// app/controllers/LoginController.php
require_once '../../config/db.php';
require_once __DIR__ . '/../models/User.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($user) || empty($password)) {
        echo '<script>alert("Usuario o contraseña incorrectos");window.location="../../index.php";</script>';
        exit();
    }

    // Buscar usuario por identificador (num_doc, correo o nombre)
    $usuario = User::getByIdentifier($conn, $user);

    if ($usuario && password_verify($password, $usuario['contrasena'])) {
        // Guardar num_doc en sesión para compatibilidad con el resto del sistema
        $_SESSION['user'] = $usuario['num_doc'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['nombres'] = $usuario['nombres'];
        $_SESSION['apellidos'] = $usuario['apellidos'];

        // Redirigir según el rol
        switch($usuario['rol']) {
            case 'auxiliar':
                header('Location: /RMIE/app/controllers/AuxiliarController.php?accion=dashboard');
                break;
            case 'admin':
            case 'coordinador':
            default:
                header('Location: ../views/dashboard.php');
                break;
        }
        exit();
    } else {
        echo '<script>alert("Usuario o contraseña incorrectos");window.location="../../index.php";</script>';
        exit();
    }
} else {
    // Si se accede por GET, mostrar advertencia y redirigir
    echo '<script>alert("Acceso no permitido. Por favor, ingresa tus credenciales desde el formulario de inicio de sesión.");window.location="../../index.php";</script>';
    exit();
}
?>