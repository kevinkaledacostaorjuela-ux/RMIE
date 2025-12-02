<?php
// Vista moderna para ver detalles completos del día de rutas - RMIE v3.0
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rutas del <?php echo ucfirst(htmlspecialchars($dia ?? 'N/A')); ?> - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="rutas-container">
        <!-- Breadcrumb de Navegación -->
        <div class="rutas-breadcrumb">
            <a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <span class="separator">/</span>
            <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index"><i class="fas fa-route"></i> Rutas</a>
            <span class="separator">/</span>
            <span>Rutas del <?php echo ucfirst(htmlspecialchars($dia ?? 'N/A')); ?></span>
        </div>

        <!-- Header con Título y Información del Día -->
        <div class="rutas-header">
            <div>
                <h1><i class="fas fa-calendar-day"></i> Rutas del <?php echo ucfirst(htmlspecialchars($dia ?? 'N/A')); ?></h1>
                <p>Planificación completa para el día <?php echo ucfirst(htmlspecialchars($dia ?? 'N/A')); ?>
                <?php if (isset($fecha_planificacion)): ?>
                    - Fecha: <?php echo date('d/m/Y', strtotime($fecha_planificacion)); ?>
                <?php endif; ?>
                </p>
            </div>
            <div class="rutas-nav-buttons">
                <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=index" class="btn-rutas btn-rutas-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
                <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=create" class="btn-rutas btn-rutas-primary">
                    <i class="fas fa-plus"></i> Nueva Ruta
                </a>
            </div>
        </div>

        <!-- Resumen del Día -->
        <div class="day-summary">
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="summary-info">
                        <h3><?php echo count($rutas_dia ?? []); ?></h3>
                        <p>Total de Rutas</p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="summary-info">
                        <?php 
                        $clientes_unicos = array_unique(array_column($rutas_dia ?? [], 'id_clientes'));
                        ?>
                        <h3><?php echo count($clientes_unicos); ?></h3>
                        <p>Clientes Únicos</p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="summary-info">
                        <?php 
                        $locales_totales = count($rutas_dia ?? []);
                        ?>
                        <h3><?php echo $locales_totales; ?></h3>
                        <p>Rutas Totales</p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="summary-info">
                        <?php 
                        $completadas = array_filter($rutas_dia ?? [], function($r) { 
                            return strtolower($r['estado']) === 'completada'; 
                        });
                        ?>
                        <h3><?php echo count($completadas); ?></h3>
                        <p>Completadas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Completa de Rutas del Día -->
        <div class="rutas-day-content">
            <?php if (empty($rutas_dia)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3>No hay rutas para este día</h3>
                    <p>No se encontraron rutas planificadas para el <?php echo ucfirst(htmlspecialchars($dia ?? 'N/A')); ?></p>
                    <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=create" class="btn-rutas btn-rutas-primary">
                        <i class="fas fa-plus"></i> Crear Primera Ruta
                    </a>
                </div>
            <?php else: ?>
                <div class="routes-list">
                    <?php foreach ($rutas_dia as $index => $ruta): ?>
                    <div class="route-card" data-route-id="<?php echo $ruta['id_ruta']; ?>">
                        <div class="route-header">
                            <div class="route-number">
                                <span class="route-badge">#<?php echo ($index + 1); ?></span>
                            </div>
                            <div class="route-title">
                                <h4><?php echo htmlspecialchars($ruta['nombre_cliente'] ?? 'Cliente sin nombre'); ?></h4>
                                <p><?php echo htmlspecialchars($ruta['nombre_local_real'] ?? $ruta['nombre_local'] ?? 'Local sin nombre'); ?></p>
                            </div>
                            <div class="route-status">
                                <?php 
                                $estado = strtolower($ruta['estado'] ?? 'pendiente');
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
                            </div>
                        </div>
                        
                        <div class="route-details">
                            <div class="detail-row">
                                <div class="detail-item">
                                    <i class="fas fa-hashtag text-primary"></i>
                                    <span class="detail-label">ID Ruta:</span>
                                    <span class="detail-value">#<?php echo htmlspecialchars($ruta['id_ruta']); ?></span>
                                </div>
                                
                                <div class="detail-item">
                                    <i class="fas fa-calendar text-info"></i>
                                    <span class="detail-label">Día:</span>
                                    <span class="detail-value"><?php echo ucfirst(htmlspecialchars($ruta['dia_semana'] ?? 'No definido')); ?></span>
                                </div>
                                
                                <div class="detail-item">
                                    <i class="fas fa-flag text-warning"></i>
                                    <span class="detail-label">Estado:</span>
                                    <span class="detail-value"><?php echo ucfirst(htmlspecialchars($ruta['estado'] ?? 'N/A')); ?></span>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-item full-width">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    <span class="detail-label">Dirección:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($ruta['direccion_real'] ?? $ruta['direccion'] ?? 'Sin dirección'); ?></span>
                                </div>
                            </div>
                            
                            <?php if (!empty($ruta['cel_cliente']) || !empty($ruta['cel_local'])): ?>
                            <div class="detail-row">
                                <?php if (!empty($ruta['cel_cliente'])): ?>
                                <div class="detail-item">
                                    <i class="fas fa-phone text-success"></i>
                                    <span class="detail-label">Tel. Cliente:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($ruta['cel_cliente']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($ruta['cel_local'])): ?>
                                <div class="detail-item">
                                    <i class="fas fa-phone-alt text-success"></i>
                                    <span class="detail-label">Tel. Local:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($ruta['cel_local']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="route-actions">
                            <a href="/RMIE/app/controllers/RouteControllerModern.php?accion=edit&id=<?php echo $ruta['id_ruta']; ?>" 
                               class="btn-action btn-action-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            
                            <?php if (strtolower($ruta['estado']) !== 'completada'): ?>
                            <button onclick="marcarCompletada(<?php echo $ruta['id_ruta']; ?>)" 
                                    class="btn-action btn-action-complete">
                                <i class="fas fa-check"></i> Completar
                            </button>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                            <button onclick="eliminarRuta(<?php echo $ruta['id_ruta']; ?>)" 
                                    class="btn-action btn-action-delete">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function marcarCompletada(idRuta) {
            if (confirm('¿Marcar esta ruta como completada?')) {
                window.location.href = `/RMIE/app/controllers/RouteControllerModern.php?accion=completar&id=${idRuta}`;
            }
        }

        function eliminarRuta(idRuta) {
            if (confirm('¿Estás seguro de eliminar esta ruta?\n\nEsta acción no se puede deshacer.')) {
                window.location.href = `/RMIE/app/controllers/RouteControllerModern.php?accion=delete&id=${idRuta}`;
            }
        }

        // Animaciones suaves para las cards
        document.addEventListener('DOMContentLoaded', function() {
            const routeCards = document.querySelectorAll('.route-card');
            routeCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

    <!-- CSS Adicional -->
    <style>
        .rutas-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .day-summary {
            margin: 30px 0;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .summary-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .summary-info h3 {
            margin: 0 0 5px 0;
            font-size: 2.2em;
            font-weight: 700;
            color: #333;
        }
        
        .summary-info p {
            margin: 0;
            color: #666;
            font-size: 1em;
            font-weight: 500;
        }
        
        .routes-list {
            display: grid;
            gap: 20px;
        }
        
        .route-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            overflow: hidden;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }
        
        .route-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .route-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .route-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.1em;
        }
        
        .route-title {
            flex: 1;
        }
        
        .route-title h4 {
            margin: 0 0 5px 0;
            font-size: 1.4em;
            font-weight: 600;
        }
        
        .route-title p {
            margin: 0;
            opacity: 0.9;
            font-size: 1em;
        }
        
        .route-details {
            padding: 25px;
        }
        
        .detail-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .detail-row:last-child {
            margin-bottom: 0;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-item.full-width {
            grid-column: 1 / -1;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 80px;
        }
        
        .detail-value {
            color: #333;
            font-size: 1em;
        }
        
        .route-actions {
            padding: 20px 25px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-action-edit {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-action-edit:hover {
            background: #ffb300;
            transform: translateY(-2px);
        }
        
        .btn-action-complete {
            background: #28a745;
            color: white;
        }
        
        .btn-action-complete:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .btn-action-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-action-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        .estado-ruta {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .estado-pendiente {
            background-color: rgba(255, 193, 7, 0.2);
            color: #856404;
        }
        
        .estado-activa {
            background-color: rgba(40, 167, 69, 0.2);
            color: #155724;
        }
        
        .estado-completada {
            background-color: rgba(23, 162, 184, 0.2);
            color: #0c5460;
        }
        
        .estado-cancelada {
            background-color: rgba(220, 53, 69, 0.2);
            color: #721c24;
        }
        
        .estado-inactiva {
            background-color: rgba(108, 117, 125, 0.2);
            color: #495057;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-icon {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            margin: 0 0 15px 0;
            color: #333;
        }
        
        .empty-state p {
            margin: 0 0 30px 0;
            font-size: 1.1em;
        }
        
        .text-primary { color: #007bff !important; }
        .text-info { color: #17a2b8 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-success { color: #28a745 !important; }
        .text-secondary { color: #6c757d !important; }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
            
            .route-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .route-actions {
                flex-direction: column;
            }
            
            .btn-action {
                justify-content: center;
            }
        }
    </style>
</body>
</html>