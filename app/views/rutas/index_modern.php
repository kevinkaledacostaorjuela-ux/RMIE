<?php
// Vista moderna de rutas - RMIE v3.0
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Calcular estadísticas de rutas
$totalRutas = count($rutas ?? []);
$rutasActivas = 0;
$rutasInactivas = 0;
$rutasPendientes = 0;
$rutasCompletadas = 0;
$rutasRecientes = 0;

$fechaReciente = date('Y-m-d', strtotime('-7 days'));

if (isset($rutas) && is_array($rutas)) {
    foreach ($rutas as $ruta) {
        switch (strtolower($ruta['estado'] ?? 'pendiente')) {
            case 'activa':
                $rutasActivas++;
                break;
            case 'inactiva':
                $rutasInactivas++;
                break;
            case 'pendiente':
                $rutasPendientes++;
                break;
            case 'completada':
                $rutasCompletadas++;
                break;
        }
        
        if (!empty($ruta['fecha_creacion']) && $ruta['fecha_creacion'] >= $fechaReciente) {
            $rutasRecientes++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rutas - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="rutas-container">
        <!-- Breadcrumb de Navegación -->
        <div class="rutas-breadcrumb">
            <a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <span class="separator">/</span>
            <span>Gestión de Rutas</span>
        </div>

        <!-- Header con Título y Botones de Navegación -->
        <div class="rutas-header">
            <div>
                <h1><i class="fas fa-route"></i> Gestión de Rutas</h1>
                <p>Administra las rutas de entrega y optimiza la logística</p>
            </div>
            <div class="rutas-nav-buttons">
                <a href="/RMIE/app/views/dashboard.php" class="btn-rutas btn-rutas-info">
                    <i class="fas fa-home"></i> Volver al Dashboard
                </a>
                <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=create" class="btn-rutas btn-rutas-success">
                    <i class="fas fa-plus"></i> Nueva Ruta
                </a>
            </div>
        </div>

        <!-- Mensajes de Estado -->
        <?php if (!empty($success_message)): ?>
            <div class="rutas-alert rutas-alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="rutas-alert rutas-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Panel de Estadísticas -->
        <div class="rutas-stats">
            <div class="stat-card-rutas">
                <span class="stat-number-rutas"><?php echo $totalRutas; ?></span>
                <span class="stat-label-rutas"><i class="fas fa-route"></i> Total Rutas</span>
            </div>
            <div class="stat-card-rutas">
                <span class="stat-number-rutas"><?php echo $rutasActivas; ?></span>
                <span class="stat-label-rutas"><i class="fas fa-play-circle"></i> Activas</span>
            </div>
            <div class="stat-card-rutas">
                <span class="stat-number-rutas"><?php echo $rutasPendientes; ?></span>
                <span class="stat-label-rutas"><i class="fas fa-clock"></i> Pendientes</span>
            </div>
            <div class="stat-card-rutas">
                <span class="stat-number-rutas"><?php echo $rutasCompletadas; ?></span>
                <span class="stat-label-rutas"><i class="fas fa-check-double"></i> Completadas</span>
            </div>
            <div class="stat-card-rutas">
                <span class="stat-number-rutas"><?php echo $rutasRecientes; ?></span>
                <span class="stat-label-rutas"><i class="fas fa-calendar-week"></i> Esta Semana</span>
            </div>
        </div>

        <!-- Filtros Avanzados -->
        <div class="rutas-filters">
            <form method="GET" action="/RMIE/app/controllers/RouteControllerModern.php" class="fade-in-rutas">
                <input type="hidden" name="accion" value="index">
                
                <div class="filter-row">
                    <div class="form-group">
                        <label for="cliente"><i class="fas fa-user"></i> Cliente</label>
                        <select name="cliente" id="cliente" class="form-control">
                            <option value="">Todos los clientes</option>
                            <?php if (isset($available_clients) && is_array($available_clients)): ?>
                                <?php foreach ($available_clients as $cliente): ?>
                                    <option value="<?php echo htmlspecialchars($cliente['nombre']); ?>"
                                        <?php echo (isset($_GET['cliente']) && $_GET['cliente'] === $cliente['nombre']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cliente['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="local"><i class="fas fa-store"></i> Local</label>
                        <select name="local" id="local" class="form-control">
                            <option value="">Todos los locales</option>
                            <?php if (isset($available_locals) && is_array($available_locals)): ?>
                                <?php foreach ($available_locals as $local): ?>
                                    <option value="<?php echo htmlspecialchars($local['nombre_local']); ?>"
                                        <?php echo (isset($_GET['local']) && $_GET['local'] === $local['nombre_local']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($local['nombre_local']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estado"><i class="fas fa-flag"></i> Estado</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="activa" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'activa') ? 'selected' : ''; ?>>Activa</option>
                            <option value="inactiva" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'inactiva') ? 'selected' : ''; ?>>Inactiva</option>
                            <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="completada" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'completada') ? 'selected' : ''; ?>>Completada</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dia"><i class="fas fa-calendar-day"></i> Día</label>
                        <select name="dia" id="dia" class="form-control">
                            <option value="">Todos los días</option>
                            <option value="Lunes" <?php echo (isset($_GET['dia']) && $_GET['dia'] === 'Lunes') ? 'selected' : ''; ?>>Lunes</option>
                            <option value="Martes" <?php echo (isset($_GET['dia']) && $_GET['dia'] === 'Martes') ? 'selected' : ''; ?>>Martes</option>
                            <option value="Miercoles" <?php echo (isset($_GET['dia']) && $_GET['dia'] === 'Miercoles') ? 'selected' : ''; ?>>Miércoles</option>
                            <option value="Jueves" <?php echo (isset($_GET['dia']) && $_GET['dia'] === 'Jueves') ? 'selected' : ''; ?>>Jueves</option>
                            <option value="Viernes" <?php echo (isset($_GET['dia']) && $_GET['dia'] === 'Viernes') ? 'selected' : ''; ?>>Viernes</option>
                            <option value="Sabado" <?php echo (isset($_GET['dia']) && $_GET['dia'] === 'Sabado') ? 'selected' : ''; ?>>Sábado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="buscar"><i class="fas fa-search"></i> Búsqueda</label>
                        <input type="text" name="buscar" id="buscar" class="form-control" 
                               placeholder="Buscar por dirección, cliente o local..."
                               value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>">
                    </div>

                    <div class="form-group" style="align-self: end;">
                        <button type="submit" class="btn-rutas btn-rutas-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index" class="btn-rutas btn-rutas-warning">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de Rutas -->
        <div class="rutas-table-container">
            <table class="rutas-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Cliente</th>
                        <th><i class="fas fa-store"></i> Local</th>
                        <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                        <th><i class="fas fa-calendar-day"></i> Día</th>
                        <th><i class="fas fa-flag"></i> Estado</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($rutas_agrupadas) && is_array($rutas_agrupadas) && count($rutas_agrupadas) > 0): ?>
                        <?php foreach ($rutas_agrupadas as $dia_grupo): ?>
                            <tr class="fade-in-rutas">
                                <td>
                                    <span class="badge bg-secondary">#<?php echo count($dia_grupo['rutas']); ?></span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo $dia_grupo['total_clientes']; ?> Cliente(s)</strong>
                                        <br><small class="text-muted">
                                            <?php 
                                            $clientes_nombres = array_unique(array_column($dia_grupo['rutas'], 'cliente_nombre'));
                                            $clientes_nombres = array_filter($clientes_nombres);
                                            echo implode(', ', array_slice($clientes_nombres, 0, 2));
                                            if (count($clientes_nombres) > 2) echo '...';
                                            ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo $dia_grupo['total_locales']; ?> Local(es)</strong>
                                        <br><small class="text-muted">
                                            <?php 
                                            $locales_nombres = array_unique(array_column($dia_grupo['rutas'], 'local_nombre'));
                                            $locales_nombres = array_filter($locales_nombres);
                                            echo implode(', ', array_slice($locales_nombres, 0, 2));
                                            if (count($locales_nombres) > 2) echo '...';
                                            ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="ruta-tooltip">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <?php echo count($dia_grupo['rutas']); ?> direcciones
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $dia = $dia_grupo['dia'];
                                    $esHoy = (isset($dia_hoy) && $dia === $dia_hoy);
                                    ?>
                                    <span class="dia-semana <?php echo $esHoy ? 'dia-hoy' : ''; ?>">
                                        <i class="fas fa-calendar-day"></i> <?php echo htmlspecialchars($dia); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    // Mostrar estado más común del día
                                    $estados = array_column($dia_grupo['rutas'], 'estado');
                                    $estado_comun = array_count_values($estados);
                                    arsort($estado_comun);
                                    $estado = strtolower(array_keys($estado_comun)[0] ?? 'pendiente');
                                    $iconos = [
                                        'activa' => 'play-circle',
                                        'inactiva' => 'pause-circle', 
                                        'pendiente' => 'clock',
                                        'completada' => 'check-circle',
                                        'cancelada' => 'times-circle'
                                    ];
                                    $icono = $iconos[$estado] ?? 'question-circle';
                                    ?>
                                    <span class="estado-ruta estado-<?php echo $estado; ?>">
                                        <i class="fas fa-<?php echo $icono; ?>"></i>
                                        <?php echo ucfirst($estado); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    // Usar la primera ruta del grupo para las acciones
                                    $primera_ruta = $dia_grupo['rutas'][0];
                                    ?>
                                    <div class="d-flex gap-1">
                                        <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=view&id=<?php echo $primera_ruta['id_ruta']; ?>" 
                                           class="btn-rutas btn-rutas-info" 
                                           title="Ver todas las rutas del <?php echo $dia_grupo['dia']; ?>"
                                           style="position: relative; z-index: 999; pointer-events: auto !important;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=edit&id=<?php echo $primera_ruta['id_ruta']; ?>" 
                                           class="btn-rutas btn-rutas-success" 
                                           title="Editar rutas del <?php echo $dia_grupo['dia']; ?>"
                                           style="position: relative; z-index: 999; pointer-events: auto !important;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Botón para completar todo el día -->
                                        <?php 
                                        $hay_pendientes = false;
                                        foreach ($dia_grupo['rutas'] as $r) {
                                            if (strtolower($r['estado']) !== 'completada') {
                                                $hay_pendientes = true;
                                                break;
                                            }
                                        }
                                        if ($hay_pendientes): ?>
                                            <button onclick="completarTodoElDia('<?php echo $dia_grupo['dia']; ?>')" 
                                                    class="btn-rutas btn-rutas-primary" 
                                                    title="Completar todas las rutas del <?php echo $dia_grupo['dia']; ?>"
                                                    style="position: relative; z-index: 999; pointer-events: auto !important; border: none; cursor: pointer;">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                            <button onclick="eliminarDia('<?php echo $dia_grupo['dia']; ?>')"
                                                    class="btn-rutas btn-rutas-danger" 
                                                    title="Eliminar todas las rutas del <?php echo $dia_grupo['dia']; ?>"
                                                    style="position: relative; z-index: 999; pointer-events: auto !important; border: none; cursor: pointer;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-route fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay rutas registradas</h5>
                                    <p class="text-muted">Comienza creando una nueva ruta para gestionar tus entregas</p>
                                    <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=create" class="btn-rutas btn-rutas-primary">
                                        <i class="fas fa-plus"></i> Crear Primera Ruta
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Planificación Semanal -->
        <?php if (isset($asignaciones_clientes) && !empty($asignaciones_clientes)): ?>
            <div class="mt-4">
                <h3><i class="fas fa-calendar-week"></i> Planificación Semanal</h3>
                <div class="planificacion-semanal">
                    <?php 
                    $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                    foreach ($dias as $dia): 
                        $clientesDia = $asignaciones_clientes[$dia] ?? [];
                    ?>
                        <div class="dia-planificacion">
                            <h4><?php echo $dia; ?></h4>
                            <div class="clientes-dia">
                                <?php if (!empty($clientesDia)): ?>
                                    <?php foreach ($clientesDia as $clienteId): ?>
                                        <?php 
                                        // Buscar el cliente en la lista de disponibles
                                        $clienteInfo = null;
                                        if (isset($available_clients)) {
                                            foreach ($available_clients as $cliente) {
                                                if ($cliente['id_clientes'] == $clienteId) {
                                                    $clienteInfo = $cliente;
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="cliente-asignado">
                                            <span>
                                                <i class="fas fa-user"></i>
                                                <?php echo $clienteInfo ? htmlspecialchars($clienteInfo['nombre']) : "Cliente #$clienteId"; ?>
                                            </span>
                                            <small class="text-muted">
                                                <?php echo $clienteInfo && !empty($clienteInfo['cel_cliente']) ? htmlspecialchars($clienteInfo['cel_cliente']) : ''; ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-muted text-center">
                                        <i class="fas fa-calendar-times"></i>
                                        Sin rutas programadas
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Botones flotantes para acciones rápidas -->
        <div class="floating-actions">
            <div class="floating-btn" onclick="window.location.href='/RMIE/app/controllers/RouteControllerModern.php?accion=create'" title="Crear nueva ruta">
                <i class="fas fa-plus"></i>
            </div>
            <div class="floating-btn floating-btn-secondary" onclick="window.location.href='/RMIE/app/views/dashboard.php'" title="Volver al Dashboard">
                <i class="fas fa-home"></i>
            </div>
        </div>
    </div>

    <!-- CSS adicional para agrupación -->
    <style>
        .day-details {
            background-color: #f8f9fa;
        }
        
        .day-routes-container {
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 10px 0;
        }
        
        .day-routes-container h6 {
            color: #007bff;
            margin-bottom: 15px;
        }
        
        .card-sm {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .card-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .direccion-info {
            font-size: 0.8em;
            color: #6c757d;
        }
        
        .estado-pendiente { color: #ffc107; }
        .estado-activa { color: #28a745; }
        .estado-completada { color: #17a2b8; }
        .estado-cancelada { color: #dc3545; }
        
        /* Fix para asegurar que los botones sean clickeables */
        .btn-rutas {
            pointer-events: auto !important;
            cursor: pointer !important;
            position: relative !important;
            z-index: 1000 !important;
            display: inline-flex !important;
        }
        
        .btn-rutas:hover {
            cursor: pointer !important;
        }
        
        .d-flex.gap-1 {
            z-index: 999 !important;
            position: relative !important;
        }
        
        /* Asegurar que nada bloquee los clicks */
        td {
            position: relative;
        }
    </style>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Auto-ocultar alertas después de 5 segundos
            setTimeout(function() {
                $('.rutas-alert').fadeOut();
            }, 5000);
            
            // Efecto de carga para la tabla
            $('.rutas-table tbody tr').each(function(index) {
                $(this).delay(index * 50).fadeIn();
            });
            
            // Tooltip para direcciones largas
            $('.ruta-tooltip').hover(function() {
                $(this).attr('title', $(this).data('tooltip'));
            });
        });
        
        // Función para mostrar/ocultar rutas de un día específico
        function toggleDayRoutes(dayId) {
            const dayRow = document.getElementById(dayId);
            if (dayRow.style.display === 'none' || dayRow.style.display === '') {
                dayRow.style.display = 'table-row';
            } else {
                dayRow.style.display = 'none';
            }
        }
        
        // Función para completar todas las rutas de un día
        function completarTodoElDia(dia) {
            if (confirm(`¿Está seguro de completar TODAS las rutas del ${dia}?\n\nEsta acción marcará todas las rutas del día como completadas.`)) {
                // Hacer petición para completar todas las rutas del día
                window.location.href = `/RMIE/app/controllers/RouteControllerModern.php?accion=completar_dia&dia=${dia}`;
            }
        }
        
        // Función para eliminar todas las rutas de un día
        function eliminarDia(dia) {
            if (confirm(`¿Está seguro de ELIMINAR todas las rutas del ${dia}?\n\n⚠️ ADVERTENCIA: Esta acción no se puede deshacer y eliminará permanentemente todas las rutas del día.`)) {
                if (confirm(`¿REALMENTE desea eliminar TODAS las rutas del ${dia}?\n\nEsta es su última oportunidad para cancelar.`)) {
                    window.location.href = `/RMIE/app/controllers/RouteControllerModern.php?accion=eliminar_dia&dia=${dia}`;
                }
            }
        }
    </script>
</body>
</html>