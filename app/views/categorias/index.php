<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Si no hay usuario logueado, redirigir
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Mensajes de sesión (protegido por null coalescing)
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : '';

// Limpiar mensajes para no mostrarlos en la siguiente petición
unset($_SESSION['error'], $_SESSION['success']);

// Obtener estadísticas de categorías de forma robusta
global $conn;
$stats = ['total_categorias' => 0, 'con_descripcion' => 0];
if (isset($conn)) {
    $statsQuery = @$conn->query("SELECT 
        COUNT(*) as total_categorias,
        COUNT(CASE WHEN descripcion IS NOT NULL AND descripcion != '' THEN 1 END) as con_descripcion
        FROM categorias");
    if ($statsQuery) {
        $f = $statsQuery->fetch_assoc();
        if (is_array($f)) {
            $stats['total_categorias'] = isset($f['total_categorias']) ? $f['total_categorias'] : 0;
            $stats['con_descripcion'] = isset($f['con_descripcion']) ? $f['con_descripcion'] : 0;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - RMIE</title>
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
            0% { left: -100%; }
            100% { left: 100%; }
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

        .badge-success { background: linear-gradient(45deg, #4facfe, #00f2fe); color: white; }
        .badge-warning { background: linear-gradient(45deg, #ff9a9e, #fecfef); color: white; }
        .badge-danger { background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; }
        .badge-info { background: linear-gradient(45deg, #4facfe, #00f2fe); color: white; }
        .badge-secondary { background: linear-gradient(45deg, #667eea, #764ba2); color: white; }

        .contact-info { font-size: 0.9rem; color: #fff !important; }
        .text-muted { color: rgba(255, 255, 255, 0.7) !important; }

        /* Responsive: scroll horizontal para móviles */
        @media (max-width: 768px) {
            .table-container { padding: 15px; overflow: visible; }
            .table-responsive { -webkit-overflow-scrolling: touch; overflow-x: auto !important; overflow-y: visible; border-radius: 10px; max-width: 100%; position: relative; }
            .table-responsive::-webkit-scrollbar { height: 12px; background: rgba(255, 255, 255, 0.2); border-radius: 6px; }
            .table-responsive::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.5); border-radius: 6px; border: 2px solid rgba(255, 255, 255, 0.1); }
            .table-responsive::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.7); }
            .table-modern { min-width: 1050px !important; margin-bottom: 0; width: 1050px; }
            .table-modern th, .table-modern td { white-space: nowrap !important; padding: 10px 12px; font-size: 0.85rem; min-width: 120px; }
            .table-modern th:nth-child(1), .table-modern td:nth-child(1) { min-width: 70px; }
            .table-modern th:nth-child(2), .table-modern td:nth-child(2) { min-width: 180px; }
            .table-modern th:nth-child(3), .table-modern td:nth-child(3) { min-width: 120px; }
            .table-modern th:nth-child(4), .table-modern td:nth-child(4) { min-width: 250px; }
            .table-modern th:nth-child(5), .table-modern td:nth-child(5) { min-width: 120px; }
            .table-modern th:nth-child(6), .table-modern td:nth-child(6) { min-width: 150px; }
            .table-modern th:nth-child(7), .table-modern td:nth-child(7) { min-width: 120px; }
            .table-modern th:nth-child(8), .table-modern td:nth-child(8) { min-width: 120px; }
            .scroll-hint { position: absolute; bottom: -30px; left: 50%; transform: translateX(-50%); color: rgba(255, 255, 255, 0.8); font-size: 0.8rem; font-style: italic; animation: pulse 2s ease-in-out infinite; background: rgba(0, 0, 0, 0.3); padding: 5px 10px; border-radius: 15px; backdrop-filter: blur(5px); }
            @keyframes pulse { 0%, 100% { opacity: 0.6; } 50% { opacity: 1; } }
        }

        /* Refuerzos responsive para evitar duplicados si las utilidades de Bootstrap no cargan */
        @media (min-width: 768px) {
            .d-block.d-md-none { display: none !important; }
            .d-none.d-md-block { display: block !important; }
        }

        @media (max-width: 767.98px) {
            .d-none.d-md-block { display: none !important; }
            .d-block.d-md-none { display: block !important; }
        }

        /* Alineación y tamaño de botones de acción dentro de tablas */
        .table-container .btn-group .btn {
            padding: 8px 12px;
            font-size: 0.85rem;
            border-radius: 25px;
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-tags"></i> Gestión de Categorías
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
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number"><?php echo isset($stats['total_categorias']) ? intval($stats['total_categorias']) : 0; ?></div>
                <div class="stat-label">Total Categorías</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-align-left"></i>
                </div>
                <div class="stat-number"><?php echo isset($stats['con_descripcion']) ? intval($stats['con_descripcion']) : 0; ?></div>
                <div class="stat-label">Con Descripción</div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mb-4 text-center">
            <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nueva Categoría
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Categorías (Desktop) -->
        <div class="table-container d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-tag"></i> Categoría</th>
                            <th><i class="fas fa-align-left"></i> Descripción</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha Creación</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($categorias) && is_array($categorias) && !empty($categorias)): ?>
                            <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($cat->id_categoria) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="category-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($cat->nombre) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $cat->id_categoria ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($cat->descripcion)): ?>
                                        <span class="badge badge-modern badge-info" title="<?= htmlspecialchars($cat->descripcion) ?>">
                                            <?= strlen($cat->descripcion) > 50 ? substr(htmlspecialchars($cat->descripcion), 0, 50) . '...' : htmlspecialchars($cat->descripcion) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin descripción
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <i class="fas fa-calendar text-info"></i>
                                        <strong><?= date('d/m/Y', strtotime($cat->fecha_creacion)) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($cat->fecha_creacion)) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar categoría">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar categoría"
                                           onclick="return confirm('¿Está seguro de eliminar la categoría \'<?= addslashes($cat->nombre) ?>\'?\n\nEsta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No hay categorías disponibles</h5>
                                        <p>No se encontraron categorías registradas en el sistema.</p>
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primera Categoría
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista de Tabla Móvil -->
        <div class="d-block d-md-none">
            <div class="table-container">
                <?php include __DIR__ . '/../partials/card_mode.php'; ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-toggle">
                        <button id="toggleCategorias">Ver como tarjetas</button>
                    </div>
                </div>
                <div id="cardsCategorias" class="card-container">
                    <?php if (isset($categorias) && is_array($categorias)): ?>
                        <?php foreach ($categorias as $cat): ?>
                            <div class="card-item">
                                <div class="card-title"><?= htmlspecialchars($cat->nombre ?? $cat['nombre'] ?? 'Sin nombre') ?></div>
                                <div class="card-subtitle"><?= htmlspecialchars($cat->descripcion ?? $cat['descripcion'] ?? '') ?></div>
                                <div class="card-actions">
                                    <a class="btn-edit" href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria ?? $cat['id_categoria'] ?? '') ?>">Editar</a>
                                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'coordinador'): ?>
                                    <a class="btn-delete" href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria ?? $cat['id_categoria'] ?? '') ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-tag"></i> Categoría</th>
                                <th><i class="fas fa-align-left"></i> Descripción</th>
                                <th><i class="fas fa-calendar-alt"></i> Fecha Creación</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($categorias) && is_array($categorias) && !empty($categorias)): ?>
                                <?php foreach ($categorias as $cat): ?>
                                <tr>
                                    <td>
                                        <strong>#<?= htmlspecialchars($cat->id_categoria) ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($cat->nombre) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-barcode"></i> ID: <?= $cat->id_categoria ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($cat->descripcion)): ?>
                                            <span class="badge badge-modern badge-info" title="<?= htmlspecialchars($cat->descripcion) ?>">
                                                <?= strlen($cat->descripcion) > 50 ? substr(htmlspecialchars($cat->descripcion), 0, 50) . '...' : htmlspecialchars($cat->descripcion) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern badge-secondary">
                                                <i class="fas fa-minus"></i> Sin descripción
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <i class="fas fa-calendar text-info"></i>
                                            <strong><?= date('d/m/Y', strtotime($cat->fecha_creacion)) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($cat->fecha_creacion)) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria) ?>" 
                                               class="btn btn-sm btn-modern btn-warning-modern" 
                                               title="Editar categoría">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'coordinador'): ?>
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria) ?>" 
                                               class="btn btn-sm btn-modern btn-danger-modern" 
                                               title="Eliminar categoría"
                                               onclick="return confirm('¿Está seguro de eliminar la categoría \'<?= addslashes($cat->nombre) ?>\'?\n\nEsta acción no se puede deshacer.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>No hay categorías disponibles</h5>
                                            <p>No se encontraron categorías registradas en el sistema.</p>
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-modern btn-success-modern">
                                                <i class="fas fa-plus"></i> Crear Primera Categoría
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
                const categoriaNombre = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
                if (confirm(`¿Está seguro de eliminar la categoría "${categoriaNombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>
