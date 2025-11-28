<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /RMIE/index.php');
    exit();
}

// Inicializar variables si no existen
if (!isset($errors)) $errors = [];
if (!isset($alerta)) $alerta = [];
if (!isset($productos)) $productos = [];
if (!isset($proveedores)) $proveedores = [];
?>
<!DOCTYPE html>
<head>
<html lang="es">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alerta - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.0.0 -->
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
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.8);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-header {
            background: var(--warning-gradient);
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
            border-bottom: 2px solid rgba(67, 174, 123, 0.3);
        }

        .section-title i {
            background: var(--warning-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.4rem;
        }

        .alertas-grid {
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
            background: var(--warning-gradient);
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
            border-color: #43e97b;
            box-shadow: 0 0 0 4px rgba(67, 233, 123, 0.1);
            transform: translateY(-2px);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(77, 85, 108, 0.6);
        }

        /* Resumen de alerta */
        .alertas-summary {
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

        .alertas-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--warning-gradient);
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
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--text-secondary);
            font-weight: 600;
        }

        .summary-value {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Botones */
        .btn {
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            transition: var(--transition);
            cursor: pointer;
            min-width: 180px;
            position: relative;
            overflow: hidden;
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

        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .alertas-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Alerta informativa */
        .info-alert {
            background: rgba(255, 193, 7, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 12px;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .info-alert i {
            color: #ffc107;
            margin-right: 0.7rem;
        }

        .alert {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-left: 4px solid #28a745;
        }

        .alert-danger i, .alert-success i {
            margin-right: 0.5rem;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.5);
            color: #856404;
        }

        @media (max-width: 768px) {
            .alertas-grid {
                grid-template-columns: 1fr;
            }
            
            .alertas-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="glass-container animate-fade-in">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="modern-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/AlertController.php">Alertas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Alerta</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Editar Alerta #<?= isset($alerta['id_alertas']) ? htmlspecialchars($alerta['id_alertas']) : '' ?></h1>
            <p>Modifica los parámetros de la alerta de inventario</p>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <!-- Alertas -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Se encontraron los siguientes errores:</strong>
                    <ul style="margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($debug_info)): ?>
                <div class="alert alert-info" style="background: rgba(13, 110, 253, 0.1); border: 1px solid rgba(13, 110, 253, 0.3); color: #0d6efd; padding: 12px; margin-bottom: 1rem; border-radius: 8px;">
                    <i class="fas fa-bug"></i> <strong>DEBUG:</strong>
                    <pre style="margin-top: 8px; margin-bottom: 0; white-space: pre-wrap; word-break: break-all;"><?= htmlspecialchars($debug_info) ?></pre>
                </div>
            <?php endif; ?>

            <form method="POST" action="/RMIE/app/controllers/AlertController.php?action=edit&id=<?= urlencode($alerta['id_alertas']) ?>" id="formAlerta">
                <!-- Grid Principal -->
                <div class="alertas-grid">
                    <!-- Columna Izquierda: Formulario -->
                    <div>
                        <!-- Sección Producto -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-box"></i> Información del Producto
                            </div>
                            
                            <div class="form-group">
                                <label for="id_productos">
                                    <i class="fas fa-cubes"></i> Producto
                                </label>
                                <select class="form-select" id="id_productos" name="id_productos" required>
                                    <option value="">Seleccione un producto</option>
                                    <?php if (isset($productos) && is_array($productos)): ?>
                                        <?php foreach ($productos as $prod): ?>
                                            <option value="<?= htmlspecialchars($prod->id_productos) ?>" 
                                                    data-nombre="<?= htmlspecialchars($prod->nombre) ?>"
                                                    data-stock="<?= htmlspecialchars($prod->stock ?? 0) ?>"
                                                    <?= (isset($alerta['id_productos']) && $alerta['id_productos'] == $prod->id_productos) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($prod->nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="cantidad_minima">
                                    <i class="fas fa-exclamation-triangle"></i> Cantidad mínima para alerta
                                </label>
                                <input type="number" 
                                       class="form-control"
                                       id="cantidad_minima" 
                                       name="cantidad_minima" 
                                       required 
                                       min="1"
                                       value="<?= isset($alerta['cantidad_minima']) ? htmlspecialchars($alerta['cantidad_minima']) : '' ?>"
                                       placeholder="Ingrese la cantidad mínima">
                                <small class="text-muted">La alerta se activará cuando el stock sea menor o igual a este valor</small>
                            </div>
                        </div>

                        <!-- Sección Fecha y Cliente -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-calendar-alt"></i> Fecha y Asignación
                            </div>
                            
                            <div class="form-group">
                                <label for="fecha_caducidad">
                                    <i class="fas fa-calendar"></i> Fecha de caducidad
                                </label>
                                <input type="date" 
                                       class="form-control"
                                       id="fecha_caducidad" 
                                       name="fecha_caducidad" 
                                       required
                                       value="<?= isset($alerta['fecha_caducidad']) ? htmlspecialchars($alerta['fecha_caducidad']) : '' ?>">
                                <small class="text-muted">Fecha límite para considerar el producto</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="id_proveedores">
                                    <i class="fas fa-truck"></i> Proveedor
                                </label>
                                <select class="form-select" id="id_proveedores" name="id_proveedores" required>
                                    <option value="">Seleccione un proveedor</option>
                                    <?php if (isset($proveedores) && !empty($proveedores) && is_array($proveedores)): ?>
                                        <?php foreach ($proveedores as $prov): ?>
                                            <option value="<?= htmlspecialchars($prov->id_proveedores) ?>" 
                                                    <?= (isset($alerta['id_proveedores']) && $alerta['id_proveedores'] == $prov->id_proveedores) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($prov->nombre_distribuidor) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Proveedor al que se notificará sobre esta alerta</small>
                            </div>

                            <div class="form-group">
                                <label for="estado_alerta">
                                    <i class="fas fa-toggle-on"></i> Estado de la Alerta
                                </label>
                                <select class="form-select" id="estado_alerta" name="estado_alerta" required>
                                    <option value="Activo" <?= (isset($alerta['estado']) && $alerta['estado'] === 'Activo') || !isset($alerta['estado']) ? 'selected' : '' ?>>
                                        Activo
                                    </option>
                                    <option value="Vencida" <?= (isset($alerta['estado']) && $alerta['estado'] === 'Vencida') ? 'selected' : '' ?>>
                                        Vencida
                                    </option>
                                    <option value="Crítica" <?= (isset($alerta['estado']) && $alerta['estado'] === 'Crítica') ? 'selected' : '' ?>>
                                        Crítica
                                    </option>
                                    <option value="Próxima" <?= (isset($alerta['estado']) && $alerta['estado'] === 'Próxima') ? 'selected' : '' ?>>
                                        Próxima
                                    </option>
                                    <option value="Normal" <?= (isset($alerta['estado']) && $alerta['estado'] === 'Normal') ? 'selected' : '' ?>>
                                        Normal
                                    </option>
                                </select>
                                <small class="text-muted">Seleccione el estado actual de la alerta</small>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Resumen -->
                    <div>
                        <div class="alertas-summary">
                            <div class="summary-header">
                                <h5><i class="fas fa-bell"></i> Resumen de la Alerta</h5>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-box"></i> Producto:</span>
                                <span class="summary-value" id="producto-nombre">
                                    <?php 
                                    if (isset($alerta['id_productos']) && isset($productos)) {
                                        foreach ($productos as $p) {
                                            if ($p->id_productos == $alerta['id_productos']) {
                                                echo htmlspecialchars($p->nombre);
                                                break;
                                            }
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-warehouse"></i> Stock actual:</span>
                                <span class="summary-value" id="stock-actual">-</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-exclamation-circle"></i> Cantidad mínima:</span>
                                <span class="summary-value" id="cantidad-minima-mostrar">
                                    <?= isset($alerta['cantidad_minima']) ? htmlspecialchars($alerta['cantidad_minima']) : '0' ?>
                                </span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-calendar-alt"></i> Fecha caducidad:</span>
                                <span class="summary-value" id="fecha-mostrar">
                                    <?= isset($alerta['fecha_caducidad']) ? date('d/m/Y', strtotime($alerta['fecha_caducidad'])) : '-' ?>
                                </span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-truck"></i> Proveedor:</span>
                                <span class="summary-value" id="cliente-nombre">
                                    <?php 
                                    if (isset($alerta['id_proveedores']) && isset($proveedores)) {
                                        foreach ($proveedores as $p) {
                                            if ($p->id_proveedores == $alerta['id_proveedores']) {
                                                echo htmlspecialchars($p->nombre_distribuidor);
                                                break;
                                            }
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <!-- Info de la alerta -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información importante:</strong>
                            <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                                <li>La alerta se activa cuando el stock es menor o igual a la cantidad mínima</li>
                                <li>La fecha de caducidad ayuda a priorizar productos próximos a vencer</li>
                                <li>El cliente asignado recibirá notificaciones sobre esta alerta</li>
                            </ul>
                        </div>

                        <div class="info-alert" style="margin-top: 1rem; border-left-color: #17a2b8; background: rgba(23, 162, 184, 0.1); border: 1px solid rgba(23, 162, 184, 0.3);">
                            <i class="fas fa-chart-line" style="color: #17a2b8;"></i>
                            <strong>Estado de la alerta:</strong>
                            <div style="margin-top: 0.5rem;">
                                <span class="status-badge" id="estado-badge">
                                    <i class="fas fa-bell"></i>
                                    <?php 
                                    $estado_actual = isset($alerta['estado']) ? htmlspecialchars($alerta['estado']) : 'Activo';
                                    $icono_estado = '';
                                    $clase_estado = '';
                                    
                                    switch ($estado_actual) {
                                        case 'Vencida':
                                            $icono_estado = '<i class="fas fa-times-circle"></i>';
                                            $clase_estado = 'text-danger';
                                            break;
                                        case 'Crítica':
                                            $icono_estado = '<i class="fas fa-exclamation-triangle"></i>';
                                            $clase_estado = 'text-warning';
                                            break;
                                        case 'Próxima':
                                            $icono_estado = '<i class="fas fa-hourglass-end"></i>';
                                            $clase_estado = 'text-warning';
                                            break;
                                        case 'Normal':
                                            $icono_estado = '<i class="fas fa-check-circle"></i>';
                                            $clase_estado = 'text-success';
                                            break;
                                        default:
                                            $icono_estado = '<i class="fas fa-bell"></i>';
                                            $clase_estado = 'text-info';
                                    }
                                    echo $icono_estado . ' ' . $estado_actual;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="alertas-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="/RMIE/app/controllers/AlertController.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productoSelect = document.getElementById('id_productos');
        const cantidadInput = document.getElementById('cantidad_minima');
        const fechaInput = document.getElementById('fecha_caducidad');
        const proveedorSelect = document.getElementById('id_proveedores');
        const form = document.getElementById('formAlerta');
        
        function actualizarProducto() {
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            
            if (selectedOption.value) {
                const nombreProducto = selectedOption.dataset.nombre || selectedOption.text;
                const stockActual = selectedOption.dataset.stock || 0;
                
                document.getElementById('producto-nombre').textContent = nombreProducto;
                document.getElementById('stock-actual').textContent = stockActual + ' unidades';
            } else {
                document.getElementById('producto-nombre').textContent = '-';
                document.getElementById('stock-actual').textContent = '-';
            }
        }
        
        function actualizarCantidadMinima() {
            const cantidad = cantidadInput.value || '0';
            document.getElementById('cantidad-minima-mostrar').textContent = cantidad;
        }
        
        function actualizarFecha() {
            if (fechaInput.value) {
                const fecha = new Date(fechaInput.value + 'T00:00:00');
                const dia = fecha.getDate().toString().padStart(2, '0');
                const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
                const anio = fecha.getFullYear();
                document.getElementById('fecha-mostrar').textContent = `${dia}/${mes}/${anio}`;
            } else {
                document.getElementById('fecha-mostrar').textContent = '-';
            }
        }
        
        function actualizarProveedor() {
            const selectedOption = proveedorSelect.options[proveedorSelect.selectedIndex];
            if (selectedOption.value) {
                document.getElementById('cliente-nombre').textContent = selectedOption.text;
            } else {
                document.getElementById('cliente-nombre').textContent = '-';
            }
        }
        
        productoSelect.addEventListener('change', actualizarProducto);
        cantidadInput.addEventListener('input', actualizarCantidadMinima);
        fechaInput.addEventListener('change', actualizarFecha);
        proveedorSelect.addEventListener('change', actualizarProveedor);
        
        form.addEventListener('submit', function(e) {
            if (!productoSelect.value) {
                e.preventDefault();
                alert('Debe seleccionar un producto');
                return;
            }
            
            const cantidad = parseInt(cantidadInput.value || 0);
            if (cantidad <= 0) {
                e.preventDefault();
                alert('La cantidad mínima debe ser mayor a 0');
                return;
            }
            
            if (!fechaInput.value) {
                e.preventDefault();
                alert('Debe seleccionar una fecha de caducidad');
                return;
            }
            
            if (!proveedorSelect.value) {
                e.preventDefault();
                alert('Debe seleccionar un proveedor');
                return;
            }
        });
        
        // Actualizar badge del estado cuando cambia el select
        const estadoSelect = document.getElementById('estado_alerta');
        if (estadoSelect) {
            estadoSelect.addEventListener('change', function() {
                const estadoBadge = document.getElementById('estado-badge');
                const estado = this.value;
                let icono = '';
                let clase = '';
                
                switch (estado) {
                    case 'Vencida':
                        icono = '<i class="fas fa-times-circle"></i>';
                        clase = 'text-danger';
                        break;
                    case 'Crítica':
                        icono = '<i class="fas fa-exclamation-triangle"></i>';
                        clase = 'text-warning';
                        break;
                    case 'Próxima':
                        icono = '<i class="fas fa-hourglass-end"></i>';
                        clase = 'text-warning';
                        break;
                    case 'Normal':
                        icono = '<i class="fas fa-check-circle"></i>';
                        clase = 'text-success';
                        break;
                    default:
                        icono = '<i class="fas fa-bell"></i>';
                        clase = 'text-info';
                }
                
                estadoBadge.innerHTML = icono + ' ' + estado;
                estadoBadge.className = 'status-badge ' + clase;
            });
        }
        
        actualizarProducto();
        
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-danger, .alert-success');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
    </script>
</body>
</html>
