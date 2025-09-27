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
$proveedoresConContacto = 0;
$proveedoresConEmail = 0;

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
        
        if (!empty($prov->telefono)) {
            $proveedoresConContacto++;
        }
        
        if (!empty($prov->email)) {
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
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
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
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="" id="filterForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-truck"></i> Nombre Proveedor
                        </label>
                        <input type="text" 
                               name="nombre" 
                               class="form-control form-control-modern" 
                               placeholder="Buscar por nombre..."
                               value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-filter"></i> Estado
                        </label>
                        <select name="estado" class="form-control form-control-modern">
                            <option value="">Todos los estados</option>
                            <option value="activo" <?= ($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            <option value="pendiente" <?= ($_GET['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-map-marker-alt"></i> Ciudad
                        </label>
                        <input type="text" 
                               name="ciudad" 
                               class="form-control form-control-modern" 
                               placeholder="Buscar por ciudad..."
                               value="<?= htmlspecialchars($_GET['ciudad'] ?? '') ?>">
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
            <a href="/RMIE/app/controllers/ProviderController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nuevo Proveedor
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Proveedores -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-truck"></i> Proveedor</th>
                            <th><i class="fas fa-address-card"></i> Información</th>
                            <th><i class="fas fa-phone"></i> Contacto</th>
                            <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha Registro</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($proveedores) && is_array($proveedores) && !empty($proveedores)): ?>
                            <?php foreach ($proveedores as $proveedor): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($proveedor->id_proveedor) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="provider-icon">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($proveedor->nombre) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $proveedor->id_proveedor ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($proveedor->nit)): ?>
                                        <div class="contact-info mb-1">
                                            <i class="fas fa-id-card"></i> NIT: <?= htmlspecialchars($proveedor->nit) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($proveedor->direccion)): ?>
                                        <div class="contact-info">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($proveedor->direccion) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($proveedor->telefono)): ?>
                                        <div class="contact-info mb-1">
                                            <i class="fas fa-phone"></i> <?= htmlspecialchars($proveedor->telefono) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($proveedor->email)): ?>
                                        <div class="contact-info">
                                            <i class="fas fa-envelope"></i> <?= htmlspecialchars($proveedor->email) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin email
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($proveedor->ciudad)): ?>
                                        <span class="badge badge-modern badge-info">
                                            <i class="fas fa-city"></i> <?= htmlspecialchars($proveedor->ciudad) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-question"></i> N/A
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
                                    <div class="text-center">
                                        <i class="fas fa-calendar text-info"></i>
                                        <strong><?= date('d/m/Y', strtotime($proveedor->fecha_registro ?? 'now')) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($proveedor->fecha_registro ?? 'now')) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/ProviderController.php?accion=edit&id=<?= urlencode($proveedor->id_proveedor) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar proveedor">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/ProviderController.php?accion=delete&id=<?= urlencode($proveedor->id_proveedor) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar proveedor"
                                           onclick="return confirm('¿Está seguro de eliminar el proveedor \'<?= addslashes($proveedor->nombre) ?>\'?\n\nEsta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-truck fa-3x mb-3"></i>
                                        <h5>No hay proveedores disponibles</h5>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
                const proveedorNombre = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
                if (confirm(`¿Está seguro de eliminar el proveedor "${proveedorNombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>