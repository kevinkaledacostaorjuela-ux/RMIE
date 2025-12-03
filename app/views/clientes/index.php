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
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <script src="/RMIE/public/js/selenium-messages.js"></script>
    <style>
                .filter-label {
                    color: #2c3e50 !important;
                    font-weight: 600;
                    font-size: 1rem;
                    margin-bottom: 6px;
                    display: block;
                }
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

        /* Estilos para los option del select */
        .form-control-modern option {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
        }

        .form-control-modern option:hover {
            background: #34495e;
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

        /* Estilos para la barra de acciones moderna */
        .action-toolbar {
            margin-bottom: 25px;
        }

        .toolbar-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 20px 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Botones modernos reorganizados */
        .btn-modern {
            display: flex;
            align-items: center;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            min-height: 60px;
            backdrop-filter: blur(10px);
            width: 200px;
            flex-shrink: 0;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-create {
            background: linear-gradient(135deg, #00c851, #007e33);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 200, 81, 0.4);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 200, 81, 0.6);
            color: white;
        }

        .btn-cleanup {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }

        .btn-cleanup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.6);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, #33b5e5, #0099cc);
            color: white;
            box-shadow: 0 4px 15px rgba(51, 181, 229, 0.4);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(51, 181, 229, 0.6);
            color: white;
        }

        .btn-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .btn-content {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .btn-title {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .btn-subtitle {
            font-size: 0.75rem;
            opacity: 0.8;
            line-height: 1.1;
        }

        /* Responsive para toolbar */
        @media (max-width: 768px) {
            .toolbar-container {
                flex-direction: column;
                gap: 15px;
                justify-content: center;
            }
            
            .btn-modern {
                width: 250px;
                justify-content: center;
                text-align: center;
            }
            
            .btn-content {
                text-align: center;
            }
        }

        /* Diseño de Tarjetas para clientes */
        .clients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .clients-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .clients-card::before {
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

        .clients-card:hover::before {
            transform: scaleX(1);
        }

        .clients-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .clients-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .clients-card-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.8rem;
            color: white;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .clients-card-title {
            flex: 1;
        }

        .clients-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .clients-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .clients-card-body {
            margin-bottom: 20px;
        }

        .client-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .client-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .client-info-icon {
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

        .client-info-content {
            flex: 1;
        }

        .client-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .client-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
            line-height: 1.5;
        }
        
        .client-info-value .badge {
            margin: 2px;
            display: inline-block;
        }

        .clients-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .client-actions {
            display: flex;
            gap: 8px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 20px;
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

        /* Responsive para tarjetas */
        @media (max-width: 768px) {
            .clients-grid {
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

        /* Scroll horizontal para móviles - Clientes */
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
                min-width: 1300px !important; /* Ancho mínimo para 8 columnas */
                margin-bottom: 0;
                width: 1300px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para clientes (8 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 70px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 200px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 180px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 140px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 150px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 220px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 120px; }
            
            .table-modern th:nth-child(8),
            .table-modern td:nth-child(8) { min-width: 120px; }
            
            /* Scroll indicator */
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
                <div class="filters-row">
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-user"></i> Nombre</span>
                        <input type="text"
                               name="nombre"
                               class="filter-input"
                               placeholder="Buscar por nombre..."
                               value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                    </div>

                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-envelope"></i> Email</span>
                        <input type="text"
                               name="email"
                               class="filter-input"
                               placeholder="Buscar por email..."
                               value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                    </div>

                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-toggle-on"></i> Estado</span>
                        <select name="estado" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="activo" <?= ($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-pill btn-pill-primary">
                            <i class="fas fa-search"></i> FILTRAR
                        </button>
                        <button type="button" class="btn-pill btn-pill-clear" onclick="limpiarFiltros()">
                            <i class="fas fa-times"></i> LIMPIAR
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Barra de acciones moderna y organizada -->
        <div class="action-toolbar mb-4">
            <div class="toolbar-container">
                <?php if ($_SESSION['rol'] !== 'auxiliar'): ?>
                <a href="/RMIE/app/controllers/ClientController.php?accion=create" 
                   class="btn-modern btn-create">
                    <div class="btn-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="btn-content">
                        <span class="btn-title">Nuevo Cliente</span>
                        <span class="btn-subtitle">Agregar nuevo cliente</span>
                    </div>
                </a>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <button class="btn-modern btn-cleanup" 
                        onclick="limpiarInactivos()" 
                        title="Limpiar clientes inactivos">
                    <div class="btn-icon">
                        <i class="fas fa-broom"></i>
                    </div>
                    <div class="btn-content">
                        <span class="btn-title">Limpiar Inactivos</span>
                        <span class="btn-subtitle">Remover sin actividad</span>
                    </div>
                </button>
                <?php endif; ?>
                
                <a href="/RMIE/app/views/dashboard.php" 
                   class="btn-modern btn-back">
                    <div class="btn-icon">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                    <div class="btn-content">
                        <span class="btn-title">Dashboard</span>
                        <span class="btn-subtitle">Volver al inicio</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Vista selector: Tarjetas / Tabla -->
        <div class="view-toggle">
            <button id="viewCardsBtnClientes" class="view-toggle-btn">
                <i class="fas fa-th-large"></i> Tarjetas
            </button>
            <button id="viewTableBtnClientes" class="view-toggle-btn active">
                <i class="fas fa-table"></i> Tabla
            </button>
        </div>

        <!-- Vista de Tarjetas (moved after action buttons) -->
        <div id="cardsView" class="clients-grid" style="display: none; margin-top:20px;">
            <?php if (isset($clientes) && is_array($clientes) && !empty($clientes)): ?>
                <?php foreach ($clientes as $cliente): ?>
                <div class="clients-card">
                    <div class="clients-card-header">
                        <div class="clients-card-avatar">
                            <?= strtoupper(substr(htmlspecialchars($cliente->nombre ?? 'U'), 0, 1)) ?>
                        </div>
                        <div class="clients-card-title">
                            <h4><?= htmlspecialchars($cliente->nombre ?? 'Sin nombre') ?></h4>
                            <p><i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($cliente->id_clientes ?? 'N/A') ?></p>
                        </div>
                    </div>

                    <div class="clients-card-body">
                        <?php if (!empty($cliente->documento)): ?>
                        <div class="client-info-item">
                            <div class="client-info-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="client-info-content">
                                <div class="client-info-label">Documento</div>
                                <div class="client-info-value"><?= htmlspecialchars($cliente->documento) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($cliente->correo)): ?>
                        <div class="client-info-item">
                            <div class="client-info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="client-info-content">
                                <div class="client-info-label">Email</div>
                                <div class="client-info-value"><?= htmlspecialchars($cliente->correo) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($cliente->cel_cliente)): ?>
                        <div class="client-info-item">
                            <div class="client-info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="client-info-content">
                                <div class="client-info-label">Teléfono</div>
                                <div class="client-info-value"><?= htmlspecialchars($cliente->cel_cliente) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($cliente->locales_asignados) && count($cliente->locales_asignados) > 0): ?>
                        <div class="client-info-item">
                            <div class="client-info-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="client-info-content">
                                <div class="client-info-label">
                                    <?= count($cliente->locales_asignados) === 1 ? 'Local Asignado' : 'Locales Asignados (' . count($cliente->locales_asignados) . ')' ?>
                                </div>
                                <div class="client-info-value">
                                    <?php 
                                    $nombres_locales = array_map(function($local) {
                                        return htmlspecialchars($local->nombre_local);
                                    }, $cliente->locales_asignados);
                                    echo implode(', ', $nombres_locales);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="clients-card-footer">
                        <div>
                            <?php if (($cliente->estado ?? 'inactivo') === 'activo'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="client-actions">
                            <?php if ($_SESSION['rol'] !== 'auxiliar'): ?>
                            <a href="/RMIE/app/controllers/ClientController.php?accion=edit&id=<?= urlencode($cliente->id_clientes ?? '') ?>" 
                               class="btn btn-sm btn-warning" 
                               title="Editar cliente">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php else: ?>
                            <a href="/RMIE/app/controllers/ClientController.php?accion=edit&id=<?= urlencode($cliente->id_clientes ?? '') ?>" 
                               class="btn btn-sm btn-primary" 
                               title="Ver detalles del cliente">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php endif; ?>
                            <?php if ($_SESSION['rol'] !== 'coordinador' && $_SESSION['rol'] !== 'auxiliar'): ?>
                            <a href="/RMIE/app/controllers/ClientController.php?accion=delete&id=<?= urlencode($cliente->id_clientes ?? '') ?>" 
                               class="btn btn-sm btn-danger" 
                               title="Eliminar cliente"
                               onclick="return confirmDeleteClient('<?= addslashes($cliente->nombre ?? '') ?>')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-inbox fa-3x mb-3" style="color: rgba(255,255,255,0.5);"></i>
                        <h5 style="color: #fff;">No hay clientes disponibles</h5>
                        <p style="color: rgba(255,255,255,0.7);">No se encontraron clientes que coincidan con los filtros aplicados.</p>
                        <?php if ($_SESSION['rol'] !== 'auxiliar'): ?>
                        <a href="/RMIE/app/controllers/ClientController.php?accion=create" class="btn btn-success">
                            <i class="fas fa-plus"></i> Crear Primer Cliente
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tabla de Clientes -->
        <div id="tableView" class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Teléfono</th>
                            <th><i class="fas fa-store"></i> Local</th>
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
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->correo)): ?>
                                        <i class="fas fa-envelope text-info"></i>
                                        <small><?= htmlspecialchars($cliente->correo) ?></small>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-minus"></i> Sin email
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->cel_cliente)): ?>
                                        <i class="fas fa-phone text-success"></i>
                                        <small><?= htmlspecialchars($cliente->cel_cliente) ?></small>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-minus"></i> Sin teléfono
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cliente->locales_asignados) && count($cliente->locales_asignados) > 0): ?>
                                        <?php if (count($cliente->locales_asignados) === 1): ?>
                                            <span class="badge bg-info">
                                                <i class="fas fa-store"></i> <?= htmlspecialchars($cliente->locales_asignados[0]->nombre_local) ?>
                                            </span>
                                        <?php else: ?>
                                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                                <?php foreach ($cliente->locales_asignados as $index => $local): ?>
                                                    <?php if ($index < 2): ?>
                                                        <span class="badge bg-info" style="font-size: 0.75rem;">
                                                            <i class="fas fa-store"></i> <?= htmlspecialchars($local->nombre_local) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php if (count($cliente->locales_asignados) > 2): ?>
                                                    <span class="badge bg-secondary" style="font-size: 0.75rem;" 
                                                          title="<?= implode(', ', array_map(function($l) { return htmlspecialchars($l->nombre_local); }, array_slice($cliente->locales_asignados, 2))) ?>">
                                                        +<?= count($cliente->locales_asignados) - 2 ?> más
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-minus"></i> Sin locales
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($cliente->estado === 'activo'): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if ($_SESSION['rol'] !== 'auxiliar'): ?>
                                        <a href="/RMIE/app/controllers/ClientController.php?accion=edit&id=<?= urlencode($cliente->id_clientes) ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar cliente">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php else: ?>
                                        <a href="/RMIE/app/controllers/ClientController.php?accion=edit&id=<?= urlencode($cliente->id_clientes) ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Ver detalles del cliente">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['rol'] !== 'coordinador' && $_SESSION['rol'] !== 'auxiliar'): ?>
                                        <a href="/RMIE/app/controllers/ClientController.php?accion=delete&id=<?= urlencode($cliente->id_clientes) ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Eliminar cliente"
                                           onclick="return confirmDeleteClient('<?= addslashes($cliente->nombre) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
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
                                        <a href="/RMIE/app/controllers/ClientController.php?accion=create" class="btn btn-success">
                                            <i class="fas fa-plus"></i> Crear Primer Cliente
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Indicador de scroll para móviles -->
            <div class="scroll-hint d-block d-md-none">
                <i class="fas fa-hand-point-left"></i> Desliza para ver más columnas <i class="fas fa-hand-point-right"></i>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// Capturar promesas rechazadas
window.addEventListener('unhandledrejection', function(e) { console.log('Promise Error:', e.reason); e.preventDefault(); });

// Debug mode - capturar errores JavaScript
window.addEventListener('error', function(e) { console.log('JS Error:', e.message, 'at', e.filename + ':' + e.lineno); });

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
                
                // Obtener nombre del cliente de forma segura
                let clienteNombre = 'este cliente';
                const tr = this.closest('tr');
                
                if (tr) {
                    const strongElement = tr.querySelector('td:nth-child(2) strong');
                    if (strongElement) {
                        clienteNombre = strongElement.textContent;
                    }
                } else if (this.dataset.nombre) {
                    clienteNombre = this.dataset.nombre;
                }
                
                if (confirmDeleteClient(clienteNombre)) {
                    window.location.href = this.href;
                }
            });
        });

        // Toggle entre vista de tarjetas y tabla para clientes
        (function() {
            const cardsBtn = document.getElementById('viewCardsBtnClientes');
            const tableBtn = document.getElementById('viewTableBtnClientes');
            const cardsView = document.getElementById('cardsView');
            const tableView = document.getElementById('tableView');

            function setActiveButton(activeBtn, inactiveBtn) {
                activeBtn.classList.add('active');
                inactiveBtn.classList.remove('active');
            }

            function showCardsView() {
                if (cardsView) cardsView.style.display = 'grid';
                if (tableView) tableView.style.display = 'none';
                setActiveButton(cardsBtn, tableBtn);
                try {
                    localStorage.setItem('clientesView', 'cards');
                } catch(e) {}
            }

            function showTableView() {
                if (cardsView) cardsView.style.display = 'none';
                if (tableView) tableView.style.display = 'block';
                setActiveButton(tableBtn, cardsBtn);
                try {
                    localStorage.setItem('clientesView', 'table');
                } catch(e) {}
            }

            if (cardsBtn && tableBtn) {
                cardsBtn.addEventListener('click', showCardsView);
                tableBtn.addEventListener('click', showTableView);

                // Cargar preferencia guardada
                try {
                    const savedView = localStorage.getItem('clientesView');
                    if (savedView === 'cards') {
                        showCardsView();
                    } else {
                        showTableView();
                    }
                } catch(e) {
                    showTableView();
                }
            }
        })();

        // Función para limpiar clientes inactivos
        function limpiarInactivos() {
            const opcion = prompt(`¿Qué tipo de limpieza quieres hacer en CLIENTES?\n\nEscribe el número de tu opción:\n\n1 - Solo eliminar clientes INACTIVOS\n2 - Eliminar TODOS los clientes\n3 - Cancelar`);
            
            if (opcion === '1') {
                if (confirmAction('limpiar clientes inactivos')) {
                    alert('Eliminando clientes inactivos...');
                    window.location.href = '/RMIE/app/controllers/ClientController.php?accion=clean_inactive';
                }
            } else if (opcion === '2') {
                if (confirmAction('eliminar todos los clientes')) {
                    alert('Eliminando todos los clientes...');
                    window.location.href = '/RMIE/app/controllers/ClientController.php?accion=clean_all';
                }
            }
        }
    </script>
</body>
</html>