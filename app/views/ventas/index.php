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

// Calcular estadísticas
$totalVentas = count($ventas ?? []);
$ventasCompletadas = 0;
$ventasPendientes = 0;
$ventasCanceladas = 0;
$ventasProcesando = 0;
$montoTotal = 0;
$montoMes = 0;

$fechaActual = date('Y-m');

if (isset($ventas) && is_array($ventas)) {
    foreach ($ventas as $venta) {
        $estado = strtolower($venta->estado ?? 'pendiente');
        switch ($estado) {
            case 'completada':
                $ventasCompletadas++;
                break;
            case 'pendiente':
                $ventasPendientes++;
                break;
            case 'cancelada':
                $ventasCanceladas++;
                break;
            case 'procesando':
                $ventasProcesando++;
                break;
        }
        $montoTotal += floatval($venta->total ?? 0);
        
        // Calcular monto del mes actual
        if (strpos($venta->fecha_venta ?? '', $fechaActual) === 0) {
            $montoMes += floatval($venta->total ?? 0);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <script src="/RMIE/public/js/selenium-messages.js"></script>
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

        .filters-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Estilos 'podificados' para los filtros: apariencia tipo 'pill' */
        .filters-row {
            display: flex;
            gap: 12px;
            align-items: center;
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
            background: #e20e0eff;
            color: #333;
            border-radius: 12px;
            padding: 10px 14px;
            border: none;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            min-width: 160px;
        }

        .filter-input::placeholder { color: #333; }        .filter-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
        }

        .btn-modern-filter {
            transition: all 0.3s ease;
        }

        .btn-modern-filter:hover {
            background: #3A7BC8 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.4);
        }

        .btn-modern-clear {
            transition: all 0.3s ease;
        }

        .btn-modern-clear:hover {
            background: #E67E93 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 143, 163, 0.4);
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
            width: 100%;
        }

        .btn-pill i { margin-right: 8px; }

        .btn-pill-primary {
            background: linear-gradient(180deg,#1e90ff,#2a6df4);
        }

        .btn-pill-clear {
            background: linear-gradient(180deg,#ffb3c6,#ff7aa2);
        }

        @media (max-width: 768px) {
            .filters-row { 
                gap: 8px; 
                justify-content: center;
            }
            .filter-item {
                min-width: 120px;
                max-width: 200px;
            }
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
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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

        /* Estilos para los option del select */
        .form-control-modern option {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
        }

        .form-control-modern option:hover {
            background: #34495e;
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

        .sale-icon {
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

        .money-amount {
            font-size: 1.2rem;
            font-weight: bold;
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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

        /* Diseño de Tarjetas para ventas */
        .sales-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .sales-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: #1f2937; /* texto oscuro para buen contraste dentro de la tarjeta */
        }

        .sales-card::before {
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

        .sales-card:hover::before {
            transform: scaleX(1);
        }

        .sales-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .sales-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sales-card-icon {
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

        .sales-card-title {
            flex: 1;
        }

        .sales-card-title h4 {
            color: #1f2937;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .sales-card-title p {
            color: rgba(31, 41, 55, 0.75);
            font-size: 0.85rem;
            margin: 0;
        }

        .sales-card-body {
            margin-bottom: 20px;
        }

        .sales-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .sales-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .sales-info-icon {
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

        .sales-info-content {
            flex: 1;
        }

        .sales-info-label {
            color: rgba(31, 41, 55, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .sales-info-value {
            color: #0f1724;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
        }

        .sales-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sales-actions {
            display: flex;
            gap: 8px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
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
        }

        .view-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Contenedor de ventas: mostrado por defecto; la visibilidad de tabla/tarjetas
           se controla en los selectores específicos (#tableView / #cardsView) y por JS. */

        /* Responsive para tarjetas */
        @media (max-width: 768px) {
            .sales-grid {
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

        /* Diseño de Tarjetas para ventas */
        .sales-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .sales-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sales-card::before {
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

        .sales-card:hover::before {
            transform: scaleX(1);
        }

        .sales-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .sales-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sales-card-icon {
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

        .sales-card-title {
            flex: 1;
        }

        .sales-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .sales-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .sales-card-body {
            margin-bottom: 20px;
        }

        .sales-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .sales-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .sales-info-icon {
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

        .sales-info-content {
            flex: 1;
        }

        .sales-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .sales-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
        }

        .sales-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sales-actions {
            display: flex;
            gap: 8px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
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
        }

        .view-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Vista de tabla (se maneja con JavaScript) */
        #tableView {
            display: none;
        }

        #cardsView {
            display: grid;
        }

        /* Responsive para tarjetas */
        @media (max-width: 768px) {
            .sales-grid {
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

        /* Scroll horizontal para móviles - Ventas */
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
                min-width: 1350px !important; /* Ancho mínimo para 9 columnas */
                margin-bottom: 0;
                width: 1350px;
            }
            
            .table-modern th,
            .table-modern td {
                white-space: nowrap !important;
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 120px;
            }
            
            /* Anchos específicos para ventas (9 columnas) */
            .table-modern th:nth-child(1),
            .table-modern td:nth-child(1) { min-width: 70px; }
            
            .table-modern th:nth-child(2),
            .table-modern td:nth-child(2) { min-width: 150px; }
            
            .table-modern th:nth-child(3),
            .table-modern td:nth-child(3) { min-width: 180px; }
            
            .table-modern th:nth-child(4),
            .table-modern td:nth-child(4) { min-width: 180px; }
            
            .table-modern th:nth-child(5),
            .table-modern td:nth-child(5) { min-width: 100px; }
            
            .table-modern th:nth-child(6),
            .table-modern td:nth-child(6) { min-width: 120px; }
            
            .table-modern th:nth-child(7),
            .table-modern td:nth-child(7) { min-width: 100px; }
            
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
        <!-- Debug block removed -->
    <div class="dashboard-container">
        <h1 class="page-title">
            <i class="fas fa-shopping-cart"></i> Gestión de Ventas
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
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-number"><?php echo $totalVentas; ?></div>
                <div class="stat-label">Total Ventas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $ventasCompletadas; ?></div>
                <div class="stat-label">Completadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo $ventasPendientes; ?></div>
                <div class="stat-label">Pendientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="stat-number"><?php echo $ventasProcesando; ?></div>
                <div class="stat-label">Procesando</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-number money-amount">$<?php echo number_format($montoTotal, 2); ?></div>
                <div class="stat-label">Monto Total</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-month"></i>
                </div>
                <div class="stat-number money-amount">$<?php echo number_format($montoMes, 2); ?></div>
                <div class="stat-label">Ventas del Mes</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form method="GET" action="/RMIE/app/controllers/SaleController.php" id="filterForm">
                <input type="hidden" name="accion" value="index">

                <div class="filters-row">
                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-box"></i> Producto</span>
                        <input type="text"
                               id="filtro_producto"
                               name="filtro_producto"
                               class="filter-input"
                               list="productos_datalist"
                               placeholder="Buscar producto..."
                               autocomplete="off"
                               value="<?= htmlspecialchars($_GET['filtro_producto'] ?? '') ?>">
                        <datalist id="productos_datalist">
                            <?php if (isset($productos) && is_array($productos)): ?>
                                <?php foreach ($productos as $p): ?>
                                    <option data-id="<?= htmlspecialchars($p->id_productos) ?>" value="<?= htmlspecialchars($p->nombre) ?>"></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </datalist>
                        <input type="hidden" id="producto_id" name="producto_id" value="<?= isset($_GET['producto_id']) ? htmlspecialchars($_GET['producto_id']) : '' ?>">
                    </div>

                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-user"></i> Cliente</span>
                        <input type="text"
                               id="filtro_cliente"
                               name="filtro_cliente"
                               class="filter-input"
                               list="clientes_datalist"
                               placeholder="Buscar cliente..."
                               autocomplete="off"
                               value="<?= htmlspecialchars($_GET['filtro_cliente'] ?? '') ?>">
                        <datalist id="clientes_datalist">
                            <?php if (isset($clientes) && is_array($clientes)): ?>
                                <?php foreach ($clientes as $c): ?>
                                    <option data-id="<?= htmlspecialchars($c->id_clientes) ?>" value="<?= htmlspecialchars($c->nombre) ?>"></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </datalist>
                        <input type="hidden" id="cliente_id" name="cliente_id" value="<?= isset($_GET['cliente_id']) ? htmlspecialchars($_GET['cliente_id']) : '' ?>">
                    </div>

                    <div class="filter-item">
                        <span class="filter-label"><i class="fas fa-filter"></i> Estado</span>
                        <select name="filtro_estado" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" <?= ($_GET['filtro_estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="procesando" <?= ($_GET['filtro_estado'] ?? '') === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                            <option value="completada" <?= ($_GET['filtro_estado'] ?? '') === 'completada' ? 'selected' : '' ?>>Completada</option>
                            <option value="cancelada" <?= ($_GET['filtro_estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </div>                    <div class="filter-item" style="flex: 1 1 100%; display: flex; justify-content: center; align-items: center; margin-top: 10px;">
                        <div style="display: flex; gap: 12px;">
                            <button type="submit" class="btn-modern-filter" style="background: #4A90E2; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fas fa-search"></i> FILTRAR
                            </button>
                            <button type="button" class="btn-modern-clear" onclick="limpiarFiltros()" style="background: #FF8FA3; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fas fa-times"></i> LIMPIAR
                            </button>
                        </div>
                    </div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Barra de acciones moderna y organizada -->
        <div class="action-toolbar mb-4">
            <div class="toolbar-container">
                <a href="/RMIE/app/controllers/SaleController.php?accion=create" 
                   class="btn-modern btn-create">
                    <div class="btn-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="btn-content">
                        <span class="btn-title">Nueva Venta</span>
                        <span class="btn-subtitle">Registrar nueva venta</span>
                    </div>
                </a>
                
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <button class="btn-modern btn-cleanup" 
                        onclick="limpiarVentas()" 
                        title="Limpiar ventas procesadas">
                    <div class="btn-icon">
                        <i class="fas fa-broom"></i>
                    </div>
                    <div class="btn-content">
                        <span class="btn-title">Limpiar Procesadas</span>
                        <span class="btn-subtitle">Remover completadas</span>
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

        <!-- Contenedor de Ventas con Toggle de Vista -->
        <div class="table-container" id="ventas-container">
            <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                <h3 style="color: #fff; margin: 0;"><i class="fas fa-shopping-cart"></i> Lista de Ventas (<?= $totalVentas ?>)</h3>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="toggleSalesView('cards')" id="btnCards">
                        <i class="fas fa-th-large"></i> Tarjetas
                    </button>
                    <button class="view-toggle-btn" onclick="toggleSalesView('table')" id="btnTable">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                </div>
            </div>

            <!-- Vista de Tarjetas (por defecto) -->
            <div id="cardsView" class="sales-grid">
                <?php if (is_array($ventas) && count($ventas) > 0): ?>
                    <?php foreach ($ventas as $venta): ?>
                        <div class="sales-card">
                            <div class="sales-card-header">
                                <div class="sales-card-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="sales-card-title">
                                    <h4>Venta #<?= htmlspecialchars($venta->id_ventas) ?></h4>
                                    <p><i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($venta->id_ventas) ?></p>
                                </div>
                            </div>
                            
                            <div class="sales-card-body">
                                <div class="sales-info-item">
                                    <div class="sales-info-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="sales-info-content">
                                        <div class="sales-info-label">Cliente</div>
                                        <div class="sales-info-value"><?= htmlspecialchars($venta->cliente_nombre ?? 'Cliente N/A') ?></div>
                                    </div>
                                </div>
                                
                                <div class="sales-info-item">
                                    <div class="sales-info-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="sales-info-content">
                                        <div class="sales-info-label">Producto(s)</div>
                                        <div class="sales-info-value">
                                            <?php if (!empty($venta->productos_asignados)): ?>
                                                <?php 
                                                $nombres_productos = array_map(function($p) { 
                                                    return htmlspecialchars($p->nombre); 
                                                }, $venta->productos_asignados);
                                                echo implode(', ', array_slice($nombres_productos, 0, 2));
                                                if (count($nombres_productos) > 2): ?>
                                                    <span class="badge bg-info ms-1" title="<?= implode(', ', array_slice($nombres_productos, 2)) ?>">
                                                        +<?= count($nombres_productos) - 2 ?> más
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                Producto N/A
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sales-info-item">
                                    <div class="sales-info-icon">
                                        <i class="fas fa-sort-numeric-up"></i>
                                    </div>
                                    <div class="sales-info-content">
                                        <div class="sales-info-label">Cantidad</div>
                                        <div class="sales-info-value"><?= htmlspecialchars($venta->cantidad ?? 0) ?></div>
                                    </div>
                                </div>
                                
                                <div class="sales-info-item">
                                    <div class="sales-info-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="sales-info-content">
                                        <div class="sales-info-label">Total</div>
                                        <div class="sales-info-value">$<?= number_format(floatval($venta->total ?? 0), 2) ?></div>
                                    </div>
                                </div>
                                
                                <div class="sales-info-item">
                                    <div class="sales-info-icon">
                                        <?php
                                        $estado = strtolower($venta->estado ?? 'pendiente');
                                        $iconClass = '';
                                        
                                        switch ($estado) {
                                            case 'completada':
                                                $iconClass = 'fas fa-check-circle';
                                                break;
                                            case 'pendiente':
                                                $iconClass = 'fas fa-clock';
                                                break;
                                            case 'cancelada':
                                                $iconClass = 'fas fa-times-circle';
                                                break;
                                            case 'procesando':
                                                $iconClass = 'fas fa-cogs';
                                                break;
                                            default:
                                                $iconClass = 'fas fa-question-circle';
                                        }
                                        ?>
                                        <i class="<?= $iconClass ?>"></i>
                                    </div>
                                    <div class="sales-info-content">
                                        <div class="sales-info-label">Estado</div>
                                        <div class="sales-info-value"><?= ucfirst($estado) ?></div>
                                    </div>
                                </div>
                                
                                <div class="sales-info-item">
                                    <div class="sales-info-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="sales-info-content">
                                        <div class="sales-info-label">Fecha</div>
                                        <div class="sales-info-value"><?= date('d/m/Y H:i', strtotime($venta->fecha_venta ?? 'now')) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="sales-card-footer">
                                <?php
                                $estado = strtolower($venta->estado ?? 'pendiente');
                                $badgeClass = '';
                                
                                switch ($estado) {
                                    case 'completada':
                                        $badgeClass = 'badge-success';
                                        break;
                                    case 'pendiente':
                                        $badgeClass = 'badge-warning';
                                        break;
                                    case 'cancelada':
                                        $badgeClass = 'badge-danger';
                                        break;
                                    case 'procesando':
                                        $badgeClass = 'badge-info';
                                        break;
                                    default:
                                        $badgeClass = 'badge-secondary';
                                }
                                ?>
                                <span class="badge badge-modern <?= $badgeClass ?>">
                                    <i class="<?= $iconClass ?>"></i> <?= ucfirst($estado) ?>
                                </span>
                                <div class="sales-actions">
                                    <a href="/RMIE/app/controllers/SaleController.php?accion=edit&id=<?= urlencode($venta->id_ventas) ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Editar venta">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                    <a href="/RMIE/app/controllers/SaleController.php?accion=delete&id=<?= urlencode($venta->id_ventas) ?>&t=<?= time() ?>" 
                                       class="btn btn-sm btn-danger" 
                                       title="Eliminar venta"
                                       data-id="<?= $venta->id_ventas ?>"
                                       onclick="console.log('Delete venta:', <?= $venta->id_ventas ?>); return confirmAction('eliminar venta');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #fff;">
                        <i class="fas fa-shopping-cart fa-5x mb-4" style="opacity: 0.5;"></i>
                        <h3 style="font-size: 1.8rem; margin-bottom: 15px;">No hay ventas disponibles</h3>
                        <p style="font-size: 1.1rem; opacity: 0.8; margin-bottom: 25px;">No se encontraron ventas que coincidan con los filtros aplicados.</p>
                        <a href="/RMIE/app/controllers/SaleController.php?accion=create" class="btn btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Crear Primera Venta
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vista de Tabla (oculta por defecto) -->
            <div id="tableView" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-shopping-cart"></i> Venta</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-box"></i> Producto</th>
                            <th><i class="fas fa-sort-numeric-up"></i> Cantidad</th>
                            <th><i class="fas fa-dollar-sign"></i> Total</th>
                            <th><i class="fas fa-traffic-light"></i> Estado</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($ventas) && count($ventas) > 0): ?>
                            <?php foreach ($ventas as $venta): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($venta->id_ventas) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="sale-icon">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div>
                                            <strong>Venta #<?= htmlspecialchars($venta->id_ventas) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= $venta->id_ventas ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-modern badge-info">
                                        <?= htmlspecialchars($venta->cliente_nombre ?? 'Cliente N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($venta->productos_asignados)): ?>
                                        <?php foreach (array_slice($venta->productos_asignados, 0, 2) as $producto): ?>
                                            <span class="badge badge-modern badge-secondary mb-1">
                                                <?= htmlspecialchars($producto->nombre) ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($venta->productos_asignados) > 2): ?>
                                            <span class="badge badge-modern badge-info" 
                                                  title="<?= implode(', ', array_map(function($p) { return $p->nombre; }, array_slice($venta->productos_asignados, 2))) ?>">
                                                +<?= count($venta->productos_asignados) - 2 ?> más
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-modern badge-secondary">Producto N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <strong class="fs-5"><?= htmlspecialchars($venta->cantidad ?? 0) ?></strong>
                                </td>
                                <td class="text-center">
                                    <span class="money-amount fs-5">
                                        $<?= number_format(floatval($venta->total ?? 0), 2) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $estado = strtolower($venta->estado ?? 'pendiente');
                                    $badgeClass = '';
                                    $iconClass = '';
                                    
                                    switch ($estado) {
                                        case 'completada':
                                            $badgeClass = 'badge-success';
                                            $iconClass = 'fas fa-check-circle';
                                            break;
                                        case 'pendiente':
                                            $badgeClass = 'badge-warning';
                                            $iconClass = 'fas fa-clock';
                                            break;
                                        case 'cancelada':
                                            $badgeClass = 'badge-danger';
                                            $iconClass = 'fas fa-times-circle';
                                            break;
                                        case 'procesando':
                                            $badgeClass = 'badge-info';
                                            $iconClass = 'fas fa-cogs';
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
                                        <strong><?= date('d/m/Y', strtotime($venta->fecha_venta ?? 'now')) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($venta->fecha_venta ?? 'now')) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                                     <a href="/RMIE/app/controllers/SaleController.php?accion=show&id=<?= urlencode($venta->id_ventas) ?>" 
                                                         class="btn btn-sm btn-info" 
                                                         title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/RMIE/app/controllers/SaleController.php?accion=edit&id=<?= urlencode($venta->id_ventas) ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar venta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/SaleController.php?accion=delete&id=<?= urlencode($venta->id_ventas) ?>&t=<?= time() ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Eliminar venta"
                                           data-id="<?= $venta->id_ventas ?>"
                                           onclick="console.log('Delete venta:', <?= $venta->id_ventas ?>); return confirmAction('eliminar venta')">
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
                                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                        <h5>No hay ventas disponibles</h5>
                                        <p>No se encontraron ventas que coincidan con los filtros aplicados.</p>
                                        <a href="/RMIE/app/controllers/SaleController.php?accion=create" class="btn btn-modern btn-success-modern">
                                            <i class="fas fa-plus"></i> Crear Primera Venta
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// Capturar promesas rechazadas
window.addEventListener('unhandledrejection', function(e) { console.log('Promise Error:', e.reason); e.preventDefault(); });

// Debug mode - capturar errores JavaScript
window.addEventListener('error', function(e) { console.log('JS Error:', e.message, 'at', e.filename + ':' + e.lineno); });

        function limpiarFiltros() {
            const form = document.getElementById('filterForm');
            if (form) {
                form.reset();
                // Asegurar que el id oculto se limpie para no mantener filtros fantasma
                const prodId = document.getElementById('producto_id');
                if (prodId) prodId.value = '';
                const cliId = document.getElementById('cliente_id');
                if (cliId) cliId.value = '';
            }
            // Navegar sin parámetros para ver el listado completo
            window.location.href = '/RMIE/app/controllers/SaleController.php?accion=index';
        }

        // Sincronización producto: cuando se elige nombre del datalist, guardar su ID
        (function() {
            const input = document.getElementById('filtro_producto');
            const hiddenId = document.getElementById('producto_id');
            const datalist = document.getElementById('productos_datalist');
            if (!input || !hiddenId || !datalist) return;

            function findIdByName(name) {
                const opt = Array.from(datalist.options).find(o => o.value.trim().toLowerCase() === String(name||'').trim().toLowerCase());
                return opt ? opt.getAttribute('data-id') : '';
            }

            input.addEventListener('change', function() {
                const id = findIdByName(input.value);
                hiddenId.value = id || '';
                // Si el usuario borra el texto, limpiar el id para evitar filtros vacíos
                if (!input.value.trim()) {
                    hiddenId.value = '';
                    // Restaurar listado completo si se borra el filtro
                    const url = new URL(window.location.href);
                    url.searchParams.delete('filtro_producto');
                    url.searchParams.delete('producto_id');
                    url.searchParams.set('accion', 'index');
                    window.location.href = url.pathname + '?' + url.searchParams.toString();
                }
            });

            // Si ya viene el nombre cargado, intentar precargar el id
            if (input.value && !hiddenId.value) {
                const id = findIdByName(input.value);
                hiddenId.value = id || '';
            }
        })();

        // Sincronización cliente: nombre -> id_clientes
        (function() {
            const input = document.getElementById('filtro_cliente');
            const hiddenId = document.getElementById('cliente_id');
            const datalist = document.getElementById('clientes_datalist');
            if (!input || !hiddenId || !datalist) return;

            function findIdByName(name) {
                const opt = Array.from(datalist.options).find(o => o.value.trim().toLowerCase() === String(name||'').trim().toLowerCase());
                return opt ? opt.getAttribute('data-id') : '';
            }

            input.addEventListener('change', function() {
                const id = findIdByName(input.value);
                hiddenId.value = id || '';
                if (!input.value.trim()) {
                    hiddenId.value = '';
                    // Restaurar listado completo si se borra el filtro
                    const url = new URL(window.location.href);
                    url.searchParams.delete('filtro_cliente');
                    url.searchParams.delete('cliente_id');
                    url.searchParams.set('accion', 'index');
                    window.location.href = url.pathname + '?' + url.searchParams.toString();
                }
            });

            if (input.value && !hiddenId.value) {
                const id = findIdByName(input.value);
                hiddenId.value = id || '';
            }
        })();

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
                
                // Obtener ID de la venta de forma segura
                let ventaId = 'esta venta';
                const tr = this.closest('tr');
                
                if (tr) {
                    const strongElement = tr.querySelector('td:nth-child(1) strong');
                    if (strongElement) {
                        ventaId = strongElement.textContent;
                    }
                } else if (this.dataset.id) {
                    ventaId = 'ID ' + this.dataset.id;
                }
                
                if (confirmAction('eliminar venta')) {
                    window.location.href = this.href;
                }
            });
        });

        // Toggle entre vista de tarjetas y tabla para ventas
        function toggleSalesView(view) {
            const cardsView = document.getElementById('cardsView');
            const tableView = document.getElementById('tableView');
            const btnCards = document.getElementById('btnCards');
            const btnTable = document.getElementById('btnTable');
            
            if (view === 'cards') {
                cardsView.style.display = 'grid';
                tableView.style.display = 'none';
                btnCards.classList.add('active');
                btnTable.classList.remove('active');
                localStorage.setItem('ventasView', 'cards');
            } else {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                btnCards.classList.remove('active');
                btnTable.classList.add('active');
                localStorage.setItem('ventasView', 'table');
            }
        }
        
        // Restaurar vista guardada
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('ventasView') || 'cards';
            toggleSalesView(savedView);
        });

        // Función para limpiar ventas procesadas
        function limpiarVentas() {
            const opcion = prompt(`¿Qué tipo de limpieza quieres hacer en VENTAS?\n\nEscribe el número de tu opción:\n\n1 - Solo eliminar ventas PROCESADAS/ENTREGADAS\n2 - Eliminar ventas CANCELADAS\n3 - Eliminar TODAS las ventas\n4 - Cancelar`);
            
            if (opcion === '1') {
                if (confirmAction('limpiar registros')) {
                    alert('Eliminando ventas procesadas...');
                    window.location.href = '/RMIE/app/controllers/SaleController.php?accion=clean_processed';
                }
            } else if (opcion === '2') {
                if (confirmAction('limpiar registros')) {
                    alert('Eliminando ventas canceladas...');
                    window.location.href = '/RMIE/app/controllers/SaleController.php?accion=clean_cancelled';
                }
            } else if (opcion === '3') {
                if (confirmAction('eliminar todos')) {
                    alert('Eliminando todas las ventas...');
                    window.location.href = '/RMIE/app/controllers/SaleController.php?accion=clean_all';
                }
            }
        }
    </script>
</body>
</html>