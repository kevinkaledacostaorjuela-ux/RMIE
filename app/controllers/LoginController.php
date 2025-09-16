<?php
// app/controllers/LoginController.php
require_once '../../config/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE correo = :user');
    $stmt->execute(['user' => $user]);
    $usuario = $stmt->fetch();
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
    header('Location: ../../index.php');
    exit();
}
?>