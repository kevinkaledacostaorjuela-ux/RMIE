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
            font-weight: 600;
        }

        .badge-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .badge-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
        }

        .badge-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
        }

        .badge-stock-bajo {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
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
            color: #fff;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.3);
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
        }

        /* Scroll horizontal para móviles - Alertas */
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
    <div class="dashboard-container">
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
        </div>

        <!-- Vista selector: Tarjetas / Tabla -->
        <div style="display:flex; justify-content:flex-end; gap:10px; margin:10px 0 20px;">
            <button id="viewCardsBtnAlertas" class="btn btn-modern btn-primary-modern">Tarjetas</button>
            <button id="viewTableBtnAlertas" class="btn btn-modern btn-secondary-modern" style="background:transparent; color:#fff; border:1px solid rgba(255,255,255,0.15);">Tabla</button>
        </div>

        <!-- Cards container for alertas -->
        <div id="cardsContainerAlertas" style="display:none; margin-bottom:20px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:16px;">
                <?php if (!empty($alertas) && is_array($alertas)): ?>
                    <?php foreach ($alertas as $alerta): ?>
                        <?php
                            $fecha_actual = date('Y-m-d');
                            $fecha_caducidad = $alerta['fecha_caducidad'] ?? null;
                            $dias_restantes = $fecha_caducidad ? (strtotime($fecha_caducidad) - strtotime($fecha_actual)) / (60 * 60 * 24) : null;
                            if ($dias_restantes !== null) {
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
                        ?>
                        <div class="card" style="background: rgba(255,255,255,0.04); border-radius:12px; padding:16px; border:1px solid rgba(255,255,255,0.06);">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <strong style="color:#fff;">Alerta #<?= htmlspecialchars($alerta['id_alertas'] ?? '') ?></strong>
                                    <div style="color:rgba(255,255,255,0.7); font-size:0.9rem;">Producto: <?= htmlspecialchars($alerta['producto_nombre'] ?? 'N/A') ?></div>
                                </div>
                                <div style="font-size:1.4rem; color:rgba(255,255,255,0.8);"><i class="fas fa-exclamation-triangle"></i></div>
                            </div>
                            <div style="margin-top:10px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div><strong>Tipo:</strong> <?= htmlspecialchars($alerta['tipo_alerta'] ?? 'N/A') ?></div>
                                    <div><strong>Cantidad mínima:</strong> <?= htmlspecialchars($alerta['cantidad_minima'] ?? 'N/A') ?></div>
                                </div>
                                <div style="text-align:right;">
                                    <div class="text-muted"><?= $fecha_caducidad ? date('d/m/Y', strtotime($fecha_caducidad)) : 'N/A' ?></div>
                                    <div style="font-size:0.85rem; color:rgba(255,255,255,0.8);"><?= $dias_restantes !== null ? ($dias_restantes < 0 ? 'Vencida hace ' . abs(round($dias_restantes)) . ' días' : round($dias_restantes) . ' días restantes') : '' ?></div>
                                </div>
                            </div>
                            <div style="margin-top:12px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <span class="badge badge-modern <?= $badge_class ?>">
                                        <i class="<?= $icono ?>"></i> <?= $estado ?>
                                    </span>
                                </div>
                                <div style="display:flex; gap:8px;">
                                    <a href="/RMIE/app/controllers/AlertController.php?accion=edit&id=<?= urlencode($alerta['id_alertas'] ?? '') ?>" class="btn btn-sm btn-modern btn-warning-modern"><i class="fas fa-edit"></i></a>
                                    <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                    <button type="button" class="btn btn-sm btn-modern btn-danger-modern" onclick="confirmarEliminacion(<?= htmlspecialchars($alerta['id_alertas'] ?? 0) ?>, '<?= htmlspecialchars($alerta['producto_nombre'] ?? 'Producto', ENT_QUOTES) ?>')"><i class="fas fa-trash"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div>No hay alertas disponibles</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filtros Avanzados -->
        <div class="filters-container">
            <div class="filters-inner" style="padding: 35px 60px; max-width: 96% !important;">
                <div class="filter-title" style="margin-bottom: 25px; text-align: center; font-size: 1.5rem; font-weight: 600;">
                    <i class="fas fa-filter"></i> Filtros de Búsqueda
                </div>
                <form method="GET" action="/RMIE/app/controllers/AlertController.php" id="filterForm">
                    <input type="hidden" name="accion" value="index" />

                    <div class="row g-4" style="max-width: 100%; margin: 0 auto;">
                        <div class="col-md-2">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-bell"></i> Tipo
                            </label>
                            <input type="text"
                                   name="tipo"
                                   class="form-control"
                                   placeholder="Buscar por tipo..."
                                   style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem; width: 100%;"
                                   value="<?= htmlspecialchars($_GET['tipo'] ?? '') ?>">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-exclamation-triangle"></i> Prioridad
                            </label>
                            <select name="prioridad" 
                                    class="form-select"
                                    style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem; width: 100%;">
                                <option value="">Todas</option>
                                <option value="alta" <?= ($_GET['prioridad'] ?? '') === 'alta' ? 'selected' : '' ?>>Alta</option>
                                <option value="media" <?= ($_GET['prioridad'] ?? '') === 'media' ? 'selected' : '' ?>>Media</option>
                                <option value="baja" <?= ($_GET['prioridad'] ?? '') === 'baja' ? 'selected' : '' ?>>Baja</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-toggle-on"></i> Estado
                            </label>
                            <select name="estado" 
                                    class="form-select"
                                    style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem; width: 100%;">
                                <option value="">Todos</option>
                                <option value="activo" <?= ($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="inactivo" <?= ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-calendar"></i> Desde
                            </label>
                            <input type="date"
                                   name="fecha_desde"
                                   class="form-control"
                                   style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem; width: 100%;"
                                   value="<?= htmlspecialchars($filtros['fecha_desde'] ?? '') ?>">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-calendar"></i> Hasta
                            </label>
                            <input type="date"
                                   name="fecha_hasta"
                                   class="form-control"
                                   style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem; width: 100%;"
                                   value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>">
                        </div>

                        <div class="col-md-1 d-flex align-items-end justify-content-center">
                            <div class="d-flex gap-2 flex-column w-100">
                                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; padding: 12px; border-radius: 50%; font-weight: 600; font-size: 1rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto;" title="Buscar">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()" style="background: #6c757d; border: none; padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                                <i class="fas fa-times"></i> Limpiar Filtros
                            </button>
                        </div>
                    </div>
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

        <!-- Tabla de Alertas -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-bell"></i> Tipo</th>
                            <th><i class="fas fa-box"></i> Producto</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
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
                                
                                // Determinar estado y badge
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
                                            <?= htmlspecialchars($alerta['cliente_nombre'] ?? 'Cliente #' . $alerta['id_clientes']) ?>
                                            <br>
                                            <small class="text-muted">ID: <?= $alerta['id_clientes'] ?></small>
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
        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
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
            if (confirm('¿Está seguro de eliminar esta alerta del producto "' + nombreProducto + '"?\n\nEsta acción no se puede deshacer.')) {
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

            function setView(view){
                if (view === 'cards'){
                    if (cardsContainer) cardsContainer.style.display = '';
                    if (tableContainer) tableContainer.style.display = 'none';
                } else {
                    if (cardsContainer) cardsContainer.style.display = 'none';
                    if (tableContainer) tableContainer.style.display = '';
                }
                try{ localStorage.setItem('alertas_view', view); }catch(e){}
            }

            if (cardsBtn && tableBtn){
                cardsBtn.addEventListener('click', ()=>setView('cards'));
                tableBtn.addEventListener('click', ()=>setView('table'));
                const pref = (function(){ try{ return localStorage.getItem('alertas_view'); }catch(e){return null;} })();
                setView(pref === 'cards' ? 'cards' : 'table');
            }
        })();
    </script>
</body>
</html>