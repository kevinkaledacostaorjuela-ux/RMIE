<?php
// Verificar que la sesión esté iniciada (el controlador ya debería haberlo hecho)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}

// Obtener mensajes de sesión
$error_message = $_SESSION['error'] ?? '';
$success_message = $_SESSION['success'] ?? '';

// Limpiar mensajes de sesión
unset($_SESSION['error'], $_SESSION['success']);

// Obtener estadísticas básicas
global $conn;
$statsQuery = $conn->query("SELECT 
    COUNT(*) as total_clientes,
    COUNT(CASE WHEN estado = 'activo' THEN 1 END) as clientes_activos,
    COUNT(CASE WHEN cel_cliente IS NOT NULL AND cel_cliente != '' THEN 1 END) as con_telefono
    FROM clientes");
$stats = $statsQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - RMIE</title>
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

        .client-icon {
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

        .badge-info {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .badge-secondary {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-users"></i> Gestión de Clientes
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
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total_clientes']; ?></div>
                <div class="stat-label">Total Clientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number"><?php echo $stats['clientes_activos']; ?></div>
                <div class="stat-label">Clientes Activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="stat-number"><?php echo $stats['con_telefono']; ?></div>
                <div class="stat-label">Con Teléfono</div>
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
                            <i class="fas fa-user"></i> Nombre
                        </label>
                        <input type="text" 
                               name="nombre" 
                               class="form-control form-control-modern" 
                               placeholder="Buscar por nombre..."
                               value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="text" 
                               name="email" 
                               class="form-control form-control-modern" 
                               placeholder="Buscar por email..."
                               value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-white">
                            <i class="fas fa-toggle-on"></i> Estado
                        </label>
                        <select name="estado" class="form-control form-control-modern">
                            <option value="">Todos los estados</option>
                            <option value="activo" <?= ($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3 d-flex align-items-end">
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
            <a href="/RMIE/app/controllers/ClientController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Clientes -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Teléfono</th>
                            <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                            <th><i class="fas fa-toggle-on"></i> Estado</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($clientes) && is_array($clientes) && !empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($cliente->id_clientes) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="client-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($cliente->nombre) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card"></i> <?= htmlspecialchars($cliente->documento ?? 'Sin documento') ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->email)): ?>
                                        <i class="fas fa-envelope text-info"></i>
                                        <small><?= htmlspecialchars($cliente->email) ?></small>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin email
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->cel_cliente)): ?>
                                        <i class="fas fa-phone text-success"></i>
                                        <small><?= htmlspecialchars($cliente->cel_cliente) ?></small>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin teléfono
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->direccion)): ?>
                                        <span class="badge badge-modern badge-info" title="<?= htmlspecialchars($cliente->direccion) ?>">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <?= strlen($cliente->direccion) > 30 ? substr(htmlspecialchars($cliente->direccion), 0, 30) . '...' : htmlspecialchars($cliente->direccion) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin dirección
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($cliente->estado === 'activo'): ?>
                                        <span class="badge badge-modern badge-success">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-danger">
                                            <i class="fas fa-times-circle"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/ClientController.php?accion=edit&id=<?= urlencode($cliente->id_clientes) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar cliente">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/ClientController.php?accion=delete&id=<?= urlencode($cliente->id_clientes) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar cliente"
                                           onclick="return confirm('¿Está seguro de eliminar el cliente \'<?= addslashes($cliente->nombre) ?>\'?\n\nEsta acción no se puede deshacer.')">
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
                                        <h5>No hay clientes disponibles</h5>
                                        <p>No se encontraron clientes que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/ClientController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primer Cliente
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
            window.location.href = '/RMIE/app/controllers/ClientController.php?accion=index';
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
                const clienteNombre = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
                if (confirm(`¿Está seguro de eliminar el cliente "${clienteNombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>