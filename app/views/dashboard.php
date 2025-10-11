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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RMIE</title>
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../public/css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Menú lateral moderno -->
    <nav class="sidebar-modern">
        <div class="sidebar-header">
            <h4 class="sidebar-title">
                <i class="fas fa-tachometer-alt"></i>
                <span>Panel de Control</span>
            </h4>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="../controllers/CategoryController.php?accion=index">
                    <i class="nav-icon fas fa-tags"></i>
                    <span class="nav-text">Categorías</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="../controllers/SubcategoryController.php?accion=index">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <span class="nav-text">Subcategorías</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="../controllers/ProductController.php?accion=index">
                    <i class="nav-icon fas fa-box"></i>
                    <span class="nav-text">Productos</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/SaleController.php?accion=index">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <span class="nav-text">Ventas</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/ReportController.php?action=index">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span class="nav-text">Reportes</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AlertController.php?accion=index">
                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                    <span class="nav-text">Alertas</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/ProviderController.php?accion=index">
                    <i class="nav-icon fas fa-truck"></i>
                    <span class="nav-text">Proveedores</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/UserController.php?accion=index">
                    <i class="nav-icon fas fa-users"></i>
                    <span class="nav-text">Usuarios</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/ClientController.php?accion=index">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <span class="nav-text">Clientes</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/LocalController.php?action=index">
                    <i class="nav-icon fas fa-building"></i>
                    <span class="nav-text">Locales</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/RouteController.php?accion=index">
                    <i class="nav-icon fas fa-route"></i>
                    <span class="nav-text">Rutas</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
        <div class="sidebar-divider"></div>
        <div class="logout-section">
            <a href="../../logout.php" class="btn-logout-modern">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>
    </nav>
    <!-- Contenido principal -->
    <main class="flex-fill" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); min-height: 100vh;">
        <!-- Header del dashboard -->
        <div class="dashboard-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; color: white; margin-bottom: 30px;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 style="margin: 0; font-weight: 700; font-size: 2.5rem;">
                            <i class="fas fa-chart-line me-3"></i>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?>
                        </h1>
                        <p style="margin: 5px 0 0 0; font-size: 1.1rem; opacity: 0.9;">
                            <i class="fas fa-user-shield me-2"></i>Rol: <?php echo ucfirst($rol); ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 15px; backdrop-filter: blur(10px);">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span id="currentDateTime"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4">
            <!-- Estadísticas generales -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 style="color: #333; font-weight: 600; margin-bottom: 20px;">
                        <i class="fas fa-chart-pie me-2"></i>Resumen del Sistema
                    </h3>
                </div>
            </div>

            <!-- SECCIÓN 1: GESTIÓN DE INVENTARIO -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 style="color: #667eea; font-weight: 600; border-left: 4px solid #667eea; padding-left: 15px;">
                        <i class="fas fa-boxes me-2"></i>Gestión de Inventario
                    </h4>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Categorías</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Gestionar categorías</p>
                                </div>
                                <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Subcategorías</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Gestionar subcategorías</p>
                                </div>
                                <a href="/RMIE/app/controllers/SubcategoryController.php?accion=index" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Productos</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Gestionar productos</p>
                                </div>
                                <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #ff7e79 0%, #40e0d0 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(255, 126, 121, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Proveedores</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Gestionar proveedores</p>
                                </div>
                                <a href="/RMIE/app/controllers/ProviderController.php?accion=index" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-truck"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: VENTAS Y REPORTES -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 style="color: #fa709a; font-weight: 600; border-left: 4px solid #fa709a; padding-left: 15px;">
                        <i class="fas fa-chart-line me-2"></i>Ventas y Reportes
                    </h4>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(250, 112, 154, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Ventas</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Gestionar ventas</p>
                                </div>
                                <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(168, 237, 234, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Reportes</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Ver reportes</p>
                                </div>
                                <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(255, 154, 158, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Alertas</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Ver alertas</p>
                                </div>
                                <a href="/RMIE/app/controllers/AlertController.php?accion=index" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: GESTIÓN DE USUARIOS Y LOCALES -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 style="color: #96fbc4; font-weight: 600; border-left: 4px solid #96fbc4; padding-left: 15px;">
                        <i class="fas fa-users-cog me-2"></i>Gestión de Usuarios y Locales
                    </h4>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(150, 251, 196, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Usuarios</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Gestionar usuarios</p>
                                </div>
                                <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(255, 236, 210, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Clientes</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Gestionar clientes</p>
                                </div>
                                <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(161, 196, 253, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Locales</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Gestionar locales</p>
                                </div>
                                <a href="/RMIE/app/controllers/LocalController.php?action=index" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(210, 153, 194, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Rutas</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Gestionar rutas</p>
                                </div>
                                <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Acceder
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-route"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de información adicional -->
            <div class="row mt-4">
                <div class="col-12">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; color: white; text-align: center; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);">
                        <i class="fas fa-rocket" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                        <h4 style="margin: 0 0 10px 0; font-weight: 700;">Sistema RMIE</h4>
                        <p style="margin: 0; font-size: 1.1rem; opacity: 0.9;">
                            Gestión integral de inventario, ventas y reportes
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Actualizar fecha y hora en tiempo real
function updateDateTime() {
    const now = new Date();
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit',
        second: '2-digit'
    };
    document.getElementById('currentDateTime').textContent = now.toLocaleDateString('es-ES', options);
}
updateDateTime();
setInterval(updateDateTime, 1000);
</script>
</body>
</html>