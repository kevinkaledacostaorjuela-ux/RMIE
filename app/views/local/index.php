<?php
// La vista asume que el controlador ha pasado las variables necesarias
// Variables disponibles: $locales, $filtros, mensajes de sesión

// Obtener mensajes de sesión
$error_message = $_SESSION['error'] ?? '';
$success_message = $_SESSION['success'] ?? '';

// Limpiar mensajes de sesión
unset($_SESSION['error'], $_SESSION['success']);

// Obtener estadísticas básicas
global $conn;
$statsQuery = $conn->query("SELECT 
    COUNT(DISTINCT l.id_locales) as total_locales,
    SUM(CASE WHEN l.estado = 'activo' THEN 1 ELSE 0 END) as locales_activos,
    COUNT(DISTINCT c.id_clientes) as total_clientes
    FROM locales l
    LEFT JOIN clientes c ON l.id_locales = c.id_locales");
$stats = $statsQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Locales - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <style>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'admin'): ?>
        /* Ocultar botones de eliminar para roles que no sean admin */
        a[href*="accion=delete"],
        button[onclick*="delete"],
        button[onclick*="Eliminacion"],
        button[onclick*="eliminar"],
        .btn-danger[href*="delete"],
        .btn-danger-modern,
        button.btn-danger-modern {
            display: none !important;
        }
        <?php endif; ?>

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

        /* Asegurar que la barra de acciones no oculte botones en pantallas pequeñas */
        .acciones-principales {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            z-index: 5;
        }

        /* Permitir que el contenedor principal muestre elementos que sobresalgan */
        .dashboard-container {
            overflow: visible;
        }

        /* Forzar que el area de resumen ocupe solo el espacio necesario y garantizar alineado */
        .resumen-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-shrink: 0;
        }
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
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            padding: 15px 10px;
        }

        .table-modern td {
            border: none;
            padding: 15px 10px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-modern tbody tr {
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

        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        /* Estilos para los option del select */
        .form-control-modern option {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
        }

        .form-control-modern option:hover {
            background: #34495e;
        }

        .badge-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-activo {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .badge-inactivo {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
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

        /* Estilos adicionales para la tabla mejorada */
        .local-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .ubicacion-info {
            min-width: 200px;
        }

        .contacto-info {
            min-width: 150px;
        }

        .fecha-info {
            min-width: 120px;
        }

        .table-modern td {
            padding: 15px 12px;
            vertical-align: middle;
        }

        .badge {
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 12px;
        }

        .badge i {
            margin-right: 4px;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .table-modern .btn-group .btn {
            padding: 8px 12px;
            margin: 0 2px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .table-modern .btn-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .info-badge {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 10px 20px;
            color: white;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .acciones-principales .btn {
            margin-right: 10px;
        }

        .resumen-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .table-container .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Mejoras para scroll horizontal en móviles */
        @media (max-width: 768px) {
            .table-container {
                padding: 15px;
                overflow: visible;
            }
            
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                overflow-x: auto !important;
                overflow-y: visible;
                border-radius: 10px;
                max-width: 100%;
                position: relative;
            }
            
            .table-responsive::-webkit-scrollbar {
                height: 12px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 6px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.5);
                border-radius: 6px;
                border: 2px solid rgba(255, 255, 255, 0.1);
            }
            
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.7);
            }
            
            .table-modern {
                min-width: 1400px !important; /* Forzar ancho mínimo */
                margin-bottom: 0;
                width: 1400px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 15px;
                font-size: 0.9rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para cada columna */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 80px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 200px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 250px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 180px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 150px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 150px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 120px; }
            
            .table-modern th:nth-child(8),
            .table-modern td:nth-child(8) { min-width: 120px; }
            
            .table-modern th:nth-child(9),
            .table-modern td:nth-child(9) { min-width: 150px; }
            
            .table-modern th:nth-child(10),
            .table-modern td:nth-child(10) { min-width: 120px; }
            
            /* Scroll indicator visible */
            .scroll-hint {
                position: absolute;
                bottom: -30px;
                left: 50%;
                transform: translateX(-50%);
                color: rgba(255, 255, 255, 0.8);
                font-size: 0.8rem;
                font-style: italic;
                animation: pulse 2s ease-in-out infinite;
                background: rgba(0, 0, 0, 0.3);
                padding: 5px 10px;
                border-radius: 15px;
                backdrop-filter: blur(5px);
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Diseño de Tarjetas para locales */
        .locals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .locals-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .locals-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2, #4facfe);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .locals-card:hover::before {
            transform: scaleX(1);
        }

        .locals-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .locals-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .locals-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .locals-card-title {
            flex: 1;
        }

        .locals-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .locals-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .locals-card-body {
            margin-bottom: 20px;
        }

        .locals-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .locals-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .locals-info-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .locals-info-content {
            flex: 1;
        }

        .locals-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .locals-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
        }

        .locals-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .locals-actions {
            display: flex;
            gap: 8px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin: 15px 0 25px 0;
        }

        .view-toggle-btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-decoration: none;
        }

        .view-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            color: #fff;
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .badge-clients {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            color: white;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Responsive para tarjetas */
        @media (max-width: 768px) {
            .locals-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .view-toggle {
                flex-direction: column;
                gap: 5px;
            }
            
            .view-toggle-btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-content {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Cargando Gestión de Locales...</h3>
            <p>Preparando el dashboard</p>
        </div>
    </div>

    <div class="dashboard-container">
        <h1 class="page-title floating">
            <i class="fas fa-building"></i> Gestión de Locales
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
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total_locales']; ?></div>
                <div class="stat-label">Total Locales</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $stats['locales_activos']; ?></div>
                <div class="stat-label">Locales Activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total_clientes'] ?? 0; ?></div>
                <div class="stat-label">Total Clientes</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="/RMIE/app/controllers/LocalController.php" id="filterForm">
                <input type="hidden" name="accion" value="index">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fas fa-search"></i> Buscar</label>
                        <input type="text" name="buscar" class="form-control-modern form-control" placeholder="Buscar por nombre del local" value="<?php echo htmlspecialchars($filtros['nombre'] ?? ''); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-city"></i> Localidad</label>
                        <input type="text" name="localidad" class="form-control-modern form-control" placeholder="Localidad" value="<?php echo htmlspecialchars($filtros['localidad'] ?? ''); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-map-marked-alt"></i> Barrio</label>
                        <input type="text" name="barrio" class="form-control-modern form-control" placeholder="Barrio" value="<?php echo htmlspecialchars($filtros['barrio'] ?? ''); ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label"><i class="fas fa-toggle-on"></i> Estado</label>
                        <select name="estado" class="form-control-modern form-select">
                            <option value="">Todos</option>
                            <option value="activo" <?php echo ($filtros['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                            <option value="inactivo" <?php echo ($filtros['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-modern btn-primary-modern me-2">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="/RMIE/app/controllers/LocalController.php?accion=index" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Barra de acciones y resumen -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="acciones-principales">
                <a href="/RMIE/app/controllers/LocalController.php?accion=create" 
                   class="btn btn-modern btn-success-modern">
                    <i class="fas fa-plus"></i> Nuevo Local
                </a>
                <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
            <!-- Resumen de locales eliminado por solicitud -->
        </div>

        <!-- Vista selector: Tarjetas / Tabla -->
        <div class="view-toggle">
            <button id="viewCardsBtnLocales" class="view-toggle-btn active">
                <i class="fas fa-th-large"></i> Tarjetas
            </button>
            <button id="viewTableBtnLocales" class="view-toggle-btn">
                <i class="fas fa-table"></i> Tabla
            </button>
        </div>

        <!-- Vista de Tarjetas -->
        <div id="cardsViewLocales" class="locals-grid">
            <?php if (!empty($locales)): ?>
                <?php foreach ($locales as $local): ?>
                    <div class="locals-card">
                        <div class="locals-card-header">
                            <div class="locals-card-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="locals-card-title">
                                <h4><?php echo htmlspecialchars($local->nombre_local); ?></h4>
                                <p>#<?php echo $local->id_locales; ?> - Local comercial</p>
                            </div>
                            <div>
                                <span class="badge badge-status badge-<?php echo $local->estado; ?>">
                                    <?php if ($local->estado === 'activo'): ?>
                                        <i class="fas fa-check-circle"></i> Activo
                                    <?php else: ?>
                                        <i class="fas fa-times-circle"></i> Inactivo
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="locals-card-body">
                            <div class="locals-info-item">
                                <div class="locals-info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="locals-info-content">
                                    <div class="locals-info-label">Dirección</div>
                                    <div class="locals-info-value"><?php echo htmlspecialchars($local->direccion); ?></div>
                                </div>
                            </div>
                            
                            <div class="locals-info-item">
                                <div class="locals-info-icon">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div class="locals-info-content">
                                    <div class="locals-info-label">Localidad y Barrio</div>
                                    <div class="locals-info-value">
                                        <?php echo htmlspecialchars($local->localidad ?? 'Sin localidad'); ?>, 
                                        <?php echo htmlspecialchars($local->barrio ?? 'Sin barrio'); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($local->cel_local)): ?>
                            <div class="locals-info-item">
                                <div class="locals-info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="locals-info-content">
                                    <div class="locals-info-label">Teléfono</div>
                                    <div class="locals-info-value"><?php echo htmlspecialchars($local->cel_local); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="locals-info-item">
                                <div class="locals-info-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="locals-info-content">
                                    <div class="locals-info-label">Clientes asociados</div>
                                    <div class="locals-info-value">
                                        <?php if ($local->total_clientes > 0): ?>
                                            <span class="badge-clients">
                                                <?php echo $local->total_clientes; ?> 
                                                <?php echo $local->total_clientes == 1 ? 'cliente' : 'clientes'; ?>
                                            </span>
                                            <?php if (!empty($local->nombres_clientes)): ?>
                                                <small class="d-block text-muted mt-2" style="color: rgba(255, 255, 255, 0.7) !important;">
                                                    <?php 
                                                    $nombres = explode(', ', $local->nombres_clientes);
                                                    if (count($nombres) > 2) {
                                                        echo htmlspecialchars($nombres[0]) . ', ' . htmlspecialchars($nombres[1]) . ' y ' . (count($nombres) - 2) . ' más';
                                                    } else {
                                                        echo htmlspecialchars($local->nombres_clientes);
                                                    }
                                                    ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span style="color: rgba(255, 255, 255, 0.6);">Sin clientes asociados</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="locals-info-item">
                                <div class="locals-info-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="locals-info-content">
                                    <div class="locals-info-label">Fecha de creación</div>
                                    <div class="locals-info-value">
                                        <?php echo date('d/m/Y H:i', strtotime($local->fecha_creacion)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="locals-card-footer">
                            <div class="locals-actions">
                                <a href="/RMIE/app/controllers/LocalController.php?accion=edit&id=<?php echo $local->id_locales; ?>" 
                                   class="btn btn-sm btn-modern btn-warning-modern" 
                                   title="Editar local">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                <a href="/RMIE/app/controllers/LocalController.php?accion=delete&id=<?php echo $local->id_locales; ?>" 
                                   class="btn btn-sm btn-modern btn-danger-modern" 
                                   title="Eliminar local"
                                   onclick="return confirm('¿Estás seguro de eliminar el local \'<?php echo addslashes($local->nombre_local); ?>\'?\n\nSi tiene clientes, productos o ventas asociadas, no se podrá eliminar.')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-building"></i>
                        <?php if ($filtros_activos): ?>
                            <h3>No se encontraron locales</h3>
                            <p>No hay locales que coincidan con los filtros aplicados</p>
                            <div class="mt-3">
                                <a href="/RMIE/app/controllers/LocalController.php?accion=index" 
                                   class="btn btn-modern btn-secondary-modern me-2">
                                    <i class="fas fa-times"></i> Limpiar Filtros
                                </a>
                                <a href="/RMIE/app/controllers/LocalController.php?accion=create" 
                                   class="btn btn-modern btn-success-modern">
                                    <i class="fas fa-plus"></i> Crear Nuevo Local
                                </a>
                            </div>
                        <?php else: ?>
                            <h3>No hay locales registrados</h3>
                            <p>Comienza creando tu primer local comercial</p>
                            <div class="mt-3">
                                <a href="/RMIE/app/controllers/LocalController.php?accion=create" 
                                   class="btn btn-modern btn-success-modern">
                                    <i class="fas fa-plus"></i> Crear Primer Local
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tabla de Locales -->
        <div id="tableViewLocales" class="table-container" style="display: none;">
            <?php if (empty($locales)): ?>
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <?php if ($filtros_activos): ?>
                        <h3>No se encontraron locales</h3>
                        <p>No hay locales que coincidan con los filtros aplicados</p>
                        <div class="mt-3">
                            <a href="/RMIE/app/controllers/LocalController.php?accion=index" 
                               class="btn btn-modern btn-secondary-modern me-2">
                                <i class="fas fa-times"></i> Limpiar Filtros
                            </a>
                            <a href="/RMIE/app/controllers/LocalController.php?accion=create" 
                               class="btn btn-modern btn-success-modern">
                                <i class="fas fa-plus"></i> Crear Nuevo Local
                            </a>
                        </div>
                    <?php else: ?>
                        <h3>No hay locales registrados</h3>
                        <p>Comienza creando tu primer local comercial</p>
                        <div class="mt-3">
                            <a href="/RMIE/app/controllers/LocalController.php?accion=create" 
                               class="btn btn-modern btn-success-modern">
                                <i class="fas fa-plus"></i> Crear Primer Local
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <div style="overflow-x:auto; width:100%">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-building"></i> Local</th>
                                <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                                <th><i class="fas fa-phone"></i> Contacto</th>
                                <th><i class="fas fa-city"></i> Localidad</th>
                                <th><i class="fas fa-map-marked-alt"></i> Barrio</th>
                                <th><i class="fas fa-users"></i> Clientes</th>
                                <th><i class="fas fa-toggle-on"></i> Estado</th>
                                <th><i class="fas fa-calendar"></i> Creado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($locales as $local): ?>
                            <tr>
                                <td>
                                    <span class="badge" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white; font-weight: bold;">
                                        #<?php echo $local->id_locales; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="local-icon me-2">
                                            <i class="fas fa-building text-primary" style="font-size: 1.2em;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-primary"><?php echo htmlspecialchars($local->nombre_local); ?></div>
                                            <small class="text-muted"><i class="fas fa-info-circle"></i> Local comercial</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ubicacion-info">
                                        <div class="mb-1">
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                            <span class="fw-semibold"><?php echo htmlspecialchars($local->direccion); ?></span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-location-dot"></i> 
                                            <?php echo htmlspecialchars($local->localidad ?? 'Sin localidad'); ?>, 
                                            <?php echo htmlspecialchars($local->barrio ?? 'Sin barrio'); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($local->cel_local)): ?>
                                        <div class="contacto-info">
                                            <div class="mb-1">
                                                <i class="fas fa-phone text-success"></i>
                                                <span class="fw-semibold"><?php echo htmlspecialchars($local->cel_local); ?></span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-mobile-alt"></i> Contacto principal
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="fas fa-phone-slash text-muted"></i>
                                            <small class="d-block text-muted">Sin teléfono</small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($local->localidad)): ?>
                                        <span class="badge" style="background: linear-gradient(45deg, #4facfe, #00f2fe); color: white;">
                                            <i class="fas fa-city"></i> <?php echo htmlspecialchars($local->localidad); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Sin localidad</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($local->barrio)): ?>
                                        <span class="badge" style="background: linear-gradient(45deg, #fa709a, #fee140); color: white;">
                                            <i class="fas fa-map-marked-alt"></i> <?php echo htmlspecialchars($local->barrio); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Sin barrio</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <?php if ($local->total_clientes > 0): ?>
                                            <span class="badge" style="background: linear-gradient(45deg, #11998e, #38ef7d); color: white; font-size: 1rem; padding: 8px 15px;">
                                                <i class="fas fa-users"></i> <?php echo $local->total_clientes; ?>
                                            </span>
                                            <small class="d-block text-muted mt-1">
                                                <?php if (!empty($local->nombres_clientes)): ?>
                                                    <?php 
                                                    $nombres = explode(', ', $local->nombres_clientes);
                                                    if (count($nombres) > 2) {
                                                        echo htmlspecialchars($nombres[0]) . ', ' . htmlspecialchars($nombres[1]) . ' y ' . (count($nombres) - 2) . ' más';
                                                    } else {
                                                        echo htmlspecialchars($local->nombres_clientes);
                                                    }
                                                    ?>
                                                <?php else: ?>
                                                    <?php echo $local->total_clientes == 1 ? 'cliente' : 'clientes'; ?>
                                                <?php endif; ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="badge" style="background: rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.5);">
                                                <i class="fas fa-user-slash"></i> 0
                                            </span>
                                            <small class="d-block text-muted mt-1">Sin clientes</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-status badge-<?php echo $local->estado; ?> d-flex align-items-center justify-content-center">
                                        <?php if ($local->estado === 'activo'): ?>
                                            <i class="fas fa-check-circle me-1"></i> Activo
                                        <?php else: ?>
                                            <i class="fas fa-times-circle me-1"></i> Inactivo
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fecha-info">
                                        <div class="mb-1">
                                            <i class="fas fa-calendar text-info"></i>
                                            <span class="fw-semibold"><?php echo date('d/m/Y', strtotime($local->fecha_creacion)); ?></span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($local->fecha_creacion)); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/LocalController.php?accion=edit&id=<?php echo $local->id_locales; ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/LocalController.php?accion=delete&id=<?php echo $local->id_locales; ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar local"
                                           onclick="return confirm('¿Estás seguro de eliminar el local \'<?php echo addslashes($local->nombre_local); ?>\'?\n\nSi tiene clientes, productos o ventas asociadas, no se podrá eliminar.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- Indicador de scroll para móviles -->
                <div class="scroll-hint d-block d-md-none">
                    <i class="fas fa-hand-point-left"></i> Desliza para ver más columnas <i class="fas fa-hand-point-right"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingScreen').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('loadingScreen').style.display = 'none';
                }, 500);
            }, 1000);
        });

        // Smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });

        // Table row hover effect
        document.querySelectorAll('.table-modern tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(255, 255, 255, 0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.background = 'transparent';
            });
        });

        // View toggle: Tarjetas / Tabla (locales)
        document.addEventListener('DOMContentLoaded', function() {
            const cardsBtn = document.getElementById('viewCardsBtnLocales');
            const tableBtn = document.getElementById('viewTableBtnLocales');
            const cardsView = document.getElementById('cardsViewLocales');
            const tableView = document.getElementById('tableViewLocales');

            function setView(view) {
                if (view === 'cards') {
                    cardsView.style.display = 'grid';
                    tableView.style.display = 'none';
                    cardsBtn.classList.add('active');
                    tableBtn.classList.remove('active');
                } else {
                    cardsView.style.display = 'none';
                    tableView.style.display = 'block';
                    cardsBtn.classList.remove('active');
                    tableBtn.classList.add('active');
                }
                try {
                    localStorage.setItem('localesView', view);
                } catch(e) {
                    console.warn('No se pudo guardar la preferencia de vista');
                }
            }

            if (cardsBtn && tableBtn && cardsView && tableView) {
                cardsBtn.addEventListener('click', () => setView('cards'));
                tableBtn.addEventListener('click', () => setView('table'));
                
                // Cargar preferencia guardada o usar tarjetas por defecto
                const savedView = (function() {
                    try {
                        return localStorage.getItem('localesView');
                    } catch(e) {
                        return null;
                    }
                })();
                
                setView(savedView === 'table' ? 'table' : 'cards');
            }
        });
    </script>
</body>
</html>