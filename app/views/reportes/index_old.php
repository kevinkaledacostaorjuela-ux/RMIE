<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .glass-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .hero-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 0 0 50px 50px;
        }
        
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }
        
        .btn-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table-modern thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 15px;
            border: none;
        }
        
        .table-modern tbody tr {
            background: white;
            transition: all 0.3s ease;
        }
        
        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: scale(1.01);
        }
        
        .table-modern tbody td {
            padding: 20px 15px;
            border: none;
            border-bottom: 1px solid #f8f9fa;
            vertical-align: middle;
        }
        
        .badge-activo {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }
        
        .badge-inactivo {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }
        
        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            margin: 0 2px;
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            color: white;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #17a2b8, #20c997);
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
        }
        
        .no-reports {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .no-reports i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .floating-add-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .floating-add-btn:hover {
            transform: translateY(-5px) rotate(180deg);
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.6);
            color: white;
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1><i class="fas fa-chart-bar me-3"></i>Gestión de Reportes</h1>
                    <p class="lead">Centro de control y análisis de informes empresariales</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="/RMIE/app/controllers/MainController.php?action=dashboard" class="btn btn-outline-light me-3">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="/RMIE/app/controllers/ReportController.php?action=create" class="btn btn-light">
                        <i class="fas fa-plus me-2"></i>Nuevo Reporte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-number"><?php echo isset($stats['total']) ? $stats['total'] : 0; ?></div>
                    <div class="stat-label">Total Reportes</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number"><?php echo isset($stats['activos']) ? $stats['activos'] : 0; ?></div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-pause-circle"></i>
                    </div>
                    <div class="stat-number"><?php echo isset($stats['inactivos']) ? $stats['inactivos'] : 0; ?></div>
                    <div class="stat-label">Inactivos</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-number"><?php echo isset($stats['mes_actual']) ? $stats['mes_actual'] : 0; ?></div>
                    <div class="stat-label">Este Mes</div>
                </div>
            </div>
        </div>

        <div class="glass-container">
            <h5 class="mb-4"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h5>
            <form method="GET" action="/RMIE/app/controllers/ReportController.php">
                <input type="hidden" name="action" value="index">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="buscar" class="form-label">Buscar Reporte</label>
                        <input type="text" class="form-control" id="buscar" name="buscar" 
                               placeholder="Nombre o descripción..." 
                               value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="">Todos</option>
                            <option value="activo" <?php echo ($_GET['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                            <option value="inactivo" <?php echo ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                               value="<?php echo htmlspecialchars($_GET['fecha_inicio'] ?? ''); ?>">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                               value="<?php echo htmlspecialchars($_GET['fecha_fin'] ?? ''); ?>">
                    </div>
                    <div class="col-lg-3 col-md-12 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-modern me-2">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                        <a href="/RMIE/app/controllers/ReportController.php?action=index" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="glass-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="fas fa-list me-2"></i>Lista de Reportes</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Imprimir
                    </button>
                </div>
            </div>

            <?php if (empty($reportes)): ?>
                <div class="no-reports">
                    <i class="fas fa-inbox"></i>
                    <h4>No hay reportes disponibles</h4>
                    <p>No se encontraron reportes con los criterios de búsqueda especificados.</p>
                    <a href="/RMIE/app/controllers/ReportController.php?action=create" class="btn btn-modern">
                        <i class="fas fa-plus me-2"></i>Crear Primer Reporte
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-file-alt me-2"></i>Nombre</th>
                                <th><i class="fas fa-align-left me-2"></i>Descripción</th>
                                <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reportes as $reporte): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">#<?php echo $reporte->id_reportes; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($reporte->nombre); ?></strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?php 
                                            $descripcion = $reporte->descripcion;
                                            echo htmlspecialchars(strlen($descripcion) > 50 ? substr($descripcion, 0, 50) . '...' : $descripcion); 
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($reporte->fecha)); ?>
                                    </td>
                                    <td>
                                        <span class="badge-<?php echo $reporte->estado; ?>">
                                            <?php echo ucfirst($reporte->estado); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/RMIE/app/controllers/ReportController.php?action=generate&id=<?php echo $reporte->id_reportes; ?>" 
                                           class="btn-action btn-view" title="Generar Reporte">
                                            <i class="fas fa-eye"></i>
                                        </a>  
                                        <a href="/RMIE/app/controllers/ReportController.php?action=edit&id=<?php echo $reporte->id_reportes; ?>" 
                                           class="btn-action btn-edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/ReportController.php?action=delete&id=<?php echo $reporte->id_reportes; ?>" 
                                           class="btn-action btn-delete" title="Eliminar"
                                           onclick="return confirm('¿Estás seguro de eliminar este reporte?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <a href="/RMIE/app/controllers/ReportController.php?action=create" class="floating-add-btn" title="Nuevo Reporte">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
