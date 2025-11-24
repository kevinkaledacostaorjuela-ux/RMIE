<?php
// app/views/auxiliar/reportes.php
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
    <title>Consultar Reportes - Auxiliar</title>
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
                <a class="nav-link-modern" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_ventas">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <span class="nav-text">Ventas (Consulta)</span>
                    <i class="nav-arrow fas fa-chevron-right"></i>
                </a>
            </li>
            <li class="nav-item-modern">
                <a class="nav-link-modern active" href="/RMIE/app/controllers/AuxiliarController.php?accion=consultar_reportes">
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
                <h1 class="h2"><i class="fas fa-chart-bar me-2"></i>Consultar Reportes (Solo Lectura)</h1>
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
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-file-alt"></i> Total Reportes</h5>
                            <h2><?php echo $stats['total_reportes'] ?? count($reportes ?? []); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-clock"></i> Pendientes</h5>
                            <h2><?php echo $stats['reportes_pendientes'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-check-circle"></i> Completados</h5>
                            <h2><?php echo $stats['reportes_completados'] ?? 0; ?></h2>
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
                        <input type="hidden" name="accion" value="consultar_reportes">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Estado:</label>
                                <select name="estado" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="completado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'completado') ? 'selected' : ''; ?>>Completado</option>
                                    <option value="revisado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'revisado') ? 'selected' : ''; ?>>Revisado</option>
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
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de reportes -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-list"></i> Listado de Reportes
                </div>
                <div class="card-body">
                    <?php if (isset($reportes) && count($reportes) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Fecha Creación</th>
                                        <th>Estado</th>
                                        <th>Prioridad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reportes as $reporte): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reporte->id_reportes ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($reporte->titulo ?? 'Sin título'); ?></td>
                                        <td><?php echo htmlspecialchars(substr($reporte->descripcion ?? '', 0, 50)) . '...'; ?></td>
                                        <td><?php echo htmlspecialchars($reporte->fecha_creacion ?? 'N/A'); ?></td>
                                        <td>
                                            <?php 
                                            $estado = $reporte->estado ?? 'pendiente';
                                            $badge_class = 'secondary';
                                            if ($estado == 'completado') $badge_class = 'success';
                                            elseif ($estado == 'revisado') $badge_class = 'info';
                                            elseif ($estado == 'pendiente') $badge_class = 'warning';
                                            ?>
                                            <span class="badge bg-<?php echo $badge_class; ?>"><?php echo ucfirst($estado); ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                            $prioridad = $reporte->prioridad ?? 'media';
                                            $badge_prio = 'secondary';
                                            if ($prioridad == 'alta') $badge_prio = 'danger';
                                            elseif ($prioridad == 'media') $badge_prio = 'warning';
                                            elseif ($prioridad == 'baja') $badge_prio = 'success';
                                            ?>
                                            <span class="badge bg-<?php echo $badge_prio; ?>"><?php echo ucfirst($prioridad); ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No se encontraron reportes con los criterios seleccionados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="fas fa-eye"></i> <strong>Modo Solo Lectura:</strong> Como auxiliar, solo puedes consultar la información de reportes. No puedes crear, editar o eliminar registros.
            </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
