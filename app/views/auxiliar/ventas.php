<?php
// app/views/auxiliar/ventas.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'auxiliar') {
    header('Location: ../../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Ventas - Auxiliar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../public/css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">
    <!-- Menú lateral moderno -->
    <nav class="sidebar-modern" id="sidebar">
        <div class="sidebar-header">
            <h4 class="sidebar-title">
                <i class="fas fa-user-shield"></i>
                <span>Panel Auxiliar</span>
            </h4>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=dashboard">
                    <i class="nav-icon fas fa-home"></i>
                    <span class="nav-text">Mi Dashboard</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern active" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_ventas">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <span class="nav-text">Ventas (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_reportes">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span class="nav-text">Reportes (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_alertas">
                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                    <span class="nav-text">Alertas (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_rutas">
                    <i class="nav-icon fas fa-route"></i>
                    <span class="nav-text">Rutas (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_usuarios">
                    <i class="nav-icon fas fa-users"></i>
                    <span class="nav-text">Usuarios (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern mt-3">
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil">
                    <i class="nav-icon fas fa-user-edit"></i>
                    <span class="nav-text">Mi Perfil</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern" href="/RMIE/logout.php" style="color: #ff6b6b;">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <span class="nav-text">Cerrar Sesión</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>

        <!-- Main content -->
        <main class="main-content">
            <div class="container-fluid px-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-shopping-cart me-2"></i>Consultar Ventas (Solo Lectura)</h1>
                <div>
                    <a href="/RMIE/app/views/dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Estadísticas rápidas -->
            <?php if (isset($stats)): ?>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar-day"></i> Ventas Hoy</h5>
                            <h2><?php echo $stats['ventas_hoy']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar-week"></i> Ventas Semana</h5>
                            <h2><?php echo $stats['ventas_semana']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar-alt"></i> Ventas Mes</h5>
                            <h2><?php echo $stats['ventas_mes']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-shopping-bag"></i> Total Ventas</h5>
                            <h2><?php echo $stats['total_ventas']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-filter"></i> Filtros de Búsqueda
                </div>
                <div class="card-body">
                    <form method="GET" action="/RMIE/app/controllers/AuxiliarController.php">
                        <input type="hidden" name="accion" value="consultar_ventas">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Estado:</label>
                                <select name="estado" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="completada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
                                    <option value="cancelada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Fecha Desde:</label>
                                <input type="date" name="fecha_desde" class="form-control" value="<?php echo $_GET['fecha_desde'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3">
                                <label>Fecha Hasta:</label>
                                <input type="date" name="fecha_hasta" class="form-control" value="<?php echo $_GET['fecha_hasta'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de ventas -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-list"></i> Listado de Ventas
                </div>
                <div class="card-body">
                    <?php if (isset($ventas) && count($ventas) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Total</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventas as $venta): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($venta->id_ventas ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($venta->id_clientes ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($venta->id_productos ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($venta->cantidad ?? '0'); ?></td>
                                        <td>$<?php echo number_format($venta->precio_total ?? 0, 2); ?></td>
                                        <td><?php echo htmlspecialchars($venta->fecha_venta ?? 'N/A'); ?></td>
                                        <td>
                                            <?php 
                                            $estado = $venta->estado ?? 'pendiente';
                                            $badge_class = 'secondary';
                                            if ($estado == 'completada') $badge_class = 'success';
                                            elseif ($estado == 'cancelada') $badge_class = 'danger';
                                            elseif ($estado == 'pendiente') $badge_class = 'warning';
                                            ?>
                                            <span class="badge bg-<?php echo $badge_class; ?>"><?php echo ucfirst($estado); ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No se encontraron ventas con los criterios seleccionados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="fas fa-eye"></i> <strong>Modo Solo Lectura:</strong> Como auxiliar, solo puedes consultar la información de ventas. No puedes crear, editar o eliminar registros.
            </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
