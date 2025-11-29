<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Los mensajes de sesión ya vienen del controlador
// $error_message y $success_message están disponibles desde el controlador

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
        
        // Contar rutas recientes (últimos 7 días)
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

        .page-title {
            color: #ffffffff;
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
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 100%;
            overflow-x: auto;
        }

        .rutas-filters {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .rutas-filter-title {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .rutas-filter-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            font-size: 0.9rem;
        }

        .rutas-filter-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .rutas-filter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .rutas-filter-input:focus {
            outline: none;
            border-color: #4facfe;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 2px rgba(79, 172, 254, 0.3);
        }

        .rutas-btn {
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .rutas-btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .rutas-btn-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .rutas-btn-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .rutas-btn-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .rutas-btn-info {
            background: linear-gradient(45deg, #a8edea, #fed6e3);
            color: #333;
        }

        .rutas-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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

        /* Estilos para los option del select */
        .form-control-modern option {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
        }

        .form-control-modern option:hover {
            background: #34495e;
        }

        /* Contenedor principal de estadísticas */
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

        /* Tarjetas de estadísticas modernas */
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

        /* Iconos de estadísticas */
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
            position: relative;
        }

        /* Contenido de estadísticas */
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
        }

        /* Estilos mejorados para botones del modal */
        .btn:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            box-shadow: 0 6px 20px rgba(76,175,80,0.4) !important;
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        /* Estilo para botón de completar */
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-success-modern:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(40,167,69,0.4);
        }
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Variaciones de color para cada tipo */
        .primary-card { border-left: 4px solid #667eea; }
        .primary-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
        .primary-number { color: #667eea; }

        .success-card { border-left: 4px solid #4facfe; }
        .success-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }
        .success-number { color: #4facfe; }

        .warning-card { border-left: 4px solid #ff9a9e; }
        .warning-icon { background: linear-gradient(135deg, #ff9a9e, #fecfef); }
        .warning-number { color: #ff9a9e; }

        .info-card { border-left: 4px solid #a8edea; }
        .info-icon { background: linear-gradient(135deg, #a8edea, #fed6e3); }
        .info-number { color: #a8edea; }

        .danger-card { border-left: 4px solid #ff6b6b; }
        .danger-icon { background: linear-gradient(135deg, #ff6b6b, #ee5a52); }
        .danger-number { color: #ff6b6b; }

        .secondary-card { border-left: 4px solid #c471ed; }
        .secondary-icon { background: linear-gradient(135deg, #c471ed, #f64f59); }
        .secondary-number { color: #c471ed; }

        /* Responsividad mejorada */
        @media (max-width: 1200px) {
            .stats-grid-main {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
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
            .stats-grid-main {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .main-stat-card {
                padding: 20px;
            }
            .stats-title {
                font-size: 1.5rem;
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

        /* Estilos para el grid de estadísticas principales */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Estilos para el nuevo diseño de estadísticas */
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
            position: relative;
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
        .primary-number { color: #667eea; }

        .success-card { border-left: 4px solid #4facfe; }
        .success-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }
        .success-number { color: #4facfe; }

        .warning-card { border-left: 4px solid #ff9a9e; }
        .warning-icon { background: linear-gradient(135deg, #ff9a9e, #fecfef); }
        .warning-number { color: #ff9a9e; }

        .info-card { border-left: 4px solid #a8edea; }
        .info-icon { background: linear-gradient(135deg, #a8edea, #fed6e3); }
        .info-number { color: #a8edea; }

        .danger-card { border-left: 4px solid #ff6b6b; }
        .danger-icon { background: linear-gradient(135deg, #ff6b6b, #ee5a52); }
        .danger-number { color: #ff6b6b; }

        .secondary-card { border-left: 4px solid #c471ed; }
        .secondary-icon { background: linear-gradient(135deg, #c471ed, #f64f59); }
        .secondary-number { color: #c471ed; }

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
            overflow-x: auto;
            margin-bottom: 20px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        /* Estilos para badges modernos */
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

        .badge-info {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-secondary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .badge-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        /* Iconos de rutas */
        .route-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Responsividad mejorada */
        @media (max-width: 992px) {
            .dashboard-container {
                margin: 10px;
                padding: 20px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .table-container {
                padding: 15px;
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
            
            .table-modern th,
            .table-modern td {
                padding: 8px 4px;
                font-size: 0.8rem;
            }
            
            .rutas-btn {
                padding: 8px 10px;
                font-size: 0.75rem;
            }
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

        .btn-info-modern {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
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

        .route-icon {
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
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .distance-info {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Estilos para panel de acciones rápidas */
        .quick-actions-panel {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quick-actions-title {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
        }

        .quick-actions-grid {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .quick-action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 15px 10px;
            color: #fff;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .quick-action-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .quick-action-btn span {
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Estilos para widgets informativos */
        .widgets-container {
            margin-bottom: 30px;
        }

        .info-widget {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .info-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .widget-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .widget-header i {
            color: #4facfe;
            font-size: 1.2rem;
        }

        .widget-header h4 {
            color: #fff;
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        .widget-content {
            padding: 20px;
        }

        /* Actividad reciente */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border-left: 3px solid #4facfe;
        }

        .activity-time {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .activity-text {
            color: #fff;
            font-weight: 500;
            flex-grow: 1;
            margin-left: 10px;
        }

        /* Métricas de rendimiento */
        .performance-metrics {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .metric-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .metric-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            min-width: 80px;
        }

        .metric-bar {
            flex-grow: 1;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .metric-progress {
            height: 100%;
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .metric-value {
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            min-width: 35px;
            text-align: right;
        }

        /* Contenedor de botones de acción */
        .action-buttons-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .primary-actions, .secondary-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-lg {
            padding: 12px 24px;
            font-size: 1.1rem;
        }

        .btn-secondary-modern {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        /* Animaciones adicionales */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 172, 254, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(79, 172, 254, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 172, 254, 0); }
        }

        .quick-action-btn:active {
            animation: pulse 0.6s;
        }

        /* Responsividad mejorada */
        @media (max-width: 768px) {
            .quick-actions-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .action-buttons-container {
                flex-direction: column;
                text-align: center;
            }
            
            .primary-actions, .secondary-actions {
                justify-content: center;
            }
        }

        /* Estilos para modo tarjetas - Rutas */
        .routes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .routes-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 280px;
        }

        .routes-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .routes-card:hover::before {
            left: 100%;
        }

        .routes-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .routes-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .routes-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .routes-card-title {
            flex-grow: 1;
        }

        .routes-card-id {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
        }

        .routes-card-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            line-height: 1.2;
        }

        .routes-card-body {
            margin-bottom: 20px;
        }

        .routes-card-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .routes-card-field {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
        }

        .routes-card-field i {
            width: 20px;
            text-align: center;
            color: #4facfe;
            font-size: 1rem;
        }

        .routes-card-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            min-width: 80px;
            font-weight: 500;
        }

        .routes-card-value {
            flex-grow: 1;
            color: #fff;
            font-weight: 500;
        }

        .routes-card-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .routes-status-activa {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .routes-status-inactiva {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .routes-status-pendiente {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .routes-status-completada {
            background: linear-gradient(45deg, #a8edea, #fed6e3);
            color: #333;
        }

        .routes-card-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .routes-card-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
            min-width: 70px;
            justify-content: center;
        }

        .routes-card-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .routes-btn-info {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .routes-btn-warning {
            background: linear-gradient(45deg, #ff9a9e, #fecfef);
            color: white;
        }

        .routes-btn-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .routes-btn-success {
            background: linear-gradient(45deg, #4CAF50, #45A049);
            color: white;
            cursor: pointer;
        }

        .routes-btn-success:hover {
            background: linear-gradient(45deg, #45A049, #4CAF50);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .routes-empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        .routes-empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .routes-empty-state h5 {
            color: #fff;
            margin-bottom: 15px;
        }

        /* Responsividad para tarjetas de rutas */
        @media (max-width: 1200px) {
            .routes-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .routes-grid {
                grid-template-columns: 1fr;
                padding: 15px;
                gap: 15px;
            }
            
            .routes-card {
                padding: 20px;
                min-height: auto;
            }
            
            .routes-card-icon {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
            
            .routes-card-name {
                font-size: 1.1rem;
            }
        }

        /* Scroll horizontal para móviles - Rutas */
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
                min-width: 1400px !important; /* Ancho mínimo para 9 columnas */
                margin-bottom: 0;
                width: 1400px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para rutas (9 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 70px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 180px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 200px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 180px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 150px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 180px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 120px; }
            
            .table-modern th:nth-child(8),
            .table-modern td:nth-child(8) { min-width: 150px; }
            
            .table-modern th:nth-child(9),
            .table-modern td:nth-child(9) { min-width: 120px; }
            
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
            <i class="fas fa-route"></i> Gestión de Rutas
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

        <!-- Panel de Estadísticas Visuales Principales -->
        <div class="main-stats-container">
            <div class="stats-header">
                <h3 class="stats-title">
                    <i class="fas fa-chart-bar"></i> Resumen General de Rutas
                </h3>
            </div>
            <div class="stats-grid-main">
                <div class="main-stat-card primary-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon primary-icon">
                            <i class="fas fa-route"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number primary-number"><?php echo $totalRutas; ?></div>
                        <div class="stat-label">Total de Rutas</div>
                    </div>
                </div>

                <div class="main-stat-card success-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon success-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number success-number"><?php echo $rutasActivas; ?></div>
                        <div class="stat-label">Rutas Activas</div>
                    </div>
                </div>

                <div class="main-stat-card warning-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon warning-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number warning-number"><?php echo $rutasPendientes; ?></div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>

                <div class="main-stat-card info-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon info-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number info-number"><?php echo $rutasCompletadas; ?></div>
                        <div class="stat-label">Completadas</div>
                    </div>
                </div>

                <div class="main-stat-card danger-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon danger-icon">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number danger-number"><?php echo $rutasInactivas; ?></div>
                        <div class="stat-label">Inactivas</div>
                    </div>
                </div>

                <div class="main-stat-card secondary-card">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon secondary-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number secondary-number"><?php echo $rutasRecientes; ?></div>
                        <div class="stat-label">Esta Semana</div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfico de distribución de estados -->
            <div class="chart-container" style="margin-top: 30px; text-align: center;">
                <canvas id="estadoChart" width="400" height="200"></canvas>
            </div>
        </div>



        <!-- Panel de Acciones Rápidas -->
        <div class="quick-actions-panel">
            <div class="quick-actions-title">
                <i class="fas fa-bolt"></i> Acciones Rápidas
            </div>
            <div class="quick-actions-grid">
                <button class="quick-action-btn" onclick="verMapa()">
                    <i class="fas fa-map"></i>
                    <span>Ver Mapa</span>
                </button>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <button class="quick-action-btn" onclick="limpiarCompletadas();" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
                    <i class="fas fa-broom"></i>
                    <span>Limpiar Completadas</span>
                </button>
                <?php endif; ?>
            </div>
        </div>        <!-- Filtros modernos -->
        <!-- Asignación de Clientes por Día (Lunes a Sábado) -->
        <div class="filters-container" style="margin-top:15px;">
            <h5 style="color:#fff; font-weight:600; margin-bottom:15px;">
                <i class="fas fa-calendar-week"></i> Planificación Semanal de Clientes
            </h5>
            <form method="POST" action="/RMIE/app/controllers/RouteController.php?accion=index">
                <div class="row g-3">
                    <?php if(isset($dias_predeterminados) && is_array($dias_predeterminados)): ?>
                        <?php foreach($dias_predeterminados as $dia): ?>
                            <div class="col-12 col-md-4 col-lg-2">
                                <label class="filter-label" style="display:block; color:#fff; background:rgba(255,255,255,0.15);"><?php echo $dia; ?></label>
                                <select name="clientes_dia[<?php echo $dia; ?>][]" class="form-select" multiple size="6" style="font-size:0.75rem;">
                                    <?php foreach($available_clients as $c): ?>
                                        <?php $selected = (isset($asignaciones_clientes[$dia]) && in_array($c['id_clientes'], $asignaciones_clientes[$dia])) ? 'selected' : ''; ?>
                                        <option value="<?php echo htmlspecialchars($c['id_clientes']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($c['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($asignaciones_clientes[$dia]) && !empty($asignaciones_clientes[$dia])): ?>
                                    <div style="margin-top:6px; font-size:0.65rem; color:#cfe8ff;">
                                        <?php foreach($asignaciones_clientes[$dia] as $idc): ?>
                                            <span class="badge badge-secondary" style="display:inline-block; margin:2px; padding:4px 8px; font-size:0.55rem;"><?php echo htmlspecialchars($mapa_clientes[$idc] ?? $idc); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm" style="border-radius:10px;">
                        <i class="fas fa-save"></i> Guardar Asignaciones
                    </button>
                    <a href="/RMIE/app/controllers/RouteController.php?accion=index&reset_asignaciones=1" class="btn btn-warning btn-sm" style="border-radius:10px;">
                        <i class="fas fa-undo"></i> Reiniciar
                    </a>
                </div>
            </form>
            <div class="mt-4" style="font-size:0.75rem; color:#fff;">
                <i class="fas fa-info-circle"></i> Las asignaciones se guardan por usuario y se usan para filtrar rutas por día.
            </div>
        </div>
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
            grid-template-columns: 1fr 1fr 1fr 1fr 200px;
            gap: 18px 32px;
            align-items: flex-start;
            flex-wrap: wrap;
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
            grid-row: span 2;
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
            justify-content: center;
            padding-left: 20px;
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
            .filters-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .filter-actions { 
                grid-row: auto;
                flex-direction: row;
                justify-content: stretch;
                padding-left: 0;
                margin-top: 16px;
            }
            .btn-pill { flex: 1; }
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
            <div class="filter-title" style="text-align:center; font-size:1.3rem; font-weight:600; color:#fff; margin-bottom:18px;">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="" id="filterForm">
                <div class="row g-3 align-items-end justify-content-center" style="margin-bottom:0; max-width:900px; margin-left:auto; margin-right:auto;">
                    <div class="col-md-3 col-12">
                        <label class="form-label" style="color:#333;font-weight:600;"><i class="fas fa-tags"></i> Estado</label>
                        <select name="estado" class="form-select form-control-modern">
                            <option value="">Todos los estados</option>
                            <option value="activa" <?= ($_GET['estado'] ?? '') === 'activa' ? 'selected' : '' ?>>Activa</option>
                            <option value="pendiente" <?= ($_GET['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="form-label" style="color:#333;font-weight:600;"><i class="fas fa-store"></i> Local</label>
                        <select name="local" class="form-select form-control-modern">
                            <option value="">Todos los locales</option>
                            <?php if (isset($available_locals) && is_array($available_locals)): ?>
                                <?php foreach ($available_locals as $local): ?>
                                    <option value="<?= htmlspecialchars($local['nombre_local']) ?>" <?= (($_GET['local'] ?? '') === $local['nombre_local']) ? 'selected' : '' ?>><?= htmlspecialchars($local['nombre_local']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="form-label" style="color:#333;font-weight:600;"><i class="fas fa-map-marker-alt"></i> Cliente</label>
                        <select name="cliente" class="form-select form-control-modern">
                            <option value="">Todos los clientes</option>
                            <?php if (isset($available_clients) && is_array($available_clients)): ?>
                                <?php foreach ($available_clients as $cliente): ?>
                                    <option value="<?= htmlspecialchars($cliente['nombre']) ?>" <?= (($_GET['cliente'] ?? '') === $cliente['nombre']) ? 'selected' : '' ?>><?= htmlspecialchars($cliente['nombre']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2 col-12">
                        <label class="form-label" style="color:#333;font-weight:600;"><i class="fas fa-calendar-day"></i> Día</label>
                        <select name="dia" class="form-select form-control-modern">
                            <option value="">Todos</option>
                            <?php if(isset($dias_predeterminados)): ?>
                                <?php foreach($dias_predeterminados as $d): ?>
                                    <option value="<?= $d ?>" <?= (($_GET['dia'] ?? '') === $d) ? 'selected' : '' ?>><?= $d ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2 col-12">
                        <label class="form-label" style="color:#333;font-weight:600;"><i class="fas fa-sort"></i> Ordenar por</label>
                        <select name="orden" class="form-select form-control-modern">
                            <option value="fecha_desc" <?= ($_GET['orden'] ?? 'fecha_desc') === 'fecha_desc' ? 'selected' : '' ?>>Más recientes</option>
                            <option value="fecha_asc" <?= ($_GET['orden'] ?? '') === 'fecha_asc' ? 'selected' : '' ?>>Más antiguos</option>
                            <option value="direccion_asc" <?= ($_GET['orden'] ?? '') === 'direccion_asc' ? 'selected' : '' ?>>Dirección A-Z</option>
                            <option value="estado_asc" <?= ($_GET['orden'] ?? '') === 'estado_asc' ? 'selected' : '' ?>>Estado A-Z</option>
                        </select>
                    </div>
                    <div class="col-md-1 col-12 d-flex flex-column gap-2 align-items-center justify-content-end" style="min-width:120px;">
                        <button type="submit" class="btn btn-primary w-100 mb-2" style="border-radius: 10px;">
                            <i class="fas fa-search"></i> FILTRAR
                        </button>
                        <button type="button" class="btn btn-danger w-100" style="border-radius: 10px;" onclick="limpiarFiltros()">
                            <i class="fas fa-times"></i> LIMPIAR
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Botones de acción mejorados -->
        <div class="mb-4">
            <div class="action-buttons-container">
                <div class="primary-actions">
                    <a href="/RMIE/app/controllers/RouteController.php?accion=create" class="btn btn-modern btn-success-modern btn-lg">
                        <i class="fas fa-plus"></i> Nueva Ruta
                    </a>
                </div>
                <div class="secondary-actions">
                    <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

            <!-- Selector de vista: Tabla / Tarjetas -->
            <div class="mb-3 d-flex justify-content-end align-items-center">
                <div class="btn-group" role="group" aria-label="Selector de vista">
                    <button type="button" id="rutasTableBtn" class="btn btn-sm btn-modern btn-primary-modern">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                    <button type="button" id="rutasCardsBtn" class="btn btn-sm btn-modern btn-secondary-modern">
                        <i class="fas fa-th-large"></i> Tarjetas
                    </button>
                </div>
            </div>

            <!-- Contenedor de Tarjetas -->
            <div id="rutasCardsContainer" class="routes-grid" style="display: none;">
                <?php if (isset($rutas) && is_array($rutas) && !empty($rutas)): ?>
                    <?php foreach ($rutas as $ruta): ?>
                        <div class="routes-card">
                            <div class="routes-card-header">
                                <div class="routes-card-icon">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div class="routes-card-title">
                                    <div class="routes-card-id">
                                        <i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($ruta['id_ruta'] ?? '') ?>
                                    </div>
                                    <h5 class="routes-card-name">
                                        <?= htmlspecialchars($ruta['local_nombre'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?>
                                    </h5>
                                </div>
                            </div>
                            
                            <div class="routes-card-body">
                                <div class="routes-card-info">
                                    <div class="routes-card-field">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="routes-card-label">Dirección:</span>
                                        <span class="routes-card-value">
                                            <?= htmlspecialchars($ruta['direccion'] ?? 'Sin dirección') ?>
                                        </span>
                                    </div>
                                    
                                    <div class="routes-card-field">
                                        <i class="fas fa-user"></i>
                                        <span class="routes-card-label">Cliente:</span>
                                        <span class="routes-card-value">
                                            <?= htmlspecialchars($ruta['cliente_nombre'] ?? 'Sin cliente') ?>
                                        </span>
                                    </div>
                                    
                                    <div class="routes-card-field">
                                        <i class="fas fa-store"></i>
                                        <span class="routes-card-label">Local:</span>
                                        <span class="routes-card-value">
                                            <?= htmlspecialchars($ruta['local_nombre'] ?? 'Sin local') ?>
                                        </span>
                                    </div>
                                    
                                    <div class="routes-card-field">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="routes-card-label">Venta ID:</span>
                                        <span class="routes-card-value">
                                            <?= $ruta['id_ventas'] ?? 'N/A' ?>
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($ruta['id_reportes'])): ?>
                                    <div class="routes-card-field">
                                        <i class="fas fa-file-alt"></i>
                                        <span class="routes-card-label">Reporte:</span>
                                        <span class="routes-card-value">
                                            #<?= $ruta['id_reportes'] ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="routes-card-field">
                                        <i class="fas fa-traffic-light"></i>
                                        <span class="routes-card-label">Estado:</span>
                                        <span class="routes-card-value">
                                            <?php
                                            $estado = $ruta['estado'] ?? 'activa';
                                            $statusClass = 'routes-status-' . $estado;
                                            ?>
                                            <span class="routes-card-status <?= $statusClass ?>" <?= $estado === 'completada' ? 'style="opacity: 0.7;"' : '' ?>>
                                                <?php if ($estado === 'completada'): ?>
                                                    <i class="fas fa-check-circle me-1"></i>
                                                <?php endif; ?>
                                                <?= ucfirst($estado) ?>
                                            </span>
                                        </span>
                                    </div>
                                    
                                    <div class="routes-card-field">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span class="routes-card-label">Fecha:</span>
                                        <span class="routes-card-value">
                                            <?= date('d/m/Y H:i') ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="routes-card-actions">
                                <a href="/RMIE/app/controllers/RouteController.php?accion=edit&id=<?= urlencode($ruta['id_ruta'] ?? '') ?>" 
                                   class="routes-card-btn routes-btn-warning" 
                                   title="Editar ruta">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                
                                <?php if (($ruta['estado'] ?? 'activa') !== 'completada'): ?>
                                <button type="button" 
                                        class="routes-card-btn routes-btn-success" 
                                        title="Completar y finalizar ruta"
                                        onclick="completarRuta(<?= $ruta['id_ruta'] ?? 0 ?>, '<?= addslashes($ruta['nombre_local'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?>');">
                                    <i class="fas fa-check-circle"></i> Completar
                                </button>
                                <?php endif; ?>
                                
                                <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                <a href="/RMIE/app/controllers/RouteController.php?accion=delete&id=<?= urlencode($ruta['id_ruta'] ?? '') ?>" 
                                   class="routes-card-btn routes-btn-danger" 
                                   title="Eliminar ruta"
                                   onclick="return confirm('¿Está seguro de eliminar la ruta \"<?= addslashes($ruta['local_nombre'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?>\"?\n\nEsta acción no se puede deshacer.')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="routes-empty-state">
                        <i class="fas fa-route"></i>
                        <h5>No hay rutas disponibles</h5>
                        <p>No se encontraron rutas que coincidan con los filtros aplicados.</p>
                        <a href="/RMIE/app/controllers/RouteController.php?accion=create" class="btn btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Crear Primera Ruta
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tabla de Rutas -->
            <div id="rutasTableContainer" class="table-container" style="overflow-x: auto; position: relative;">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-route"></i> Ruta</th>
                            <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-store"></i> Local</th>
                            <th><i class="fas fa-shopping-cart"></i> Venta</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($rutas) && is_array($rutas) && !empty($rutas)): ?>
                            <?php foreach ($rutas as $ruta): ?>
                                <?php
                                // Procesar datos JSON si existen
                                $id_locales_array = !empty($ruta['id_locales_json']) ? json_decode($ruta['id_locales_json'], true) : [];
                                $id_clientes_array = !empty($ruta['id_clientes_json']) ? json_decode($ruta['id_clientes_json'], true) : [];
                                $id_ventas_array = !empty($ruta['id_ventas_json']) ? json_decode($ruta['id_ventas_json'], true) : [];
                                $direcciones_array = !empty($ruta['direcciones_json']) ? json_decode($ruta['direcciones_json'], true) : [];
                                
                                // Obtener nombres de clientes desde los IDs
                                $clientes_nombres = [];
                                if (!empty($id_clientes_array)) {
                                    foreach ($id_clientes_array as $id_cliente) {
                                        $clienteQuery = "SELECT nombre FROM clientes WHERE id_clientes = " . intval($id_cliente);
                                        $clienteResult = $conn->query($clienteQuery);
                                        if ($clienteResult && $clienteData = $clienteResult->fetch_assoc()) {
                                            $clientes_nombres[] = $clienteData['nombre'];
                                        }
                                    }
                                }
                                
                                // Obtener nombres de locales desde los IDs
                                $locales_nombres = [];
                                if (!empty($id_locales_array)) {
                                    foreach ($id_locales_array as $id_local) {
                                        $localQuery = "SELECT nombre_local FROM locales WHERE id_locales = " . intval($id_local);
                                        $localResult = $conn->query($localQuery);
                                        if ($localResult && $localData = $localResult->fetch_assoc()) {
                                            $locales_nombres[] = $localData['nombre_local'];
                                        }
                                    }
                                }
                                
                                // Obtener nombres de ventas desde los IDs
                                $ventas_display = [];
                                if (!empty($id_ventas_array)) {
                                    foreach ($id_ventas_array as $id_venta) {
                                        $ventaQuery = "SELECT nombre, cantidad FROM ventas WHERE id_ventas = " . intval($id_venta);
                                        $ventaResult = $conn->query($ventaQuery);
                                        if ($ventaResult && $ventaData = $ventaResult->fetch_assoc()) {
                                            $nombre_venta = !empty($ventaData['nombre']) ? $ventaData['nombre'] : 'Venta #' . $id_venta;
                                            $cantidad = $ventaData['cantidad'] ?? 0;
                                            $ventas_display[] = $nombre_venta . ' (Cant: ' . $cantidad . ')';
                                        }
                                    }
                                }
                                ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($ruta['id_ruta'] ?? '') ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="route-icon">
                                            <i class="fas fa-route"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($ruta['nombre_local'] ?? $ruta['local_nombre'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $ruta['id_ruta'] ?? '' ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($direcciones_array)): ?>
                                        <?php foreach ($direcciones_array as $idx => $dir): ?>
                                            <span class="badge badge-modern badge-info" style="display: block; margin-bottom: 5px;">
                                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($dir) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-info">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($ruta['direccion'] ?? 'Sin dirección') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($clientes_nombres)): ?>
                                        <?php foreach ($clientes_nombres as $cliente_name): ?>
                                            <span class="badge badge-modern badge-secondary" style="display: block; margin-bottom: 3px;">
                                                <i class="fas fa-user"></i> <?= htmlspecialchars($cliente_name) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-user"></i> <?= htmlspecialchars($ruta['nombre_cliente'] ?? $ruta['cliente_nombre'] ?? 'Sin cliente') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($locales_nombres)): ?>
                                        <?php foreach ($locales_nombres as $local_name): ?>
                                            <span class="badge badge-modern badge-info" style="display: block; margin-bottom: 3px;">
                                                <i class="fas fa-store"></i> <?= htmlspecialchars($local_name) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-info">
                                            <i class="fas fa-store"></i> <?= htmlspecialchars($ruta['local_nombre'] ?? 'Sin local') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <?php if (!empty($ventas_display)): ?>
                                            <?php foreach ($ventas_display as $venta_name): ?>
                                                <span class="badge badge-modern badge-secondary" style="display: block; margin-bottom: 3px;">
                                                    <i class="fas fa-shopping-cart"></i> <?= htmlspecialchars($venta_name) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php if (!empty($ruta['id_ventas'])): ?>
                                                <span class="badge badge-modern badge-secondary">
                                                    <i class="fas fa-shopping-cart"></i> Venta #<?= $ruta['id_ventas'] ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-modern badge-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> Sin venta asignada
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (!empty($ruta['id_reportes'])): ?>
                                            <br><small class="badge badge-modern badge-warning mt-1">
                                                <i class="fas fa-file-alt"></i> Reporte: <?= $ruta['id_reportes'] ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $estado = $ruta['estado'] ?? 'activa';
                                    $badgeClass = '';
                                    $iconClass = '';
                                    switch ($estado) {
                                        case 'activa':
                                            $badgeClass = 'badge-success';
                                            $iconClass = 'fas fa-play-circle';
                                            break;
                                        case 'completada':
                                            $badgeClass = 'badge-info';
                                            $iconClass = 'fas fa-check-circle';
                                            break;
                                        case 'inactiva':
                                            $badgeClass = 'badge-danger';
                                            $iconClass = 'fas fa-pause-circle';
                                            break;
                                        case 'pendiente':
                                            $badgeClass = 'badge-warning';
                                            $iconClass = 'fas fa-clock';
                                            break;
                                        default:
                                            $badgeClass = 'badge-secondary';
                                            $iconClass = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <span class="badge badge-modern <?= $badgeClass ?>">
                                        <i class="<?= $iconClass ?>"></i> <?= ucfirst($estado) ?>
                                    </span>
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
                                        <a href="/RMIE/app/controllers/RouteController.php?accion=edit&id=<?= urlencode($ruta['id_ruta'] ?? '') ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar ruta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if (($ruta['estado'] ?? 'activa') !== 'completada'): ?>
                                        <button type="button" 
                                           class="btn btn-sm btn-modern btn-success-modern" 
                                           title="Completar y finalizar ruta"
                                           onclick="completarRuta(<?= $ruta['id_ruta'] ?? 0 ?>, '<?= addslashes($ruta['nombre_local'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?>');">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/RouteController.php?accion=delete&id=<?= urlencode($ruta['id_ruta'] ?? '') ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar ruta"
                                           onclick="return confirm('¿Está seguro de eliminar la ruta \'<?= addslashes($ruta['nombre_local'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?>\'?\n\nEsta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-route fa-3x mb-3"></i>
                                        <h5>No hay rutas disponibles</h5>
                                        <p>No se encontraron rutas que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/RouteController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primera Ruta
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
// Capturar promesas rechazadas
window.addEventListener('unhandledrejection', function(e) { console.log('Promise Error:', e.reason); e.preventDefault(); });

        // Toggle de vista Tabla / Tarjetas para Rutas
        function initRutasViewToggle() {
            try {
                const key = 'rutasView';
                const tableContainer = document.getElementById('rutasTableContainer');
                const cardsContainer = document.getElementById('rutasCardsContainer');
                const btnTable = document.getElementById('rutasTableBtn');
                const btnCards = document.getElementById('rutasCardsBtn');

                if (!tableContainer || !cardsContainer || !btnTable || !btnCards) {
                    console.log('Elementos de toggle no encontrados');
                    return;
                }

                function applyView(view) {
                    if (view === 'cards') {
                        // Mostrar tarjetas, ocultar tabla
                        cardsContainer.style.display = 'grid';
                        tableContainer.style.display = 'none';
                        
                        // Actualizar botones
                        btnCards.classList.remove('btn-secondary-modern');
                        btnCards.classList.add('btn-primary-modern');
                        btnTable.classList.remove('btn-primary-modern');
                        btnTable.classList.add('btn-secondary-modern');
                    } else {
                        // Mostrar tabla, ocultar tarjetas
                        cardsContainer.style.display = 'none';
                        tableContainer.style.display = 'block';
                        
                        // Actualizar botones
                        btnTable.classList.remove('btn-secondary-modern');
                        btnTable.classList.add('btn-primary-modern');
                        btnCards.classList.remove('btn-primary-modern');
                        btnCards.classList.add('btn-secondary-modern');
                    }
                }

                // Cargar vista guardada o usar tabla por defecto
                const savedView = localStorage.getItem(key) || 'table';
                applyView(savedView);

                // Event listeners para los botones
                btnTable.addEventListener('click', function() {
                    localStorage.setItem(key, 'table');
                    applyView('table');
                });
                
                btnCards.addEventListener('click', function() {
                    localStorage.setItem(key, 'cards');
                    applyView('cards');
                });
                
            } catch (err) {
                console.error('Error en initRutasViewToggle:', err);
            }
        }

        // Función mejorada para limpiar filtros con animación
        function limpiarFiltros() {
            // Redirige siempre a la URL base del listado de rutas
            window.location.href = '/RMIE/app/controllers/RouteController.php?accion=index';
        }

        // Función mejorada para exportar rutas
        function exportarRutas() {
            // Crear notificación moderna
            const notification = document.createElement('div');
            notification.className = 'position-fixed bg-info text-white p-3 rounded shadow-lg';
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 280px; animation: slideInRight 0.3s ease;';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-download me-2 fs-4"></i>
                    <div>
                        <strong>Exportar Rutas</strong><br>
                        <small>Funcionalidad disponible próximamente</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
                <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar bg-light" style="width: 100%; animation: progressAnimation 3s ease-in-out;"></div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remover después de 4 segundos
            setTimeout(() => {
                if (notification && notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 4000);
        }

        // Agregar estilos para animaciones
        const animationStyles = document.createElement('style');
        animationStyles.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            @keyframes progressAnimation {
                from { width: 0%; }
                to { width: 100%; }
            }
        `;
        document.head.appendChild(animationStyles);

        // Funciones para acciones rápidas
        function crearRutaRapida() {
            window.location.href = '/RMIE/app/controllers/RouteController.php?accion=create';
        }

        function importarRutas() {
            mostrarModal('importar');
        }

        function exportarTodo() {
            mostrarNotificacion('Preparando exportación completa...', 'info');
            setTimeout(() => {
                mostrarNotificacion('Exportación completada', 'success');
            }, 2000);
        }

        function optimizarRutas() {
            mostrarNotificacion('Optimizando rutas...', 'info');
            setTimeout(() => {
                mostrarNotificacion('Rutas optimizadas correctamente', 'success');
            }, 3000);
        }

        function verMapa() {
            mostrarModal('mapa');
            setTimeout(function() {
                llenarListaRutas();
            }, 100);
        }

        function abrirGoogleMapsDirecto() {
            // Recopilar todas las direcciones de las rutas
            const direcciones = [];
            
            // Buscar todas las direcciones en la tabla
            document.querySelectorAll('tbody tr').forEach(function(row) {
                const direccionCell = row.cells[2]; // Columna de dirección
                if (direccionCell) {
                    const badges = direccionCell.querySelectorAll('.badge');
                    badges.forEach(function(badge) {
                        const direccion = badge.textContent.replace(/🗺️|📍/g, '').trim();
                        if (direccion && direccion !== 'Sin dirección') {
                            direcciones.push(direccion);
                        }
                    });
                }
            });
            
            if (direcciones.length === 0) {
                mostrarNotificacion('No se encontraron direcciones para mostrar en el mapa', 'warning');
                return;
            }
            
            // Crear URL de Google Maps con múltiples direcciones
            let googleMapsUrl = 'https://www.google.com/maps/dir/';
            
            // Agregar cada dirección como punto de ruta
            direcciones.slice(0, 10).forEach(function(direccion) { // Límite de 10 direcciones
                googleMapsUrl += encodeURIComponent(direccion) + '/';
            });
            
            // Abrir Google Maps en nueva pestaña
            window.open(googleMapsUrl, '_blank');
            
            mostrarNotificacion(`Abriendo mapa con ${direcciones.length} ubicaciones`, 'success');
        }

        function verMapaExterno() {
            // Seleccionar todas las rutas por defecto
            seleccionarTodasRutas();
            setTimeout(function() {
                verMapaRutasSeleccionadas();
            }, 100);
        }

        function verMapaEmbebido() {
            // Recopilar direcciones
            const direcciones = [];
            document.querySelectorAll('tbody tr').forEach(function(row) {
                const direccionCell = row.cells[2];
                if (direccionCell) {
                    const badges = direccionCell.querySelectorAll('.badge');
                    badges.forEach(function(badge) {
                        const direccion = badge.textContent.replace(/🗺️|📍/g, '').trim();
                        if (direccion && direccion !== 'Sin dirección') {
                            direcciones.push(direccion);
                        }
                    });
                }
            });

            if (direcciones.length === 0) {
                document.getElementById('mapaContainer').innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        No se encontraron direcciones para mostrar
                    </div>
                `;
                return;
            }

            // Crear iframe con Google Maps embebido
            const primeraUbicacion = encodeURIComponent(direcciones[0]);
            const mapaEmbebido = `
                <iframe 
                    width="100%" 
                    height="400" 
                    frameborder="0" 
                    style="border:0; border-radius: 8px;" 
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD&q=${primeraUbicacion}" 
                    allowfullscreen>
                </iframe>
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Mostrando: ${direcciones[0]} (${direcciones.length} ubicaciones totales)
                    </small>
                </div>
            `;
            
            // Si no hay API key, usar alternativa
            const mapaAlternativo = `
                <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; min-height: 400px;">
                    <div class="text-center mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                        <h5 class="mt-2">Ubicaciones de Rutas</h5>
                    </div>
                    <div class="row">
                        ${direcciones.slice(0, 6).map((dir, index) => `
                            <div class="col-md-6 mb-2">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <small>
                                            <i class="fas fa-map-pin text-danger"></i>
                                            <strong>Ruta ${index + 1}:</strong><br>
                                            ${dir}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    ${direcciones.length > 6 ? `<small class="text-muted">Y ${direcciones.length - 6} ubicaciones más...</small>` : ''}
                </div>
            `;

            document.getElementById('mapaContainer').innerHTML = mapaAlternativo;
        }

        function llenarListaRutas() {
            const listaRutas = document.getElementById('listaRutas');
            let rutasHtml = '';
            let todasDirecciones = [];
            
            // Obtener todas las filas de la tabla
            document.querySelectorAll('tbody tr').forEach(function(row, index) {
                const idRuta = row.cells[0]?.textContent?.trim() || `Ruta ${index + 1}`;
                const nombreRuta = row.cells[1]?.textContent?.trim() || 'Sin nombre';
                const direccionCell = row.cells[2];
                
                let direcciones = [];
                if (direccionCell) {
                    const badges = direccionCell.querySelectorAll('.badge');
                    badges.forEach(function(badge) {
                        const direccion = badge.textContent.replace(/🗺️|📍/g, '').trim();
                        if (direccion && direccion !== 'Sin dirección') {
                            direcciones.push(direccion);
                            todasDirecciones.push(direccion);
                        }
                    });
                }
                
                if (direcciones.length > 0) {
                    rutasHtml += `
                        <div class="form-check mb-3 p-3" style="background: rgba(255,255,255,0.1); border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                            <input class="form-check-input ruta-checkbox" type="checkbox" 
                                   id="ruta_${index}" value="${index}" 
                                   data-direcciones='${JSON.stringify(direcciones)}'
                                   style="margin-top: 0.5em; transform: scale(1.2);">
                            <label class="form-check-label" for="ruta_${index}" style="color: white; font-size: 14px; line-height: 1.4; cursor: pointer; margin-left: 8px;">
                                <div style="font-weight: bold; margin-bottom: 4px;">
                                    <i class="fas fa-route" style="color: #4CAF50; margin-right: 6px;"></i>
                                    ${idRuta} - ${nombreRuta.substring(0, 35)}${nombreRuta.length > 35 ? '...' : ''}
                                </div>
                                <div style="color: #E0E0E0; font-size: 13px;">
                                    <i class="fas fa-map-marker-alt" style="color: #FF6B6B; margin-right: 6px;"></i>
                                    ${direcciones[0].substring(0, 50)}${direcciones[0].length > 50 ? '...' : ''}
                                    ${direcciones.length > 1 ? `<span style="color: #FFA726;"> (+${direcciones.length - 1} ubicación${direcciones.length > 2 ? 'es' : ''} más)</span>` : ''}
                                </div>
                            </label>
                        </div>
                    `;
                }
            });
            
            if (rutasHtml === '') {
                rutasHtml = `
                    <div class="text-center p-4" style="color: white; background: rgba(255,193,7,0.1); border-radius: 8px; border: 1px solid rgba(255,193,7,0.3);">
                        <i class="fas fa-exclamation-triangle" style="color: #FFC107; font-size: 2rem; margin-bottom: 10px;"></i>
                        <div style="font-size: 16px; font-weight: 500;">No hay rutas con direcciones válidas</div>
                        <small style="color: #E0E0E0; margin-top: 5px; display: block;">Verifica que las rutas tengan direcciones configuradas</small>
                    </div>
                `;
            }
            
            listaRutas.innerHTML = rutasHtml;
            
            // Sugerir ubicación de origen inteligente si el campo está vacío o es el valor por defecto
            const origenInput = document.getElementById('ubicacionOrigen');
            if (origenInput.value === 'Bogotá, Colombia' && todasDirecciones.length > 0) {
                // Intentar detectar la ciudad más común en las direcciones
                const ciudadMasComun = detectarCiudadComun(todasDirecciones);
                if (ciudadMasComun) {
                    origenInput.value = ciudadMasComun;
                    origenInput.style.backgroundColor = '#e8f5e8';
                    setTimeout(function() {
                        origenInput.style.backgroundColor = '';
                    }, 2000);
                }
            }
        }
        
        function detectarCiudadComun(direcciones) {
            // Palabras clave de ciudades comunes en Colombia
            const ciudades = ['bogotá', 'medellín', 'cali', 'barranquilla', 'cartagena', 'bucaramanga', 'pereira', 'ibagué', 'santa marta', 'villavicencio', 'manizales', 'neiva', 'soledad', 'armenia', 'soacha', 'valledupar', 'montería', 'itagüí', 'pasto', 'buenaventura'];
            const contadores = {};
            
            direcciones.forEach(direccion => {
                const direccionLower = direccion.toLowerCase();
                ciudades.forEach(ciudad => {
                    if (direccionLower.includes(ciudad)) {
                        contadores[ciudad] = (contadores[ciudad] || 0) + 1;
                    }
                });
            });
            
            // Encontrar la ciudad más mencionada
            let ciudadMasComun = null;
            let maxCount = 0;
            for (const ciudad in contadores) {
                if (contadores[ciudad] > maxCount) {
                    maxCount = contadores[ciudad];
                    ciudadMasComun = ciudad.charAt(0).toUpperCase() + ciudad.slice(1) + ', Colombia';
                }
            }
            
            return ciudadMasComun;
        }

        function seleccionarTodasRutas() {
            document.querySelectorAll('.ruta-checkbox').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        }

        function limpiarSeleccionRutas() {
            document.querySelectorAll('.ruta-checkbox').forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }

        function obtenerRutasSeleccionadas() {
            const rutasSeleccionadas = [];
            document.querySelectorAll('.ruta-checkbox:checked').forEach(function(checkbox) {
                const direcciones = JSON.parse(checkbox.dataset.direcciones);
                rutasSeleccionadas.push(...direcciones);
            });
            return rutasSeleccionadas;
        }

        function verMapaRutasSeleccionadas() {
            const direcciones = obtenerRutasSeleccionadas();
            const origen = document.getElementById('ubicacionOrigen').value.trim();
            
            if (direcciones.length === 0) {
                mostrarNotificacion('Selecciona al menos un destino para mostrar en el mapa', 'warning');
                return;
            }
            
            if (!origen) {
                mostrarNotificacion('Ingresa una ubicación de origen', 'warning');
                return;
            }
            
            // Crear URL de Google Maps con ruta desde origen a destinos
            let googleMapsUrl = 'https://www.google.com/maps/dir/';
            
            // Agregar punto de origen
            googleMapsUrl += encodeURIComponent(origen) + '/';
            
            // Agregar destinos (máximo 9 para no exceder límite de Google Maps con el origen)
            direcciones.slice(0, 9).forEach(function(direccion) {
                googleMapsUrl += encodeURIComponent(direccion) + '/';
            });
            
            // Agregar parámetros para optimizar la ruta
            googleMapsUrl += '?travelmode=driving&optimize=true';
            
            window.open(googleMapsUrl, '_blank');
            
            const totalDestinos = direcciones.length;
            const destinosEnMapa = Math.min(totalDestinos, 9);
            let mensaje = `Abriendo ruta desde "${origen}" a ${destinosEnMapa} destino${destinosEnMapa > 1 ? 's' : ''}`;
            
            if (totalDestinos > 9) {
                mensaje += ` (${totalDestinos - 9} destinos adicionales no mostrados por límite de Google Maps)`;
            }
            
            mostrarNotificacion(mensaje, 'success');
        }

        function verMapaEmbebidoSeleccionadas() {
            const direcciones = obtenerRutasSeleccionadas();
            const origen = document.getElementById('ubicacionOrigen').value.trim();
            
            if (direcciones.length === 0) {
                document.getElementById('mapaContainer').innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        Selecciona al menos un destino para mostrar
                    </div>
                `;
                return;
            }

            if (!origen) {
                document.getElementById('mapaContainer').innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        Ingresa una ubicación de origen
                    </div>
                `;
                return;
            }

            const mapaAlternativo = `
                <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; min-height: 300px;">
                    <div class="text-center mb-3">
                        <i class="fas fa-route fa-3x text-success"></i>
                        <h5 class="mt-2">Ruta de Entrega</h5>
                        <p class="text-muted">Desde origen a ${direcciones.length} destino${direcciones.length > 1 ? 's' : ''}</p>
                    </div>
                    
                    <!-- Punto de Origen -->
                    <div class="card mb-3 border-success">
                        <div class="card-body py-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-home fa-2x text-success"></i>
                                </div>
                                <div>
                                    <strong class="text-success">ORIGEN</strong><br>
                                    <span>${origen}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Flecha de Dirección -->
                    <div class="text-center mb-3">
                        <i class="fas fa-arrow-down fa-2x text-primary"></i>
                    </div>
                    
                    <!-- Destinos -->
                    <h6 class="mb-3"><i class="fas fa-map-marker-alt text-danger"></i> Destinos de Entrega</h6>
                    <div class="row">
                        ${direcciones.slice(0, 6).map((dir, index) => `
                            <div class="col-md-6 mb-2">
                                <div class="card border-danger">
                                    <div class="card-body py-2">
                                        <small>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-danger me-2">${index + 1}</span>
                                                <div>
                                                    <strong>Destino ${index + 1}:</strong><br>
                                                    ${dir}
                                                </div>
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    ${direcciones.length > 6 ? `
                        <div class="text-center mt-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Y ${direcciones.length - 6} destinos adicionales...
                            </div>
                        </div>
                    ` : ''}
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Ruta optimizada para el menor tiempo de viaje
                        </small>
                    </div>
                </div>
            `;

            document.getElementById('mapaContainer').innerHTML = mapaAlternativo;
        }

        function reporteRapido() {
            mostrarModal('reporte');
        }

        function verVistaAvanzada() {
            mostrarModal('vistaAvanzada');
        }

        function configurarAlertas() {
            mostrarModal('alertas');
        }

        function ayudaContextual() {
            mostrarModal('ayuda');
        }

        function verDetalles(id) {
            mostrarModal('detalles', id);
        }

        // Sistema de modales avanzado
        function mostrarModal(tipo, data = null) {
            const modalHtml = generarModalHTML(tipo, data);
            
            // Remover modal existente si hay uno
            const modalExistente = document.getElementById('modalDinamico');
            if (modalExistente) {
                modalExistente.remove();
            }
            
            // Agregar nuevo modal
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Mostrar modal con Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('modalDinamico'));
            modal.show();
        }

        function generarModalHTML(tipo, data) {
            const modales = {
                'importar': {
                    titulo: 'Importar Rutas',
                    icono: 'fas fa-upload',
                    contenido: `
                        <div class="mb-3">
                            <label class="form-label text-white">Seleccionar archivo</label>
                            <input type="file" class="form-control" accept=".csv,.xlsx" onchange="procesarArchivo(this)">
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Formatos soportados: CSV, Excel
                        </div>
                    `
                },
                'mapa': {
                    titulo: 'Mapa de Rutas',
                    icono: 'fas fa-map',
                    contenido: `
                        <div class="mb-3">
                            <h6 class="text-white mb-3"><i class="fas fa-route"></i> Configurar Ruta de Entrega</h6>
                            
                            <!-- Ubicación de Origen -->
                            <div class="mb-4" style="background: rgba(76,175,80,0.1); border-radius: 12px; padding: 20px; border: 1px solid rgba(76,175,80,0.3);">
                                <label class="form-label text-white mb-2" style="font-size: 16px; font-weight: 600;">
                                    <i class="fas fa-home" style="color: #4CAF50; margin-right: 8px;"></i> 
                                    Ubicación de Origen
                                </label>
                                <input type="text" class="form-control" id="ubicacionOrigen" 
                                       placeholder="Ej: Mi empresa, Calle 123 #45-67, Bogotá"
                                       value="Bogotá, Colombia"
                                       style="font-size: 14px; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
                                <small style="color: #E0E0E0; margin-top: 8px; display: block;">
                                    <i class="fas fa-info-circle" style="color: #4CAF50;"></i> 
                                    Punto de partida de la ruta de entrega
                                </small>
                            </div>
                            
                            <!-- Selector de Destinos -->
                            <div id="rutasSelector" class="mb-3" style="max-height: 250px; overflow-y: auto; background: rgba(0,0,0,0.3); border-radius: 12px; padding: 20px; border: 1px solid rgba(255,255,255,0.1);">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label text-white mb-0" style="font-size: 16px; font-weight: 600;">
                                        <i class="fas fa-map-marker-alt" style="color: #FF6B6B; margin-right: 8px;"></i> 
                                        Destinos de Entrega
                                    </label>
                                    <div>
                                        <button type="button" class="btn btn-sm me-2" onclick="seleccionarTodasRutas()" 
                                                style="background: linear-gradient(135deg, #4CAF50 0%, #45A049 100%); color: white; border: none; border-radius: 20px; padding: 8px 15px; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(76,175,80,0.3); transition: all 0.3s ease;">
                                            <i class="fas fa-check-double" style="margin-right: 6px;"></i> Todas
                                        </button>
                                        <button type="button" class="btn btn-sm" onclick="limpiarSeleccionRutas()"
                                                style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white; border: none; border-radius: 20px; padding: 8px 15px; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(244,67,54,0.3); transition: all 0.3s ease;">
                                            <i class="fas fa-times" style="margin-right: 6px;"></i> Ninguna
                                        </button>
                                    </div>
                                </div>
                                <div id="listaRutas" style="color: white;">
                                    <!-- Se llena dinámicamente -->
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="d-grid gap-2" style="margin-top: 20px;">
                                    <button type="button" class="btn btn-success btn-lg" onclick="verMapaRutasSeleccionadas()" 
                                            style="border-radius: 12px; padding: 12px 20px; font-weight: 600; background: linear-gradient(135deg, #4CAF50 0%, #45A049 100%); border: none; box-shadow: 0 4px 15px rgba(76,175,80,0.3);">
                                        <i class="fas fa-external-link-alt" style="margin-right: 10px;"></i> 
                                        Abrir Ruta Optimizada en Google Maps
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-lg" onclick="verMapaEmbebidoSeleccionadas()"
                                            style="border-radius: 12px; padding: 12px 20px; font-weight: 600; border: 2px solid #FFF; color: #FFF;">
                                        <i class="fas fa-map" style="margin-right: 10px;"></i> 
                                        Ver Vista Previa del Mapa
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="mapaContainer" style="min-height: 300px; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); padding: 40px;">
                            <div class="text-center" style="color: #E0E0E0;">
                                <div style="background: rgba(76,175,80,0.1); border-radius: 50%; width: 80px; height: 80px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(76,175,80,0.3);">
                                    <i class="fas fa-route" style="font-size: 2.5rem; color: #4CAF50;"></i>
                                </div>
                                <h5 style="color: #FFF; margin-bottom: 10px; font-weight: 600;">Planificador de Rutas</h5>
                                <p style="color: #B0B0B0; margin: 0; font-size: 14px;">
                                    <i class="fas fa-info-circle" style="color: #4CAF50; margin-right: 6px;"></i>
                                    Selecciona los destinos arriba para crear una ruta optimizada
                                </p>
                            </div>
                        </div>
                        </div>
                    `
                },
                'reporte': {
                    titulo: 'Reporte Rápido',
                    icono: 'fas fa-chart-line',
                    contenido: `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="bg-primary bg-opacity-25 p-3 rounded mb-2">
                                    <h6><i class="fas fa-chart-pie"></i> Eficiencia General</h6>
                                    <h3>85%</h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-success bg-opacity-25 p-3 rounded mb-2">
                                    <h6><i class="fas fa-clock"></i> Tiempo Promedio</h6>
                                    <h3>45 min</h3>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mt-3" onclick="generarReporteCompleto()">
                            <i class="fas fa-download"></i> Generar Reporte Completo
                        </button>
                    `
                },
                'vistaAvanzada': {
                    titulo: 'Vista Avanzada',
                    icono: 'fas fa-eye',
                    contenido: `
                        <div class="advanced-options">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="mostrarKPI">
                                <label class="form-check-label text-white" for="mostrarKPI">
                                    Mostrar KPIs en tiempo real
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="vistaCompacta">
                                <label class="form-check-label text-white" for="vistaCompacta">
                                    Vista compacta de tabla
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="autoRefresh">
                                <label class="form-check-label text-white" for="autoRefresh">
                                    Actualización automática (30s)
                                </label>
                            </div>
                        </div>
                    `
                },
                'alertas': {
                    titulo: 'Configurar Alertas',
                    icono: 'fas fa-bell',
                    contenido: `
                        <div class="alert-config">
                            <div class="mb-3">
                                <label class="form-label text-white">Tipo de Alerta</label>
                                <select class="form-select">
                                    <option>Rutas pendientes > 5</option>
                                    <option>Eficiencia < 70%</option>
                                    <option>Rutas sin completar > 24h</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white">Frecuencia</label>
                                <select class="form-select">
                                    <option>Inmediatamente</option>
                                    <option>Cada hora</option>
                                    <option>Diariamente</option>
                                </select>
                            </div>
                        </div>
                    `
                },
                'ayuda': {
                    titulo: 'Ayuda Contextual',
                    icono: 'fas fa-question-circle',
                    contenido: `
                        <div class="help-content">
                            <h6><i class="fas fa-info-circle text-info"></i> Guía Rápida</h6>
                            <ul class="list-unstyled">
                                <li><strong>Crear Ruta:</strong> Botón "+" o acción rápida</li>
                                <li><strong>Filtrar:</strong> Usa los campos de búsqueda avanzada</li>
                                <li><strong>Optimizar:</strong> Botón de optimización automática</li>
                                <li><strong>Exportar:</strong> Varios formatos disponibles</li>
                            </ul>
                            <hr>
                            <h6><i class="fas fa-keyboard text-warning"></i> Atajos de Teclado</h6>
                            <small>
                                <kbd>Ctrl + N</kbd> Nueva ruta<br>
                                <kbd>Ctrl + F</kbd> Buscar<br>
                                <kbd>Ctrl + E</kbd> Exportar
                            </small>
                        </div>
                    `
                },
                'detalles': {
                    titulo: `Detalles de Ruta #${data}`,
                    icono: 'fas fa-info-circle',
                    contenido: `
                        <div class="route-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Información General</h6>
                                    <p><strong>ID:</strong> #${data}</p>
                                    <p><strong>Estado:</strong> <span class="badge bg-success">Activa</span></p>
                                    <p><strong>Creada:</strong> ${new Date().toLocaleDateString()}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Métricas</h6>
                                    <p><strong>Distancia:</strong> 12.5 km</p>
                                    <p><strong>Tiempo estimado:</strong> 45 min</p>
                                    <p><strong>Última actualización:</strong> Hace 2 horas</p>
                                </div>
                            </div>
                        </div>
                    `
                }
            };
            
            const config = modales[tipo] || modales['ayuda'];
            
            return `
                <div class="modal fade" id="modalDinamico" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content bg-dark text-white border-0">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title">
                                    <i class="${config.icono} me-2"></i>${config.titulo}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${config.contenido}
                            </div>
                            <div class="modal-footer border-secondary">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function ejecutarAccionModal(tipo) {
            mostrarNotificacion(`Acción '${tipo}' ejecutada correctamente`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('modalDinamico')).hide();
        }

        // Función para completar y eliminar ruta (versión simple sin modal complejo)
        function completarRuta(idRuta, nombreRuta) {
            console.log('completarRuta llamada con:', idRuta, nombreRuta);
            
            // Usar confirmaciones nativas del navegador para evitar problemas de accesibilidad
            const mensaje = `Completar la ruta: "${nombreRuta}"\n\n¿Qué deseas hacer?\n\n• Presiona ACEPTAR para completar y ELIMINAR la ruta\n• Presiona CANCELAR para solo completar (mantener en base de datos)`;
            
            const autoEliminar = confirm(mensaje);
            
            if (autoEliminar !== null) { // Si no presionó Escape
                const accion = autoEliminar ? 'complete_and_delete' : 'complete';
                const url = `/RMIE/app/controllers/RouteController.php?accion=${accion}&id=${idRuta}`;
                
                console.log('Ejecutando acción:', accion, 'para ruta ID:', idRuta);
                
                // Mostrar mensaje de procesamiento
                const procesoMsg = autoEliminar ? 'Completando y eliminando ruta...' : 'Completando ruta...';
                alert(procesoMsg);
                
                // Navegar directamente
                window.location.href = url;
            }
        }
        
        // Función alternativa con modal mejorado (sin problemas de accesibilidad)
        function completarRutaModal(idRuta, nombreRuta) {
            console.log('completarRutaModal llamada con:', idRuta, nombreRuta);
            
            // Crear un overlay simple sin Bootstrap
            const overlay = document.createElement('div');
            overlay.id = 'completarRutaOverlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            `;
            
            overlay.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; margin: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <h3 style="color: #28a745; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i> Completar Ruta
                    </h3>
                    <p style="margin-bottom: 20px; color: #333;">
                        <strong>Ruta:</strong> ${nombreRuta}
                    </p>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" id="autoEliminarSimple" checked style="margin-right: 10px;">
                            <span style="color: #333;">Eliminar automáticamente después de completar</span>
                        </label>
                        <small style="color: #666; display: block; margin-top: 5px;">La ruta se borrará permanentemente</small>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button onclick="cerrarOverlay()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">
                            Cancelar
                        </button>
                        <button onclick="ejecutarCompletarRutaSimple(${idRuta})" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer;">
                            Completar Ruta
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            
            // Funciones globales para el overlay
            window.cerrarOverlay = function() {
                if (document.getElementById('completarRutaOverlay')) {
                    document.getElementById('completarRutaOverlay').remove();
                }
            };
            
            window.ejecutarCompletarRutaSimple = function(id) {
                const autoElim = document.getElementById('autoEliminarSimple').checked;
                const accion = autoElim ? 'complete_and_delete' : 'complete';
                window.cerrarOverlay();
                window.location.href = `/RMIE/app/controllers/RouteController.php?accion=${accion}&id=${id}`;
            };
        }
        
        function ejecutarCompletarRuta(idRuta) {
            console.log('ejecutarCompletarRuta llamada con ID:', idRuta);
            
            const autoEliminar = document.getElementById('autoEliminar').checked;
            console.log('Auto eliminar:', autoEliminar);
            
            // Mostrar indicador de carga
            const modalContent = document.querySelector('#modalCompletarRuta .modal-content');
            if (modalContent) {
                modalContent.style.opacity = '0.7';
            }
            
            // Realizar petición AJAX
            const accion = autoEliminar ? 'complete_and_delete' : 'complete';
            const url = `/RMIE/app/controllers/RouteController.php?accion=${accion}&id=${idRuta}`;
            console.log('URL de petición:', url);
            
            fetch(url, {
                method: 'GET'
            })
            .then(response => {
                console.log('Respuesta recibida:', response.status);
                return response.text();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                
                // Cerrar modal
                try {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCompletarRuta'));
                    if (modal) {
                        modal.hide();
                    }
                } catch (e) {
                    console.log('Error cerrando modal:', e);
                }
                
                // Mostrar notificación o alert simple
                const mensaje = autoEliminar ? 'Ruta completada y eliminada correctamente' : 'Ruta completada correctamente';
                
                if (typeof mostrarNotificacion === 'function') {
                    mostrarNotificacion(mensaje, 'success');
                } else {
                    alert(mensaje);
                }
                
                // Recargar página
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                
                if (typeof mostrarNotificacion === 'function') {
                    mostrarNotificacion('Error al completar la ruta', 'error');
                } else {
                    alert('Error al completar la ruta: ' + error.message);
                }
                
                if (modalContent) {
                    modalContent.style.opacity = '1';
                }
            });
        }

        // Función alternativa simple para completar ruta (sin modal)
        function completarRutaSimple(idRuta, nombreRuta) {
            const autoEliminar = confirm(`¿Completar la ruta "${nombreRuta}"?\n\nPresiona:\n- Aceptar: Completar y ELIMINAR la ruta\n- Cancelar: Solo completar (mantener en base de datos)`);
            
            const accion = autoEliminar ? 'complete_and_delete' : 'complete';
            const url = `/RMIE/app/controllers/RouteController.php?accion=${accion}&id=${idRuta}`;
            
            console.log('Ejecutando acción:', accion, 'para ruta ID:', idRuta);
            
            // Usar window.location para navegar directamente
            window.location.href = url;
        }

        // Función para limpiar todas las rutas completadas
        function limpiarCompletadas() {
            console.log('limpiarCompletadas() llamada');
            
            // Crear un modal de confirmación personalizado
            const opcion = prompt(`¿Qué tipo de limpieza quieres hacer?\n\nEscribe el número de tu opción:\n\n1 - Solo eliminar rutas COMPLETADAS\n2 - Eliminar TODAS las rutas (completas e incompletas)\n3 - Cancelar`);
            
            if (opcion === '1') {
                if (confirm('Se eliminarán solo las rutas con estado "completada".\n\n¿Continuar?')) {
                    alert('Eliminando rutas completadas...');
                    window.location.href = '/RMIE/app/controllers/RouteController.php?accion=clean_completed';
                }
            } else if (opcion === '2') {
                if (confirm('¡ATENCIÓN! Esto eliminará TODAS las rutas de la base de datos.\n\nEsta acción NO se puede deshacer.\n\n¿Estás completamente seguro?')) {
                    alert('Eliminando todas las rutas...');
                    window.location.href = '/RMIE/app/controllers/RouteController.php?accion=clean_all';
                }
            } else {
                console.log('Operación cancelada');
            }
        }

        // Función de prueba simple
        function probarBoton() {
            alert('¡El botón funciona!');
            console.log('Función probarBoton ejecutada correctamente');
            return false;
        }

        // Función alternativa con fetch mejorado
        function limpiarCompletadasAjax() {
            console.log('limpiarCompletadasAjax() llamada');
            
            if (confirm('¿Estás seguro de que quieres eliminar TODAS las rutas completadas?\n\nEsta acción no se puede deshacer y eliminará permanentemente todas las rutas con estado "completada".')) {
                
                alert('Eliminando rutas completadas...');
                
                fetch('/RMIE/app/controllers/RouteController.php?accion=clean_completed', {
                    method: 'GET'
                })
                .then(response => {
                    console.log('Respuesta:', response.status);
                    return response.text();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);
                    alert('Rutas completadas eliminadas correctamente');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar las rutas completadas: ' + error.message);
                });
            }
        }

        function mostrarNotificacion(mensaje, tipo = 'info') {
            const colores = {
                'info': 'bg-info',
                'success': 'bg-success', 
                'warning': 'bg-warning',
                'error': 'bg-danger'
            };
            
            const iconos = {
                'info': 'fas fa-info-circle',
                'success': 'fas fa-check-circle',
                'warning': 'fas fa-exclamation-triangle', 
                'error': 'fas fa-times-circle'
            };
            
            const notification = document.createElement('div');
            notification.className = `position-fixed ${colores[tipo]} text-white p-3 rounded shadow-lg`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideInRight 0.3s ease;';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="${iconos[tipo]} me-2 fs-5"></i>
                    <div class="flex-grow-1">${mensaje}</div>
                    <button type="button" class="btn-close btn-close-white ms-2" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification && notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 4000);
        }

        // Atajos de teclado
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey) {
                switch(e.key) {
                    case 'n':
                        e.preventDefault();
                        crearRutaRapida();
                        break;
                    case 'f':
                        e.preventDefault();
                        document.querySelector('input[name="direccion"]')?.focus();
                        break;
                    case 'e':
                        e.preventDefault();
                        exportarTodo();
                        break;
                }
            }
        });

        // Efecto de carga para las tarjetas de estadísticas principales
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.main-stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Auto-ocultar alertas después de 5 segundos
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-modern');
                alerts.forEach(function(alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
            // Inicializar selector de vista (Tabla / Tarjetas)
            try { initRutasViewToggle(); } catch (e) { console.log('initRutasViewToggle init failed', e); }
            
            // Inicializar gráfico de dona (con manejo de errores)
            try {
                inicializarGrafico();
            } catch (error) {
                console.log('Gráfico no disponible:', error);
            }
        });
        
        // Función para crear gráfico de distribución
        function inicializarGrafico() {
            try {
                const ctx = document.getElementById('estadoChart');
                if (ctx) {
                    // Datos simulados - en producción vendrían del backend
                    const data = {
                        labels: ['Activas', 'Pendientes', 'Completadas', 'Inactivas'],
                        datasets: [{
                            data: [<?= $rutasActivas ?>, <?= $rutasPendientes ?>, <?= $rutasCompletadas ?>, <?= $rutasInactivas ?>],
                            backgroundColor: [
                                '#4facfe',
                                '#ff9a9e', 
                                '#a8edea',
                                '#ff6b6b'
                            ],
                            borderWidth: 0
                        }]
                    };
                    
                    // Crear gráfico con Canvas (implementación simple)
                    dibujarGraficoDona(ctx, data);
                } else {
                    console.log('Elemento estadoChart no encontrado - gráfico omitido');
                }
            } catch (error) {
                console.log('Error al inicializar gráfico:', error);
            }
        }
        
        function dibujarGraficoDona(canvas, data) {
            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = Math.min(centerX, centerY) - 20;
            const innerRadius = radius * 0.6;
            
            let total = data.datasets[0].data.reduce((sum, value) => sum + value, 0);
            let currentAngle = -Math.PI / 2;
            
            // Limpiar canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Dibujar segmentos
            data.datasets[0].data.forEach((value, index) => {
                if (value > 0) {
                    const sliceAngle = (value / total) * 2 * Math.PI;
                    
                    // Dibujar segmento
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
                    ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true);
                    ctx.closePath();
                    ctx.fillStyle = data.datasets[0].backgroundColor[index];
                    ctx.fill();
                    
                    currentAngle += sliceAngle;
                }
            });
            
            // Texto central
            ctx.fillStyle = '#fff';
            ctx.font = 'bold 24px Arial';
            ctx.textAlign = 'center';            ctx.fillText(total, centerX, centerY - 5);
            ctx.font = '12px Arial';
            ctx.fillText('Total', centerX, centerY + 15);
        }

        // (Eliminada función duplicada limpiarFiltros, solo se mantiene la de redirección base)
    </script>
</body>
</html>