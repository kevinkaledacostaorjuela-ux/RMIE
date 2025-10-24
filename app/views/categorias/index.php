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
    COUNT(*) as total_categorias,
    COUNT(CASE WHEN descripcion IS NOT NULL AND descripcion != '' THEN 1 END) as con_descripcion
    FROM categorias");
$stats = $statsQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <style>
        /* Advanced Glassmorphism Design */
        @keyframes floatContainer {
            0%, 100% { transform: translateY(0px) rotateX(0deg); }
            50% { transform: translateY(-10px) rotateX(2deg); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes titleGlow {
            0%, 100% { text-shadow: 0 0 20px rgba(255,255,255,0.5), 0 0 40px rgba(102,126,234,0.3); }
            50% { text-shadow: 0 0 30px rgba(255,255,255,0.8), 0 0 60px rgba(118,75,162,0.5); }
        }

        @keyframes rotatingBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        body {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(118,75,162,0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px);
            border-radius: 30px;
            padding: 50px;
            margin: 30px auto;
            max-width: 1400px;
            width: calc(100% - 60px);
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 2;
            animation: floatContainer 6s ease-in-out infinite;
        }

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #667eea 50%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 3s ease-in-out infinite;
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
            animation: fadeInUp 0.8s ease-out 0.5s both;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 45px 35px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 15px 45px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            perspective: 1000px;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #667eea, #764ba2, #667eea);
            border-radius: 32px;
            z-index: -1;
            animation: rotatingBorder 3s linear infinite;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.3) 50%, 
                transparent 100%);
            transition: left 0.8s ease;
        }

        .stat-card:hover::after {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-15px) rotateX(5deg) scale(1.05);
            box-shadow: 
                0 25px 60px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.18);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, #667eea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 12px;
            text-shadow: 
                0 4px 15px rgba(0, 0, 0, 0.4),
                0 0 30px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            animation: fadeInUp 0.6s ease-out 0.4s both;
            position: relative;
        }

        .table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            border-radius: 25px 25px 0 0;
        }

        .table-modern {
            background: transparent;
            color: #fff;
            width: 100%;
            margin-bottom: 0;
            border-radius: 15px;
            overflow: hidden;
        }

        .table-modern thead {
            background: linear-gradient(135deg, 
                rgba(102, 126, 234, 0.4) 0%, 
                rgba(118, 75, 162, 0.4) 100%);
            backdrop-filter: blur(20px);
        }

        .table-modern th {
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 22px 18px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-modern th::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.2) 50%, 
                transparent 100%);
            transition: left 0.6s ease;
        }

        .table-modern th:hover::before {
            left: 100%;
        }

        .table-modern tbody tr {
            border: none;
            transition: all 0.4s ease;
            background: rgba(255, 255, 255, 0.03);
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(8px) translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .table-modern td {
            border: none;
            color: #fff;
            padding: 20px 18px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .btn-modern {
            padding: 14px 30px;
            border-radius: 35px;
            border: none;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.3) 50%, 
                transparent 100%);
            transition: left 0.6s ease;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-modern:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
        }

        .btn-modern:active {
            transform: translateY(-2px) scale(1.02);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, 
                #667eea 0%, 
                #764ba2 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, 
                #764ba2 0%, 
                #5a3472 100%);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, 
                #4facfe 0%, 
                #00f2fe 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-success-modern:hover {
            background: linear-gradient(135deg, 
                #00f2fe 0%, 
                #00d4f7 100%);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, 
                #ff9a9e 0%, 
                #fecfef 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-warning-modern:hover {
            background: linear-gradient(135deg, 
                #fecfef 0%, 
                #fda4af 100%);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, 
                #ff6b6b 0%, 
                #ee5a52 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-danger-modern:hover {
            background: linear-gradient(135deg, 
                #ee5a52 0%, 
                #dc3545 100%);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .btn-modern i {
            font-size: 1rem;
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

        .category-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .badge-modern {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-info {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-secondary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        /* Mejorar visibilidad de formularios */
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: #2c3e50 !important;
            font-weight: 600 !important;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: #764ba2 !important;
            color: #2c3e50 !important;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25) !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500 !important;
        }

        .form-label {
            color: #fff !important;
            font-weight: 700 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) !important;
        }

        .table-modern td {
            color: #fff !important;
            font-weight: 600 !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5) !important;
        }

        .table-modern th {
            color: #fff !important;
            font-weight: 700 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) !important;
        }

        .btn-modern {
            color: #fff !important;
            font-weight: 700 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
        }

        /* Scroll horizontal para móviles - Categorías */
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
            
            .d-block.d-md-none .table-modern {
                min-width: 750px !important; /* Ancho mínimo para 5 columnas */
                margin-bottom: 0;
                width: 750px;
            }
            
            .d-block.d-md-none .table-modern th,
            .d-block.d-md-none .table-modern td {
                white-space: nowrap !important;
                padding: 10px 8px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para categorías móvil (5 columnas) */
            .d-block.d-md-none .table-modern th:nth-child(1),
            .d-block.d-md-none .table-modern td:nth-child(1) { min-width: 70px; }
            
            .d-block.d-md-none .table-modern th:nth-child(2),
            .d-block.d-md-none .table-modern td:nth-child(2) { min-width: 200px; }
            
            .d-block.d-md-none .table-modern th:nth-child(3),
            .d-block.d-md-none .table-modern td:nth-child(3) { min-width: 250px; }
            
            .d-block.d-md-none .table-modern th:nth-child(4),
            .d-block.d-md-none .table-modern td:nth-child(4) { min-width: 120px; }
            
            .d-block.d-md-none .table-modern th:nth-child(5),
            .d-block.d-md-none .table-modern td:nth-child(5) { min-width: 120px; }
            
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
            <i class="fas fa-tags"></i> Gestión de Categorías
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
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total_categorias']; ?></div>
                <div class="stat-label">Total Categorías</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-align-left"></i>
                </div>
                <div class="stat-number"><?php echo $stats['con_descripcion']; ?></div>
                <div class="stat-label">Con Descripción</div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mb-4 text-center">
            <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nueva Categoría
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Categorías (Desktop) -->
        <div class="table-container d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-tag"></i> Categoría</th>
                            <th><i class="fas fa-align-left"></i> Descripción</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha Creación</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($categorias) && is_array($categorias) && !empty($categorias)): ?>
                            <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($cat->id_categoria) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="category-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($cat->nombre) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $cat->id_categoria ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($cat->descripcion)): ?>
                                        <span class="badge badge-modern badge-info" title="<?= htmlspecialchars($cat->descripcion) ?>">
                                            <?= strlen($cat->descripcion) > 50 ? substr(htmlspecialchars($cat->descripcion), 0, 50) . '...' : htmlspecialchars($cat->descripcion) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-minus"></i> Sin descripción
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <i class="fas fa-calendar text-info"></i>
                                        <strong><?= date('d/m/Y', strtotime($cat->fecha_creacion)) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($cat->fecha_creacion)) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar categoría">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar categoría">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No hay categorías disponibles</h5>
                                        <p>No se encontraron categorías registradas en el sistema.</p>
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primera Categoría
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista de Tabla Móvil -->
        <div class="d-block d-md-none">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-modern table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-tag"></i> Categoría</th>
                                <th><i class="fas fa-align-left"></i> Descripción</th>
                                <th><i class="fas fa-calendar-alt"></i> Fecha Creación</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($categorias) && is_array($categorias) && !empty($categorias)): ?>
                                <?php foreach ($categorias as $cat): ?>
                                <tr>
                                    <td>
                                        <strong>#<?= htmlspecialchars($cat->id_categoria) ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($cat->nombre) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-barcode"></i> ID: <?= $cat->id_categoria ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($cat->descripcion)): ?>
                                            <span class="badge badge-modern badge-info" title="<?= htmlspecialchars($cat->descripcion) ?>">
                                                <?= strlen($cat->descripcion) > 50 ? substr(htmlspecialchars($cat->descripcion), 0, 50) . '...' : htmlspecialchars($cat->descripcion) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern badge-secondary">
                                                <i class="fas fa-minus"></i> Sin descripción
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <i class="fas fa-calendar text-info"></i>
                                            <strong><?= date('d/m/Y', strtotime($cat->fecha_creacion)) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($cat->fecha_creacion)) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria) ?>" 
                                               class="btn btn-sm btn-modern btn-warning-modern" 
                                               title="Editar categoría">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria) ?>" 
                                               class="btn btn-sm btn-modern btn-danger-modern" 
                                               title="Eliminar categoría">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>No hay categorías disponibles</h5>
                                            <p>No se encontraron categorías registradas en el sistema.</p>
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-modern btn-success-modern">
                                                <i class="fas fa-plus"></i> Crear Primera Categoría
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
                const categoriaNombre = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
                if (confirm(`¿Está seguro de eliminar la categoría "${categoriaNombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>
