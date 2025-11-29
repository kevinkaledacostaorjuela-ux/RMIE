<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Obtener día en español
$dias_esp = [
    'Monday' => 'Lunes',
    'Tuesday' => 'Martes', 
    'Wednesday' => 'Miércoles',
    'Thursday' => 'Jueves',
    'Friday' => 'Viernes',
    'Saturday' => 'Sábado',
    'Sunday' => 'Domingo'
];

$dia_mostrar = $dias_esp[$dia_seleccionado] ?? $dia_seleccionado;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rutas del <?php echo $dia_mostrar; ?> - RMIE</title>
    <link href="/RMIE/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header del día -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="fas fa-route text-primary"></i> Rutas del <?php echo $dia_mostrar; ?></h2>
                <p class="text-muted">Total de rutas: <span class="badge bg-primary"><?php echo $total_rutas; ?></span></p>
            </div>
            <div>
                <a href="/RMIE/rutas.php?accion=index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Panel General
                </a>
                <a href="/RMIE/rutas.php?accion=create" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nueva Ruta
                </a>
            </div>
        </div>

        <!-- Navegación entre días -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title">Cambiar día:</h6>
                <div class="btn-group" role="group">
                    <?php 
                    $dias_semana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                    foreach ($dias_semana as $dia): 
                        $activo = ($dia === $dia_seleccionado) ? 'active' : '';
                    ?>
                        <a href="/RMIE/rutas.php?accion=dia&dia=<?php echo $dia; ?>" 
                           class="btn btn-outline-primary <?php echo $activo; ?>">
                            <?php echo $dias_esp[$dia] ?? $dia; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Lista de rutas -->
        <?php if (empty($rutas)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                No hay rutas planificadas para el <?php echo $dia_mostrar; ?>.
                <a href="/RMIE/rutas.php?accion=index" class="alert-link">Configure su planificación semanal</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($rutas as $ruta): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    Ruta #<?php echo $ruta['id_ruta']; ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Cliente:</strong> <?php echo htmlspecialchars($ruta['cliente_nombre'] ?? 'N/A'); ?>
                                </p>
                                <p class="card-text">
                                    <strong>Local:</strong> <?php echo htmlspecialchars($ruta['local_nombre'] ?? 'N/A'); ?>
                                </p>
                                <p class="card-text">
                                    <strong>Dirección:</strong> <?php echo htmlspecialchars($ruta['direccion'] ?? 'N/A'); ?>
                                </p>
                                <p class="card-text">
                                    <strong>Estado:</strong> 
                                    <span class="badge bg-<?php 
                                        echo match($ruta['estado'] ?? 'pendiente') {
                                            'activa' => 'success',
                                            'completada' => 'primary',
                                            'pendiente' => 'warning',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($ruta['estado'] ?? 'pendiente'); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group btn-group-sm w-100" role="group">
                                    <a href="/RMIE/rutas.php?accion=edit&id=<?php echo $ruta['id_ruta']; ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($ruta['estado'] !== 'completada'): ?>
                                        <a href="/RMIE/rutas.php?accion=complete&id=<?php echo $ruta['id_ruta']; ?>" 
                                           class="btn btn-outline-success">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="/RMIE/rutas.php?accion=delete&id=<?php echo $ruta['id_ruta']; ?>" 
                                       class="btn btn-outline-danger"
                                       onclick="return confirm('¿Está seguro de eliminar esta ruta?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas del día -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Resumen del <?php echo $dia_mostrar; ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <h4 class="text-primary"><?php echo $total_rutas; ?></h4>
                                    <p class="text-muted">Total Rutas</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <h4 class="text-success">
                                        <?php echo count(array_filter($rutas, fn($r) => $r['estado'] === 'completada')); ?>
                                    </h4>
                                    <p class="text-muted">Completadas</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <h4 class="text-warning">
                                        <?php echo count(array_filter($rutas, fn($r) => $r['estado'] === 'pendiente')); ?>
                                    </h4>
                                    <p class="text-muted">Pendientes</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <h4 class="text-info">
                                        <?php echo count(array_filter($rutas, fn($r) => $r['estado'] === 'activa')); ?>
                                    </h4>
                                    <p class="text-muted">Activas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>