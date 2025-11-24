<?php
// app/views/dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
}
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'invitado';
$nombreCompleto = trim((isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '') . ' ' . (isset($_SESSION['apellidos']) ? $_SESSION['apellidos'] : ''));
if (empty($nombreCompleto)) {
    $nombreCompleto = 'Usuario';
}
// Cargar configuración de permisos si es necesario
require_once __DIR__ . '/../utils/PermissionsConfig.php';
$allowedAux = [];
if ($rol === 'auxiliar') {
    $allowedAux = array_keys(PermissionsConfig::getAuxiliarModules());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../public/css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        /* Menú móvil hamburguesa */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1050;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
            
            .sidebar-modern {
                position: fixed;
                top: 0;
                left: -280px;
                height: 100vh;
                width: 280px;
                z-index: 1045;
                transition: left 0.3s ease;
                background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }
            
            .sidebar-modern.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding-top: 80px;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Estilos de la moto animada */
        .bike-container {
            position: relative;
            overflow: hidden;
        }

        .bike-wrapper {
            position: absolute;
            bottom: 20px;
            right: -100px;
            animation: bikeDrive 8s infinite linear;
            z-index: 5;
            transform: scaleX(-1);
        }

        .bike {
            width: 60px;
            height: 35px;
            position: relative;
        }

        .bike-body {
            width: 35px;
            height: 10px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            border-radius: 5px;
            position: absolute;
            bottom: 18px;
            left: 12px;
            box-shadow: 0 2px 4px rgba(255, 107, 53, 0.3);
        }

        .bike-seat {
            width: 8px;
            height: 4px;
            background: #2c3e50;
            border-radius: 2px;
            position: absolute;
            bottom: 28px;
            left: 28px;
        }

        .bike-handle {
            width: 12px;
            height: 2px;
            background: #95a5a6;
            border-radius: 1px;
            position: absolute;
            bottom: 30px;
            left: 8px;
            transform: rotate(-10deg);
        }

        .bike-wheel {
            width: 14px;
            height: 14px;
            background: #2c3e50;
            border-radius: 50%;
            position: absolute;
            bottom: 2px;
            border: 1px solid #1a252f;
        }

        .bike-wheel.front {
            left: 8px;
            animation: wheelSpin 0.3s infinite linear;
        }

        .bike-wheel.rear {
            right: 8px;
            animation: wheelSpin 0.3s infinite linear;
        }

        .bike-rider {
            position: absolute;
            bottom: 28px;
            left: 20px;
            width: 10px;
            height: 15px;
        }

        .rider-head {
            width: 8px;
            height: 8px;
            background: #f39c12;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 1px;
            border: 1px solid #e67e22;
        }

        .rider-body {
            width: 6px;
            height: 10px;
            background: #3498db;
            border-radius: 3px;
            position: absolute;
            bottom: 0;
            left: 2px;
        }

        .exhaust-smoke:nth-child(1) {
            animation-delay: 0s;
        }

        .exhaust-smoke:nth-child(2) {
            animation-delay: 0.2s;
        }

        .exhaust-smoke:nth-child(3) {
            animation-delay: 0.4s;
        }

        .road-lines {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: repeating-linear-gradient(
                90deg,
                transparent 0px,
                transparent 15px,
                #fff 15px,
                #fff 30px
            );
            animation: roadMove 1.5s infinite linear;
        }

        /* Estrellas de fondo */
        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            animation: twinkle 2s infinite alternate;
        }

        .star:nth-child(1) { top: 20%; left: 15%; animation-delay: 0s; }
        .star:nth-child(2) { top: 30%; left: 80%; animation-delay: 0.5s; }
        .star:nth-child(3) { top: 60%; left: 25%; animation-delay: 1s; }
        .star:nth-child(4) { top: 70%; left: 70%; animation-delay: 1.5s; }
        .star:nth-child(5) { top: 15%; left: 50%; animation-delay: 2s; }
        .star:nth-child(6) { top: 45%; left: 90%; animation-delay: 2.5s; }
        .star:nth-child(7) { top: 80%; left: 40%; animation-delay: 3s; }
        .star:nth-child(8) { top: 25%; left: 75%; animation-delay: 3.5s; }

        .bike-text {
            position: relative;
            z-index: 10;
            margin-top: 40px;
        }

        /* Animaciones */
        @keyframes bikeDrive {
            0% { 
                right: -100px; 
                bottom: 20px; 
            }
            15% { 
                right: 15%; 
                bottom: 22px; 
            }
            30% { 
                right: 30%; 
                bottom: 18px; 
            }
            45% { 
                right: 45%; 
                bottom: 24px; 
            }
            60% { 
                right: 60%; 
                bottom: 19px; 
            }
            75% { 
                right: 75%; 
                bottom: 23px; 
            }
            100% { 
                right: calc(100% + 100px); 
                bottom: 20px; 
            }
        }

        @keyframes wheelSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes smokeRise {
            0% { 
                bottom: 0; 
                opacity: 0.8; 
                transform: scale(0.5);
            }
            100% { 
                bottom: 20px; 
                opacity: 0; 
                transform: scale(1.5);
            }
        }

        @keyframes roadMove {
            0% { transform: translateX(0); }
            100% { transform: translateX(-30px); }
        }

        @keyframes twinkle {
            0% { 
                opacity: 0.3;
                transform: scale(1);
            }
            100% { 
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Responsive para móviles */
        @media (max-width: 768px) {
            .bike-wrapper {
                animation: bikeDriveMobile 6s infinite linear;
            }
            
            .bike {
                width: 45px;
                height: 25px;
            }
            
            .bike-body {
                width: 22px;
                height: 6px;
            }
            
            .bike-wheel {
                width: 12px;
                height: 12px;
            }
            
            @keyframes bikeDriveMobile {
                0% { 
                    right: -60px; 
                    bottom: 15px; 
                }
                25% { 
                    right: 25%; 
                    bottom: 18px; 
                }
                50% { 
                    right: 50%; 
                    bottom: 15px; 
                }
                75% { 
                    right: 75%; 
                    bottom: 18px; 
                }
                100% { 
                    right: calc(100% + 60px); 
                    bottom: 15px; 
                }
            }
        }
    </style>
</head>
<body>

<!-- Botón hamburguesa para móviles -->
<button class="mobile-toggle" id="mobileToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay para cerrar menú en móviles -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="d-flex">
    <!-- Menú lateral moderno -->
    <nav class="sidebar-modern" id="sidebar">
        <div class="sidebar-header">
            <h4 class="sidebar-title">
                <i class="fas fa-tachometer-alt"></i>
                <span>Panel de Control</span>
            </h4>
        </div>
        <ul class="sidebar-nav">
            <?php if ($rol === 'auxiliar'): ?>
                <li class="nav-item-modern">
                    <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=dashboard">
                        <i class="nav-icon fas fa-home"></i>
                        <span class="nav-text">Mi Dashboard</span>
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
                <li class="nav-item-modern">
                    <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <span class="nav-text">Mi Perfil</span>
                        <i class="nav-arrow fas fa-chevron-right"></i>
                    </a>
                </li>
            <?php else: ?>
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
                    <a class="nav-link-modern" href="/RMIE/app/controllers/RouteController.php?accion=index">
                        <i class="nav-icon fas fa-route"></i>
                        <span class="nav-text">Rutas</span>
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
                    <a class="nav-link-modern" href="/RMIE/app/controllers/UserController.php?accion=index">
                        <i class="nav-icon fas fa-users"></i>
                        <span class="nav-text">Usuarios</span>
                        <i class="nav-arrow fas fa-chevron-right"></i>
                    </a>
                </li>
            <?php endif; ?>
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

            <!-- SECCIÓN 1: GESTIÓN DE INVENTARIO -->
            <?php if ($rol !== 'auxiliar'): ?>
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
            <?php endif; ?>

            <?php if ($rol !== 'auxiliar' || in_array('subcategorias', $allowedAux)): ?>
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
            <?php endif; ?>

            <?php if ($rol !== 'auxiliar' || in_array('productos', $allowedAux)): ?>
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
            <?php endif; ?>

            <?php if ($rol !== 'auxiliar' || in_array('proveedores', $allowedAux)): ?>
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
            <?php endif; ?>
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
            <?php if ($rol === 'auxiliar'): ?>
            <!-- Dashboard Especial para Auxiliar -->
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(210, 153, 194, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Rutas</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Gestionar rutas</p>
                                </div>
                                <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Ir
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-route"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="dashboard-card" style="background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(150, 251, 196, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div class="d-flex align-items-center justify-content-between h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Usuarios</h5>
                                    <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Gestionar usuarios</p>
                                </div>
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_usuarios" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
                                    <i class="fas fa-arrow-right me-1"></i>Ir
                                </a>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
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
            </div>
            <?php else: ?>
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 style="color: #96fbc4; font-weight: 600; border-left: 4px solid #96fbc4; padding-left: 15px;">
                        <i class="fas fa-users-cog me-2"></i>Gestión de Usuarios y Locales
                    </h4>
                </div>
            <?php if ($rol !== 'auxiliar'): ?>
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
            <?php endif; ?>

            <?php if ($rol !== 'auxiliar'): ?>
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
            <?php endif; ?>

            <?php if ($rol !== 'auxiliar'): ?>
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
            <?php endif; ?>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="dashboard-card" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); border-radius: 20px; padding: 25px; color: #333; box-shadow: 0 10px 30px rgba(210, 153, 194, 0.3); transition: all 0.3s ease; height: 180px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="d-flex align-items-center justify-content-between h-100">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <div>
                                <h5 style="margin: 0; font-weight: 600; font-size: 1.2rem;">Rutas <?php echo ($rol === 'auxiliar') ? '(Consulta)' : ''; ?></h5>
                                <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;"><?php echo ($rol === 'auxiliar') ? 'Consultar rutas' : 'Gestionar rutas'; ?></p>
                            </div>
                            <a href="<?php echo ($rol === 'auxiliar') ? '/RMIE/app/controllers/AuxiliarController.php?accion=consultar_rutas' : '/RMIE/app/controllers/RouteController.php?accion=index'; ?>" class="btn btn-dark btn-sm" style="border-radius: 15px; font-weight: 600; width: fit-content;">
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
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
// Capturar promesas rechazadas
window.addEventListener('unhandledrejection', function(e) { console.log('Promise Error:', e.reason); e.preventDefault(); });

// Debug mode - capturar errores JavaScript
window.addEventListener('error', function(e) { console.log('JS Error:', e.message, 'at', e.filename + ':' + e.lineno); });

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

// JavaScript para menú móvil
document.addEventListener('DOMContentLoaded', function() {
    try {
    // Inicializar fecha y hora
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Verificar que los elementos existen antes de agregar event listeners
    if (mobileToggle && sidebar && sidebarOverlay) {
        // Abrir menú móvil
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
            mobileToggle.innerHTML = '<i class="fas fa-times"></i>';
        });
        
        // Cerrar menú móvil
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
        });
        
        // Cerrar menú al hacer clic en un enlace
        const navLinks = document.querySelectorAll('.nav-link-modern');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        });
        
        // Manejar cambio de orientación/tamaño
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    }
    } catch (error) {
        console.log('DOMContentLoaded error:', error);
    }
});
</script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Footer con moto animada -->
<footer class="mt-5 p-0" style="position: relative; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="bike-container" style="min-height: 120px; padding: 20px 0; color: white; text-align: center; position: relative; overflow: hidden;">
        
        <!-- Moto animada -->
        <div class="bike-wrapper">
            <div class="bike">
                <!-- Cuerpo de la moto -->
                <div class="bike-body"></div>
                <!-- Asiento -->
                <div class="bike-seat"></div>
                <!-- Manillar -->
                <div class="bike-handle"></div>
                <!-- Faro delantero -->
                <div style="position: absolute; left: 2px; bottom: 22px; width: 6px; height: 4px; background: #f1c40f; border-radius: 2px; box-shadow: 0 0 8px #f1c40f;"></div>
                <!-- Ruedas -->
                <div class="bike-wheel front">
                    <div class="bike-spoke spoke-1"></div>
                    <div class="bike-spoke spoke-2"></div>
                    <div class="bike-spoke spoke-3"></div>
                    <div class="bike-spoke spoke-4"></div>
                </div>
                <div class="bike-wheel rear">
                    <div class="bike-spoke spoke-1"></div>
                    <div class="bike-spoke spoke-2"></div>
                    <div class="bike-spoke spoke-3"></div>
                    <div class="bike-spoke spoke-4"></div>
                </div>
                <!-- Conductor -->
                <div class="bike-rider">
                    <div class="rider-head"></div>
                    <div class="rider-body"></div>
                </div>
                <!-- Escape -->
                <div class="bike-exhaust">
                    <div class="exhaust-smoke"></div>
                    <div class="exhaust-smoke"></div>
                    <div class="exhaust-smoke"></div>
                </div>
            </div>
        </div>

        <!-- Líneas de carretera -->
        <div class="road-lines"></div>

        <!-- Estrellas de fondo -->
        <div class="stars">
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
        </div>

        <!-- Texto del footer -->
        <div class="bike-text">
            <h4 style="margin: 10px 0 5px 0; font-weight: 700; z-index: 10; position: relative;">🏍️ Sistema RMIE</h4>
            <p style="margin: 0; font-size: 1rem; opacity: 0.9; z-index: 10; position: relative;">
                Gestión integral de inventario, ventas y reportes
            </p>
        </div>
    </div>
</footer>

</body>
</html>