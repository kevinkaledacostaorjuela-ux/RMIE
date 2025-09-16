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
            <li class="nav-item"><a class="nav-link text-white" href="#">Productos</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Subcategorías</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Ventas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Reportes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Alertas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Proveedores</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Clientes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Locales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Rutas</a></li>
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
                        <h5 class="card-title">Gestión de Productos</h5>
                        <a href="#" class="btn btn-primary">Ir</a>
                    </div>
                </div>
            </div>
            <!-- ...otros accesos rápidos... -->
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