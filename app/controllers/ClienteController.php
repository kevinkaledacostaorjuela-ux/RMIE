<?php
// app/controllers/ClienteController.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../../config/db.php';

$clienteModel = new Cliente($pdo);
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'crear':
        $locales = $clienteModel->obtenerLocales();
        include __DIR__ . '/../views/clientes/clientes_crear.php';
        break;
    case 'guardar':
        $clienteModel->crear(
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['cel_cliente'],
            $_POST['correo'],
            $_POST['estado'],
            $_POST['id_locales']
        );
        header('Location: ClienteController.php?action=listar');
        exit();
    case 'editar':
        $id = $_GET['id'];
        $cliente = $clienteModel->obtenerPorId($id);
        $locales = $clienteModel->obtenerLocales();
        include __DIR__ . '/../views/clientes/clientes_editar.php';
        break;
    case 'actualizar':
        $clienteModel->actualizar(
            $_GET['id'],
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['cel_cliente'],
            $_POST['correo'],
            $_POST['estado'],
            $_POST['id_locales']
        );
        header('Location: ClienteController.php?action=listar');
        exit();
    case 'eliminar':
        $clienteModel->eliminar($_GET['id']);
        header('Location: ClienteController.php?action=listar');
        exit();
    case 'listar':
    default:
        $clientes = $clienteModel->obtenerTodos();
        include __DIR__ . '/../views/clientes/clientes_listar.php';
        break;
}
