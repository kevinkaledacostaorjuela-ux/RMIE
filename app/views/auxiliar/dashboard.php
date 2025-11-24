<?php
// Vista del Dashboard para Auxiliares
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que sea auxiliar
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'auxiliar') {
    header('Location: ../../index.php');
    exit();
}

$rol = 'auxiliar';
$nombreCompleto = trim(($_SESSION['nombres'] ?? '') . ' ' . ($_SESSION['apellidos'] ?? ''));
if (empty($nombreCompleto)) {
    $nombreCompleto = 'Auxiliar';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Auxiliar - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../public/css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">
    <!-- Menú lateral moderno -->
    <nav class="sidebar-modern" id="sidebar">
        <div class="sidebar-header">
            <h4 class="sidebar-title">
                <i class="fas fa-user-shield"></i>
                <span>Panel Auxiliar</span>
            </h4>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-item-modern">
                <a class="nav-link-modern active" href="/RMIE/app/controllers/AuxiliarController.php?accion=dashboard">
                    <i class="nav-icon fas fa-home"></i>
                    <span class="nav-text">Mi Dashboard</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_ventas">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <span class="nav-text">Ventas (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_reportes">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span class="nav-text">Reportes (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_alertas">
                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                    <span class="nav-text">Alertas (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_rutas">
                    <i class="nav-icon fas fa-route"></i>
                    <span class="nav-text">Rutas (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_usuarios">
                    <i class="nav-icon fas fa-users"></i>
                    <span class="nav-text">Usuarios (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern mt-3">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil">
                    <i class="nav-icon fas fa-user-edit"></i>
                    <span class="nav-text">Mi Perfil</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/logout.php" style="color: #ff6b6b;">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <span class="nav-text">Cerrar Sesión</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main content -->
    <main class="main-content">
        <!-- Header con degradado -->
        <div class="dashboard-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; color: white; margin-bottom: 30px;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 style="margin: 0; font-weight: 700; font-size: 2.5rem;">
                            <i class="fas fa-chart-line me-3"></i>Bienvenido, <?php echo htmlspecialchars($nombreCompleto); ?>
                        </h1>
                        <p style="margin: 5px 0 0 0; font-size: 1.1rem; opacity: 0.9;">
                            <i class="fas fa-user-shield me-2"></i>Rol: <strong><?php echo ucfirst($rol); ?></strong>
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

            <!-- ALERTA DE SOLO LECTURA -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; color: white;">
                        <h5 class="alert-heading"><i class="fas fa-eye me-2"></i>Auxiliar - Solo Lectura</h5>
                        <p class="mb-0">Como <strong>Auxiliar</strong>, tienes acceso de solo lectura a la información del sistema. Puedes consultar datos, generar reportes y analizar estadísticas sin modificar registros.</p>
                    </div>
                </div>
            </div>

            <!-- Panel de Consultas para Auxiliar -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 style="color: #28a745; font-weight: 600; border-left: 4px solid #28a745; padding-left: 15px;">
                        <i class="fas fa-eye me-2"></i>Panel de Consultas y Análisis
                    </h4>
                </div>

                <!-- Ventas (Consulta) -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(250, 112, 154, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Ventas (Consulta)</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Ver historial de ventas</p>
                                </div>
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_ventas" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-eye me-1"></i>Consultar
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reportes (Consulta) -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(168, 237, 234, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Reportes (Consulta)</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Visualizar reportes</p>
                                </div>
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_reportes" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-eye me-1"></i>Consultar
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas (Consulta) -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(255, 154, 158, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Alertas (Consulta)</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Ver alertas del sistema</p>
                                </div>
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_alertas" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-eye me-1"></i>Consultar
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rutas (Consulta) -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(210, 153, 194, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Rutas (Consulta)</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Ver rutas registradas</p>
                                </div>
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_rutas" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-eye me-1"></i>Consultar
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-route"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Usuarios y Perfil -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 style="color: #96fbc4; font-weight: 600; border-left: 4px solid #96fbc4; padding-left: 15px;">
                        <i class="fas fa-users-cog me-2"></i>Usuarios y Perfil
                    </h4>
                </div>

                <!-- Usuarios (Consulta) -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(150, 251, 196, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Usuarios (Consulta)</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Ver información de usuarios</p>
                                </div>
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_usuarios" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-eye me-1"></i>Consultar
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mi Perfil -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(255, 154, 158, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Mi Perfil</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Editar información personal</p>
                                </div>
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-user-edit me-1"></i>Editar
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-id-card"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Volver al Dashboard Principal -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 25px; color: white; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Dashboard Principal</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Ir al panel general</p>
                                </div>
                                <a href="/RMIE/app/views/dashboard.php" class="btn btn-light btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-home me-1"></i>Ir
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas (si existen) -->
            <?php if (isset($stats)): ?>
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 style="color: #4facfe; font-weight: 600; border-left: 4px solid #4facfe; padding-left: 15px;">
                        <i class="fas fa-chart-line me-2"></i>Estadísticas del Sistema
                    </h4>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card text-center" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <i class="fas fa-users" style="font-size: 2rem; color: #667eea;"></i>
                            <h3 class="mt-2"><?php echo $stats['total_usuarios'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Usuarios Totales</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card text-center" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <i class="fas fa-user-tie" style="font-size: 2rem; color: #fa709a;"></i>
                            <h3 class="mt-2"><?php echo $stats['total_clientes'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Clientes Registrados</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card text-center" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <i class="fas fa-box" style="font-size: 2rem; color: #28a745;"></i>
                            <h3 class="mt-2"><?php echo $stats['total_productos'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Productos Disponibles</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card text-center" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart" style="font-size: 2rem; color: #17a2b8;"></i>
                            <h3 class="mt-2"><?php echo $stats['ventas_hoy'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Ventas Hoy</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card text-center" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <i class="fas fa-truck" style="font-size: 2rem; color: #ff7e79;"></i>
                            <h3 class="mt-2"><?php echo $stats['total_proveedores'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Proveedores</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card text-center" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <i class="fas fa-bell" style="font-size: 2rem; color: #ffc107;"></i>
                            <h3 class="mt-2"><?php echo $stats['alertas_activas'] ?? 0; ?></h3>
                            <p class="mb-0 text-muted">Alertas Activas</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Actualizar fecha y hora en tiempo real
function updateDateTime() {
    const dateTimeElement = document.getElementById('currentDateTime');
    if (dateTimeElement) {
        const now = new Date();
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit'
        };
        dateTimeElement.textContent = now.toLocaleDateString('es-ES', options);
    }
}

// Actualizar cada segundo
setInterval(updateDateTime, 1000);
updateDateTime();
</script>
</body>
</html>
