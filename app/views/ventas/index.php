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
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-box"></i> Producto
                        </label>
                        <input type="text" 
                               name="filtro_producto" 
                               class="form-control form-control-modern" 
                               placeholder="Nombre del producto..."
                               value="<?= htmlspecialchars($_GET['filtro_producto'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-user"></i> Cliente
                        </label>
                        <input type="text" 
                               name="filtro_cliente" 
                               class="form-control form-control-modern" 
                               placeholder="Nombre del cliente..."
                               value="<?= htmlspecialchars($_GET['filtro_cliente'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-calendar"></i> Fecha
                        </label>
                        <input type="date" 
                               name="filtro_fecha" 
                               class="form-control form-control-modern"
                               value="<?= htmlspecialchars($_GET['filtro_fecha'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-filter"></i> Estado
                        </label>
                        <select name="filtro_estado" class="form-control form-control-modern">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" <?= ($_GET['filtro_estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="procesando" <?= ($_GET['filtro_estado'] ?? '') === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                            <option value="completada" <?= ($_GET['filtro_estado'] ?? '') === 'completada' ? 'selected' : '' ?>>Completada</option>
                            <option value="cancelada" <?= ($_GET['filtro_estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <div class="w-100">
                            <button type="submit" class="btn btn-modern btn-primary-modern me-1 mb-1">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-modern btn-warning-modern mb-1" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
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
                                        <a href="/RMIE/app/controllers/SaleController.php?accion=delete&id=<?= urlencode($venta->id_ventas) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar venta"
                                           onclick="return confirm('¿Está seguro de eliminar la venta #<?= addslashes($venta->id_ventas) ?>?\n\nEsta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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