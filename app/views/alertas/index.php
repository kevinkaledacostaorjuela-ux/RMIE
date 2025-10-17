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

        .btn-modern {
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
                min-width: 1050px !important; /* Ancho mínimo para 7 columnas */
                margin-bottom: 0;
                width: 1050px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para alertas (7 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 70px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 180px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 150px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 130px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 180px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 120px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 120px; }
            
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

        <!-- Filtros Avanzados -->
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros Avanzados
            </div>
            <form method="GET" action="/RMIE/app/controllers/AlertController.php" id="filterForm">
                <input type="hidden" name="accion" value="index" />
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-box"></i> Producto
                        </label>
                        <select name="producto" class="form-control form-control-modern">
                            <option value="">Todos los productos</option>
                            <?php foreach ($productos as $prod): ?>
                                <option value="<?= $prod->id_productos ?>" <?= ($filtros['producto'] == $prod->id_productos ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($prod->nombre) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-search"></i> Nombre Producto
                        </label>
                        <input type="text" 
                               name="nombre_producto" 
                               class="form-control form-control-modern" 
                               placeholder="Buscar por nombre..."
                               value="<?= htmlspecialchars($filtros['nombre_producto']) ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-sort-numeric-up"></i> Cantidad Min.
                        </label>
                        <input type="number" 
                               name="cantidad_min" 
                               class="form-control form-control-modern" 
                               placeholder="Cantidad mínima"
                               value="<?= htmlspecialchars($filtros['cantidad_min']) ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-sort-numeric-down"></i> Cantidad Max.
                        </label>
                        <input type="number" 
                               name="cantidad_max" 
                               class="form-control form-control-modern" 
                               placeholder="Cantidad máxima"
                               value="<?= htmlspecialchars($filtros['cantidad_max']) ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-calendar-alt"></i> Fecha Desde
                        </label>
                        <input type="date" 
                               name="fecha_desde" 
                               class="form-control form-control-modern"
                               value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-calendar-alt"></i> Fecha Hasta
                        </label>
                        <input type="date" 
                               name="fecha_hasta" 
                               class="form-control form-control-modern"
                               value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="w-100">
                            <button type="submit" class="btn btn-modern btn-primary-modern me-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-modern btn-warning-modern" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
                                        <a href="/RMIE/app/controllers/AlertController.php?accion=delete&id=<?= $alerta['id_alertas'] ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar alerta"
                                           onclick="return confirm('¿Está seguro de eliminar esta alerta del producto \'<?= addslashes($alerta['producto_nombre'] ?? 'Producto #' . $alerta['id_productos']) ?>\'?\n\nEsta acción no se puede deshacer.')">
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

        // Confirmar eliminación con más detalles
        document.querySelectorAll('a[onclick*="confirm"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const productoNombre = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
                if (confirm(`¿Está seguro de eliminar la alerta del producto "${productoNombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>