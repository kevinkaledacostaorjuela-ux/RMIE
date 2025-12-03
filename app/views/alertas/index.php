<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Limpiar mensajes de sesión
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alertas - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <script src="/RMIE/public/js/selenium-messages.js"></script>
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

        .btn-modern {
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-modern:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.3);
        }

        .btn-primary-modern {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(135deg, #89a7b1 0%, #b4c5cc 100%);
            color: white;
            border: none;
        }

        .btn-secondary-modern:hover {
            background: linear-gradient(135deg, #b4c5cc 0%, #89a7b1 100%);
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);
            color: white;
            border: none;
        }

        .btn-warning-modern:hover {
            background: linear-gradient(135deg, #a6c1ee 0%, #fbc2eb 100%);
        }

        .btn-danger-modern {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
            border: none;
        }

        .btn-danger-modern:hover {
            background: linear-gradient(45deg, #ee5a52, #ff6b6b);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-group {
            display: flex;
            gap: 0;
            justify-content: center;
            align-items: center;
        }

        .btn-group .btn {
            flex: 0 0 auto;
            min-width: 42px;
            max-width: 42px;
            height: 42px;
            padding: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-radius: 10px 0 0 10px;
        }

        .btn-group .btn:last-child {
            border-radius: 0 10px 10px 0;
        }

        .btn-sm {
            font-size: 14px;
        }

        .btn-sm i {
            font-size: 16px;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 5px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
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

        .badge-modern {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #1a1a1a; /* Mejor contraste para texto */
            text-shadow: none;
        }

        .badge-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: #0b2438; /* azul oscuro para contraste */
        }

        .badge-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: #3a0d0d; /* marrón oscuro para contraste */
        }

        .badge-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: #300000; /* rojo muy oscuro para contraste */
        }

        .badge-stock-bajo {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .badge-vencimiento {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.3);
        }

        /* Estilos específicos para tarjetas de alertas */
        .alerts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            padding: 0;
        }

        .alerts-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .alerts-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.08);
        }

        .alerts-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            background-size: 200% 100%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 0%; }
            50% { background-position: 100% 0%; }
        }

        .alerts-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .alerts-card-title {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alerts-card-id {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .alerts-card-icon {
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .alerts-card:hover .alerts-card-icon {
            color: #fff;
            transform: scale(1.1);
        }

        .alerts-card-body {
            margin-bottom: 20px;
        }

        .alerts-card-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .alerts-card-field {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .alerts-card-field-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .alerts-card-field-value {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .alerts-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .alerts-card-status {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .alerts-card-actions {
            display: flex;
            gap: 8px;
        }

        .alerts-card-actions .btn {
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .alerts-card-actions .btn:hover {
            transform: scale(1.1);
        }

        .alerts-card-date {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 16px;
            margin: 16px 0;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .alerts-card-date-main {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .alerts-card-date-sub {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        .view-toggle {
            display: flex;
            gap: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 4px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .view-toggle .btn {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            transition: all 0.3s ease;
            background: transparent;
            color: rgba(255, 255, 255, 0.7);
        }

        .view-toggle .btn.active {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .view-toggle .btn:not(.active):hover {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }

        .alert-modern {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            padding: 15px 20px;
            font-weight: 500;
        }

        .alert-success-modern {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.4);
        }

        .alert-danger-modern {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid rgba(231, 76, 60, 0.4);
        }        /* Scroll horizontal para móviles - Alertas */
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
                min-width: 1220px !important; /* Ancho mínimo para 8 columnas */
                margin-bottom: 0;
                width: 1220px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para alertas (8 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 70px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 170px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 180px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 150px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 130px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 180px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 120px; }
            
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
    <div class="dashboard-container" id="alertasMainContent">
        <h1 class="page-title">
            <i class="fas fa-exclamation-triangle"></i> Gestión de Alertas
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
                <div class="stat-number"><?php echo $estadisticas['total']; ?></div>
                <div class="stat-label">Total Alertas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['vencidas']; ?></div>
                <div class="stat-label">Vencidas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['proximas']; ?></div>
                <div class="stat-label">Próximas (30 días)</div>
            </div>
        </div>        <!-- Filtros -->
        <div class="filters-container">
            <div class="filters-inner">
                <form method="GET" action="/RMIE/app/controllers/AlertController.php" id="filterForm" 
                      style="background: white; padding: 2.5rem 3.5rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto;">
                    <input type="hidden" name="accion" value="index">
                    
                    <div class="filtros-alertas-flex" style="display: flex; flex-wrap: wrap; gap: 1.5rem 2.5rem; justify-content: center; align-items: end;">
                        <div class="filtro-alerta-item" style="min-width: 210px; max-width: 260px; flex: 1 1 210px;">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-bell"></i> Tipo
                            </label>
                            <select name="tipo" class="form-select" style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;">
                                <option value="">Todos</option>
                                <?php if (isset($tipos_disponibles) && is_array($tipos_disponibles) && count($tipos_disponibles) > 0): ?>
                                    <?php foreach ($tipos_disponibles as $tipo): ?>
                                        <option value="<?= htmlspecialchars($tipo) ?>" <?= (isset($_GET['tipo']) && $_GET['tipo'] == $tipo) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tipo) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="stock" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'stock') ? 'selected' : '' ?>>Stock Bajo</option>
                                    <option value="expiration" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'expiration') ? 'selected' : '' ?>>Vencimiento</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="filtro-alerta-item" style="min-width: 210px; max-width: 260px; flex: 1 1 210px;">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-exclamation-triangle"></i> Prioridad
                            </label>
                            <select name="prioridad" class="form-select" style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;">
                                <option value="">Todas las prioridades</option>
                                <?php if (isset($prioridades_disponibles) && is_array($prioridades_disponibles) && count($prioridades_disponibles) > 0): ?>
                                    <?php foreach ($prioridades_disponibles as $prioridad): ?>
                                        <option value="<?= htmlspecialchars($prioridad) ?>" <?= ($_GET['prioridad'] ?? '') === $prioridad ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($prioridad) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="alta" <?= ($_GET['prioridad'] ?? '') === 'alta' ? 'selected' : '' ?>>Alta</option>
                                    <option value="media" <?= ($_GET['prioridad'] ?? '') === 'media' ? 'selected' : '' ?>>Media</option>
                                    <option value="baja" <?= ($_GET['prioridad'] ?? '') === 'baja' ? 'selected' : '' ?>>Baja</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="filtro-alerta-item" style="min-width: 210px; max-width: 260px; flex: 1 1 210px;">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-toggle-on"></i> Estado
                            </label>
                            <select name="estado" class="form-select" style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;">
                                <option value="">Todos los estados</option>
                                <?php if (isset($estados_disponibles) && is_array($estados_disponibles) && count($estados_disponibles) > 0): ?>
                                    <?php foreach ($estados_disponibles as $estado): ?>
                                        <option value="<?= htmlspecialchars($estado) ?>" <?= ($_GET['estado'] ?? '') === $estado ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($estado) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="Activo" <?= ($_GET['estado'] ?? '') === 'Activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="Vencida" <?= ($_GET['estado'] ?? '') === 'Vencida' ? 'selected' : '' ?>>Vencida</option>
                                    <option value="Crítica" <?= ($_GET['estado'] ?? '') === 'Crítica' ? 'selected' : '' ?>>Crítica</option>
                                    <option value="Próxima" <?= ($_GET['estado'] ?? '') === 'Próxima' ? 'selected' : '' ?>>Próxima</option>
                                    <option value="Normal" <?= ($_GET['estado'] ?? '') === 'Normal' ? 'selected' : '' ?>>Normal</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div style="display: flex; gap: 12px; align-items: end;">
                            <button type="submit" class="btn-modern-filter" style="background: #007bff; color: white; border: none; padding: 12px 32px; border-radius: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 1rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.2s;">
                                <i class="fas fa-search"></i> FILTRAR
                            </button>
                            <button type="button" class="btn-modern-clear" onclick="limpiarFiltros()" style="background: #ff5c7a; color: white; border: none; padding: 12px 32px; border-radius: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 1rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.2s;">
                                <i class="fas fa-times"></i> LIMPIAR
                            </button>
                        </div>
                    </div>
                    <style>
                    @media (max-width: 900px) {
                        .filtros-alertas-flex {
                            flex-direction: column !important;
                            align-items: stretch !important;
                        }
                        .filtro-alerta-item {
                            max-width: 100% !important;
                        }
                    }
                    </style>
                </form>
            </div>
        </div>
        

        <!-- Botones de acción -->
        <div class="mb-4 text-center">
            <a href="/RMIE/app/controllers/AlertController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nueva Alerta
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Vista selector: Tarjetas / Tabla -->
        <div class="d-flex justify-content-end mb-4">
            <div class="view-toggle">
                <button id="viewCardsBtnAlertas" class="btn">
                    <i class="fas fa-th-large me-2"></i> Tarjetas
                </button>
                <button id="viewTableBtnAlertas" class="btn active">
                    <i class="fas fa-table me-2"></i> Tabla
                </button>
            </div>
        </div>
        <!-- Cards container for alertas (moved after action buttons) -->
        <div id="cardsContainerAlertas" class="alerts-grid" style="display:none; margin-bottom:30px; position: relative;">
            <?php if (!empty($alertas) && is_array($alertas)): ?>
                <?php foreach ($alertas as $alerta): ?>
                    <?php
                        $fecha_actual = date('Y-m-d');
                        $fecha_caducidad = $alerta['fecha_caducidad'] ?? null;
                        $dias_restantes = $fecha_caducidad ? (strtotime($fecha_caducidad) - strtotime($fecha_actual)) / (60 * 60 * 24) : null;
                        
                        // Si existe un estado guardado en BD, usarlo. De lo contrario, calcularlo
                        if (!empty($alerta['estado'])) {
                            $estado = $alerta['estado'];
                            // Asignar clase y ícono según el estado
                            switch ($estado) {
                                case 'Vencida':
                                    $badge_class = 'badge-danger'; $icono = 'fas fa-times-circle';
                                    break;
                                case 'Crítica':
                                    $badge_class = 'badge-danger'; $icono = 'fas fa-exclamation-triangle';
                                    break;
                                case 'Próxima':
                                    $badge_class = 'badge-warning'; $icono = 'fas fa-exclamation-circle';
                                    break;
                                case 'Normal':
                                    $badge_class = 'badge-success'; $icono = 'fas fa-check-circle';
                                    break;
                                case 'Activo':
                                    $badge_class = 'badge-info'; $icono = 'fas fa-bell';
                                    break;
                                default:
                                    $badge_class = 'badge-secondary'; $icono = 'fas fa-question-circle';
                            }
                        } elseif ($dias_restantes !== null) {
                            // Si no hay estado en BD, calcularlo
                            if ($dias_restantes < 0) {
                                $estado = 'Vencida'; $badge_class = 'badge-danger'; $icono = 'fas fa-times-circle';
                            } elseif ($dias_restantes <= 7) {
                                $estado = 'Crítica'; $badge_class = 'badge-danger'; $icono = 'fas fa-exclamation-triangle';
                            } elseif ($dias_restantes <= 30) {
                                $estado = 'Próxima'; $badge_class = 'badge-warning'; $icono = 'fas fa-exclamation-circle';
                            } else {
                                $estado = 'Normal'; $badge_class = 'badge-success'; $icono = 'fas fa-check-circle';
                            }
                        } else {
                            $estado = 'N/A'; $badge_class = 'badge-secondary'; $icono = 'fas fa-question-circle';
                        }
                        
                        $tipo = $alerta['tipo_alerta'] ?? 'stock_bajo';
                    ?>
                    <div class="alerts-card">
                        <div class="alerts-card-header">
                            <div>
                                <h3 class="alerts-card-title">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Alerta #<?= htmlspecialchars($alerta['id_alertas'] ?? '') ?>
                                </h3>
                                <div class="alerts-card-id">ID: <?= htmlspecialchars($alerta['id_alertas'] ?? '') ?></div>
                            </div>
                            <div class="alerts-card-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>

                        <div class="alerts-card-body">
                            <div class="alerts-card-info">
                                <div class="alerts-card-field">
                                    <div class="alerts-card-field-label">
                                        <i class="fas fa-box"></i> Producto
                                    </div>
                                    <div class="alerts-card-field-value">
                                        <?= htmlspecialchars($alerta['producto_nombre'] ?? 'Producto #' . $alerta['id_productos']) ?>
                                    </div>
                                </div>
                                
                                <div class="alerts-card-field">
                                    <div class="alerts-card-field-label">
                                        <i class="fas fa-truck"></i> Proveedor
                                    </div>
                                    <div class="alerts-card-field-value">
                                        <?= htmlspecialchars($alerta['proveedor_nombre'] ?? 'Proveedor #' . $alerta['id_proveedores']) ?>
                                    </div>
                                </div>
                                
                                <div class="alerts-card-field">
                                    <div class="alerts-card-field-label">
                                        <i class="fas fa-tag"></i> Tipo
                                    </div>
                                    <div class="alerts-card-field-value">
                                        <?php if ($tipo === 'stock' || $tipo === 'stock_bajo'): ?>
                                            <span class="badge badge-stock-bajo">
                                                <i class="fas fa-boxes"></i> Stock Bajo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-vencimiento">
                                                <i class="fas fa-calendar-times"></i> Vencimiento
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="alerts-card-field">
                                    <div class="alerts-card-field-label">
                                        <i class="fas fa-sort-numeric-up"></i> Cantidad Mín.
                                    </div>
                                    <div class="alerts-card-field-value">
                                        <span class="badge badge-modern badge-warning">
                                            <?= htmlspecialchars($alerta['cantidad_minima'] ?? 'N/A') ?> unidades
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($fecha_caducidad): ?>
                            <div class="alerts-card-date">
                                <div class="alerts-card-date-main">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= date('d/m/Y', strtotime($fecha_caducidad)) ?>
                                </div>
                                <div class="alerts-card-date-sub">
                                    <?php if ($dias_restantes !== null): ?>
                                        <?php if ($dias_restantes < 0): ?>
                                            Vencida hace <?= abs(round($dias_restantes)) ?> días
                                        <?php else: ?>
                                            <?= round($dias_restantes) ?> días restantes
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="alerts-card-footer">
                            <div class="alerts-card-status">
                                <span class="badge badge-modern <?= $badge_class ?>">
                                    <i class="<?= $icono ?>"></i> <?= $estado ?>
                                </span>
                            </div>
                            
                            <div class="alerts-card-actions">
                                <a href="/RMIE/app/controllers/AlertController.php?accion=edit&id=<?= urlencode($alerta['id_alertas'] ?? '') ?>" 
                                   class="btn btn-modern btn-warning-modern" 
                                   title="Editar alerta">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                <button type="button" 
                                       class="btn btn-modern btn-danger-modern" 
                                       title="Eliminar alerta"
                                       onclick="confirmarEliminacion(<?= htmlspecialchars($alerta['id_alertas'] ?? 0) ?>, '<?= htmlspecialchars($alerta['producto_nombre'] ?? 'Producto', ENT_QUOTES) ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alerts-card" style="text-align: center; padding: 40px;">
                    <div class="alerts-card-icon" style="font-size: 4rem; margin-bottom: 20px;">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 style="color: #fff; margin-bottom: 10px;">No hay alertas disponibles</h3>
                    <p style="color: rgba(255,255,255,0.7); margin-bottom: 20px;">No se encontraron alertas que coincidan con los filtros aplicados.</p>
                    <a href="/RMIE/app/controllers/AlertController.php?accion=create" class="btn btn-modern btn-success-modern">
                        <i class="fas fa-plus"></i> Crear Primera Alerta
                    </a>
                </div>
            <?php endif; ?>
        </div>
        

        <!-- Tabla de Alertas -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-bell"></i> Tipo</th>
                            <th><i class="fas fa-box"></i> Producto</th>
                            <th><i class="fas fa-truck"></i> Proveedor</th>
                            <th><i class="fas fa-sort-numeric-up"></i> Cantidad Mín.</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha Caducidad</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($alertas)): ?>
                            <?php foreach ($alertas as $alerta): 
                                $fecha_actual = date('Y-m-d');
                                $fecha_caducidad = $alerta['fecha_caducidad'];
                                $dias_restantes = (strtotime($fecha_caducidad) - strtotime($fecha_actual)) / (60 * 60 * 24);
                                
                                // Determinar estado y badge: primero verificar si existe en BD
                                if (!empty($alerta['estado'])) {
                                    $estado = $alerta['estado'];
                                    // Asignar clase y ícono según el estado
                                    switch ($estado) {
                                        case 'Vencida':
                                            $badge_class = 'badge-danger'; $icono = 'fas fa-times-circle';
                                            break;
                                        case 'Crítica':
                                            $badge_class = 'badge-danger'; $icono = 'fas fa-exclamation-triangle';
                                            break;
                                        case 'Próxima':
                                            $badge_class = 'badge-warning'; $icono = 'fas fa-exclamation-circle';
                                            break;
                                        case 'Normal':
                                            $badge_class = 'badge-success'; $icono = 'fas fa-check-circle';
                                            break;
                                        case 'Activo':
                                            $badge_class = 'badge-info'; $icono = 'fas fa-bell';
                                            break;
                                        default:
                                            $badge_class = 'badge-secondary'; $icono = 'fas fa-question-circle';
                                    }
                                } else {
                                    // Si no hay estado en BD, calcularlo basado en la fecha
                                    if ($dias_restantes < 0) {
                                        $estado = 'Vencida';
                                        $badge_class = 'badge-danger';
                                        $icono = 'fas fa-times-circle';
                                    } elseif ($dias_restantes <= 7) {
                                        $estado = 'Crítica';
                                        $badge_class = 'badge-danger';
                                        $icono = 'fas fa-exclamation-triangle';
                                    } elseif ($dias_restantes <= 30) {
                                        $estado = 'Próxima';
                                        $badge_class = 'badge-warning';
                                        $icono = 'fas fa-exclamation-circle';
                                    } else {
                                        $estado = 'Normal';
                                        $badge_class = 'badge-success';
                                        $icono = 'fas fa-check-circle';
                                    }
                                }
                            ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($alerta['id_alertas']) ?></strong>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        // Determinar tipo de alerta desde la BD
                                        $tipo = $alerta['tipo_alerta'] ?? 'stock_bajo';
                                        if ($tipo === 'stock' || $tipo === 'stock_bajo'): 
                                    ?>
                                        <span class="badge badge-stock-bajo">
                                            <i class="fas fa-boxes"></i> Stock Bajo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-vencimiento">
                                            <i class="fas fa-calendar-times"></i> Vencimiento
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-box text-info"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($alerta['producto_nombre'] ?? 'Producto #' . $alerta['id_productos']) ?></strong>
                                            <br>
                                            <small class="text-muted">ID: <?= $alerta['id_productos'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-user text-warning"></i>
                                        </div>
                                        <div>
                                            <?= htmlspecialchars($alerta['proveedor_nombre'] ?? 'Proveedor #' . $alerta['id_proveedores']) ?>
                                            <br>
                                            <small class="text-muted">ID: <?= $alerta['id_proveedores'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-warning">
                                        <?= htmlspecialchars($alerta['cantidad_minima'] ?? 'N/A') ?> unidades
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong><?= date('d/m/Y', strtotime($fecha_caducidad)) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?php if ($dias_restantes < 0): ?>
                                                Vencida hace <?= abs(round($dias_restantes)) ?> días
                                            <?php else: ?>
                                                <?= round($dias_restantes) ?> días restantes
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-modern <?= $badge_class ?>">
                                        <i class="<?= $icono ?>"></i> <?= $estado ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/AlertController.php?accion=edit&id=<?= $alerta['id_alertas'] ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar alerta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <button type="button"
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar alerta"
                                           onclick="confirmarEliminacion(<?= $alerta['id_alertas'] ?>, '<?= htmlspecialchars($alerta['producto_nombre'] ?? 'Producto #' . $alerta['id_productos'], ENT_QUOTES) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No hay alertas disponibles</h5>
                                        <p>No se encontraron alertas que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/AlertController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primera Alerta
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar ventana emergente para crear alertas si hay próximas o vencidas
        document.addEventListener('DOMContentLoaded', function() {
            try {
                var modalEl = document.getElementById('crearAlertasModal');
                var showModal = <?php echo (($hay_stock_bajo ?? false) || ($hay_vencidas ?? false) || ($hay_proximas ?? false)) ? 'true' : 'false'; ?>;
                if (modalEl && showModal) {
                    modalEl.removeAttribute('aria-hidden');
                    modalEl.setAttribute('role', 'dialog');
                    modalEl.setAttribute('aria-modal', 'true');
                    // Observador para evitar que aria-hidden vuelva a true mientras está visible
                    var observer = new MutationObserver(function(mutations){
                        mutations.forEach(function(m){
                            if (m.attributeName === 'aria-hidden') {
                                var isVisible = modalEl.classList.contains('show') || modalEl.style.display === 'block';
                                var ah = modalEl.getAttribute('aria-hidden');
                                if (isVisible && ah === 'true') {
                                    modalEl.setAttribute('aria-hidden','false');
                                }
                            }
                        });
                    });
                    observer.observe(modalEl, { attributes: true, attributeFilter: ['aria-hidden','class','style']});
                    setTimeout(function(){
                        var modal = new bootstrap.Modal(modalEl, {keyboard: true});
                        // Asegurar que aria-hidden se limpie tras mostrar
                        modalEl.addEventListener('shown.bs.modal', function(){
                            modalEl.setAttribute('aria-hidden','false');
                            // Aplicar inert al contenido principal para impedir foco fuera del modal
                            try {
                                var main = document.getElementById('alertasMainContent');
                                if (main) { main.setAttribute('inert',''); }
                            } catch(e) {}
                        }, { once: true });
                        // Antes de ocultar, mover el foco fuera del modal para evitar advertencia
                        modalEl.addEventListener('hide.bs.modal', function(){
                            try {
                                if (modalEl.contains(document.activeElement)) {
                                    document.activeElement.blur();
                                }
                                var focusTarget = document.querySelector('.page-title') || document.body;
                                if (focusTarget) {
                                    // Asegurar que sea enfocables temporalmente
                                    if (!focusTarget.hasAttribute('tabindex')) {
                                        focusTarget.setAttribute('tabindex', '-1');
                                    }
                                    focusTarget.focus({ preventScroll: true });
                                }
                            } catch(e) { console.warn('No se pudo reasignar el foco al cerrar el modal', e); }
                            // Detener observador al cerrar
                            try { observer.disconnect(); } catch(e){}
                            // Quitar inert del contenido principal
                            try {
                                var main = document.getElementById('alertasMainContent');
                                if (main) { main.removeAttribute('inert'); }
                            } catch(e) {}
                        });
                        modal.show();
                    }, 50);
                }
            } catch (e) {
                console.warn('No se pudo mostrar la ventana emergente de alertas', e);
            }
        });

        function limpiarFiltros() {
            // Redirige siempre a la URL base del listado de alertas
            window.location.href = '/RMIE/app/controllers/AlertController.php?accion=index';
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

        // Función para confirmar eliminación de alertas
        function confirmarEliminacion(idAlerta, nombreProducto) {
            if (confirmAction("acción")) {
                // Crear formulario dinámico para enviar POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/RMIE/app/controllers/AlertController.php?accion=delete&id=' + idAlerta;
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'confirmar_eliminar';
                input.value = '1';
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // View toggle: Tarjetas / Tabla (alertas)
        (function(){
            const cardsBtn = document.getElementById('viewCardsBtnAlertas');
            const tableBtn = document.getElementById('viewTableBtnAlertas');
            const cardsContainer = document.getElementById('cardsContainerAlertas');
            const tableContainer = document.querySelector('.table-container');

            function updateButtonStates(activeView) {
                if (cardsBtn && tableBtn) {
                    cardsBtn.classList.toggle('active', activeView === 'cards');
                    tableBtn.classList.toggle('active', activeView === 'table');
                }
            }

            function setView(view){
                if (view === 'cards'){
                    if (cardsContainer) {
                        cardsContainer.style.display = 'grid';
                        cardsContainer.style.animation = 'fadeInUp 0.5s ease';
                    }
                    if (tableContainer) tableContainer.style.display = 'none';
                    updateButtonStates('cards');
                } else {
                    if (cardsContainer) cardsContainer.style.display = 'none';
                    if (tableContainer) {
                        tableContainer.style.display = 'block';
                        tableContainer.style.animation = 'fadeInUp 0.5s ease';
                    }
                    updateButtonStates('table');
                }
                
                try { 
                    localStorage.setItem('alertasView', view); 
                } catch(e) { 
                    console.warn('No se pudo guardar la preferencia de vista:', e); 
                }
            }

            if (cardsBtn && tableBtn){
                cardsBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    setView('cards');
                });
                
                tableBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    setView('table');
                });
                
                // Cargar preferencia guardada
                let savedView = 'table';
                try { 
                    savedView = localStorage.getItem('alertasView') || 'table';
                } catch(e) { 
                    console.warn('No se pudo cargar la preferencia de vista:', e);
                }
                
                setView(savedView);
            }

            // Animaciones CSS para transiciones suaves
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            `;
            document.head.appendChild(style);
        })();
    </script>

        <!-- Modal: Crear Alertas -->
        <div class="modal fade" id="crearAlertasModal" tabindex="-1" aria-labelledby="crearAlertasModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                    <div class="modal-header" style="background: linear-gradient(45deg, #667eea, #764ba2); color:#fff;">
                        <h5 class="modal-title" id="crearAlertasModalLabel"><i class="fas fa-bell"></i> Alertas pendientes</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="background: rgba(255,255,255,0.95);">
                        <p style="color:#2c3e50;">
                            <?php if (($hay_vencidas ?? false) && ($hay_stock_bajo ?? false) && ($hay_proximas ?? false)): ?>
                                Se detectaron <strong>productos vencidos</strong>, <strong>próximos a vencer</strong> y <strong>stock bajo</strong>.
                            <?php elseif (($hay_vencidas ?? false) && ($hay_stock_bajo ?? false)): ?>
                                Se detectaron <strong>productos vencidos</strong> y <strong>stock bajo</strong>.
                            <?php elseif (($hay_vencidas ?? false) && ($hay_proximas ?? false)): ?>
                                Se detectaron <strong>productos vencidos</strong> y <strong>próximos a vencer</strong>.
                            <?php elseif (($hay_stock_bajo ?? false) && ($hay_proximas ?? false)): ?>
                                Se detectaron <strong>stock bajo</strong> y <strong>próximos a vencer</strong>.
                            <?php elseif ($hay_vencidas ?? false): ?>
                                Se detectaron <strong>productos vencidos</strong>.
                            <?php elseif ($hay_proximas ?? false): ?>
                                Se detectaron productos <strong>próximos a vencer</strong>.
                            <?php elseif ($hay_stock_bajo ?? false): ?>
                                Se detectó <strong>stock bajo</strong>.
                            <?php else: ?>
                                No hay alertas críticas actualmente.
                            <?php endif; ?>
                            ¿Deseas crear una alerta ahora?
                        </p>
                        <div class="d-flex gap-2">
                            <a href="/RMIE/app/controllers/AlertController.php?accion=create&tipo=stock" class="btn btn-modern btn-success-modern" style="flex:1;">
                                <i class="fas fa-boxes"></i> Stock Bajo
                            </a>
                            <a href="/RMIE/app/controllers/AlertController.php?accion=create&tipo=expiration" class="btn btn-modern btn-warning-modern" style="flex:1;">
                                <i class="fas fa-calendar-times"></i> Vencimiento
                            </a>
                            <?php if ($hay_vencidas ?? false): ?>
                            <a href="/RMIE/app/controllers/AlertController.php?accion=index&estado=Vencida" class="btn btn-modern btn-danger-modern" style="flex:1; min-width: 180px;">
                                <i class="fas fa-filter"></i> Ver Vencidas
                            </a>
                            <?php endif; ?>
              
                        </div>
                    </div>
                    <div class="modal-footer" style="background: rgba(255,255,255,0.95);">
                        <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Ahora no</button>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>