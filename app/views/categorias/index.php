<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Si no hay usuario logueado, redirigir
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Mensajes de sesión (protegido por null coalescing)
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : '';

// Limpiar mensajes para no mostrarlos en la siguiente petición
unset($_SESSION['error'], $_SESSION['success']);

// Obtener estadísticas de categorías de forma robusta
global $conn;
$stats = ['total_categorias' => 0, 'con_descripcion' => 0];
if (isset($conn)) {
    $statsQuery = @$conn->query("SELECT 
        COUNT(*) as total_categorias,
        COUNT(CASE WHEN descripcion IS NOT NULL AND descripcion != '' THEN 1 END) as con_descripcion
        FROM categorias");
    if ($statsQuery) {
        $f = $statsQuery->fetch_assoc();
        if (is_array($f)) {
            $stats['total_categorias'] = isset($f['total_categorias']) ? $f['total_categorias'] : 0;
            $stats['con_descripcion'] = isset($f['con_descripcion']) ? $f['con_descripcion'] : 0;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <script src="/RMIE/public/js/selenium-messages.js"></script>

    <script>
        // Suprimir warnings específicos de Firefox
        if (typeof console !== 'undefined') {
            const originalWarn = console.warn;
            console.warn = function(...args) {
                const message = args.join(' ');
                if (message.includes('Components') || 
                    message.includes('desaprobado') || 
                    message.includes('deprecated')) {
                    return; // Ignorar estos warnings
                }
                originalWarn.apply(console, args);
            };
        }
    </script>

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

        /* Diseño de tarjetas para categorías */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .categories-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .categories-card::before {
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

        .categories-card:hover::before {
            transform: scaleX(1);
        }

        .categories-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .categories-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .categories-card-icon {
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

        .categories-card-title {
            flex: 1;
        }

        .categories-card-title h4 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .categories-card-title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .categories-card-body {
            margin-bottom: 20px;
        }

        .categories-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .categories-info-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .categories-info-icon {
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

        .categories-info-content {
            flex: 1;
        }

        .categories-info-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .categories-info-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            word-break: break-word;
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

        .categories-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .categories-actions {
            display: flex;
            gap: 8px;
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
            0% { left: -100%; }
            100% { left: 100%; }
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

        .provider-icon {
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

        .badge-success { background: linear-gradient(45deg, #4facfe, #00f2fe); color: white; }
        .badge-warning { background: linear-gradient(45deg, #ff9a9e, #fecfef); color: white; }
        .badge-danger { background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; }
        .badge-info { background: linear-gradient(45deg, #4facfe, #00f2fe); color: white; }
        .badge-secondary { background: linear-gradient(45deg, #667eea, #764ba2); color: white; }

        .contact-info { font-size: 0.9rem; color: #fff !important; }
        .text-muted { color: rgba(255, 255, 255, 0.7) !important; }

        /* Responsive: scroll horizontal para móviles */
        @media (max-width: 768px) {
            .table-container { padding: 15px; overflow: visible; }
            .table-responsive { -webkit-overflow-scrolling: touch; overflow-x: auto !important; overflow-y: visible; border-radius: 10px; max-width: 100%; position: relative; }
            .table-responsive::-webkit-scrollbar { height: 12px; background: rgba(255, 255, 255, 0.2); border-radius: 6px; }
            .table-responsive::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.5); border-radius: 6px; border: 2px solid rgba(255, 255, 255, 0.1); }
            .table-responsive::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.7); }
            .table-modern { min-width: 1050px !important; margin-bottom: 0; width: 1050px; }
            .table-modern th, .table-modern td { white-space: nowrap !important; padding: 10px 12px; font-size: 0.85rem; min-width: 120px; }
            .table-modern th:nth-child(1), .table-modern td:nth-child(1) { min-width: 70px; }
            .table-modern th:nth-child(2), .table-modern td:nth-child(2) { min-width: 180px; }
            .table-modern th:nth-child(3), .table-modern td:nth-child(3) { min-width: 120px; }
            .table-modern th:nth-child(4), .table-modern td:nth-child(4) { min-width: 250px; }
            .table-modern th:nth-child(5), .table-modern td:nth-child(5) { min-width: 120px; }
            .table-modern th:nth-child(6), .table-modern td:nth-child(6) { min-width: 150px; }
            .table-modern th:nth-child(7), .table-modern td:nth-child(7) { min-width: 120px; }
            .table-modern th:nth-child(8), .table-modern td:nth-child(8) { min-width: 120px; }
            .scroll-hint { position: absolute; bottom: -30px; left: 50%; transform: translateX(-50%); color: rgba(255, 255, 255, 0.8); font-size: 0.8rem; font-style: italic; animation: pulse 2s ease-in-out infinite; background: rgba(0, 0, 0, 0.3); padding: 5px 10px; border-radius: 15px; backdrop-filter: blur(5px); }
            @keyframes pulse { 0%, 100% { opacity: 0.6; } 50% { opacity: 1; } }
        }

        /* Refuerzos responsive para evitar duplicados si las utilidades de Bootstrap no cargan */
        @media (min-width: 768px) {
            .d-block.d-md-none { display: none !important; }
            .d-none.d-md-block { display: block !important; }
        }

        @media (max-width: 767.98px) {
            .d-none.d-md-block { display: none !important; }
            .d-block.d-md-none { display: block !important; }
        }

        /* Alineación y tamaño de botones de acción dentro de tablas */
        .table-container .btn-group .btn {
            padding: 8px 12px;
            font-size: 0.85rem;
            border-radius: 25px;
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        <!-- Filtro de Búsqueda -->
        <div class="mb-5" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 20px; padding: 35px; border: 1px solid rgba(255, 255, 255, 0.2);">
            <form method="GET" action="/RMIE/app/controllers/CategoryController.php">
                <input type="hidden" name="accion" value="index">
                
                <!-- Campo de búsqueda -->
                <div class="row mb-4">
                    <div class="col-12">
                        <input type="text" 
                               name="buscar" 
                               class="form-control" 
                               placeholder="Escriba el nombre de la categoría que desea buscar..." 
                               value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
                               style="border: none; font-size: 18px; padding: 15px 25px; height: 55px; background: white; color: #333; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <button type="submit" class="btn d-flex align-items-center justify-content-center" 
                                style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; font-weight: 600; height: 50px; font-size: 16px; border-radius: 12px; box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3); min-width: 180px; padding: 0 20px;">
                            <i class="fas fa-search me-2"></i>Buscar Categoría
                        </button>
                    </div>
                    <?php if (isset($_GET['buscar']) && !empty($_GET['buscar'])): ?>
                        <div class="col-auto">
                            <a href="/RMIE/app/controllers/CategoryController.php?accion=index" 
                               class="btn d-flex align-items-center justify-content-center text-decoration-none" 
                               style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none; font-weight: 600; height: 50px; font-size: 16px; border-radius: 12px; box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3); min-width: 180px; padding: 0 20px;">
                                <i class="fas fa-times me-2"></i>Limpiar Búsqueda
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
            
            <!-- Indicador de filtro activo -->
            <?php if (isset($_GET['buscar']) && !empty($_GET['buscar'])): ?>
                <div class="mt-4">
                    <div class="text-center p-3" style="background: rgba(40, 167, 69, 0.15); border-radius: 15px; border: 2px solid rgba(40, 167, 69, 0.3);">
                        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                            <i class="fas fa-search text-success fs-5"></i>
                            <span class="text-white fw-bold fs-6">Mostrando resultados para:</span>
                            <span class="badge fs-6 px-3 py-2" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 20px;">
                                "<?php echo htmlspecialchars($_GET['buscar']); ?>"
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number"><?php echo isset($stats['total_categorias']) ? intval($stats['total_categorias']) : 0; ?></div>
                <div class="stat-label">Total Categorías</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-align-left"></i>
                </div>
                <div class="stat-number"><?php echo isset($stats['con_descripcion']) ? intval($stats['con_descripcion']) : 0; ?></div>
                <div class="stat-label">Con Descripción</div>
            </div>
        </div>

        <!-- Barra de acciones moderna y organizada -->
        <div class="action-toolbar mb-4">
            <div class="toolbar-container">
                <a href="/RMIE/app/controllers/CategoryController.php?accion=create" 
                   class="btn-modern btn-create">
                    <div class="btn-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="btn-content">
                        <span class="btn-title">Nueva Categoría</span>
                        <span class="btn-subtitle">Crear nueva categoría</span>
                    </div>
                </a>
                
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <button class="btn-modern btn-cleanup" 
                        onclick="limpiarCategorias()" 
                        title="Eliminar categorías vacías">
                    <div class="btn-icon">
                        <i class="fas fa-broom"></i>
                    </div>
                    <div class="btn-content">
                        <span class="btn-title">Limpiar Vacías</span>
                        <span class="btn-subtitle">Remover sin productos</span>
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

        <!-- Tabla de Categorías (Desktop) -->
        <div class="table-container d-none d-md-block">
            <div class="table-header">
                <h3><i class="fas fa-tags"></i> Lista de Categorías (<?= count($categorias ?? []) ?>)</h3>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="toggleCategoriesView('cards')" id="btnCards">
                        <i class="fas fa-th-large"></i> Tarjetas
                    </button>
                    <button class="view-toggle-btn" onclick="toggleCategoriesView('table')" id="btnTable">
                        <i class="fas fa-table"></i> Tabla
                    </button>
                </div>
            </div>

            <!-- Vista de Tarjetas (por defecto) -->
            <div id="cardsView" class="categories-grid">
                <?php if (isset($categorias) && is_array($categorias) && !empty($categorias)): ?>
                    <?php foreach ($categorias as $cat): ?>
                        <div class="categories-card">
                            <div class="categories-card-header">
                                <div class="categories-card-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="categories-card-title">
                                    <h4><?= htmlspecialchars($cat->nombre ?? 'Sin nombre') ?></h4>
                                    <p><i class="fas fa-hashtag"></i> ID: <?= htmlspecialchars($cat->id_categoria ?? '') ?></p>
                                </div>
                            </div>

                            <div class="categories-card-body">
                                <div class="categories-info-item">
                                    <div class="categories-info-icon">
                                        <i class="fas fa-align-left"></i>
                                    </div>
                                    <div class="categories-info-content">
                                        <div class="categories-info-label">Descripción</div>
                                        <div class="categories-info-value">
                                            <?= !empty($cat->descripcion) ? htmlspecialchars($cat->descripcion) : 'Sin descripción' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="categories-info-item">
                                    <div class="categories-info-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="categories-info-content">
                                        <div class="categories-info-label">Fecha de Creación</div>
                                        <div class="categories-info-value">
                                            <?= !empty($cat->fecha_creacion) ? date('d/m/Y H:i', strtotime($cat->fecha_creacion)) : 'Sin fecha' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="categories-card-footer">
                                <div class="categories-actions">
                                    <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria ?? '') ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Editar categoría">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'coordinador'): ?>
                                    <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria ?? '') ?>" 
                                       class="btn btn-sm btn-danger" 
                                       title="Eliminar categoría"
                                       onclick="return confirmAction('acción general');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 1.2rem;">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No hay categorías registradas</p>
                            <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-success">
                                <i class="fas fa-plus"></i> Crear Primera Categoría
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
                                    <strong>#<?= htmlspecialchars($cat->id_categoria ?? '') ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="category-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($cat->nombre ?? 'Sin nombre') ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-barcode"></i> ID: <?= htmlspecialchars($cat->id_categoria ?? '') ?>
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
                                        <strong><?= !empty($cat->fecha_creacion) ? date('d/m/Y', strtotime($cat->fecha_creacion)) : 'Sin fecha' ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= !empty($cat->fecha_creacion) ? date('H:i', strtotime($cat->fecha_creacion)) : '--:--' ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria ?? '') ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar categoría">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'coordinador'): ?>
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria ?? '') ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Eliminar categoría"
                                           onclick="return confirmAction('acción general');">
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
                                        <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-success">
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
                <?php include __DIR__ . '/../partials/card_mode.php'; ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-toggle">
                        <button id="toggleCategorias">Ver como tarjetas</button>
                    </div>
                </div>
                <div id="cardsCategorias" class="card-container">
                    <?php if (isset($categorias) && is_array($categorias)): ?>
                        <?php foreach ($categorias as $cat): ?>
                            <div class="card-item">
                                <div class="card-title"><?= htmlspecialchars($cat->nombre ?? $cat['nombre'] ?? 'Sin nombre') ?></div>
                                <div class="card-subtitle"><?= htmlspecialchars($cat->descripcion ?? $cat['descripcion'] ?? '') ?></div>
                                <div class="card-actions">
                                    <a class="btn-edit" href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria ?? $cat['id_categoria'] ?? '') ?>&t=<?= time() ?>" data-controller="CategoryController" onclick="console.log('🔍 Editando desde móvil:', this.href); return true;">Editar</a>
                                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'coordinador'): ?>
                                    <a class="btn-delete" href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria ?? $cat['id_categoria'] ?? '') ?>&t=<?= time() ?>" data-controller="CategoryController" onclick="console.log('🗑️ Eliminando desde móvil:', this.href); return confirmDeleteCategory('<?= addslashes($cat->nombre ?? 'Sin nombre') ?>');">Eliminar</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
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
                                        <strong>#<?= htmlspecialchars($cat->id_categoria ?? '') ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($cat->nombre ?? 'Sin nombre') ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-barcode"></i> ID: <?= htmlspecialchars($cat->id_categoria ?? '') ?>
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
                                            <strong><?= !empty($cat->fecha_creacion) ? date('d/m/Y', strtotime($cat->fecha_creacion)) : 'Sin fecha' ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= !empty($cat->fecha_creacion) ? date('H:i', strtotime($cat->fecha_creacion)) : '--:--' ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria ?? '') ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar categoría">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'coordinador'): ?>
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria ?? '') ?>" 
                                               class="btn btn-sm btn-danger" 
                                               title="Eliminar categoría"
                                               onclick="return confirmAction('acción general');">
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
                                            <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn btn-success">
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
        // Toggle entre vista de tarjetas y tabla para categorías
        function toggleCategoriesView(view) {
            const cardsView = document.getElementById('cardsView');
            const tableView = document.getElementById('tableView');
            const btnCards = document.getElementById('btnCards');
            const btnTable = document.getElementById('btnTable');
            
            if (view === 'cards') {
                cardsView.style.display = 'grid';
                tableView.style.display = 'none';
                btnCards.classList.add('active');
                btnTable.classList.remove('active');
                localStorage.setItem('categoriasView', 'cards');
            } else {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                btnCards.classList.remove('active');
                btnTable.classList.add('active');
                localStorage.setItem('categoriasView', 'table');
            }
        }
        
        // Restaurar vista guardada
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('categoriasView') || 'cards';
            toggleCategoriesView(savedView);
        });

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
                
                // Obtener nombre de la categoría de forma segura
                let categoriaNombre = 'esta categoría';
                const tr = this.closest('tr');
                
                if (tr) {
                    // Si está en una tabla
                    const strongElement = tr.querySelector('td:nth-child(2) strong');
                    if (strongElement) {
                        categoriaNombre = strongElement.textContent;
                    }
                } else {
                    // Si está en una tarjeta, buscar el nombre en data-nombre o en la tarjeta
                    if (this.dataset.nombre) {
                        categoriaNombre = this.dataset.nombre;
                    } else {
                        const card = this.closest('.categories-card');
                        if (card) {
                            const cardTitle = card.querySelector('.categories-card-title h4');
                            if (cardTitle) {
                                categoriaNombre = cardTitle.textContent;
                            }
                        }
                    }
                }
                
                if (confirmDeleteCategory(categoriaNombre)) {
                    window.location.href = this.href;
                }
            });
        });

        // Debug: Log de todos los enlaces de edición
        document.addEventListener('DOMContentLoaded', function() {
            // console.log('=== DEBUG: Enlaces de Categorías ===');
            
            // Verificar botones de edición en tarjetas
            const editButtons = document.querySelectorAll('.btn-edit-categoria');
            // console.log(`Encontrados ${editButtons.length} botones de edición en tarjetas`);
            
            editButtons.forEach((btn, index) => {
                const href = btn.getAttribute('href');
                const id = btn.getAttribute('data-id');
                const nombre = btn.getAttribute('data-nombre');
                // console.log(`Botón ${index + 1}: ID=${id}, Nombre="${nombre}", URL="${href}"`);
                
                // Agregar listener para verificar clicks
                btn.addEventListener('click', function(e) {
                    // console.log(`CLICK en botón editar: Redirigiendo a ${this.href}`);
                    // console.log(`Tipo: ${this.getAttribute('data-type')}, ID: ${this.getAttribute('data-id')}`);
                });
            });
            
            // Verificar si hay enlaces que no sean de CategoryController
            const allLinks = document.querySelectorAll('a[href*="Controller"]');
            const wrongLinks = Array.from(allLinks).filter(link => 
                !link.href.includes('CategoryController') && 
                link.closest('.dashboard-container')
            );
            
            if (wrongLinks.length > 0) {
                console.warn('⚠️ ADVERTENCIA: Encontrados enlaces que NO apuntan a CategoryController:');
                wrongLinks.forEach((link, index) => {
                    console.warn(`Link ${index + 1}: ${link.href}`);
                });
            } else {
                // console.log('✅ Todos los enlaces apuntan correctamente a CategoryController');
            }
        });

        // Función para limpiar categorías vacías
        function limpiarCategorias() {
            const opcion = prompt(`¿Qué tipo de limpieza quieres hacer en CATEGORÍAS?\n\nEscribe el número de tu opción:\n\n1 - Solo eliminar categorías SIN PRODUCTOS\n2 - Eliminar TODAS las categorías\n3 - Cancelar`);
            
            if (opcion === '1') {
                if (confirmAction('limpiar registros')) {
                    alert('Eliminando categorías vacías...');
                    window.location.href = '/RMIE/app/controllers/CategoryController.php?accion=clean_empty';
                }
            } else if (opcion === '2') {
                if (confirmAction('eliminar todos')) {
                    alert('Eliminando todas las categorías...');
                    window.location.href = '/RMIE/app/controllers/CategoryController.php?accion=clean_all';
                }
            }
        }
    </script>
</body>
</html>
