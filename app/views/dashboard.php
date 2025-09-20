<?php
// app/views/dashboard.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RMIE</title>
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../public/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <!-- Menú lateral -->
    <nav class="bg-dark text-white p-3" style="min-width:220px;min-height:100vh;">
        <h4>Gestiones</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/CategoryController.php?accion=index">Gestión de Categorías</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/SubcategoryController.php?accion=index">Subcategorías</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/ProductController.php?accion=index">Productos</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/SaleController.php?accion=index">Ventas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/ReportController.php?accion=index">Reportes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/AlertController.php?accion=index">Alertas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/ProviderController.php?accion=index">Proveedores</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/UserController.php?accion=index">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/ClientController.php?accion=index">Clientes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/LocalController.php?accion=index">Locales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/RouteController.php?accion=index">Rutas</a></li>
        </ul>
        <hr>
        <a href="../../logout.php" class="btn btn-danger btn-sm">Cerrar sesión</a>
    </nav>
    <!-- Contenido principal -->
    <main class="flex-fill p-4">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?> (<?php echo $rol; ?>)</h2>
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Categorías</h5>
                        <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Subcategorías</h5>
                        <a href="../views/subcategorias/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Productos</h5>
                        <a href="../views/productos/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Ventas</h5>
                        <a href="../views/ventas/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Reportes</h5>
                        <a href="../views/reportes/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Alertas</h5>
                        <a href="../views/alertas/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Proveedores</h5>
                        <a href="../views/proveedores/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <a href="../views/usuarios/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <a href="../views/clientes/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Locales</h5>
                        <a href="../views/locales/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Rutas</h5>
                        <a href="../views/rutas/index.php" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Estadísticas</h5>
                        <p class="card-text">Aquí irán las estadísticas de registros.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>