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

// Calcular estadísticas
$totalProveedores = count($proveedores ?? []);
$proveedoresActivos = 0;
$proveedoresInactivos = 0;
$proveedoresPendientes = 0;
$proveedoresSinContacto = 0;
$proveedoresConEmail = 0;
$proveedoresConContacto = 0;

if (isset($proveedores) && is_array($proveedores)) {
    foreach ($proveedores as $prov) {
        switch (strtolower($prov->estado ?? 'pendiente')) {
            case 'activo':
                $proveedoresActivos++;
                break;
            case 'inactivo':
                $proveedoresInactivos++;
                break;
            case 'pendiente':
                $proveedoresPendientes++;
                break;
        }
        
        if (!empty($prov->cel_proveedor)) {
            $proveedoresConContacto++;
        }
        
        if (!empty($prov->correo)) {
            $proveedoresConEmail++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proveedores - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
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
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .table-header h3 {
            color: #19269cff;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        /* Diseño de Tarjetas para proveedores */
        .providers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .provider-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .provider-card::before {
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

        .provider-card:hover::before {
            transform: scaleX(1);
        }

        .provider-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .provider-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .provider-card-icon {
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
        }

        .provider-card-title {
            flex: 1;
        }

        .provider-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .provider-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .provider-card-body {
            margin-bottom: 20px;
        }

        .provider-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .provider-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .provider-info-icon {
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

        .provider-info-content {
            flex: 1;
        }

        .provider-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .provider-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
        }

        .provider-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .provider-actions {
            display: flex;
            gap: 8px;
        }

        .table-modern {
            background: transparent;
            color: #fff;
            display: none;
            width: 100%;
            margin-bottom: 0;
        }

        .table-modern th {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: none;
            padding: 18px 15px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-modern td {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 15px;
            vertical-align: middle;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.05);
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .table-modern tbody tr:hover td {
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
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
        }

        .view-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #fff;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .empty-state p {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 25px;
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

        .provider-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.2rem;
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

        .contact-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            color: #192d86ff !important;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
        }

        .contact-info i {
            color: rgba(79, 172, 254, 1);
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive::-webkit-scrollbar {
            height: 10px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Responsive design */
        @media (max-width: 992px) {
            .providers-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .providers-grid {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .view-toggle {
                width: 100%;
                justify-content: center;
            }

            .table-modern {
                font-size: 0.85rem;
            }

            .table-modern th,
            .table-modern td {
                padding: 12px 8px;
                white-space: nowrap;
            }

            .contact-info {
                font-size: 0.85rem;
                padding: 6px 10px;
            }

            .provider-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .badge-modern {
                padding: 6px 12px;
                font-size: 0.75rem;
            }

            .btn-modern {
                padding: 6px 12px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-truck"></i> Gestión de Proveedores
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
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-number"><?php echo $totalProveedores; ?></div>
                <div class="stat-label">Total Proveedores</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $proveedoresActivos; ?></div>
                <div class="stat-label">Activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number"><?php echo $proveedoresInactivos; ?></div>
                <div class="stat-label">Inactivos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $proveedoresPendientes; ?></div>
                <div class="stat-label">Pendientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="stat-number"><?php echo $proveedoresConContacto; ?></div>
                <div class="stat-label">Con Teléfono</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-number"><?php echo $proveedoresConEmail; ?></div>
                <div class="stat-label">Con Email</div>
            </div>
        </div>        <!-- Filtros -->
        <style>
        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }        .filters-row {
            display: grid;
            grid-template-columns: 1fr 1fr 200px;
            gap: 18px 32px;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 140px;
            max-width: 320px;
        }
        .filter-label {
            background: rgba(8, 8, 8, 0.06);
            color: hsla(207, 85%, 46%, 0.95);
            padding: 6px 10px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        .filter-input, .filter-select {
            background: #fff;
            color: #333;
            border-radius: 12px;
            padding: 10px 14px;
            border: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            min-width: 160px;
        }
        .filter-input::placeholder { color: #333; }
        .filter-actions {
            grid-row: span 2;
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
            justify-content: center;
            padding-left: 20px;
        }
        .btn-pill {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            border: none;
            color: white;
            min-width: 120px;
        }
        .btn-pill i { margin-right: 8px; }
        .btn-pill-primary {
            background: linear-gradient(180deg,#1e90ff,#2a6df4);
        }
        .btn-pill-clear {
            background: linear-gradient(180deg,#ffb3c6,#ff7aa2);
            padding: 10px 10px;
            min-width: 120px;
        }        @media (max-width: 768px) {
            .filters-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .filter-actions { 
                grid-row: auto;
                flex-direction: row;
                justify-content: stretch;
                padding-left: 0;
                margin-top: 16px;
            }
            .btn-pill { flex: 1; }
        }
        .filter-title {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        </style>
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="/RMIE/app/controllers/ProviderController.php" id="filterForm">
                <input type="hidden" name="accion" value="index">
                <div class="filters-row">
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-truck"></i> Nombre</span>
                        <input type="text" name="nombre" class="filter-input" placeholder="Buscar proveedor..." value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                    </div>
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-envelope"></i> Email</span>
                        <input type="text" name="correo" class="filter-input" placeholder="Buscar por email..." value="<?= htmlspecialchars($_GET['correo'] ?? '') ?>">
                    </div>
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-mobile-alt"></i> Teléfono</span>
                        <input type="text" name="telefono" class="filter-input" placeholder="Buscar por teléfono..." value="<?= htmlspecialchars($_GET['telefono'] ?? '') ?>">
                    </div>
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-traffic-light"></i> Estado</span>
                        <select name="estado" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="activo" <?= isset($_GET['estado']) && $_GET['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= isset($_GET['estado']) && $_GET['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            <option value="pendiente" <?= isset($_GET['estado']) && $_GET['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn-pill btn-pill-primary">
                            <i class="fas fa-search"></i> FILTRAR
                        </button>
                        <button type="button" class="btn-pill btn-pill-clear" onclick="limpiarFiltros()">
                            <i class="fas fa-times"></i> LIMPIAR
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botones de acción -->
        <div class="mb-4 text-center">
            <a href="/RMIE/app/controllers/ProviderController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nuevo Proveedor
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Contenedor de Proveedores con Toggle de Vista -->
        <div class="table-container">
            <div class="table-header">
                <h3><i class="fas fa-truck-loading"></i> Lista de Proveedores (<?= $totalProveedores ?>)</h3>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="toggleView('cards')" id="btnCards">
                        <i class="fas fa-th-large"></i> Tarjetas
                    </button>
                    <button class="view-toggle-btn" onclick="toggleView('table')" id="btnTable">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                </div>
            </div>

            <!-- Vista de Tarjetas (por defecto) -->
            <div id="cardsView" class="providers-grid">
                <?php if (isset($proveedores) && is_array($proveedores) && !empty($proveedores)): ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <div class="provider-card">
                            <div class="provider-card-header">
                                <div class="provider-card-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="provider-card-title">
                                    <h4><?= htmlspecialchars($proveedor->nombre_distribuidor) ?></h4>
                                    <p><i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($proveedor->id_proveedores) ?></p>
                                </div>
                            </div>
                            
                            <div class="provider-card-body">
                                <?php if (!empty($proveedor->cel_proveedor)): ?>
                                <div class="provider-info-item">
                                    <div class="provider-info-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="provider-info-content">
                                        <div class="provider-info-label">Teléfono</div>
                                        <div class="provider-info-value"><?= htmlspecialchars($proveedor->cel_proveedor) ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($proveedor->correo)): ?>
                                <div class="provider-info-item">
                                    <div class="provider-info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="provider-info-content">
                                        <div class="provider-info-label">Correo Electrónico</div>
                                        <div class="provider-info-value"><?= htmlspecialchars($proveedor->correo) ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($proveedor->ubicacion)): ?>
                                <div class="provider-info-item">
                                    <div class="provider-info-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="provider-info-content">
                                        <div class="provider-info-label">Dirección</div>
                                        <div class="provider-info-value"><?= htmlspecialchars($proveedor->ubicacion) ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Mostrar productos del proveedor -->
                                <div class="provider-info-item">
                                    <div class="provider-info-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="provider-info-content">
                                        <div class="provider-info-label">Productos</div>
                                        <div class="provider-info-value">
                                            <?php if (isset($productosPorProveedor[$proveedor->id_proveedores]) && !empty($productosPorProveedor[$proveedor->id_proveedores])): ?>
                                                <?php
                                                $productos = $productosPorProveedor[$proveedor->id_proveedores];
                                                $nombresProductos = [];
                                                foreach ($productos as $producto) {
                                                    // Los productos ahora vienen como objetos directos
                                                    if (is_object($producto)) {
                                                        $nombresProductos[] = $producto->nombre;
                                                    }
                                                }
                                                $productosTexto = implode(', ', $nombresProductos);
                                                ?>
                                                <span title="<?= htmlspecialchars($productosTexto) ?>">
                                                    <?= htmlspecialchars(strlen($productosTexto) > 60 ? substr($productosTexto, 0, 60) . '...' : $productosTexto) ?>
                                                </span>
                                                <small class="text-muted d-block"><?= count($nombresProductos) ?> producto(s)</small>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-question"></i> Sin productos asignados
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="provider-card-footer">
                                <div>
                                    <?php
                                    $estado = strtolower($proveedor->estado ?? 'pendiente');
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
                                        case 'pendiente':
                                            $badgeClass = 'badge-warning';
                                            $iconClass = 'fas fa-clock';
                                            break;
                                        default:
                                            $badgeClass = 'badge-secondary';
                                            $iconClass = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <span class="badge badge-modern <?= $badgeClass ?>">
                                        <i class="<?= $iconClass ?>"></i> <?= ucfirst($estado) ?>
                                    </span>
                                </div>
                                <div class="provider-actions">
                                    <a href="/RMIE/app/controllers/ProviderController.php?accion=edit&id=<?= urlencode($proveedor->id_proveedores) ?>&t=<?= time() ?>" 
                                       class="btn btn-sm btn-modern btn-warning-modern" 
                                       data-controller="ProviderController"
                                       title="Editar proveedor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                    <a href="/RMIE/app/controllers/ProviderController.php?accion=delete&id=<?= urlencode($proveedor->id_proveedores) ?>&t=<?= time() ?>" 
                                       class="btn btn-sm btn-modern btn-danger-modern" 
                                       data-controller="ProviderController"
                                       data-id="<?= htmlspecialchars($proveedor->id_proveedores) ?>"
                                       data-nombre="<?= htmlspecialchars($proveedor->nombre_distribuidor) ?>"
                                       title="Eliminar proveedor: <?= htmlspecialchars($proveedor->nombre_distribuidor) ?>"
                                       onclick="console.log('🗑️ Eliminando proveedor ID: <?= $proveedor->id_proveedores ?>', this.href); return confirm('¿Está seguro de eliminar el proveedor \'<?= addslashes($proveedor->nombre_distribuidor) ?>\'?\n\nEsta acción no se puede deshacer.');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-truck"></i>
                        <h3>No hay proveedores disponibles</h3>
                        <p>No se encontraron proveedores que coincidan con los filtros aplicados.</p>
                        <a href="/RMIE/app/controllers/ProviderController.php?accion=create" class="btn btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Crear Primer Proveedor
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vista de Tabla (oculta por defecto) -->
            <div id="tableView" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-modern table-hover" style="display: table;">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-truck"></i> Proveedor</th>
                                <th><i class="fas fa-mobile-alt"></i> Teléfono</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                                <th><i class="fas fa-box"></i> Productos</th>
                                <th><i class="fas fa-traffic-light"></i> Estado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($proveedores) && is_array($proveedores) && !empty($proveedores)): ?>
                                <?php foreach ($proveedores as $proveedor): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($proveedor->id_proveedores) ?></strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="provider-icon">
                                                <i class="fas fa-truck"></i>
                                            </div>
                                            <div>
                                                <strong style="font-size: 1.05rem;"><?= htmlspecialchars($proveedor->nombre_distribuidor) ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($proveedor->cel_proveedor)): ?>
                                            <span class="contact-info">
                                                <i class="fas fa-mobile-alt"></i> <?= htmlspecialchars($proveedor->cel_proveedor) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern badge-secondary">
                                                <i class="fas fa-minus"></i> Sin teléfono
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($proveedor->correo)): ?>
                                            <span class="contact-info">
                                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($proveedor->correo) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern badge-secondary">
                                                <i class="fas fa-minus"></i> Sin email
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($proveedor->ubicacion)): ?>
                                            <span class="contact-info" title="<?= htmlspecialchars($proveedor->ubicacion) ?>">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?= htmlspecialchars(strlen($proveedor->ubicacion) > 30 ? substr($proveedor->ubicacion, 0, 30) . '...' : $proveedor->ubicacion) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern badge-secondary">
                                                <i class="fas fa-question"></i> Sin dirección
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($productosPorProveedor[$proveedor->id_proveedores]) && !empty($productosPorProveedor[$proveedor->id_proveedores])): ?>
                                            <?php
                                            $productos = $productosPorProveedor[$proveedor->id_proveedores];
                                            $nombresProductos = [];
                                            foreach ($productos as $producto) {
                                                // Verificar si es array con 'obj' o directamente un objeto
                                                if (is_array($producto) && isset($producto['obj'])) {
                                                    $nombresProductos[] = $producto['obj']->nombre;
                                                } elseif (is_object($producto)) {
                                                    $nombresProductos[] = $producto->nombre;
                                                }
                                            }
                                            $productosTexto = implode(', ', $nombresProductos);
                                            ?>
                                            <span class="contact-info" title="<?= htmlspecialchars($productosTexto) ?>">
                                                <i class="fas fa-box"></i> 
                                                <?= htmlspecialchars(strlen($productosTexto) > 40 ? substr($productosTexto, 0, 40) . '...' : $productosTexto) ?>
                                            </span>
                                            <small class="text-muted d-block"><?= count($nombresProductos) ?> producto(s)</small>
                                        <?php else: ?>
                                            <span class="badge badge-modern badge-secondary">
                                                <i class="fas fa-question"></i> Sin productos
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $estado = strtolower($proveedor->estado ?? 'pendiente');
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
                                            case 'pendiente':
                                                $badgeClass = 'badge-warning';
                                                $iconClass = 'fas fa-clock';
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
                                        <div class="btn-group" role="group">
                                            <a href="/RMIE/app/controllers/ProviderController.php?accion=edit&id=<?= urlencode($proveedor->id_proveedores) ?>&t=<?= time() ?>" 
                                               class="btn btn-sm btn-modern btn-warning-modern"
                                               data-controller="ProviderController" 
                                               title="Editar proveedor">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                            <a href="/RMIE/app/controllers/ProviderController.php?accion=delete&id=<?= urlencode($proveedor->id_proveedores) ?>&t=<?= time() ?>" 
                                               class="btn btn-sm btn-modern btn-danger-modern"
                                               data-controller="ProviderController"
                                               data-id="<?= htmlspecialchars($proveedor->id_proveedores) ?>"
                                               data-nombre="<?= htmlspecialchars($proveedor->nombre_distribuidor) ?>" 
                                               title="Eliminar proveedor"
                                               onclick="console.log('🗑️ Eliminando desde tabla ID: <?= $proveedor->id_proveedores ?>', this.href); return confirm('¿Está seguro de eliminar el proveedor \'<?= addslashes($proveedor->nombre_distribuidor) ?>\'?\n\nEsta acción no se puede deshacer.');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-truck"></i>
                                            <h3>No hay proveedores disponibles</h3>
                                            <p>No se encontraron proveedores que coincidan con los filtros aplicados.</p>
                                            <a href="/RMIE/app/controllers/ProviderController.php?accion=create" class="btn btn-modern btn-success-modern">
                                                <i class="fas fa-plus"></i> Crear Primer Proveedor
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle entre vista de tarjetas y tabla
        function toggleView(view) {
            const cardsView = document.getElementById('cardsView');
            const tableView = document.getElementById('tableView');
            const btnCards = document.getElementById('btnCards');
            const btnTable = document.getElementById('btnTable');
            
            if (view === 'cards') {
                cardsView.style.display = 'grid';
                tableView.style.display = 'none';
                btnCards.classList.add('active');
                btnTable.classList.remove('active');
                localStorage.setItem('proveedoresView', 'cards');
            } else {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                btnCards.classList.remove('active');
                btnTable.classList.add('active');
                localStorage.setItem('proveedoresView', 'table');
            }
        }
        
        // Restaurar vista guardada
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('proveedoresView') || 'cards';
            toggleView(savedView);
        });

        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
            window.location.href = '/RMIE/app/controllers/ProviderController.php?accion=index';
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

        // Confirmar eliminación con más detalles
        document.querySelectorAll('a[onclick*="confirm"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Obtener nombre del proveedor de forma segura
                let proveedorNombre = 'este proveedor';
                const tr = this.closest('tr');
                
                if (tr) {
                    const strongElement = tr.querySelector('td:nth-child(2) strong');
                    if (strongElement) {
                        proveedorNombre = strongElement.textContent;
                    }
                } else if (this.dataset.nombre) {
                    proveedorNombre = this.dataset.nombre;
                }
                
                if (confirm(`¿Está seguro de eliminar el proveedor "${proveedorNombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>