<?php
// app/controllers/LoginController.php
require_once '../../config/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE correo = ?');
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    if ($usuario && password_verify($password, $usuario['contrasena'])) {
        $_SESSION['user'] = $usuario['correo'];
        $_SESSION['rol'] = $usuario['rol'];
        header('Location: ../views/dashboard.php');
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