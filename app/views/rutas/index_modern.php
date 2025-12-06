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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <style>
        /* Estilos para mejorar la visibilidad del texto en los filtros */
        .rutas-filters .form-group label {
            color: #2c3e50 !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            margin-bottom: 8px !important;
            display: block !important;
        }
        
        .select2-container .select2-selection--single {
            background-color: #ffffff !important;
            border: 2px solid #e1e8ed !important;
            border-radius: 8px !important;
            height: 42px !important;
            line-height: 42px !important;
        }
        
        .select2-container .select2-selection--single .select2-selection__rendered {
            color: #2c3e50 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        
        .select2-container .select2-selection--single .select2-selection__placeholder {
            color: #6c757d !important;
            font-style: italic !important;
        }
        
        .select2-container--default .select2-selection--single:focus {
            border-color: #4a90e2 !important;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1) !important;
        }
        
        .select2-dropdown {
            border: 2px solid #e1e8ed !important;
            border-radius: 8px !important;
        }
        
        .select2-results__option {
            color: #2c3e50 !important;
            font-size: 14px !important;
            padding: 10px 12px !important;
        }
        
        .select2-results__option--highlighted {
            background-color: #4a90e2 !important;
            color: #ffffff !important;
        }
        
        /* Estilos para select normales también */
        .form-control {
            color: #2c3e50 !important;
            background-color: #ffffff !important;
            border: 2px solid #e1e8ed !important;
            font-size: 14px !important;
            font-weight: 500 !important;
        }
        
        .form-control option {
            color: #2c3e50 !important;
            background-color: #ffffff !important;
        }
    </style>
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
                        <select name="cliente" id="cliente" class="form-control cliente-filter-select">
                            <option value="">Buscar cliente por nombre...</option>
                            <?php if (isset($available_clients) && is_array($available_clients)): ?>
                                <?php foreach ($available_clients as $cliente): ?>
                                    <option value="<?php echo htmlspecialchars($cliente['id_clientes']); ?>" <?php echo (isset($_GET['cliente']) && $_GET['cliente'] == $cliente['id_clientes']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cliente['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="local"><i class="fas fa-store"></i> Local</label>
                        <select name="local" id="local" class="form-control local-filter-select">
                            <option value="">Buscar local por nombre...</option>
                            <?php if (isset($available_locals) && is_array($available_locals)): ?>
                                <?php foreach ($available_locals as $local): ?>
                                    <option value="<?php echo htmlspecialchars($local['id_locales']); ?>" <?php echo (isset($_GET['local']) && $_GET['local'] == $local['id_locales']) ? 'selected' : ''; ?>>
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
                                        <strong><?php echo $dia_grupo['total_locales']; ?> Parada(s)</strong>
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
                                        <?php echo count($dia_grupo['rutas']); ?> ruta(s)
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
                                    
                                    // Debug temporal - eliminar después
                                    if (empty($estados) || !array_filter($estados)) {
                                        // Si no hay estados o están vacíos, usar estado por defecto
                                        $estado = 'pendiente';
                                    } else {
                                        $estado_comun = array_count_values(array_filter($estados));
                                        arsort($estado_comun);
                                        $estado = strtolower(array_keys($estado_comun)[0] ?? 'pendiente');
                                    }
                                    
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
                                        <button onclick="verRutaEnMapa('<?php echo $dia_grupo['dia']; ?>')" 
                                                class="btn-rutas btn-rutas-warning" 
                                                title="Ver rutas del <?php echo $dia_grupo['dia']; ?> en el mapa"
                                                style="position: relative; z-index: 999; pointer-events: auto !important; border: none; cursor: pointer;">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </button>
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
        
        .estado-ruta {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            margin-bottom: 0;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.5;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
            border: 1px solid transparent;
        }
        
        .estado-pendiente { 
            background-color: #ffc107; 
            border-color: #ffc107;
            color: #212529;
        }
        .estado-activa { 
            background-color: #28a745; 
            border-color: #28a745;
            color: #fff;
        }
        .estado-completada { 
            background-color: #17a2b8; 
            border-color: #17a2b8;
            color: #fff;
        }
        .estado-cancelada { 
            background-color: #dc3545; 
            border-color: #dc3545;
            color: #fff;
        }
        .estado-inactiva { 
            background-color: #6c757d; 
            border-color: #6c757d;
            color: #fff;
        }
        
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

    <!-- Modal para mostrar rutas en mapa -->
    <div id="mapaModal" class="modal-mapa" style="display: none;">
        <div class="modal-content-mapa">
            <div class="modal-header-mapa">
                <h5><i class="fas fa-map-marked-alt"></i> Ver Rutas del <span id="modalDia">Día</span> en Mapa</h5>
                <button onclick="cerrarMapaModal()" class="btn-close-mapa">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-mapa">
                <div id="mapaLoader" class="text-center p-4" style="display: none;">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                    <p>Cargando rutas en el mapa...</p>
                </div>
                <div id="mapaContent" style="display: none;">
                    <!-- Contenido del mapa se carga aquí -->
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-mapa {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content-mapa {
            background: white;
            border-radius: 12px;
            width: 95%;
            max-width: 1200px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header-mapa {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-mapa h5 {
            margin: 0;
            font-weight: 600;
        }

        .btn-close-mapa {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .btn-close-mapa:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body-mapa {
            padding: 20px;
            max-height: calc(90vh - 80px);
            overflow-y: auto;
        }

        .ruta-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .ruta-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
            border-color: #007bff;
        }

        .rutas-list::-webkit-scrollbar {
            width: 6px;
        }

        .rutas-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .rutas-list::-webkit-scrollbar-thumb {
            background: #007bff;
            border-radius: 3px;
        }

        .rutas-list::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .ruta-item {
            transition: all 0.3s ease;
        }
        
        .ruta-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-lg {
            min-width: 200px;
        }
        
        .btn-success:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .alert-success h6 {
            color: #155724;
            margin-bottom: 10px;
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

            // Inicializar Select2 para filtro de clientes
            $('#cliente').select2({
                placeholder: 'Buscar cliente por nombre...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/RMIE/app/api/get_clientes_edit.php',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results || [],
                            pagination: {
                                more: (params.page * 50) < (data.total_count || 0)
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 0,
                templateResult: function(cliente) {
                    if (cliente.loading) return cliente.text;
                    return cliente.nombre || cliente.text;
                },
                templateSelection: function(cliente) {
                    return cliente.nombre || cliente.text || 'Buscar cliente por nombre...';
                }
            });

            // Inicializar Select2 para filtro de locales
            $('#local').select2({
                placeholder: 'Buscar local por nombre...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/RMIE/app/api/get_locales_edit.php',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results || [],
                            pagination: {
                                more: (params.page * 50) < (data.total_count || 0)
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 0,
                templateResult: function(local) {
                    if (local.loading) return local.text;
                    return local.nombre_local || local.text;
                },
                templateSelection: function(local) {
                    return local.nombre_local || local.text || 'Buscar local por nombre...';
                }
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
            if (confirm(`¿Estás seguro de que quieres marcar como completadas todas las rutas del ${dia}?`)) {
                // Hacer petición para completar todas las rutas del día
                window.location.href = `/RMIE/app/controllers/RouteControllerModern.php?accion=completar_dia&dia=${dia}`;
            }
        }
        
        // Función para eliminar todas las rutas de un día
        function eliminarDia(dia) {
            if (confirm(`¿Estás seguro de que quieres eliminar TODAS las rutas del ${dia}?`)) {
                if (confirm('Esta acción no se puede deshacer. ¿Confirmas que quieres proceder?')) {
                    window.location.href = `/RMIE/app/controllers/RouteControllerModern.php?accion=eliminar_dia&dia=${dia}`;
                }
            }
        }

        // Función para ver ruta en mapa
        function verRutaEnMapa(dia) {
            // Mostrar modal de carga
            document.getElementById('mapaModal').style.display = 'flex';
            document.getElementById('mapaLoader').style.display = 'block';
            document.getElementById('mapaContent').style.display = 'none';
            document.getElementById('modalDia').textContent = dia;
            
            // Obtener rutas del día
            fetch(`/RMIE/app/api/get_rutas_dia.php?dia=${encodeURIComponent(dia)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.rutas.length > 0) {
                        mostrarRutasEnMapa(data.rutas, dia);
                    } else {
                        document.getElementById('mapaLoader').style.display = 'none';
                        document.getElementById('mapaContent').innerHTML = `
                            <div class="text-center p-4">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5>No hay rutas para el ${dia}</h5>
                                <p class="text-muted">No se encontraron rutas configuradas para este día.</p>
                            </div>
                        `;
                        document.getElementById('mapaContent').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('mapaLoader').style.display = 'none';
                    document.getElementById('mapaContent').innerHTML = `
                        <div class="text-center p-4">
                            <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                            <h5>Error al cargar rutas</h5>
                            <p class="text-muted">Ocurrió un error al obtener las rutas del día.</p>
                        </div>
                    `;
                    document.getElementById('mapaContent').style.display = 'block';
                });
        }

        // Función para mostrar rutas en mapa
        function mostrarRutasEnMapa(rutas, dia) {
            // Eliminar duplicados basándose en id_ruta por seguridad adicional
            const rutasUnicas = rutas.filter((ruta, index, arr) => 
                arr.findIndex(r => r.id_ruta === ruta.id_ruta) === index
            );
            
            let mapaHtml = `
                <div class="row">
                    <div class="col-md-4">
                        <h6><i class="fas fa-list"></i> Rutas del ${dia} (${rutasUnicas.length})</h6>
                        <div class="rutas-list" style="max-height: 400px; overflow-y: auto;">
            `;
            
            let direcciones = [];
            rutasUnicas.forEach((ruta, index) => {
                const direccion = ruta.direccion || 'Dirección no especificada';
                direcciones.push(direccion);
                
                mapaHtml += `
                    <div class="card mb-2 ruta-item" data-index="${index}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><i class="fas fa-user text-primary"></i> ${ruta.nombre_cliente || 'Cliente N/A'}</strong><br>
                                    <small><i class="fas fa-store text-success"></i> ${ruta.nombre_local || 'Local N/A'}</small><br>
                                    <small><i class="fas fa-map-marker-alt text-warning"></i> ${direccion}</small>
                                </div>
                                <span class="badge bg-primary">${index + 1}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            mapaHtml += `
                        </div>
                        <div class="mt-3 text-center">
                            <button onclick="abrirEnGoogleMaps('${dia}')" class="btn btn-success btn-lg mb-2">
                                <i class="fas fa-location-arrow"></i> Ver Ruta desde Mi Ubicación
                            </button>
                            <button onclick="abrirEnGoogleMapsSinUbicacion('${dia}')" class="btn btn-secondary btn-lg mb-2 ml-2">
                                <i class="fas fa-route"></i> Ver Ruta Normal
                            </button>
                            <br>
                            <button onclick="copiarDirecciones()" class="btn btn-info btn-lg">
                                <i class="fas fa-copy"></i> Copiar Direcciones
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6><i class="fas fa-info-circle"></i> Información de la Ruta</h6>
                    <div class="alert alert-info">
                        <p><strong>Total de paradas:</strong> ${rutasUnicas.length}</p>
                        <p><strong>Primera dirección:</strong> ${direcciones[0] || 'No especificada'}</p>
                        <p><strong>Última dirección:</strong> ${direcciones[direcciones.length - 1] || 'No especificada'}</p>
                    </div>
                    <div class="alert alert-success">
                        <h6><i class="fas fa-map-marked-alt"></i> Opciones de Navegación:</h6>
                        <p><strong><i class="fas fa-location-arrow text-success"></i> Desde Mi Ubicación:</strong> Usa tu ubicación actual como punto de partida hacia todas las direcciones.</p>
                        <p class="mb-0"><strong><i class="fas fa-route text-secondary"></i> Ruta Normal:</strong> Crea una ruta entre las direcciones de la lista, empezando por la primera.</p>
                    </div>
                </div>
            `;
            
            document.getElementById('mapaContent').innerHTML = mapaHtml;
            
            // Guardar rutas únicas para uso posterior
            window.rutasActuales = rutasUnicas;
            
            document.getElementById('mapaLoader').style.display = 'none';
            document.getElementById('mapaContent').style.display = 'block';
        }

        // Función para abrir en Google Maps con ubicación actual
        function abrirEnGoogleMaps(dia) {
            if (!window.rutasActuales || window.rutasActuales.length === 0) {
                alert('No hay rutas disponibles para mostrar');
                return;
            }
            
            const direcciones = window.rutasActuales.map(ruta => ruta.direccion).filter(d => d && d.trim() !== '');
            if (direcciones.length === 0) {
                alert('No hay direcciones válidas para crear la ruta');
                return;
            }
            
            // Mostrar mensaje de carga
            const btnMaps = event.target;
            const originalText = btnMaps.innerHTML;
            btnMaps.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Obteniendo ubicación...';
            btnMaps.disabled = true;
            
            // Obtener ubicación actual
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        // Éxito: usar ubicación actual como origen
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const origin = `${lat},${lng}`;
                        
                        crearRutaConOrigen(origin, direcciones);
                        
                        // Restaurar botón
                        btnMaps.innerHTML = originalText;
                        btnMaps.disabled = false;
                    },
                    function(error) {
                        // Error o denegado: usar primera dirección como origen
                        console.log('Geolocalización no disponible o denegada:', error.message);
                        const origin = direcciones[0];
                        crearRutaConOrigen(origin, direcciones);
                        
                        // Restaurar botón
                        btnMaps.innerHTML = originalText;
                        btnMaps.disabled = false;
                    },
                    {
                        timeout: 10000,
                        enableHighAccuracy: true,
                        maximumAge: 300000 // 5 minutos
                    }
                );
            } else {
                // Navegador no soporta geolocalización
                alert('Tu navegador no soporta geolocalización. Se usará la primera dirección como origen.');
                const origin = direcciones[0];
                crearRutaConOrigen(origin, direcciones);
                
                // Restaurar botón
                btnMaps.innerHTML = originalText;
                btnMaps.disabled = false;
            }
        }
        
        // Función para abrir Google Maps sin ubicación actual (ruta normal)
        function abrirEnGoogleMapsSinUbicacion(dia) {
            if (!window.rutasActuales || window.rutasActuales.length === 0) {
                alert('No hay rutas disponibles para mostrar');
                return;
            }
            
            const direcciones = window.rutasActuales.map(ruta => ruta.direccion).filter(d => d && d.trim() !== '');
            if (direcciones.length === 0) {
                alert('No hay direcciones válidas para crear la ruta');
                return;
            }
            
            // Usar primera dirección como origen
            const origin = direcciones[0];
            crearRutaConOrigen(origin, direcciones.slice(1));
        }
        
        // Función auxiliar para crear la ruta con el origen determinado
        function crearRutaConOrigen(origin, direcciones) {
            let url = `https://www.google.com/maps/dir/${encodeURIComponent(origin)}`;
            
            // Agregar todas las direcciones como destinos
            direcciones.forEach(direccion => {
                url += `/${encodeURIComponent(direccion)}`;
            });
            
            window.open(url, '_blank');
        }
        
        // Función para copiar direcciones al portapapeles
        function copiarDirecciones() {
            if (!window.rutasActuales || window.rutasActuales.length === 0) {
                alert('No hay rutas disponibles');
                return;
            }
            
            const direcciones = window.rutasActuales.map((ruta, index) => 
                `${index + 1}. ${ruta.nombre_cliente || 'Cliente N/A'} - ${ruta.nombre_local || 'Local N/A'}\n   📍 ${ruta.direccion || 'Dirección no especificada'}`
            ).join('\n\n');
            
            const textoCompleto = `🗺️ RUTA DEL DÍA\n\n📍 PUNTO DE PARTIDA:\n   • Para navegación GPS: Usar "Desde Mi Ubicación"\n   • Para planificación: Primera dirección de la lista\n\n🚗 DIRECCIONES A VISITAR:\n${direcciones}\n\n📊 Total de paradas: ${window.rutasActuales.length}\n\n💡 TIP: Usa "Ver Ruta desde Mi Ubicación" para navegación GPS optimizada`;
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textoCompleto).then(() => {
                    // Mostrar mensaje de éxito
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-success';
                    toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; animation: slideIn 0.3s ease-out; max-width: 350px;';
                    toast.innerHTML = '<i class="fas fa-check"></i> Ruta completa copiada al portapapeles';
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                }).catch(() => {
                    alert('Error al copiar al portapapeles');
                });
            } else {
                // Fallback para navegadores antiguos
                const textarea = document.createElement('textarea');
                textarea.value = textoCompleto;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('Ruta completa copiada al portapapeles');
            }
        }

        // Función para cerrar modal
        function cerrarMapaModal() {
            document.getElementById('mapaModal').style.display = 'none';
        }
    </script>
</body>
</html>