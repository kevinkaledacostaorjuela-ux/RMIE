<?php
// app/views/dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <!-- Menú lateral -->
    <nav class="bg-dark text-white p-3" style="min-width:220px;min-height:100vh;">
        <h4><i class="fas fa-tachometer-alt"></i> Panel de Control</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/CategoryController.php?accion=index"><i class="fas fa-tags"></i> Categorías</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/SubcategoryController.php?accion=index"><i class="fas fa-layer-group"></i> Subcategorías</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../controllers/ProductController.php?accion=index"><i class="fas fa-box"></i> Productos</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/SaleController.php?accion=index"><i class="fas fa-shopping-cart"></i> Ventas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/ReportController.php?action=index"><i class="fas fa-chart-bar"></i> Reportes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/AlertController.php?accion=index"><i class="fas fa-exclamation-triangle"></i> Alertas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/ProviderController.php?accion=index"><i class="fas fa-truck"></i> Proveedores</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/UserController.php?accion=index"><i class="fas fa-users"></i> Usuarios</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/ClientController.php?accion=index"><i class="fas fa-user-tie"></i> Clientes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/LocalController.php?action=index"><i class="fas fa-building"></i> Locales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/RMIE/app/controllers/RouteController.php?accion=index"><i class="fas fa-route"></i> Rutas</a></li>
        </ul>
        <hr>
        <a href="../../logout.php" class="btn btn-danger btn-sm">
            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
        </a>
    </nav>
    <!-- Contenido principal -->
    <main class="flex-fill p-4">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?> (<?php echo $rol; ?>)</h2>
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-tags fa-2x text-primary mb-2"></i>
                        <h5 class="card-title">Categorías</h5>
                        <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-primary">
                            <i class="fas fa-tags"></i> Gestionar categorias
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-layer-group fa-2x text-success mb-2"></i>
                        <h5 class="card-title">Subcategorías</h5>
                        <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn btn-success">
                            <i class="fas fa-layer-group"></i> Gestionar subcategorias
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-box fa-2x text-warning mb-2"></i>
                        <h5 class="card-title">Productos</h5>
                        <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn btn-warning">
                            <i class="fas fa-box"></i> Gestionar Productos
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-2x text-info mb-2"></i>
                        <h5 class="card-title">Ventas</h5>
                        <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-info">
                            <i class="fas fa-shopping-cart"></i> Gestionar ventas
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-2x text-secondary mb-2"></i>
                        <h5 class="card-title">Reportes</h5>
                        <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn btn-secondary">
                            <i class="fas fa-chart-bar"></i> Gestionar reportes
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                        <h5 class="card-title">Alertas</h5>
                        <a href="/RMIE/app/controllers/AlertController.php?accion=index" class="btn btn-danger">
                            <i class="fas fa-exclamation-triangle"></i> Gestionar alertas
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                        <h5 class="card-title">Proveedores</h5>
                        <a href="/RMIE/app/controllers/ProviderController.php?accion=index" class="btn btn-primary">
                            <i class="fas fa-truck"></i> Gestionar proveedores
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-dark mb-2"></i>
                        <h5 class="card-title">Usuarios</h5>
                        <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn btn-dark">
                            <i class="fas fa-users"></i> Gestionar usuarios
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-tie fa-2x text-success mb-2"></i>
                        <h5 class="card-title">Clientes</h5>
                        <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn btn-success">
                            <i class="fas fa-user-tie"></i> Gestionar clientes
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-building fa-2x text-info mb-2"></i>
                        <h5 class="card-title">Locales</h5>
                        <a href="/RMIE/app/controllers/LocalController.php?action=index" class="btn btn-info">
                            <i class="fas fa-building"></i> Gestionar Locales
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-route fa-2x text-warning mb-2"></i>
                        <h5 class="card-title">Rutas</h5>
                        <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn btn-warning">
                            <i class="fas fa-route"></i> Gestionar Rutas
                        </a>
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