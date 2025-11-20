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
    COUNT(*) as total_subcategorias,
    COUNT(DISTINCT id_categoria) as categorias_con_subcategorias,
    COUNT(CASE WHEN descripcion IS NOT NULL AND descripcion != '' THEN 1 END) as con_descripcion
    FROM subcategorias");
$stats = $statsQuery->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Subcategorías - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #9c27b0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="50" cy="50" r="0.5" fill="rgba(255,255,255,0.04)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: 1;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px);
            border-radius: 25px;
            padding: 50px;
            margin: 30px auto;
            max-width: 1400px;
            width: calc(100% - 60px);
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            position: relative;
            z-index: 2;
            animation: containerFloat 6s ease-in-out infinite;
        }

        @keyframes containerFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .page-title {
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
            font-size: 3rem;
            font-weight: 800;
            text-shadow: 
                0 4px 8px rgba(0, 0, 0, 0.4),
                0 0 20px rgba(255, 255, 255, 0.2);
            position: relative;
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        .page-title i {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease-in-out infinite;
            margin-right: 15px;
        }

        @keyframes titleGlow {
            0% { text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4), 0 0 20px rgba(255, 255, 255, 0.2); }
            100% { text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4), 0 0 30px rgba(255, 255, 255, 0.4); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .filters-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 35px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .filters-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #f39c12);
            background-size: 200% 100%;
            animation: borderGlow 3s ease-in-out infinite;
        }

        @keyframes borderGlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .filter-title {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .filter-title i {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-right: 10px;
        }

        /* Centrar el bloque de filtros y crear un inner-card centrado */
        .filters-container {
            display: flex;
            justify-content: center;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .filters-inner {
            background: rgba(255,255,255,0.94);
            color: #2c3e50;
            border-radius: 12px;
            padding: 22px;
            width: 100%;
            max-width: 1200px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.18);
            border: 1px solid rgba(0,0,0,0.06);
        }

        .filters-inner .filter-title {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 12px;
            background: none;
            -webkit-background-clip: initial;
            -webkit-text-fill-color: initial;
            text-shadow: none;
        }

        /* Inputs inside the inner card should use darker text */
        .filters-inner .filter-input,
        .filters-inner .filter-select {
            background: #fff;
            color: #2c3e50;
            box-shadow: none;
        }

        /* Mejorar labels del formulario */
        .form-label {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 8px;
        }

        /* Mejorar texto de botones */
        .btn-modern {
            color: #fff !important;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            color: #2c3e50;
            font-weight: 600;
            padding: 15px 20px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #4facfe;
            box-shadow: 
                0 0 0 0.2rem rgba(79, 172, 254, 0.25),
                0 8px 25px rgba(79, 172, 254, 0.2);
            color: #2c3e50;
            transform: translateY(-2px);
        }

        /* Mejorar selects */
        .form-select {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            color: #2c3e50;
            font-weight: 600;
            padding: 15px 20px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-select:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #4facfe;
            box-shadow: 
                0 0 0 0.2rem rgba(79, 172, 254, 0.25),
                0 8px 25px rgba(79, 172, 254, 0.2);
            color: #2c3e50;
        }

        .form-select option {
            background: #ffffff;
            color: #2c3e50;
            font-weight: 600;
            padding: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 45px 35px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 15px 40px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: perspective(1000px) rotateX(0deg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                from 0deg at 50% 50%,
                transparent 0deg,
                rgba(255, 255, 255, 0.1) 60deg,
                transparent 120deg,
                rgba(255, 255, 255, 0.05) 180deg,
                transparent 240deg,
                rgba(255, 255, 255, 0.1) 300deg,
                transparent 360deg
            );
            animation: rotate 8s linear infinite;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: perspective(1000px) rotateX(5deg) translateY(-10px) scale(1.02);
            box-shadow: 
                0 25px 60px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                0 0 30px rgba(255, 255, 255, 0.1);
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stat-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #f39c12);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: iconPulse 3s ease-in-out infinite, gradientShift 6s ease-in-out infinite;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: numberFloat 4s ease-in-out infinite;
        }

        @keyframes numberFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-2px); }
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-modern {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .table-modern thead {
            background: linear-gradient(135deg, 
                rgba(0, 123, 255, 0.3) 0%, 
                rgba(79, 172, 254, 0.3) 100%);
            backdrop-filter: blur(20px);
        }

        .table-modern th {
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 20px 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
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
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.03);
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .table-modern td {
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .table-modern th {
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 20px 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-modern {
            padding: 12px 20px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-modern:active {
            transform: translateY(-1px) scale(1.02);
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
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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

        .subcategory-icon {
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

        /* Scroll horizontal para móviles - Subcategorías */
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
                min-width: 900px !important; /* Ancho mínimo para 6 columnas */
                margin-bottom: 0;
                width: 900px;
            }
            
            .d-block.d-md-none .table-modern th,
            .d-block.d-md-none .table-modern td {
                white-space: nowrap !important;
                padding: 10px 8px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para subcategorías móvil (6 columnas) */
            .d-block.d-md-none .table-modern th:nth-child(1),
            .d-block.d-md-none .table-modern td:nth-child(1) { min-width: 70px; }
            
            .d-block.d-md-none .table-modern th:nth-child(2),
            .d-block.d-md-none .table-modern td:nth-child(2) { min-width: 180px; }
            
            .d-block.d-md-none .table-modern th:nth-child(3),
            .d-block.d-md-none .table-modern td:nth-child(3) { min-width: 150px; }
            
            .d-block.d-md-none .table-modern th:nth-child(4),
            .d-block.d-md-none .table-modern td:nth-child(4) { min-width: 220px; }
            
            .d-block.d-md-none .table-modern th:nth-child(5),
            .d-block.d-md-none .table-modern td:nth-child(5) { min-width: 150px; }
            
            .d-block.d-md-none .table-modern th:nth-child(6),
            .d-block.d-md-none .table-modern td:nth-child(6) { min-width: 130px; }
            
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
            <i class="fas fa-layer-group"></i> Gestión de Subcategorías
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
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-number"><?php echo $stats['total_subcategorias']; ?></div>
                <div class="stat-label">Total Subcategorías</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number"><?php echo $stats['categorias_con_subcategorias']; ?></div>
                <div class="stat-label">Categorías con Subcategorías</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-align-left"></i>
                </div>
                <div class="stat-number"><?php echo $stats['con_descripcion']; ?></div>
                <div class="stat-label">Con Descripción</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <div class="filters-inner" style="padding: 35px 50px; max-width: 95% !important;">
                <div class="filter-title" style="margin-bottom: 25px; text-align: center; font-size: 1.2rem;">
                    <i class="fas fa-filter"></i> Filtros de Búsqueda
                </div>
                <form method="GET" action="" id="filterForm">
                    <div class="row g-4" style="max-width: 100%; margin: 0 auto;">
                        <div class="col-md-3">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-tag"></i> Nombre
                            </label>
                            <input type="text"
                                   name="nombre"
                                   class="form-control"
                                   placeholder="Buscar..."
                                   style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem; width: 100%;"
                                   value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-layer-group"></i> Categoría
                            </label>
                            <select name="categoria" 
                                    class="form-select"
                                    style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem; width: 100%;">
                                <option value="">Todas</option>
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat->id_categoria ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat->id_categoria ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end justify-content-center">
                            <div class="d-flex gap-3 w-100 justify-content-center">
                                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; padding: 12px; border-radius: 50%; font-weight: 600; font-size: 1rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;" title="Buscar">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()" style="background: #6c757d; border: none; padding: 12px; border-radius: 50%; font-weight: 600; font-size: 1rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;" title="Limpiar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mb-4 text-center">
            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=create" class="btn btn-modern btn-success-modern me-2">
                <i class="fas fa-plus"></i> Nueva Subcategoría
            </a>
            <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <!-- Tabla de Subcategorías (Desktop) -->
        <div class="table-container d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-layer-group"></i> Subcategoría</th>
                            <th><i class="fas fa-tag"></i> Categoría Padre</th>
                            <th><i class="fas fa-align-left"></i> Descripción</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha Creación</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($subcategorias) && is_array($subcategorias) && !empty($subcategorias)): ?>
                            <?php foreach ($subcategorias as $subcatData): ?>
                            <?php 
                            // Extraer el objeto subcategoría y el nombre de la categoría
                            $subcat = $subcatData['obj'];
                            $categoria_nombre = $subcatData['categoria_nombre'];
                            ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($subcat->id_subcategoria) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="subcategory-icon">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($subcat->nombre) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $subcat->id_subcategoria ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-info">
                                        <?= htmlspecialchars($categoria_nombre ?? 'Sin categoría') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($subcat->descripcion)): ?>
                                        <span class="badge badge-modern badge-success" title="<?= htmlspecialchars($subcat->descripcion) ?>">
                                            <?= strlen($subcat->descripcion) > 50 ? substr(htmlspecialchars($subcat->descripcion), 0, 50) . '...' : htmlspecialchars($subcat->descripcion) ?>
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
                                        <strong><?= date('d/m/Y') ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i') ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/SubcategoryController.php?accion=edit&id=<?= urlencode($subcat->id_subcategoria) ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar subcategoría">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/views/subcategorias/delete.php?id=<?= urlencode($subcat->id_subcategoria) ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar subcategoría">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No hay subcategorías disponibles</h5>
                                        <p>No se encontraron subcategorías que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/SubcategoryController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primera Subcategoría
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
                                <th><i class="fas fa-layer-group"></i> Subcategoría</th>
                                <th><i class="fas fa-tag"></i> Categoría Padre</th>
                                <th><i class="fas fa-align-left"></i> Descripción</th>
                                <th><i class="fas fa-calendar-alt"></i> Fecha Creación</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($subcategorias) && is_array($subcategorias) && !empty($subcategorias)): ?>
                                <?php foreach ($subcategorias as $subcatData): ?>
                                <?php 
                                // Extraer el objeto subcategoría y el nombre de la categoría
                                $subcat = $subcatData['obj'];
                                $categoria_nombre = $subcatData['categoria_nombre'];
                                ?>
                                <tr>
                                    <td>
                                        <strong>#<?= htmlspecialchars($subcat->id_subcategoria) ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="subcategory-icon">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($subcat->nombre) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-barcode"></i> ID: <?= $subcat->id_subcategoria ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern badge-info">
                                            <?= htmlspecialchars($categoria_nombre ?? 'Sin categoría') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($subcat->descripcion)): ?>
                                            <span class="badge badge-modern badge-success" title="<?= htmlspecialchars($subcat->descripcion) ?>">
                                                <?= strlen($subcat->descripcion) > 50 ? substr(htmlspecialchars($subcat->descripcion), 0, 50) . '...' : htmlspecialchars($subcat->descripcion) ?>
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
                                            <strong><?= date('d/m/Y') ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('H:i') ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=edit&id=<?= urlencode($subcat->id_subcategoria) ?>" 
                                               class="btn btn-sm btn-modern btn-warning-modern" 
                                               title="Editar subcategoría">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                            <a href="/RMIE/app/views/subcategorias/delete.php?id=<?= urlencode($subcat->id_subcategoria) ?>" 
                                               class="btn btn-sm btn-modern btn-danger-modern" 
                                               title="Eliminar subcategoría">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>No hay subcategorías disponibles</h5>
                                            <p>No se encontraron subcategorías que coincidan con los filtros aplicados.</p>
                                            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=create" class="btn btn-modern btn-success-modern">
                                                <i class="fas fa-plus"></i> Crear Primera Subcategoría
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
        function limpiarFiltros() {
            document.getElementById('filterForm').reset();
            window.location.href = '/RMIE/app/controllers/SubcategoryController.php?accion=index';
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
                const subcategoriaNombre = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
                if (confirm(`¿Está seguro de eliminar la subcategoría "${subcategoriaNombre}"?\n\nEsta acción no se puede deshacer.`)) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>
</html>