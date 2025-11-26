<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
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
    COUNT(*) as total_productos,
    COUNT(CASE WHEN precio_unitario > 0 THEN 1 END) as con_precio,
    COUNT(CASE WHEN stock > 0 THEN 1 END) as con_stock
    FROM productos");
$stats = $statsQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - RMIE</title>
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

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Animaciones */
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

        /* Contenedor de estadísticas */
        .main-stats-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .stats-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .stats-title {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stats-grid-main {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .main-stat-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .main-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
        }

        .main-stat-card:hover::before {
            left: 100%;
        }

        .main-stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .stat-icon-wrapper {
            flex-shrink: 0;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .stat-content {
            flex-grow: 1;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Variaciones de color para cada tipo */
        .primary-card { border-left: 4px solid #667eea; }
        .primary-icon { background: linear-gradient(135deg, #667eea, #764ba2); }

        .success-card { border-left: 4px solid #4facfe; }
        .success-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }

        .warning-card { border-left: 4px solid #ff9a9e; }
        .warning-icon { background: linear-gradient(135deg, #ff9a9e, #fecfef); }

        /* Filtros */
        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
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

        .form-label {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            font-size: 0.9rem;
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #fff;
            padding: 10px 15px;
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 1);
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            color: #fff;
        }

        /* Estilos para los option del select */
        .form-control-modern option {
            background: #667eea;
            color: #fff;
            padding: 10px;
        }

        .form-control-modern option:hover {
            background: #5a6fcf;
        }

        /* Tabla */
        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;  
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow-x: auto;
            margin-bottom: 20px;
        }

        /* Estilos para toggle de vista */
        .view-toggle {
            display: flex;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 5px;
            gap: 5px;
            backdrop-filter: blur(10px);
        }

        .view-toggle-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .view-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateY(-2px);
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .table-header h3 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        /* Diseño de tarjetas para productos */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .products-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .products-card::before {
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

        .products-card:hover::before {
            transform: scaleX(1);
        }

        .products-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .products-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .products-card-icon {
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

        .products-card-title {
            flex: 1;
        }

        .products-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .products-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .products-card-body {
            margin-bottom: 20px;
        }

        .products-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .products-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .products-info-icon {
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

        .products-info-content {
            flex: 1;
        }

        .products-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .products-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
        }

        .products-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .products-actions {
            display: flex;
            gap: 8px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .table-modern {
            background:rgba(255, 255, 255, 0.1);
            color: #667eea;
        }

        .table-modern th {
            background: rgba(255, 255, 255, 0.2);
            color: #667eea;
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

        /* Badges */
        .badge-modern {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin: 2px;
        }

        .badge-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .badge-info {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-secondary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        /* Botones */
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

        .btn-info-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Alerts */
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

        /* Iconos */
        .product-icon {
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .empty-state i {
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 25px;
        }

        .empty-state h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 25px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-container {
                margin: 10px;
                padding: 20px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .stats-grid-main {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .main-stat-card {
                padding: 20px;
                gap: 15px;
            }
            
            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 5px;
                padding: 15px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .stats-grid-main {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .table-modern th,
            .table-modern td {
                padding: 8px 4px;
                font-size: 0.8rem;
            }
            
            .btn-modern {
                padding: 8px 10px;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .main-stats-container {
                padding: 20px 15px;
            }
            
            .main-stat-card {
                padding: 15px;
                gap: 12px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .stat-label {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <h1 class="page-title">
            <i class="fas fa-box"></i> Gestión de Productos
        </h1>

        <!-- Mensajes -->
        <?php if ($success_message): ?>
            <div class="alert alert-success-modern alert-modern">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger-modern alert-modern">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas Principales -->
        <div class="main-stats-container">
            <div class="stats-header">
                <h2 class="stats-title">
                    <i class="fas fa-chart-line"></i> Estadísticas de Productos
                </h2>
            </div>
            <div class="stats-grid-main">
                <div class="main-stat-card primary-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon primary-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $stats['total_productos']; ?></div>
                        <div class="stat-label">Total Productos</div>
                    </div>
                </div>
                <div class="main-stat-card success-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon success-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $stats['con_precio']; ?></div>
                        <div class="stat-label">Con Precio</div>
                    </div>
                </div>
                <div class="main-stat-card warning-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon warning-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $stats['con_stock']; ?></div>
                        <div class="stat-label">Con Stock</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <style>
        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .filters-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 32px;
            align-items: flex-start;
            flex-wrap: wrap;
            grid-template-rows: auto auto auto;
        }
        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 140px;
            max-width: 320px;
        }
        .filter-label {
            background: rgba(8, 8, 8, 0.06);
            color: hsla(207, 85%, 46%, 0.95);
            padding: 6px 10px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        .filter-input, .filter-select {
            background: #fff;
            color: #333;
            border-radius: 12px;
            padding: 10px 14px;
            border: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            min-width: 160px;
        }
        .filter-input::placeholder { color: #333; }
        .filter-actions {
            grid-column: span 2;
            display: flex;
            flex-direction: row;
            gap: 16px;
            align-items: center;
            justify-content: center;
            margin-top: 24px;
        }
        .btn-pill {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            border: none;
            color: white;
            min-width: 120px;
        }
        .btn-pill i { margin-right: 8px; }
        .btn-pill-primary {
            background: linear-gradient(180deg,#1e90ff,#2a6df4);
        }
        .btn-pill-clear {
            background: linear-gradient(180deg,#ffb3c6,#ff7aa2);
            padding: 10px 10px;
            min-width: 120px;
        }
        @media (max-width: 768px) {
            .filter-actions { width: 100%; flex-direction: row; justify-content: stretch; }
            .btn-pill { flex: 1; }
            .filters-row { gap: 8px; }
        }
        .filter-title {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        </style>
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="/RMIE/app/controllers/ProductController.php" id="filterForm">
                <input type="hidden" name="accion" value="index">
                <div class="filters-row">
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-box"></i> Nombre</span>
                        <input type="text" name="nombre" class="filter-input" placeholder="Buscar nombre..." value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                    </div>
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-tags"></i> Categoría</span>
                        <select name="categoria" class="filter-select">
                            <option value="">Categoría</option>
                            <?php if (isset($categorias) && is_array($categorias)): ?>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat->id_categoria ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat->id_categoria ? 'selected' : '' ?>><?= htmlspecialchars($cat->nombre) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-layer-group"></i> Subcategoría</span>
                        <select name="subcategoria" class="filter-select">
                            <option value="">Subcategoría</option>
                            <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
                                <?php foreach ($subcategorias as $subcat): ?>
                                    <?php $obj = isset($subcat['obj']) ? $subcat['obj'] : null; ?>
                                    <?php if ($obj): ?>
                                        <option value="<?= $obj->id_subcategoria ?>" <?= isset($_GET['subcategoria']) && $_GET['subcategoria'] == $obj->id_subcategoria ? 'selected' : '' ?>><?= htmlspecialchars($obj->nombre) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-truck"></i> Proveedor</span>
                        <select name="id_proveedores" class="filter-select">
                            <option value="">Proveedor</option>
                            <?php if (isset($proveedores) && is_array($proveedores)): ?>
                                <?php foreach ($proveedores as $prov): ?>
                                    <option value="<?= $prov->id_proveedores ?>" <?= isset($_GET['id_proveedores']) && $_GET['id_proveedores'] == $prov->id_proveedores ? 'selected' : '' ?>><?= htmlspecialchars($prov->nombre_distribuidor) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
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

        <!-- Tabla de Productos -->
        <div class="table-container">
            <div class="table-header">
                <h3><i class="fas fa-box"></i> Lista de Productos (<?= count($productos ?? []) ?>)</h3>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="toggleProductsView('cards')" id="btnCards">
                        <i class="fas fa-th-large"></i> Tarjetas
                    </button>
                    <button class="view-toggle-btn" onclick="toggleProductsView('table')" id="btnTable">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div></div>
                <div>
                    <a href="/RMIE/app/controllers/ProductController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                    <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-info-modern">
                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
            </div>

            <!-- Vista de Tarjetas (por defecto) -->
            <div id="cardsView" class="products-grid">
                <?php if (isset($productos) && is_array($productos) && !empty($productos)): ?>
                    <?php foreach ($productos as $productData): ?>
                    <?php $producto = $productData['obj']; ?>
                        <div class="products-card">
                            <div class="products-card-header">
                                <div class="products-card-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="products-card-title">
                                    <h4><?= htmlspecialchars($producto->nombre ?? 'Sin nombre') ?></h4>
                                    <p><i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($producto->id_productos ?? '') ?></p>
                                </div>
                            </div>

                            <div class="products-card-body">
                                <div class="products-info-item">
                                    <div class="products-info-icon">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="products-info-content">
                                        <div class="products-info-label">Categoría</div>
                                        <div class="products-info-value"><?= htmlspecialchars($productData['categoria_nombre'] ?? 'Sin categoría') ?></div>
                                    </div>
                                </div>

                                <div class="products-info-item">
                                    <div class="products-info-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="products-info-content">
                                        <div class="products-info-label">Subcategoría</div>
                                        <div class="products-info-value"><?= htmlspecialchars($productData['subcategoria_nombre'] ?? 'Sin subcategoría') ?></div>
                                    </div>
                                </div>

                                <div class="products-info-item">
                                    <div class="products-info-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="products-info-content">
                                        <div class="products-info-label">Precio Unitario</div>
                                        <div class="products-info-value">$<?= number_format($producto->precio_unitario ?? 0, 2) ?></div>
                                    </div>
                                </div>

                                <div class="products-info-item">
                                    <div class="products-info-icon">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                    <div class="products-info-content">
                                        <div class="products-info-label">Stock</div>
                                        <div class="products-info-value">
                                            <span class="badge <?= ($producto->stock ?? 0) > 10 ? 'badge-success' : (($producto->stock ?? 0) > 0 ? 'badge-warning' : 'badge-danger') ?>">
                                                <?= $producto->stock ?? 0 ?> unidades
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="products-info-item">
                                    <div class="products-info-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="products-info-content">
                                        <div class="products-info-label">Proveedor</div>
                                        <div class="products-info-value"><?= htmlspecialchars($productData['proveedor_nombre'] ?? 'Sin proveedor') ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="products-card-footer">
                                <div class="products-actions">
                                    <a href="/RMIE/app/controllers/ProductController.php?accion=edit&id=<?= urlencode($producto->id_productos ?? '') ?>" 
                                       class="btn btn-sm btn-modern btn-warning-modern" 
                                       title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/RMIE/app/controllers/ProductController.php?accion=delete&id=<?= urlencode($producto->id_productos ?? '') ?>" 
                                       class="btn btn-sm btn-modern btn-danger-modern" 
                                       title="Eliminar producto"
                                       onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 1.2rem;">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No hay productos registrados</p>
                            <a href="/RMIE/app/controllers/ProductController.php?accion=create" class="btn btn-modern btn-success-modern">
                                <i class="fas fa-plus"></i> Crear Primer Producto
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vista de Tabla -->
            <div id="tableView" style="display: none;">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-box"></i> Producto</th>
                            <th><i class="fas fa-tags"></i> Categoría</th>
                            <th><i class="fas fa-layer-group"></i> Subcategoría</th>
                            <th><i class="fas fa-dollar-sign"></i> Precio</th>
                            <th><i class="fas fa-warehouse"></i> Stock</th>
                            <th><i class="fas fa-truck"></i> Proveedor</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($productos) && is_array($productos) && !empty($productos)): ?>
                            <?php foreach ($productos as $prodData): ?>
                            <?php 
                            // Extraer el objeto producto y los nombres relacionados
                            $prod = $prodData['obj'];
                            $categoria_nombre = $prodData['categoria_nombre'];
                            $subcategoria_nombre = $prodData['subcategoria_nombre'];
                            $proveedor_nombre = $prodData['proveedor_nombre'];
                            $usuario_nombre = $prodData['usuario_nombre'];
                            ?>
                            <tr>
                                <td>
                                    <span class="badge badge-modern badge-secondary">#<?= htmlspecialchars($prod->id_productos ?? '') ?></span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($prod->nombre ?? 'Sin nombre') ?></strong>
                                        <br>
                                        <small style="color: rgba(255,255,255,0.7);">
                                            <?= htmlspecialchars(substr($prod->descripcion ?? '', 0, 50)) ?>...
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-info">
                                        <?= htmlspecialchars($categoria_nombre ?? 'Sin categoría') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-secondary">
                                        <?= htmlspecialchars(!empty($subcategoria_nombre) ? $subcategoria_nombre : 'Sin subcategoría') ?>
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: #4facfe;">
                                        $<?= number_format($prod->precio_unitario ?? 0, 0, ',', '.') ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php 
                                    $stock = $prod->stock ?? 0;
                                    if ($stock > 50) {
                                        $badge_class = 'badge-modern badge-success';
                                    } elseif ($stock > 10) {
                                        $badge_class = 'badge-modern badge-warning';
                                    } else {
                                        $badge_class = 'badge-modern badge-danger';
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= $stock ?> uds
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($proveedor_nombre)): ?>
                                        <span class="badge badge-modern badge-info">
                                            <i class="fas fa-truck"></i> <?= htmlspecialchars($proveedor_nombre) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-question"></i> Sin proveedor
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <?= $prod->fecha_entrada && $prod->fecha_entrada !== '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($prod->fecha_entrada)) : 'Sin fecha' ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="/RMIE/app/controllers/ProductController.php?accion=edit&id=<?= urlencode($prod->id_productos ?? '') ?>" 
                                       class="btn btn-modern btn-warning-modern btn-action" 
                                       title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                    <a href="/RMIE/app/views/productos/delete.php?id=<?= urlencode($prod->id_productos ?? '') ?>" 
                                       class="btn btn-modern btn-danger-modern btn-action" 
                                       onclick="return confirm('¿Está seguro de eliminar este producto?')"
                                       title="Eliminar producto">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h5>No hay productos disponibles</h5>
                                        <p>No se encontraron productos que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/ProductController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primer Producto
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
        // Toggle entre vista de tarjetas y tabla para productos
        function toggleProductsView(view) {
            const cardsView = document.getElementById('cardsView');
            const tableView = document.getElementById('tableView');
            const btnCards = document.getElementById('btnCards');
            const btnTable = document.getElementById('btnTable');
            
            if (view === 'cards') {
                cardsView.style.display = 'grid';
                tableView.style.display = 'none';
                btnCards.classList.add('active');
                btnTable.classList.remove('active');
                localStorage.setItem('productosView', 'cards');
            } else {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                btnCards.classList.remove('active');
                btnTable.classList.add('active');
                localStorage.setItem('productosView', 'table');
            }
        }
        
        // Restaurar vista guardada
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('productosView') || 'cards';
            toggleProductsView(savedView);
        });

        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
            window.location.href = '/RMIE/app/controllers/ProductController.php?accion=index';
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>