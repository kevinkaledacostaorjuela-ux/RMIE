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
$totalVentas = count($ventas ?? []);
$ventasCompletadas = 0;
$ventasPendientes = 0;
$ventasCanceladas = 0;
$ventasProcesando = 0;
$montoTotal = 0;
$montoMes = 0;

$fechaActual = date('Y-m');

if (isset($ventas) && is_array($ventas)) {
    foreach ($ventas as $venta) {
        $estado = strtolower($venta->estado ?? 'pendiente');
        switch ($estado) {
            case 'completada':
                $ventasCompletadas++;
                break;
            case 'pendiente':
                $ventasPendientes++;
                break;
            case 'cancelada':
                $ventasCanceladas++;
                break;
            case 'procesando':
                $ventasProcesando++;
                break;
        }
        $montoTotal += floatval($venta->total ?? 0);
        
        // Calcular monto del mes actual
        if (strpos($venta->fecha_venta ?? '', $fechaActual) === 0) {
            $montoMes += floatval($venta->total ?? 0);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <style>
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

        /* Estilos 'podificados' para los filtros: apariencia tipo 'pill' */
        .filters-row {
            display: flex;
            gap: 12px;
            align-items: center;
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
            background: #e20e0eff;
            color: #333;
            border-radius: 12px;
            padding: 10px 14px;
            border: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            min-width: 160px;
        }

        .filter-input::placeholder { color: #333; }

        .filter-actions {
            margin-left: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
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
        }

        @media (max-width: 768px) {
            .filter-actions { width: 100%; flex-direction: row; justify-content: stretch; }
            .btn-pill { flex: 1; }
            .filters-row { gap: 8px; }
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

        .sale-icon {
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

        .money-amount {
            font-size: 1.2rem;
            font-weight: bold;
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Scroll horizontal para móviles - Ventas */
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
                min-width: 1350px !important; /* Ancho mínimo para 9 columnas */
                margin-bottom: 0;
                width: 1350px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para ventas (9 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 70px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 150px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 180px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 180px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 100px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 120px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 100px; }
            
            .table-modern th:nth-child(8),
            .table-modern td:nth-child(8) { min-width: 150px; }
            
            .table-modern th:nth-child(9),
            .table-modern td:nth-child(9) { min-width: 120px; }
            
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
            <i class="fas fa-shopping-cart"></i> Gestión de Ventas
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
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-number"><?php echo $totalVentas; ?></div>
                <div class="stat-label">Total Ventas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $ventasCompletadas; ?></div>
                <div class="stat-label">Completadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $ventasPendientes; ?></div>
                <div class="stat-label">Pendientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="stat-number"><?php echo $ventasProcesando; ?></div>
                <div class="stat-label">Procesando</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-number money-amount">$<?php echo number_format($montoTotal, 2); ?></div>
                <div class="stat-label">Monto Total</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-month"></i>
                </div>
                <div class="stat-number money-amount">$<?php echo number_format($montoMes, 2); ?></div>
                <div class="stat-label">Ventas del Mes</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="/RMIE/app/controllers/SaleController.php" id="filterForm">
                <input type="hidden" name="accion" value="index">

                <div class="filters-row">
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-box"></i> Producto</span>
                        <input type="text"
                               name="filtro_producto"
                               class="filter-input"
                               placeholder="Buscar producto..."
                               value="<?= htmlspecialchars($_GET['filtro_producto'] ?? '') ?>">
                    </div>

                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-user"></i> Cliente</span>
                        <input type="text"
                               name="filtro_cliente"
                               class="filter-input"
                               placeholder="Buscar cliente..."
                               value="<?= htmlspecialchars($_GET['filtro_cliente'] ?? '') ?>">
                    </div>

                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-calendar"></i> Fecha</span>
                        <input type="date"
                               name="filtro_fecha"
                               class="filter-input"
                               value="<?= htmlspecialchars($_GET['filtro_fecha'] ?? '') ?>">
                    </div>

                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-filter"></i> Estado</span>
                        <select name="filtro_estado" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" <?= ($_GET['filtro_estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="procesando" <?= ($_GET['filtro_estado'] ?? '') === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                            <option value="completada" <?= ($_GET['filtro_estado'] ?? '') === 'completada' ? 'selected' : '' ?>>Completada</option>
                            <option value="cancelada" <?= ($_GET['filtro_estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
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
            <a href="/RMIE/app/controllers/SaleController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nueva Venta
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Ventas -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-shopping-cart"></i> Venta</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-box"></i> Producto</th>
                            <th><i class="fas fa-sort-numeric-up"></i> Cantidad</th>
                            <th><i class="fas fa-dollar-sign"></i> Total</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($ventas) && is_array($ventas) && !empty($ventas)): ?>
                            <?php foreach ($ventas as $venta): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($venta->id_ventas) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="sale-icon">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div>
                                            <strong>Venta #<?= htmlspecialchars($venta->id_ventas) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $venta->id_ventas ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-info">
                                        <?= htmlspecialchars($venta->cliente_nombre ?? 'Cliente N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-secondary">
                                        <?= htmlspecialchars($venta->producto_nombre ?? 'Producto N/A') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <strong class="fs-5"><?= htmlspecialchars($venta->cantidad ?? 0) ?></strong>
                                </td>
                                <td class="text-center">
                                    <span class="money-amount fs-5">
                                        $<?= number_format(floatval($venta->total ?? 0), 2) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $estado = strtolower($venta->estado ?? 'pendiente');
                                    $badgeClass = '';
                                    $iconClass = '';
                                    
                                    switch ($estado) {
                                        case 'completada':
                                            $badgeClass = 'badge-success';
                                            $iconClass = 'fas fa-check-circle';
                                            break;
                                        case 'pendiente':
                                            $badgeClass = 'badge-warning';
                                            $iconClass = 'fas fa-clock';
                                            break;
                                        case 'cancelada':
                                            $badgeClass = 'badge-danger';
                                            $iconClass = 'fas fa-times-circle';
                                            break;
                                        case 'procesando':
                                            $badgeClass = 'badge-info';
                                            $iconClass = 'fas fa-cogs';
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
                                        <strong><?= date('d/m/Y', strtotime($venta->fecha_venta ?? 'now')) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($venta->fecha_venta ?? 'now')) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/SaleController.php?accion=edit&id=<?= urlencode($venta->id_ventas) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar venta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/SaleController.php?accion=delete&id=<?= urlencode($venta->id_ventas) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar venta"
                                           onclick="return confirm('¿Está seguro de eliminar la venta #<?= addslashes($venta->id_ventas) ?>?\n\nEsta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                        <h5>No hay ventas disponibles</h5>
                                        <p>No se encontraron ventas que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/SaleController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primera Venta
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
// Capturar promesas rechazadas
window.addEventListener('unhandledrejection', function(e) { console.log('Promise Error:', e.reason); e.preventDefault(); });

// Debug mode - capturar errores JavaScript
window.addEventListener('error', function(e) { console.log('JS Error:', e.message, 'at', e.filename + ':' + e.lineno); });

        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
            window.location.href = '/RMIE/app/controllers/SaleController.php?accion=index';
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
                const ventaId = this.closest('tr').querySelector('td:nth-child(1) strong').textContent;
                if (confirm(`¿Está seguro de eliminar la venta ${ventaId}?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>