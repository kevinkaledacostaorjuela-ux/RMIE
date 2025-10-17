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

        /* Estilos del cohete animado */
        .rocket-container {
            position: relative;
            overflow: hidden;
        }

        .rocket-wrapper {
            position: absolute;
            top: 20px;
            left: 20px;
            animation: rocketFlight 8s infinite linear;
            z-index: 5;
        }

        .rocket {
            width: 40px;
            height: 60px;
            position: relative;
            transform: rotate(-45deg);
        }

        .rocket-body {
            width: 20px;
            height: 40px;
            background: linear-gradient(145deg, #e8eaf6 0%, #c5cae9 100%);
            border-radius: 10px 10px 2px 2px;
            position: relative;
            margin: 0 auto;
            box-shadow: inset 2px 2px 4px rgba(255,255,255,0.3), inset -2px -2px 4px rgba(0,0,0,0.2);
        }

        .rocket-tip {
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 20px solid #f44336;
            position: absolute;
            top: -19px;
            left: 50%;
            transform: translateX(-50%);
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        .rocket-window {
            width: 8px;
            height: 8px;
            background: radial-gradient(circle, #42a5f5 0%, #1976d2 100%);
            border-radius: 50%;
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 6px rgba(66, 165, 245, 0.8);
        }

        .rocket-fin {
            width: 8px;
            height: 15px;
            background: linear-gradient(145deg, #ff7043 0%, #d84315 100%);
            position: absolute;
            bottom: -2px;
            border-radius: 0 0 4px 4px;
        }

        .left-fin {
            left: -4px;
            transform: skew(-20deg);
        }

        .right-fin {
            right: -4px;
            transform: skew(20deg);
        }

        .rocket-fire {
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 12px;
            height: 15px;
        }

        .fire-particle {
            position: absolute;
            width: 4px;
            height: 8px;
            background: linear-gradient(180deg, #ff5722 0%, #ff9800 50%, #ffc107 100%);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            animation: fireFlicker 0.3s infinite alternate;
        }

        .fire-particle:nth-child(1) {
            left: 0;
            animation-delay: 0s;
        }

        .fire-particle:nth-child(2) {
            left: 4px;
            animation-delay: 0.1s;
            height: 12px;
        }

        .fire-particle:nth-child(3) {
            left: 8px;
            animation-delay: 0.2s;
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

        .rocket-text {
            position: relative;
            z-index: 10;
            margin-top: 40px;
        }

        /* Animaciones */
        @keyframes rocketFlight {
            0% { 
                transform: translate(0, 0) rotate(-45deg);
                opacity: 1;
            }
            15% { 
                transform: translate(150px, -30px) rotate(-30deg);
                opacity: 1;
            }
            30% { 
                transform: translate(300px, -20px) rotate(-15deg);
                opacity: 1;
            }
            45% { 
                transform: translate(450px, -40px) rotate(0deg);
                opacity: 1;
            }
            60% { 
                transform: translate(600px, -60px) rotate(15deg);
                opacity: 1;
            }
            75% { 
                transform: translate(750px, -30px) rotate(30deg);
                opacity: 1;
            }
            90% { 
                transform: translate(900px, -10px) rotate(45deg);
                opacity: 0.5;
            }
            100% { 
                transform: translate(1050px, 20px) rotate(60deg);
                opacity: 0;
            }
        }

        @keyframes fireFlicker {
            0% { 
                transform: scaleY(1) scaleX(1);
                opacity: 1;
            }
            100% { 
                transform: scaleY(1.3) scaleX(0.8);
                opacity: 0.8;
            }
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
            .rocket-wrapper {
                animation: rocketFlightMobile 6s infinite linear;
            }
            
            .rocket {
                width: 30px;
                height: 45px;
            }
            
            .rocket-body {
                width: 15px;
                height: 30px;
            }
            
            @keyframes rocketFlightMobile {
                0% { 
                    transform: translate(0, 0) rotate(-45deg);
                    opacity: 1;
                }
                25% { 
                    transform: translate(100px, -20px) rotate(-15deg);
                    opacity: 1;
                }
                50% { 
                    transform: translate(200px, -30px) rotate(15deg);
                    opacity: 1;
                }
                75% { 
                    transform: translate(300px, -10px) rotate(35deg);
                    opacity: 0.7;
                }
                100% { 
                    transform: translate(400px, 10px) rotate(45deg);
                    opacity: 0;
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
                    <div class="rocket-container" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; color: white; text-align: center; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2); position: relative; overflow: hidden; min-height: 200px;">
                        
                        <!-- Cohete animado -->
                        <div class="rocket-wrapper">
                            <div class="rocket">
                                <!-- Cuerpo del cohete -->
                                <div class="rocket-body"></div>
                                <!-- Punta del cohete -->
                                <div class="rocket-tip"></div>
                                <!-- Ventanas -->
                                <div class="rocket-window"></div>
                                <!-- Aletas -->
                                <div class="rocket-fin left-fin"></div>
                                <div class="rocket-fin right-fin"></div>
                                <!-- Fuego del cohete -->
                                <div class="rocket-fire">
                                    <div class="fire-particle"></div>
                                    <div class="fire-particle"></div>
                                    <div class="fire-particle"></div>
                                </div>
                            </div>
                        </div>

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

                        <!-- Texto -->
                        <div class="rocket-text">
                            <h4 style="margin: 0 0 10px 0; font-weight: 700; z-index: 10; position: relative;">Sistema RMIE</h4>
                            <p style="margin: 0; font-size: 1.1rem; opacity: 0.9; z-index: 10; position: relative;">
                                Gestión integral de inventario, ventas y reportes
                            </p>
                        </div>
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

// JavaScript para menú móvil
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
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
});
</script>
</body>
</html>