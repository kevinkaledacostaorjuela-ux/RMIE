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
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'admin'): ?>
        /* Ocultar botones de eliminar para roles que no sean admin */
        a[href*="accion=delete"],
        button[onclick*="delete"],
        .btn-danger[href*="delete"] {
            display: none !important;
        }
        <?php endif; ?>

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
        }        .filters-container {
            margin-bottom: 2rem;
        }        .filters-inner {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 35px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
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

        @keyframes borderGlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
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

        /* Diseño de tarjetas para subcategorías */
        .subcategories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .subcategories-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .subcategories-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2, #9c27b0);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .subcategories-card:hover::before {
            transform: scaleX(1);
        }

        .subcategories-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .subcategories-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .subcategories-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #9c27b0);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 5px 15px rgba(156, 39, 176, 0.4);
        }

        .subcategories-card-title {
            flex: 1;
        }

        .subcategories-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .subcategories-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .subcategories-card-body {
            margin-bottom: 20px;
        }

        .subcategories-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .subcategories-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .subcategories-info-icon {
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

        .subcategories-info-content {
            flex: 1;
        }

        .subcategories-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .subcategories-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
        }

        .subcategories-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .subcategories-actions {
            display: flex;
            gap: 8px;
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
        </div>        <!-- Filtros -->
        <div class="filters-container">
            <div class="filters-inner">
                <form method="GET" action="/RMIE/app/controllers/SubcategoryController.php" id="filterForm" 
                      style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <input type="hidden" name="accion" value="index">
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-sitemap"></i> Subcategoría
                            </label>
                            <input type="text"
                                   name="nombre"
                                   class="form-control"
                                   placeholder="Buscar subcategoría..."
                                   style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;"
                                   value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-layer-group"></i> Categoría
                            </label>
                            <select name="categoria" 
                                    class="form-select"
                                    style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;">
                                <option value="">Todas las categorías</option>
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat->id_categoria ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat->id_categoria ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>                        <div class="col-md-3">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-calendar"></i> Fecha
                            </label>
                            <input type="date"
                                   name="fecha"
                                   class="form-control"
                                   style="background: #fff; color: #2c3e50; border: 1px solid #ddd; padding: 12px 15px; border-radius: 8px; font-size: 0.95rem;"
                                   value="<?= htmlspecialchars($_GET['fecha'] ?? '') ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" style="color: #2c3e50; font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 8px;">
                                <i class="fas fa-cogs"></i> Acciones
                            </label>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <button type="submit" class="btn-modern-filter" style="background: #4A90E2; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    <i class="fas fa-search"></i> FILTRAR
                                </button>
                                <button type="button" class="btn-modern-clear" onclick="limpiarFiltros()" style="background: #FF8FA3; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.9rem; width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    <i class="fas fa-times"></i> LIMPIAR
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
            <div class="table-header">
                <h3><i class="fas fa-layer-group"></i> Lista de Subcategorías (<?= count($subcategorias ?? []) ?>)</h3>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="toggleSubcategoriesView('cards')" id="btnCards">
                        <i class="fas fa-th-large"></i> Tarjetas
                    </button>
                    <button class="view-toggle-btn" onclick="toggleSubcategoriesView('table')" id="btnTable">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                </div>
            </div>

            <!-- Vista de Tarjetas (por defecto) -->
            <div id="cardsView" class="subcategories-grid">
                <?php if (isset($subcategorias) && is_array($subcategorias) && !empty($subcategorias)): ?>
                    <?php foreach ($subcategorias as $subcatData): ?>
                    <?php 
                    // Extraer el objeto subcategoría y el nombre de la categoría
                    $subcat = $subcatData['obj'];
                    $categoria_nombre = $subcatData['categoria_nombre'];
                    ?>
                        <div class="subcategories-card">
                            <div class="subcategories-card-header">
                                <div class="subcategories-card-icon">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="subcategories-card-title">
                                    <h4><?= htmlspecialchars($subcat->nombre ?? 'Sin nombre') ?></h4>
                                    <p><i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($subcat->id_subcategoria ?? '') ?></p>
                                </div>
                            </div>

                            <div class="subcategories-card-body">
                                <div class="subcategories-info-item">
                                    <div class="subcategories-info-icon">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="subcategories-info-content">
                                        <div class="subcategories-info-label">Categoría Padre</div>
                                        <div class="subcategories-info-value"><?= htmlspecialchars($categoria_nombre) ?></div>
                                    </div>
                                </div>

                                <div class="subcategories-info-item">
                                    <div class="subcategories-info-icon">
                                        <i class="fas fa-align-left"></i>
                                    </div>
                                    <div class="subcategories-info-content">
                                        <div class="subcategories-info-label">Descripción</div>
                                        <div class="subcategories-info-value">
                                            <?= !empty($subcat->descripcion) ? htmlspecialchars($subcat->descripcion) : 'Sin descripción' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="subcategories-info-item">
                                    <div class="subcategories-info-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="subcategories-info-content">
                                        <div class="subcategories-info-label">Fecha de Creación</div>
                                        <div class="subcategories-info-value">
                                            <?= !empty($subcat->fecha_creacion) ? date('d/m/Y H:i', strtotime($subcat->fecha_creacion)) : 'Sin fecha' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="subcategories-card-footer">
                                <div class="subcategories-actions">
                                    <a href="/RMIE/app/controllers/SubcategoryController.php?accion=edit&id=<?= urlencode($subcat->id_subcategoria ?? '') ?>" 
                                       class="btn btn-sm btn-modern btn-warning-modern" 
                                       title="Editar subcategoría">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=<?= urlencode($subcat->id_subcategoria ?? '') ?>" 
                                       class="btn btn-sm btn-modern btn-danger-modern" 
                                       title="Eliminar subcategoría"
                                       onclick="return confirm('¿Está seguro de eliminar esta subcategoría?')">
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
                            <p>No hay subcategorías registradas</p>
                            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=create" class="btn btn-modern btn-success-modern">
                                <i class="fas fa-plus"></i> Crear Primera Subcategoría
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vista de Tabla -->
            <div id="tableView" style="display: none;">
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
                                    <strong>#<?= htmlspecialchars($subcat->id_subcategoria ?? '') ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="subcategory-icon">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($subcat->nombre ?? 'Sin nombre') ?></strong>
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
                                        <a href="/RMIE/app/controllers/SubcategoryController.php?accion=edit&id=<?= urlencode($subcat->id_subcategoria ?? '') ?>" 
                                           class="btn btn-sm btn-modern btn-warning-modern" 
                                           title="Editar subcategoría">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/views/subcategorias/delete.php?id=<?= urlencode($subcat->id_subcategoria ?? '') ?>" 
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
                                        <strong>#<?= htmlspecialchars($subcat->id_subcategoria ?? '') ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="subcategory-icon">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($subcat->nombre ?? 'Sin nombre') ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-barcode"></i> ID: <?= htmlspecialchars($subcat->id_subcategoria ?? '') ?>
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
                                            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=edit&id=<?= urlencode($subcat->id_subcategoria ?? '') ?>" 
                                               class="btn btn-sm btn-modern btn-warning-modern" 
                                               title="Editar subcategoría">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($_SESSION['rol'] !== 'coordinador'): ?>
                                            <a href="/RMIE/app/views/subcategorias/delete.php?id=<?= urlencode($subcat->id_subcategoria ?? '') ?>" 
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
        // Toggle entre vista de tarjetas y tabla para subcategorías
        function toggleSubcategoriesView(view) {
            const cardsView = document.getElementById('cardsView');
            const tableView = document.getElementById('tableView');
            const btnCards = document.getElementById('btnCards');
            const btnTable = document.getElementById('btnTable');
            
            if (view === 'cards') {
                cardsView.style.display = 'grid';
                tableView.style.display = 'none';
                btnCards.classList.add('active');
                btnTable.classList.remove('active');
                localStorage.setItem('subcategoriasView', 'cards');
            } else {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                btnCards.classList.remove('active');
                btnTable.classList.add('active');
                localStorage.setItem('subcategoriasView', 'table');
            }
        }
        
        // Restaurar vista guardada
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('subcategoriasView') || 'cards';
            toggleSubcategoriesView(savedView);
        });

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