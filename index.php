<?php
session_start();

// Enrutamiento para categorías
if (isset($_SESSION['user'])) {
    require_once __DIR__ . '/config/db.php';
    $action = $_GET['action'] ?? null;
    if (strpos($action, 'producto') !== false) {
        require_once __DIR__ . '/app/controllers/ProductosController.php';
        $controller = new ProductosController($pdo);
        switch ($action) {
            case 'listar_productos':
                $controller->listar();
                break;
            case 'crear_producto':
                $controller->crear();
                break;
            case 'guardar_producto':
                $controller->guardar();
                break;
            case 'editar_producto':
                $controller->editar();
                break;
            case 'actualizar_producto':
                $controller->actualizar();
                break;
            case 'eliminar_producto':
                $controller->eliminar();
                break;
            case 'eliminar_producto_confirmar':
                $controller->eliminarConfirmar();
                break;
            default:
                header('Location: app/views/dashboard.php');
                exit();
        }
        exit();
    } else if (strpos($action, 'subcategoria') !== false) {
        require_once __DIR__ . '/app/controllers/SubcategoriaController.php';
        $controller = new SubcategoriaController($pdo);
        switch ($action) {
            case 'listar_subcategorias':
                $controller->listar();
                break;
            case 'crear_subcategoria':
                $controller->crear();
                break;
            case 'guardar_subcategoria':
                $controller->guardar();
                break;
            case 'editar_subcategoria':
                $controller->editar();
                break;
            case 'actualizar_subcategoria':
                $controller->actualizar();
                break;
            case 'eliminar_subcategoria':
                $controller->eliminar();
                break;
            case 'eliminar_subcategoria_confirmar':
                $controller->eliminarConfirmar();
                break;
            default:
                header('Location: app/views/dashboard.php');
                exit();
        }
        exit();
    } else {
        require_once __DIR__ . '/app/controllers/CategoriaController.php';
        $controller = new CategoriaController($pdo);
        switch ($action) {
            case 'listar_categorias':
                $controller->listar();
                break;
            case 'crear_categoria':
                $controller->crear();
                break;
            case 'guardar_categoria':
                $controller->guardar();
                break;
            case 'editar_categoria':
                $controller->editar();
                break;
            case 'actualizar_categoria':
                $controller->actualizar();
                break;
            case 'eliminar_categoria':
                $controller->eliminar();
                break;
            case 'eliminar_categoria_confirmar':
                $controller->eliminarConfirmar();
                break;
            default:
                header('Location: app/views/dashboard.php');
                exit();
        }
        exit();
    }
}

// Login principal
if (isset($_SESSION['user'])) {
    header('Location: app/views/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login RMIE</title>
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow p-4" style="width: 350px;">
            <h3 class="mb-4 text-center">Iniciar Sesión</h3>
            <form action="app/controllers/LoginController.php" method="POST">
                <div class="mb-3">
                    <label for="user" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="user" name="user" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
</body>
</html>