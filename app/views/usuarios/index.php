<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Obtener mensajes de sesión
$error_message = $_SESSION['error'] ?? '';
$success_message = $_SESSION['success'] ?? '';

// Limpiar mensajes de sesión
unset($_SESSION['error'], $_SESSION['success']);

// Calcular estadísticas de usuarios
$totalUsuarios = count($usuarios ?? []);
$usuariosAdmin = 0;
$usuariosEmpleado = 0;
$usuariosActivos = 0;
$usuariosInactivos = 0;
$usuariosRecientes = 0;

$fechaReciente = date('Y-m-d', strtotime('-30 days'));

if (isset($usuarios) && is_array($usuarios)) {
    foreach ($usuarios as $usuario) {
        // Contar por rol
        switch (strtolower($usuario->rol ?? 'empleado')) {
            case 'admin':
                $usuariosAdmin++;
                break;
            case 'empleado':
                $usuariosEmpleado++;
                break;
        }
        
        // Contar por estado (todos activos por defecto)
        $usuariosActivos++;
        
        // Contar usuarios recientes (últimos 30 días)
        if (!empty($usuario->fecha_creacion) && $usuario->fecha_creacion >= $fechaReciente) {
            $usuariosRecientes++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <style>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'admin'): ?>
        /* Ocultar botones de eliminar para roles que no sean admin */
        a[href*="accion=delete"],
        button[onclick*="delete"],
        button[onclick*="Eliminacion"],
        button[onclick*="eliminar"],
        .btn-danger[href*="delete"],
        .btn-danger-modern,
        button.btn-danger-modern {
            display: none !important;
        }
        <?php endif; ?>

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            margin: 20px auto;
            max-width: 1400px;
            width: calc(100% - 40px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }        .filters-container {
            margin-bottom: 2rem;
        }

        .filters-inner {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 35px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .btn-modern-filter {
            transition: all 0.3s ease;
        }

        .btn-modern-filter:hover {
            background: #3A7BC8 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.4);
        }

        .btn-modern-clear {
            transition: all 0.3s ease;
        }

        .btn-modern-clear:hover {
            background: #E67E93 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 143, 163, 0.4);
        }

        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .filter-title {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #fff;
            padding: 10px 15px;
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            color: #fff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 10px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 500;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-modern {
            background: transparent;
            color: #fff;
        }

        .table-modern th {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: none;
            padding: 15px 10px;
            font-weight: 600;
        }

        .table-modern td {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 10px;
            vertical-align: middle;
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.02);
        }

        /* Estilos para los option del select */
        .form-control-modern option {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
        }

        .form-control-modern option:hover {
            background: #34495e;
        }

        .btn-modern {
            padding: 8px 16px;
            border-radius: 25px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }

        .btn-primary-modern {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .btn-warning-modern {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .btn-danger-modern {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .alert-modern {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            padding: 20px 25px;
            font-weight: 600;
            font-size: 1.1rem;
            animation: slideInDown 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .alert-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        .alert-success-modern {
            background: rgba(46, 204, 113, 0.3);
            color: #fff;
            border: 2px solid rgba(46, 204, 113, 0.6);
            box-shadow: 0 10px 30px rgba(46, 204, 113, 0.3);
        }

        .alert-danger-modern {
            background: rgba(231, 76, 60, 0.3);
            color: #fff;
            border: 2px solid rgba(231, 76, 60, 0.6);
            box-shadow: 0 10px 30px rgba(231, 76, 60, 0.3);
        }

        .user-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .badge-modern {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .badge-info {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-secondary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .role-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .role-admin {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .role-auxiliar {
            background: linear-gradient(45deg, #f093fb, #f5576c);
            color: white;
        }

        .role-coordinador {
            background: linear-gradient(45deg, #43e97b, #38f9d7);
            color: white;
        }

        .role-empleado {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .contact-info {
            font-size: 0.9rem;
            color: #2d3748;
            font-weight: 500;
        }

        /* Diseño de Tarjetas para usuarios */
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .users-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .users-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2, #4facfe);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .users-card:hover::before {
            transform: scaleX(1);
        }

        .users-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .users-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .users-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            font-weight: bold;
        }

        .users-card-title {
            flex: 1;
        }

        .users-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .users-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .users-card-body {
            margin-bottom: 20px;
        }

        .users-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .users-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .users-info-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .users-info-content {
            flex: 1;
        }

        .users-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .users-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
        }

        .users-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .users-actions {
            display: flex;
            gap: 8px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
        }

        .view-toggle-btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-decoration: none;
        }

        .view-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            color: #fff;
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: #fff;
        }        /* Responsive para tarjetas */
        @media (max-width: 768px) {
            .filters-container .row {
                display: flex !important;
                flex-direction: column !important;
                gap: 1rem !important;
            }
            
            .filters-container .col:last-child {
                margin-top: 1rem;
            }
            
            .filters-container .col:last-child div {
                flex-direction: row !important;
                justify-content: center !important;
                gap: 1rem !important;
            }
            
            .users-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .view-toggle {
                flex-direction: column;
                gap: 5px;
            }
            
            .view-toggle-btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }

        /* Scroll horizontal para móviles - Usuarios */
        @media (max-width: 768px) {
            .table-container {
                padding: 15px;
                overflow: visible;
            }
            
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                overflow-x: auto !important;
                overflow-y: visible;
                border-radius: 10px;
                max-width: 100%;
                position: relative;
            }
            
            .table-responsive::-webkit-scrollbar {
                height: 12px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 6px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.5);
                border-radius: 6px;
                border: 2px solid rgba(255, 255, 255, 0.1);
            }
            
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.7);
            }
            
            .table-modern {
                min-width: 1200px !important; /* Ancho mínimo para 8 columnas */
                margin-bottom: 0;
                width: 1200px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para usuarios (8 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 80px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 180px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 200px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 120px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 100px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 150px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 150px; }
            
            .table-modern th:nth-child(8),
            .table-modern td:nth-child(8) { min-width: 120px; }
            
            /* Scroll indicator */
            .scroll-hint {
                position: absolute;
                bottom: -30px;
                left: 50%;
                transform: translateX(-50%);
                color: rgba(255, 255, 255, 0.8);
                font-size: 0.8rem;
                font-style: italic;
                animation: pulse 2s ease-in-out infinite;
                background: rgba(0, 0, 0, 0.3);
                padding: 5px 10px;
                border-radius: 15px;
                backdrop-filter: blur(5px);
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-users"></i> Gestión de Usuarios
        </h1>

        <!-- Mensajes -->
        <?php if ($success_message): ?>
            <div class="alert alert-modern alert-success-modern">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-modern alert-danger-modern">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo $totalUsuarios; ?></div>
                <div class="stat-label">Total Usuarios</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-number"><?php echo $usuariosAdmin; ?></div>
                <div class="stat-label">Administradores</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-number"><?php echo $usuariosEmpleado; ?></div>
                <div class="stat-label">Empleados</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number"><?php echo $usuariosActivos; ?></div>
                <div class="stat-label">Activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-number"><?php echo $usuariosInactivos; ?></div>
                <div class="stat-label">Inactivos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-number"><?php echo $usuariosRecientes; ?></div>
                <div class="stat-label">Nuevos (30 días)</div>
            </div>
        </div>        <!-- Filtros -->
        <div class="filters-container">
            <div class="filters-inner">
                <form method="GET" action="/RMIE/app/controllers/UserController.php" id="filterForm" 
                    style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 950px; margin: 0 auto;">
                    <input type="hidden" name="accion" value="index">
                    
                    <div class="filtros-usuarios-flex" style="display: flex; flex-wrap: wrap; gap: 1.5rem 2.5rem; justify-content: center; align-items: end; width: 100%; flex-direction: row;">
                            <div class="filtro-usuario-item" style="min-width: 180px; max-width: 220px; flex: 1 1 180px;">
                                <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                    <i class="fas fa-user"></i> Nombre
                                </label>
                                <input type="text"
                                       name="buscar"
                                       class="form-control"
                                       placeholder="Buscar usuario..."
                                       style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;"
                                       value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                            </div>
                            <div class="filtro-usuario-item" style="min-width: 150px; max-width: 200px; flex: 1 1 150px;">
                                <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                    <i class="fas fa-users"></i> Rol
                                </label>
                                <select name="filtro_rol" class="form-select" style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;">
                                    <option value="">Todos los roles</option>
                                    <option value="admin" <?= ($_GET['filtro_rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="coordinador" <?= ($_GET['filtro_rol'] ?? '') === 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                                    <option value="auxiliar" <?= ($_GET['filtro_rol'] ?? '') === 'auxiliar' ? 'selected' : '' ?>>Auxiliar</option>
                                </select>
                            </div>
                            <div class="filtro-usuario-item" style="min-width: 150px; max-width: 200px; flex: 1 1 150px;">
                                <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                    <i class="fas fa-toggle-on"></i> Estado
                                </label>
                                <select name="estado" class="form-select" style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;">
                                    <option value="">Todos los estados</option>
                                    <option value="activo" <?= ($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                            <div style="display: flex; gap: 12px; align-items: end;">
                                <button type="submit" class="btn-modern-filter" style="background: #4A90E2; color: white; border: none; padding: 12px 32px; border-radius: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 1rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.2s;">
                                    <i class="fas fa-search"></i> FILTRAR
                                </button>
                                <button type="button" class="btn-modern-clear" onclick="limpiarFiltros()" style="background: #FF8FA3; color: white; border: none; padding: 12px 32px; border-radius: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 1rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.2s;">
                                    <i class="fas fa-times"></i> LIMPIAR
                                </button>
                            </div>
                        </div>
                        <style>
                        @media (max-width: 900px) {
                            .filtros-usuarios-flex {
                                flex-direction: column !important;
                                align-items: stretch !important;
                            }
                            .filtro-usuario-item {
                                max-width: 100% !important;
                            }
                        }
                        </style>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mb-4 text-center">
            <a href="/RMIE/app/controllers/UserController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-user-plus"></i> Nuevo Usuario
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Contenedor de Usuarios con Toggle de Vista -->
        <div class="table-container" id="usuarios-container">
            <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                <h3 style="color: #fff; margin: 0;"><i class="fas fa-users"></i> Lista de Usuarios (<?= $totalUsuarios ?>)</h3>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="toggleUsuariosView('cards')" id="btnUsuariosCards">
                        <i class="fas fa-th-large"></i> Tarjetas
                    </button>
                    <button class="view-toggle-btn" onclick="toggleUsuariosView('table')" id="btnUsuariosTable">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                </div>
            </div>

            <!-- Vista de Tarjetas (por defecto) -->
            <div id="cardsUsuariosView" class="users-grid">
                <?php if (isset($usuarios) && is_array($usuarios) && !empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <div class="users-card">
                            <div class="users-card-header">
                                <div class="users-card-icon">
                                    <?= strtoupper(substr($usuario->nombres ?? 'U', 0, 1)) ?>
                                </div>
                                <div class="users-card-title">
                                    <h4><?= htmlspecialchars($usuario->nombres ?? 'Sin nombre') ?></h4>
                                    <p><i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($usuario->num_doc ?? 'N/A') ?></p>
                                </div>
                            </div>
                            
                            <div class="users-card-body">
                                <div class="users-info-item">
                                    <div class="users-info-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="users-info-content">
                                        <div class="users-info-label">Usuario</div>
                                        <div class="users-info-value"><?= htmlspecialchars($usuario->usuario ?? 'N/A') ?></div>
                                    </div>
                                </div>
                                
                                <div class="users-info-item">
                                    <div class="users-info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="users-info-content">
                                        <div class="users-info-label">Email</div>
                                        <div class="users-info-value"><?= htmlspecialchars($usuario->correo ?? 'Sin email') ?></div>
                                    </div>
                                </div>
                                
                                <div class="users-info-item">
                                    <div class="users-info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="users-info-content">
                                        <div class="users-info-label">Último Acceso</div>
                                        <div class="users-info-value">
                                            <?php if (!empty($usuario->ultimo_acceso)): ?>
                                                <?php
                                                $ultimoAcceso = new DateTime($usuario->ultimo_acceso);
                                                $ahora = new DateTime();
                                                $diferencia = $ahora->diff($ultimoAcceso);
                                                
                                                if ($diferencia->days == 0) {
                                                    echo '<span class="text-success"><i class="fas fa-circle"></i> Hoy a las ' . $ultimoAcceso->format('H:i') . '</span>';
                                                } elseif ($diferencia->days == 1) {
                                                    echo '<span class="text-warning"><i class="fas fa-circle"></i> Ayer a las ' . $ultimoAcceso->format('H:i') . '</span>';
                                                } else {
                                                    echo '<span class="text-muted"><i class="fas fa-calendar"></i> ' . $ultimoAcceso->format('d/m/Y H:i') . '</span>';
                                                }
                                                ?>
                                            <?php else: ?>
                                                <span class="text-muted"><i class="fas fa-question-circle"></i> Nunca se conectó</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="users-info-item">
                                    <div class="users-info-icon">
                                        <?php
                                        $rol = strtolower($usuario->rol ?? 'empleado');
                                        $roleIcon = $rol === 'admin' ? 'fas fa-user-shield' : 'fas fa-user-tie';
                                        ?>
                                        <i class="<?= $roleIcon ?>"></i>
                                    </div>
                                    <div class="users-info-content">
                                        <div class="users-info-label">Rol</div>
                                        <div class="users-info-value"><?= ucfirst($rol) ?></div>
                                    </div>
                                </div>
                                
                                <div class="users-info-item">
                                    <div class="users-info-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="users-info-content">
                                        <div class="users-info-label">Estado</div>
                                        <div class="users-info-value">Activo</div>
                                    </div>
                                </div>
                                
                                <div class="users-info-item">
                                    <div class="users-info-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div class="users-info-content">
                                        <div class="users-info-label">Documento</div>
                                        <div class="users-info-value"><?= htmlspecialchars($usuario->num_doc ?? 'N/A') ?></div>
                                    </div>
                                </div>
                                
                                <div class="users-info-item">
                                    <div class="users-info-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="users-info-content">
                                        <div class="users-info-label">Fecha Registro</div>
                                        <div class="users-info-value"><?= date('d/m/Y H:i', strtotime($usuario->fecha_creacion ?? 'now')) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="users-card-footer">
                                <?php
                                $rol = trim(strtolower($usuario->rol ?? 'empleado'));
                                
                                // Lógica de roles mejorada
                                $roleClass = 'role-empleado';
                                $roleIcon = 'fas fa-user';
                                $roleName = 'Empleado';
                                
                                if ($rol == 'admin' || $usuario->num_doc == '1') {
                                    $roleClass = 'role-admin';
                                    $roleIcon = 'fas fa-user-shield';
                                    $roleName = 'Admin';
                                } elseif ($usuario->num_doc == '123456789') {
                                    // Usuario auxiliar específico
                                    $roleClass = 'role-auxiliar';
                                    $roleIcon = 'fas fa-user-cog';
                                    $roleName = 'Auxiliar';
                                } elseif ($rol == 'coordinador' || $usuario->num_doc == '2') {
                                    $roleClass = 'role-coordinador';
                                    $roleIcon = 'fas fa-user-tie';
                                    $roleName = 'Coordinador';
                                }
                                ?>
                                <span class="role-badge <?= $roleClass ?>">
                                    <i class="<?= $roleIcon ?>"></i> <?= $roleName ?>
                                </span>
                                <div class="users-actions">
                                    <a href="/RMIE/app/controllers/UserController.php?accion=edit&id=<?= urlencode($usuario->num_doc) ?>" 
                                       class="btn btn-sm btn-modern btn-warning-modern" 
                                       title="Editar usuario">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ((strtolower($usuario->rol ?? '') !== 'admin' || ($_SESSION['rol'] ?? '') === 'admin') && $_SESSION['rol'] !== 'coordinador'): ?>
                                    <a href="/RMIE/app/controllers/UserController.php?accion=delete&id=<?= urlencode($usuario->num_doc) ?>" 
                                       class="btn btn-sm btn-modern btn-danger-modern" 
                                       title="Eliminar usuario"
                                       onclick="return confirm('¿Está seguro de eliminar el usuario \'<?= addslashes($usuario->nombres ?? 'Usuario') ?>\'?\n\nEsta acción no se puede deshacer.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #fff;">
                        <i class="fas fa-users fa-5x mb-4" style="opacity: 0.5;"></i>
                        <h3 style="font-size: 1.8rem; margin-bottom: 15px;">No hay usuarios disponibles</h3>
                        <p style="font-size: 1.1rem; opacity: 0.8; margin-bottom: 25px;">No se encontraron usuarios que coincidan con los filtros aplicados.</p>
                        <a href="/RMIE/app/controllers/UserController.php?accion=create" class="btn btn-modern btn-success-modern">
                            <i class="fas fa-user-plus"></i> Crear Primer Usuario
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vista de Tabla (oculta por defecto) -->
            <div id="tableUsuariosView" class="table-responsive" style="display: none;">>
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Usuario</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-user-tag"></i> Rol</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha Registro</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($usuarios) && is_array($usuarios) && !empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($usuario->num_doc) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-icon">
                                            <?= strtoupper(substr($usuario->nombres ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($usuario->nombres ?? 'Sin nombre') ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card"></i> Doc: <?= htmlspecialchars($usuario->num_doc ?? 'N/A') ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($usuario->correo)): ?>
                                        <div class="contact-info">
                                            <i class="fas fa-envelope"></i> <?= htmlspecialchars($usuario->correo) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin email
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $rol = trim(strtolower($usuario->rol ?? 'empleado'));
                                    
                                    // Lógica de roles mejorada
                                    $roleClass = 'role-empleado';
                                    $roleIcon = 'fas fa-user';
                                    $roleName = 'Empleado';
                                    
                                    if ($rol == 'admin' || $usuario->num_doc == '1') {
                                        $roleClass = 'role-admin';
                                        $roleIcon = 'fas fa-user-shield';
                                        $roleName = 'Admin';
                                    } elseif ($usuario->num_doc == '123456789') {
                                        // Usuario auxiliar específico
                                        $roleClass = 'role-auxiliar';
                                        $roleIcon = 'fas fa-user-cog';
                                        $roleName = 'Auxiliar';
                                    } elseif ($rol == 'coordinador' || $usuario->num_doc == '2') {
                                        $roleClass = 'role-coordinador';
                                        $roleIcon = 'fas fa-user-tie';
                                        $roleName = 'Coordinador';
                                    }
                                    ?>
                                    <span class="role-badge <?= $roleClass ?>">
                                        <i class="<?= $roleIcon ?>"></i> <?= $roleName ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    // Estado fijo ya que no existe en modelo
                                    $estado = 'activo';
                                    $badgeClass = '';
                                    $iconClass = '';
                                    
                                    switch ($estado) {
                                        case 'activo':
                                            $badgeClass = 'badge-success';
                                            $iconClass = 'fas fa-check-circle';
                                            break;
                                        case 'inactivo':
                                            $badgeClass = 'badge-danger';
                                            $iconClass = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $badgeClass = 'badge-secondary';
                                            $iconClass = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <span class="badge badge-modern <?= $badgeClass ?>">
                                        <i class="<?= $iconClass ?>"></i> <?= ucfirst($estado) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <i class="fas fa-calendar text-info"></i>
                                        <strong><?= date('d/m/Y', strtotime($usuario->fecha_creacion ?? 'now')) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($usuario->fecha_creacion ?? 'now')) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/UserController.php?accion=edit&id=<?= urlencode($usuario->num_doc) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar usuario">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ((strtolower($usuario->rol ?? '') !== 'admin' || ($_SESSION['rol'] ?? '') === 'admin') && $_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/UserController.php?accion=delete&id=<?= urlencode($usuario->num_doc) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar usuario"
                                           onclick="return confirm('¿Está seguro de eliminar el usuario \'<?= addslashes($usuario->nombres ?? 'Usuario') ?>\'?\n\nEsta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <h5>No hay usuarios disponibles</h5>
                                        <p>No se encontraron usuarios que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/UserController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-user-plus"></i> Crear Primer Usuario
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Indicador de scroll para móviles -->
            <div class="scroll-hint d-block d-md-none">
                <i class="fas fa-hand-point-left"></i> Desliza para ver más columnas <i class="fas fa-hand-point-right"></i>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// Capturar promesas rechazadas
window.addEventListener('unhandledrejection', function(e) { console.log('Promise Error:', e.reason); e.preventDefault(); });

// Debug mode - capturar errores JavaScript
window.addEventListener('error', function(e) { console.log('JS Error:', e.message, 'at', e.filename + ':' + e.lineno); });

        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
            window.location.href = '/RMIE/app/controllers/UserController.php?accion=index';
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-modern');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Toggle de vista Tabla / Tarjetas para Usuarios
        function toggleUsuariosView(view) {
            try {
                const cardsView = document.getElementById('cardsUsuariosView');
                const tableView = document.getElementById('tableUsuariosView');
                const btnCards = document.getElementById('btnUsuariosCards');
                const btnTable = document.getElementById('btnUsuariosTable');

                if (!cardsView || !tableView || !btnCards || !btnTable) return;

                if (view === 'cards') {
                    cardsView.style.display = 'grid';
                    tableView.style.display = 'none';
                    btnCards.classList.add('active');
                    btnTable.classList.remove('active');
                    localStorage.setItem('usuariosView', 'cards');
                } else {
                    cardsView.style.display = 'none';
                    tableView.style.display = 'block';
                    btnTable.classList.add('active');
                    btnCards.classList.remove('active');
                    localStorage.setItem('usuariosView', 'table');
                }
            } catch (err) {
                console.log('toggleUsuariosView error:', err);
            }
        }

        // Inicializar vista según preferencia guardada
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const savedView = localStorage.getItem('usuariosView') || 'cards';
                toggleUsuariosView(savedView);
            } catch (err) {
                console.log('DOMContentLoaded usuarios error:', err);
                toggleUsuariosView('cards'); // Fallback a tarjetas
            }
        });


        // Efectos adicionales para la tabla
        document.querySelectorAll('.table-modern tbody tr').forEach(function(row) {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
                this.style.zIndex = '10';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.zIndex = '1';
            });
        });
    </script>
</body>
</html>