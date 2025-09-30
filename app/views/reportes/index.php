
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

// Calcular estadísticas de reportes
$totalReportes = count($reportes ?? []);
$reportesActivos = 0;
$reportesInactivos = 0;
$reportesPendientes = 0;
$reportesEnProceso = 0;
$reportesRecientes = 0;

$fechaActual = date('Y-m-d');
$fechaReciente = date('Y-m-d', strtotime('-7 days'));

if (isset($reportes) && is_array($reportes)) {
    foreach ($reportes as $reporte) {
        switch (strtolower($reporte->estado ?? 'pendiente')) {
            case 'activo':
                $reportesActivos++;
                break;
            case 'inactivo':
                $reportesInactivos++;
                break;
            case 'pendiente':
                $reportesPendientes++;
                break;
            case 'en_proceso':
                $reportesEnProceso++;
                break;
        }
        
        // Contar reportes recientes (últimos 7 días)
        if (!empty($reporte->fecha) && $reporte->fecha >= $fechaReciente) {
            $reportesRecientes++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reportes - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../../public/css/styles.css" rel="stylesheet">
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
            padding: 30px;
            margin: 20px;
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

        .btn-info-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
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

        .report-icon {
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

        .report-type {
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .quick-actions {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-chart-bar"></i> Gestión de Reportes
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
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-number"><?php echo $totalReportes; ?></div>
                <div class="stat-label">Total Reportes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $reportesActivos; ?></div>
                <div class="stat-label">Activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $reportesPendientes; ?></div>
                <div class="stat-label">Pendientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="stat-number"><?php echo $reportesEnProceso; ?></div>
                <div class="stat-label">En Proceso</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-number"><?php echo $reportesRecientes; ?></div>
                <div class="stat-label">Esta Semana</div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="quick-actions">
            <div class="row text-center">
                <div class="col-md-3 mb-2">
                    <a href="/RMIE/app/controllers/ReportController.php?action=generate&tipo=ventas" class="btn btn-modern btn-success-modern w-100">
                        <i class="fas fa-shopping-cart"></i> Reporte de Ventas
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="/RMIE/app/controllers/ReportController.php?action=generate&tipo=inventario" class="btn btn-modern btn-info-modern w-100">
                        <i class="fas fa-boxes"></i> Reporte de Inventario
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="/RMIE/app/controllers/ReportController.php?action=generate&tipo=alertas" class="btn btn-modern btn-warning-modern w-100">
                        <i class="fas fa-exclamation-triangle"></i> Reporte de Alertas
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="/RMIE/app/controllers/ReportController.php?action=generate&tipo=financiero" class="btn btn-modern btn-primary-modern w-100">
                        <i class="fas fa-dollar-sign"></i> Reporte Financiero
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-chart-bar"></i> Tipo de Reporte
                        </label>
                        <select name="tipo" class="form-control form-control-modern">
                            <option value="">Todos los tipos</option>
                            <option value="ventas" <?= ($_GET['tipo'] ?? '') === 'ventas' ? 'selected' : '' ?>>Ventas</option>
                            <option value="inventario" <?= ($_GET['tipo'] ?? '') === 'inventario' ? 'selected' : '' ?>>Inventario</option>
                            <option value="alertas" <?= ($_GET['tipo'] ?? '') === 'alertas' ? 'selected' : '' ?>>Alertas</option>
                            <option value="financiero" <?= ($_GET['tipo'] ?? '') === 'financiero' ? 'selected' : '' ?>>Financiero</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-filter"></i> Estado
                        </label>
                        <select name="estado" class="form-control form-control-modern">
                            <option value="">Todos</option>
                            <option value="activo" <?= ($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="pendiente" <?= ($_GET['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="en_proceso" <?= ($_GET['estado'] ?? '') === 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
                            <option value="inactivo" <?= ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-calendar-alt"></i> Fecha Desde
                        </label>
                        <input type="date" 
                               name="fecha_desde" 
                               class="form-control form-control-modern"
                               value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-calendar-alt"></i> Fecha Hasta
                        </label>
                        <input type="date" 
                               name="fecha_hasta" 
                               class="form-control form-control-modern"
                               value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <div class="w-100">
                            <button type="submit" class="btn btn-modern btn-primary-modern mb-1 w-100">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-modern btn-warning-modern w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botones de acción -->
        <div class="mb-4 text-center">
            <a href="/RMIE/app/controllers/ReportController.php?action=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nuevo Reporte
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Reportes -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-chart-bar"></i> Reporte</th>
                            <th><i class="fas fa-tags"></i> Tipo</th>
                            <th><i class="fas fa-align-left"></i> Descripción</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($reportes) && is_array($reportes) && !empty($reportes)): ?>
                            <?php foreach ($reportes as $reporte): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($reporte->id_reportes) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="report-icon">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($reporte->nombre ?? 'Reporte #' . $reporte->id_reportes) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $reporte->id_reportes ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="report-type">
                                        <i class="fas fa-tag"></i> <?= ucfirst($reporte->tipo ?? 'General') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($reporte->descripcion)): ?>
                                        <span title="<?= htmlspecialchars($reporte->descripcion) ?>">
                                            <?= strlen($reporte->descripcion) > 50 ? substr(htmlspecialchars($reporte->descripcion), 0, 50) . '...' : htmlspecialchars($reporte->descripcion) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin descripción
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $estado = strtolower($reporte->estado ?? 'pendiente');
                                    $badgeClass = '';
                                    $iconClass = '';
                                    
                                    switch ($estado) {
                                        case 'activo':
                                            $badgeClass = 'badge-success';
                                            $iconClass = 'fas fa-check-circle';
                                            break;
                                        case 'pendiente':
                                            $badgeClass = 'badge-warning';
                                            $iconClass = 'fas fa-clock';
                                            break;
                                        case 'en_proceso':
                                            $badgeClass = 'badge-info';
                                            $iconClass = 'fas fa-cogs';
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
                                        <i class="<?= $iconClass ?>"></i> <?= ucfirst(str_replace('_', ' ', $estado)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <i class="fas fa-calendar text-info"></i>
                                        <strong><?= date('d/m/Y', strtotime($reporte->fecha ?? 'now')) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($reporte->fecha ?? 'now')) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/ReportController.php?action=view&id=<?= urlencode($reporte->id_reportes) ?>" 
                                           class="btn btn-sm btn-modern btn-info-modern" 
                                           title="Ver reporte">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/ReportController.php?action=edit&id=<?= urlencode($reporte->id_reportes) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar reporte">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar reporte"
                                           onclick="eliminarReporte(<?= $reporte->id_reportes ?>)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                        <h5>No hay reportes disponibles</h5>
                                        <p>No se encontraron reportes que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/ReportController.php?action=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primer Reporte
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
            window.location.href = '/RMIE/app/controllers/ReportController.php?action=index';
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

        // Función para eliminar reporte con confirmación
        window.eliminarReporte = function(id) {
            if (confirm('¿Está seguro de eliminar este reporte?\n\nEsta acción no se puede deshacer.')) {
                window.location.href = '/RMIE/app/controllers/ReportController.php?action=delete&id=' + id + '&confirm=yes';
            }
        };
    </script>
</body>
</html>