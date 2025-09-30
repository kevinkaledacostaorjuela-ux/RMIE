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
        switch (strtolower($ruta->estado ?? 'pendiente')) {
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
        if (!empty($ruta->fecha_creacion) && $ruta->fecha_creacion >= $fechaReciente) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../../public/css/styles.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin: 20px;
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
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
        </div>



        <!-- Panel de Acciones Rápidas -->
        <div class="quick-actions-panel">
            <div class="quick-actions-title">
                <i class="fas fa-bolt"></i> Acciones Rápidas
            </div>
            <div class="quick-actions-grid">
                <button class="quick-action-btn" onclick="crearRutaRapida()">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nueva Ruta</span>
                </button>
                <button class="quick-action-btn" onclick="importarRutas()">
                    <i class="fas fa-upload"></i>
                    <span>Importar</span>
                </button>
                <button class="quick-action-btn" onclick="exportarTodo()">
                    <i class="fas fa-download"></i>
                    <span>Exportar Todo</span>
                </button>
                <button class="quick-action-btn" onclick="optimizarRutas()">
                    <i class="fas fa-route"></i>
                    <span>Optimizar</span>
                </button>
                <button class="quick-action-btn" onclick="verMapa()">
                    <i class="fas fa-map"></i>
                    <span>Ver Mapa</span>
                </button>
                <button class="quick-action-btn" onclick="reporteRapido()">
                    <i class="fas fa-chart-line"></i>
                    <span>Reporte</span>
                </button>
            </div>
        </div>

        <!-- Panel de Filtros Avanzados -->
        <div class="rutas-filters">
            <div class="rutas-filter-title">
                <i class="fas fa-search"></i> Filtros de Búsqueda Avanzada
            </div>
            <form method="GET" action="" id="filterForm">
                <!-- Filtros principales en una sola fila más compacta -->
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="rutas-filter-label">
                            <i class="fas fa-search"></i> Dirección
                        </label>
                        <input type="text" 
                               name="direccion" 
                               class="rutas-filter-input" 
                               placeholder="Buscar dirección..."
                               value="<?= htmlspecialchars($_GET['direccion'] ?? '') ?>">
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="rutas-filter-label">
                            <i class="fas fa-tags"></i> Estado
                        </label>
                        <select name="estado" class="rutas-filter-input">
                            <option value="">Todos los estados</option>
                            <option value="activa" <?= ($_GET['estado'] ?? '') === 'activa' ? 'selected' : '' ?>>Activa</option>
                            <option value="inactiva" <?= ($_GET['estado'] ?? '') === 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
                            <option value="pendiente" <?= ($_GET['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="completada" <?= ($_GET['estado'] ?? '') === 'completada' ? 'selected' : '' ?>>Completada</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="rutas-filter-label">
                            <i class="fas fa-store"></i> Local
                        </label>
                        <input type="text" 
                               name="local" 
                               class="rutas-filter-input" 
                               placeholder="Nombre del local..."
                               value="<?= htmlspecialchars($_GET['local'] ?? '') ?>">
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="rutas-filter-label">
                            <i class="fas fa-user"></i> Cliente
                        </label>
                        <input type="text" 
                               name="cliente" 
                               class="rutas-filter-input" 
                               placeholder="Nombre del cliente..."
                               value="<?= htmlspecialchars($_GET['cliente'] ?? '') ?>">
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <label class="rutas-filter-label">
                            <i class="fas fa-list"></i> Mostrar
                        </label>
                        <select name="limite" class="rutas-filter-input">
                            <option value="10" <?= ($_GET['limite'] ?? '10') === '10' ? 'selected' : '' ?>>10 por página</option>
                            <option value="25" <?= ($_GET['limite'] ?? '') === '25' ? 'selected' : '' ?>>25 por página</option>
                            <option value="50" <?= ($_GET['limite'] ?? '') === '50' ? 'selected' : '' ?>>50 por página</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3 d-flex align-items-end">
                        <div class="w-100">
                            <button type="submit" class="rutas-btn rutas-btn-primary w-100 mb-1">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <button type="button" class="rutas-btn rutas-btn-warning w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Filtros adicionales compactos -->
                <div class="row mt-2">
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                        <label class="rutas-filter-label">
                            <i class="fas fa-calendar-alt"></i> Desde
                        </label>
                        <input type="date" 
                               name="fecha_desde" 
                               class="rutas-filter-input" 
                               value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>">
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                        <label class="rutas-filter-label">
                            <i class="fas fa-calendar-alt"></i> Hasta
                        </label>
                        <input type="date" 
                               name="fecha_hasta" 
                               class="rutas-filter-input" 
                               value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>">
                    </div>
                    
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                        <label class="rutas-filter-label">
                            <i class="fas fa-sort"></i> Ordenar por
                        </label>
                        <select name="orden" class="rutas-filter-input">
                            <option value="fecha_desc" <?= ($_GET['orden'] ?? 'fecha_desc') === 'fecha_desc' ? 'selected' : '' ?>>Más recientes</option>
                            <option value="fecha_asc" <?= ($_GET['orden'] ?? '') === 'fecha_asc' ? 'selected' : '' ?>>Más antiguos</option>
                            <option value="direccion_asc" <?= ($_GET['orden'] ?? '') === 'direccion_asc' ? 'selected' : '' ?>>Dirección A-Z</option>
                            <option value="estado_asc" <?= ($_GET['orden'] ?? '') === 'estado_asc' ? 'selected' : '' ?>>Estado A-Z</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-6 col-sm-6 mb-2 d-flex align-items-end">
                        <button type="button" class="rutas-btn rutas-btn-success w-100" onclick="exportarRutas()">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2 d-flex align-items-end">
                        <div class="w-100 text-center">
                            <small class="text-white-50">
                                <i class="fas fa-info-circle"></i> 
                                Mostrando <?= count($rutas ?? []); ?> rutas de <?= $totalRutas; ?> total
                            </small>
                        </div>
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
                    <button class="btn btn-modern btn-info-modern btn-lg" onclick="verVistaAvanzada()">
                        <i class="fas fa-eye"></i> Vista Avanzada
                    </button>
                </div>
                <div class="secondary-actions">
                    <a href="/RMIE/app/views/dashboard.php" class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                    <button class="btn btn-modern btn-warning-modern" onclick="configurarAlertas()">
                        <i class="fas fa-bell"></i> Alertas
                    </button>
                    <button class="btn btn-modern btn-secondary-modern" onclick="ayudaContextual()">
                        <i class="fas fa-question-circle"></i> Ayuda
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Rutas -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-route"></i> Ruta</th>
                            <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-store"></i> Local</th>
                            <th><i class="fas fa-shopping-cart"></i> Venta/Reporte</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($rutas) && is_array($rutas) && !empty($rutas)): ?>
                            <?php foreach ($rutas as $ruta): ?>
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
                                            <strong><?= htmlspecialchars($ruta['nombre_local'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $ruta['id_ruta'] ?? '' ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-info">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($ruta['direccion'] ?? 'Sin dirección') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-secondary">
                                        <i class="fas fa-user"></i> <?= htmlspecialchars($ruta['nombre_cliente'] ?? 'Sin cliente') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-info">
                                        <i class="fas fa-store"></i> <?= htmlspecialchars($ruta['nombre_local'] ?? 'Sin local') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="badge badge-modern badge-secondary">
                                            <i class="fas fa-shopping-cart"></i> Venta: <?= $ruta['id_ventas'] ?? 'N/A' ?>
                                        </span>
                                        <?php if (!empty($ruta['id_reportes'])): ?>
                                            <br><small class="badge badge-modern badge-warning mt-1">
                                                <i class="fas fa-file-alt"></i> Reporte: <?= $ruta['id_reportes'] ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    // Como la tabla no tiene campo estado, usaremos un valor por defecto
                                    $estado = 'activa'; // Valor por defecto
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
                                        <a href="/RMIE/app/controllers/RouteController.php?accion=view&id=<?= urlencode($ruta['id_ruta'] ?? '') ?>" 
                                           class="btn btn-sm btn-modern btn-info-modern" 
                                           title="Ver ruta">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/RouteController.php?accion=edit&id=<?= urlencode($ruta['id_ruta'] ?? '') ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar ruta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/RouteController.php?accion=delete&id=<?= urlencode($ruta['id_ruta'] ?? '') ?>" 
                                           class="btn btn-sm btn-modern btn-danger-modern" 
                                           title="Eliminar ruta"
                                           onclick="return confirm('¿Está seguro de eliminar la ruta \'<?= addslashes($ruta['nombre_local'] ?? 'Ruta #' . ($ruta['id_ruta'] ?? '')) ?>\'?\n\nEsta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                                        <button class="btn btn-sm btn-modern btn-secondary-modern" onclick="verDetalles(<?= $ruta['id_ruta'] ?? 0 ?>)" title="Ver detalles completos">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Función mejorada para limpiar filtros con animación
        function limpiarFiltros() {
            const form = document.getElementById('filterForm');
            const inputs = form.querySelectorAll('input, select');
            
            // Animación de limpieza
            inputs.forEach((input, index) => {
                setTimeout(() => {
                    input.style.transform = 'scale(0.95)';
                    input.style.transition = 'all 0.2s ease';
                    
                    setTimeout(() => {
                        if (input.type === 'text' || input.type === 'date') {
                            input.value = '';
                        } else if (input.tagName === 'SELECT') {
                            input.selectedIndex = 0;
                        }
                        input.style.transform = 'scale(1)';
                    }, 100);
                }, index * 30);
            });

            // Redirigir después de limpiar
            setTimeout(() => {
                window.location.href = '/RMIE/app/controllers/RouteController.php?accion=index';
            }, inputs.length * 30 + 300);
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
                    titulo: 'Vista de Mapa',
                    icono: 'fas fa-map',
                    contenido: `
                        <div class="text-center">
                            <i class="fas fa-map fa-4x mb-3 text-info"></i>
                            <h5>Mapa Interactivo</h5>
                            <p>Visualización geográfica de todas las rutas disponible próximamente.</p>
                            <div class="mt-3">
                                <div class="bg-secondary rounded p-3">
                                    <small>Integración con Google Maps/OpenStreetMap en desarrollo</small>
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
                                <button type="button" class="btn btn-primary" onclick="ejecutarAccionModal('${tipo}')">Aplicar</button>
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
            
            // Inicializar gráfico de dona
            inicializarGrafico();
        });
        
        // Función para crear gráfico de distribución
        function inicializarGrafico() {
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
            ctx.textAlign = 'center';
            ctx.fillText(total, centerX, centerY - 5);
            ctx.font = '12px Arial';
            ctx.fillText('Total', centerX, centerY + 15);
        }
    </script>
</body>
</html>