<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ruta - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --shadow-light: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --shadow-medium: 0 15px 35px 0 rgba(31, 38, 135, 0.2);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: var(--border-radius);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-medium);
            transition: var(--transition);
            max-width: 1200px;
            margin: 0 auto;
        }

        .glass-container:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        /* Breadcrumb moderno */
        .modern-breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .breadcrumb {
            margin: 0;
            background: none;
        }

        .breadcrumb-item a {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #f0f0f0;
            transform: translateX(2px);
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.8);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .form-header h1 {
            position: relative;
            z-index: 1;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            position: relative;
            z-index: 1;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-content {
            padding: 2rem;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(102, 126, 234, 0.3);
        }

        .section-title i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.4rem;
        }

        .rutas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .form-group {
            margin-bottom: 1.8rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .form-group label i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: var(--transition);
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(77, 85, 108, 0.6);
        }

        .form-control.is-valid {
            border-color: #28a745;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-text {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .char-counter {
            text-align: right;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        /* Resumen de información actual */
        .rutas-summary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            height: fit-content;
        }

        .rutas-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--success-gradient);
            border-radius: 2px;
        }

        .summary-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .summary-header h5 {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item:hover {
            transform: translateX(5px);
            padding-left: 10px;
            background: rgba(255, 255, 255, 0.05);
        }

        .summary-label {
            color: var(--text-secondary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-value {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Vista previa de cambios */
        .changes-preview {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .change-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .old-value {
            color: #dc3545;
            font-size: 0.9rem;
        }

        .new-value {
            color: #28a745;
            font-size: 0.9rem;
        }

        /* Botones mejorados */
        .btn {
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
            min-width: 180px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
        }

        .btn-success:hover {
            box-shadow: 0 12px 30px rgba(79, 172, 254, 0.6);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .btn-secondary:hover {
            box-shadow: 0 12px 30px rgba(108, 117, 125, 0.6);
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
            box-shadow: 0 6px 20px rgba(67, 233, 123, 0.4);
        }

        .btn-warning:hover {
            box-shadow: 0 12px 30px rgba(67, 233, 123, 0.6);
        }

        .rutas-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Alerta informativa */
        .info-alert {
            background: rgba(23, 162, 184, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(23, 162, 184, 0.3);
            border-radius: 12px;
            border-left: 4px solid #17a2b8;
            color: var(--text-primary);
            padding: 1.5rem;
            margin-top: 1.5rem;
            transition: var(--transition);
        }

        .info-alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23, 162, 184, 0.2);
        }

        .info-alert i {
            color: #17a2b8;
            margin-right: 0.7rem;
            font-size: 1.1rem;
        }

        .info-alert strong {
            color: var(--text-primary);
        }

        .info-alert ul {
            margin-left: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 0;
        }

        .info-alert li {
            margin-bottom: 0.5rem;
        }

        /* Alertas de Bootstrap personalizadas */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .alert-info {
            background: rgba(23, 162, 184, 0.15);
            border-left: 4px solid #17a2b8;
            color: var(--text-primary);
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.15);
            border-left: 4px solid #ffc107;
            color: var(--text-primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .rutas-grid {
                grid-template-columns: 1fr;
            }

            .rutas-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                min-width: unset;
            }

            .form-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <?php
    // Capturar mensajes de sesión si existen
    $error_message = $_SESSION['error'] ?? '';
    $success_message = $_SESSION['success'] ?? '';
    
    // Limpiar mensajes después de capturarlos
    unset($_SESSION['error'], $_SESSION['success']);
    ?>

    <!-- Breadcrumb moderno -->
    <nav class="modern-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/RMIE/app/views/dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="/RMIE/app/controllers/RouteController.php?accion=index">
                    <i class="fas fa-route"></i> Rutas
                </a>
            </li>
            <li class="breadcrumb-item active">
                <i class="fas fa-edit"></i> Editar Ruta #<?= $route['id_ruta'] ?? 'N/A' ?>
            </li>
        </ol>
    </nav>

    <!-- Contenedor principal con efecto glass -->
    <div class="glass-container">
        <!-- Header del formulario -->
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Editar Ruta de Entrega</h1>
            <p>Modifica la información de la ruta y visualiza los cambios en tiempo real</p>
        </div>

        <!-- Contenido del formulario -->
        <div class="form-content">
            <!-- Mensajes de éxito o error -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-check-circle"></i>
                    <strong>¡Éxito!</strong> <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($route) && $route): ?>
                <!-- Grid principal: Formulario y Resumen -->
                <div class="rutas-grid">
                    <!-- Columna izquierda: Formulario -->
                    <div>
                        <form action="/RMIE/app/controllers/RouteController.php?accion=edit&id=<?= $route['id_ruta'] ?>" method="POST" id="editRouteForm">

                                                        <div class="form-group">
                                                            <label for="estado">
                                                                <i class="fas fa-toggle-on"></i> Estado de la Ruta *
                                                            </label>
                                                            <select name="estado" id="estado" class="form-control" required>
                                                                <option value="activa" <?= (($route['estado'] ?? 'activa') === 'activa') ? 'selected' : '' ?>>Activa</option>
                                                                <option value="pendiente" <?= (($route['estado'] ?? 'activa') === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                                                            </select>
                                                            <small class="form-text">
                                                                <i class="fas fa-info-circle"></i>
                                                                Selecciona el estado de la ruta
                                                            </small>
                                                        </div>
                            
                            <!-- Sección: Información de Ubicación -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-map-marker-alt"></i> Información de Ubicación
                                </h3>
                                
                                <div class="form-group">
                                    <label for="direccion">
                                        <i class="fas fa-map-marker-alt"></i> Dirección Completa *
                                    </label>
                                    <textarea 
                                        name="direccion" 
                                        id="direccion" 
                                        required
                                        maxlength="200"
                                        placeholder="Ej: Calle 123 # 45-67, Barrio Centro, Bogotá"
                                        class="form-control"
                                        rows="3"><?= htmlspecialchars($route['direccion']) ?></textarea>
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Incluye calle, número, barrio y ciudad (mínimo 5 caracteres)
                                    </small>
                                    <div class="char-counter">
                                        <span id="direccion-counter"><?= strlen($route['direccion']) ?></span>/200 caracteres
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Información del Local -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-store"></i> Información del Local
                                </h3>
                                
                                <div class="form-group">
                                    <label for="nombre_local">
                                        <i class="fas fa-store"></i> Nombre del Local *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="nombre_local" 
                                        id="nombre_local" 
                                        required
                                        minlength="2"
                                        maxlength="100"
                                        value="<?= htmlspecialchars($route['nombre_local']) ?>"
                                        placeholder="Ej: Tienda El Éxito Centro"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Nombre comercial del establecimiento de destino
                                    </small>
                                </div>
                            </div>

                            <!-- Sección: Información del Cliente -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-user"></i> Información del Cliente
                                </h3>
                                
                                <div class="form-group">
                                    <label for="nombre_cliente">
                                        <i class="fas fa-user"></i> Nombre del Cliente *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="nombre_cliente" 
                                        id="nombre_cliente" 
                                        required
                                        minlength="2"
                                        maxlength="100"
                                        value="<?= htmlspecialchars($route['nombre_cliente']) ?>"
                                        placeholder="Ej: Juan Pérez García"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Nombre completo de la persona de contacto
                                    </small>
                                </div>
                            </div>

                            <!-- Sección: Referencias del Sistema -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-link"></i> Referencias del Sistema
                                </h3>
                                
                                <div class="form-group">
                                    <label for="id_clientes">
                                        <i class="fas fa-user-tag"></i> ID del Cliente *
                                    </label>
                                    <input 
                                        type="number" 
                                        name="id_clientes" 
                                        id="id_clientes" 
                                        required
                                        min="1"
                                        value="<?= htmlspecialchars($route['id_clientes']) ?>"
                                        placeholder="Ej: 123"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Identificador único del cliente en el sistema
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="id_ventas">
                                        <i class="fas fa-shopping-cart"></i> ID de la Venta *
                                    </label>
                                    <input 
                                        type="number" 
                                        name="id_ventas" 
                                        id="id_ventas" 
                                        required
                                        min="1"
                                        value="<?= htmlspecialchars($route['id_ventas']) ?>"
                                        placeholder="Ej: 456"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Número de venta asociada a esta ruta de entrega
                                    </small>
                                </div>

                                <!-- Estado de la Ruta -->
                                <div class="form-group">
                                    <label for="estado">
                                        <i class="fas fa-traffic-light"></i> Estado de la Ruta *
                                    </label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        <option value="activo" <?= (isset($route['estado']) && $route['estado'] === 'activo') ? 'selected' : '' ?>>Activo</option>
                                        <option value="pendiente" <?= (isset($route['estado']) && $route['estado'] === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                                    </select>
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Selecciona si la ruta estará activa o pendiente
                                    </small>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="rutas-buttons">
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    <i class="fas fa-save"></i> Actualizar Ruta
                                </button>
                                <button type="button" class="btn btn-warning" id="resetBtn">
                                    <i class="fas fa-undo"></i> Restaurar
                                </button>
                                <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Columna derecha: Resumen e información -->
                    <div>
                        <!-- Resumen de información actual -->
                        <div class="rutas-summary">
                            <div class="summary-header">
                                <h5><i class="fas fa-info-circle"></i> Información Actual</h5>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-hashtag"></i> ID de Ruta
                                </span>
                                <span class="summary-value">#<?= htmlspecialchars($route['id_ruta']) ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-store"></i> Local
                                </span>
                                <span class="summary-value"><?= htmlspecialchars($route['nombre_local']) ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-user"></i> Cliente
                                </span>
                                <span class="summary-value"><?= htmlspecialchars($route['nombre_cliente']) ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-user-tag"></i> ID Cliente
                                </span>
                                <span class="summary-value">#<?= htmlspecialchars($route['id_clientes']) ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-shopping-cart"></i> ID Venta
                                </span>
                                <span class="summary-value">#<?= htmlspecialchars($route['id_ventas']) ?></span>
                            </div>
                        </div>

                        <!-- Vista previa de cambios -->
                        <div class="form-section" style="margin-top: 2rem;">
                            <h3 class="section-title">
                                <i class="fas fa-eye"></i> Vista Previa de Cambios
                            </h3>
                            <div class="changes-preview" id="changesPreview">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Modifica los campos para ver los cambios aquí.
                                </div>
                            </div>
                        </div>

                        <!-- Información importante -->
                        <div class="info-alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Precauciones Importantes:</strong>
                            <ul>
                                <li>Verifica que la dirección sea correcta y completa</li>
                                <li>Los cambios en IDs pueden afectar relaciones con otros registros</li>
                                <li>Si la ruta está en proceso, coordina los cambios con el equipo</li>
                                <li>Los cambios se aplicarán inmediatamente al confirmar</li>
                            </ul>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Error: Ruta no encontrada -->
                <div class="alert alert-warning" style="text-align: center; padding: 2rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    <h3>Ruta No Encontrada</h3>
                    <p>La ruta que intentas editar no existe o ha sido eliminada.</p>
                    <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn btn-secondary" style="margin-top: 1rem;">
                        <i class="fas fa-arrow-left"></i> Volver a Rutas
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($route) && $route): ?>
                // Datos originales para comparación
                const originalData = {
                    direccion: <?= json_encode($route['direccion']) ?>,
                    nombre_local: <?= json_encode($route['nombre_local']) ?>,
                    nombre_cliente: <?= json_encode($route['nombre_cliente']) ?>,
                    id_clientes: <?= json_encode($route['id_clientes']) ?>,
                    id_ventas: <?= json_encode($route['id_ventas']) ?>
                };

                // Referencias a elementos del formulario
                const form = document.getElementById('editRouteForm');
                const direccionField = document.getElementById('direccion');
                const localField = document.getElementById('nombre_local');
                const clienteField = document.getElementById('nombre_cliente');
                const idClienteField = document.getElementById('id_clientes');
                const idVentaField = document.getElementById('id_ventas');
                const submitBtn = document.getElementById('submitBtn');

                // Contador de caracteres dinámico
                direccionField.addEventListener('input', function() {
                    const counter = document.getElementById('direccion-counter');
                    const length = this.value.length;
                    counter.textContent = length;
                    
                    if (length > 180) {
                        counter.style.color = '#dc3545';
                        counter.style.fontWeight = 'bold';
                    } else if (length > 150) {
                        counter.style.color = '#ffc107';
                        counter.style.fontWeight = '600';
                    } else {
                        counter.style.color = '#28a745';
                        counter.style.fontWeight = '500';
                    }
                });

                // Validación en tiempo real
                function validateField(field, minLength, maxLength) {
                    const value = field.value.trim();
                    field.classList.remove('is-valid', 'is-invalid');
                    
                    if (value.length < minLength || value.length > maxLength) {
                        field.classList.add('is-invalid');
                        return false;
                    } else {
                        field.classList.add('is-valid');
                        return true;
                    }
                }

                direccionField.addEventListener('input', () => {
                    validateField(direccionField, 5, 200);
                    showChanges();
                });

                localField.addEventListener('input', () => {
                    validateField(localField, 2, 100);
                    showChanges();
                });

                clienteField.addEventListener('input', () => {
                    validateField(clienteField, 2, 100);
                    showChanges();
                });

                idClienteField.addEventListener('input', () => {
                    const value = parseInt(idClienteField.value);
                    idClienteField.classList.remove('is-valid', 'is-invalid');
                    
                    if (isNaN(value) || value <= 0) {
                        idClienteField.classList.add('is-invalid');
                    } else {
                        idClienteField.classList.add('is-valid');
                    }
                    showChanges();
                });

                idVentaField.addEventListener('input', () => {
                    const value = parseInt(idVentaField.value);
                    idVentaField.classList.remove('is-valid', 'is-invalid');
                    
                    if (isNaN(value) || value <= 0) {
                        idVentaField.classList.add('is-invalid');
                    } else {
                        idVentaField.classList.add('is-valid');
                    }
                    showChanges();
                });

                // Vista previa de cambios
                function showChanges() {
                    const changes = [];
                    const changesContainer = document.getElementById('changesPreview');

                    const fieldChanges = [
                        { field: 'Dirección', current: direccionField.value, original: originalData.direccion, icon: 'fas fa-map-marker-alt' },
                        { field: 'Local', current: localField.value, original: originalData.nombre_local, icon: 'fas fa-store' },
                        { field: 'Cliente', current: clienteField.value, original: originalData.nombre_cliente, icon: 'fas fa-user' },
                        { field: 'ID Cliente', current: idClienteField.value, original: originalData.id_clientes, icon: 'fas fa-user-tag' },
                        { field: 'ID Venta', current: idVentaField.value, original: originalData.id_ventas, icon: 'fas fa-shopping-cart' }
                    ];

                    fieldChanges.forEach(item => {
                        if (item.current != item.original) {
                            changes.push(item);
                        }
                    });

                    if (changes.length === 0) {
                        changesContainer.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Sin cambios detectados.</strong><br>
                                Modifica los campos para ver una vista previa.
                            </div>
                        `;
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.6';
                    } else {
                        let changesHtml = `
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Cambios detectados (${changes.length}):</strong>
                            </div>
                        `;

                        changes.forEach((change, index) => {
                            changesHtml += `
                                <div class="change-item" style="animation-delay: ${index * 0.1}s;">
                                    <i class="${change.icon}" style="color: #667eea;"></i>
                                    <strong>${change.field}:</strong><br>
                                    <div style="margin-left: 20px; margin-top: 5px;">
                                        <span class="old-value">
                                            <i class="fas fa-arrow-right"></i> 
                                            Actual: "${change.original}"
                                        </span><br>
                                        <span class="new-value">
                                            <i class="fas fa-arrow-right"></i> 
                                            Nuevo: "${change.current}"
                                        </span>
                                    </div>
                                </div>
                            `;
                        });

                        changesContainer.innerHTML = changesHtml;
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                    }
                }

                // Botón de restaurar
                document.getElementById('resetBtn').addEventListener('click', function() {
                    if (confirm('¿Deseas restaurar todos los valores originales?')) {
                        direccionField.value = originalData.direccion;
                        localField.value = originalData.nombre_local;
                        clienteField.value = originalData.nombre_cliente;
                        idClienteField.value = originalData.id_clientes;
                        idVentaField.value = originalData.id_ventas;
                        
                        [direccionField, localField, clienteField, idClienteField, idVentaField].forEach(field => {
                            field.classList.remove('is-valid', 'is-invalid');
                        });
                        
                        showChanges();
                    }
                });

                // Validación del formulario
                form.addEventListener('submit', function(e) {
                    const direccion = direccionField.value.trim();
                    const local = localField.value.trim();
                    const cliente = clienteField.value.trim();
                    const idCliente = parseInt(idClienteField.value);
                    const idVenta = parseInt(idVentaField.value);

                    if (direccion.length < 5) {
                        alert('La dirección debe tener al menos 5 caracteres');
                        e.preventDefault();
                        return false;
                    }
                    
                    if (local.length < 2) {
                        alert('El nombre del local debe tener al menos 2 caracteres');
                        e.preventDefault();
                        return false;
                    }
                    
                    if (cliente.length < 2) {
                        alert('El nombre del cliente debe tener al menos 2 caracteres');
                        e.preventDefault();
                        return false;
                    }

                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
                    submitBtn.disabled = true;
                    
                    return true;
                });

                // Inicialización
                showChanges();
                
                setTimeout(() => {
                    validateField(direccionField, 5, 200);
                    validateField(localField, 2, 100);
                    validateField(clienteField, 2, 100);
                }, 500);
                
            <?php endif; ?>
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
