<?php
session_start();
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
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad mínima</th>
                    <th>Fecha caducidad</th>
                    <th>ID Cliente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($alertas)): ?>
                    <?php foreach ($alertas as $alerta): ?>
                    <tr>
                        <td><?= htmlspecialchars($alerta['id_alertas']) ?></td>
                        <td><?= htmlspecialchars($alerta['producto_nombre'] ?? $alerta['id_productos']) ?></td>
                        <td><?= htmlspecialchars($alerta['cantidad_minima'] ?? '') ?></td>
                        <td><?= htmlspecialchars($alerta['fecha_caducidad'] ?? '') ?></td>
                        <td><?= htmlspecialchars($alerta['id_clientes']) ?></td>
                        <td>
                            <a href="/RMIE/app/controllers/AlertController.php?action=edit&id=<?= urlencode($alerta['id_alertas']) ?>" class="btn-categorias">Editar</a>
                            <a href="/RMIE/app/controllers/AlertController.php?action=delete&id=<?= urlencode($alerta['id_alertas']) ?>" class="btn-categorias" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay alertas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
