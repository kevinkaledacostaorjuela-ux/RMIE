<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta - RMIE</title>
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

        .ventas-grid {
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

        .form-control:read-only {
            background: rgba(200, 200, 200, 0.3);
            cursor: not-allowed;
        }

        /* Resumen de venta */
        .ventas-summary {
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

        .ventas-summary::before {
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
            font-weight: 700;
            font-size: 1.2rem;
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem -1rem -1rem -1rem;
            padding: 1rem 2rem;
            border-radius: 0 0 12px 12px;
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
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        }

        .ventas-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Info histórica */
        .info-alert {
            background: rgba(23, 162, 184, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(23, 162, 184, 0.3);
            border-radius: 12px;
            border-left: 4px solid #17a2b8;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .info-alert i {
            color: #17a2b8;
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

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-left: 4px solid #ffc107;
        }

        /* Badge de estado */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .status-pendiente {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.5);
            color: #856404;
        }

        .status-procesando {
            background: rgba(23, 162, 184, 0.2);
            border: 1px solid rgba(23, 162, 184, 0.5);
            color: #0c5460;
        }

        .status-completada {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
            color: #155724;
        }

        .status-cancelada {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #721c24;
        }

        .current-status {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .current-status .label {
            font-weight: 600;
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .ventas-grid {
                grid-template-columns: 1fr;
            }
            
            .ventas-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="glass-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="modern-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/SaleController.php?accion=index">Ventas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Venta</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Editar Venta #<?= htmlspecialchars($venta->id_ventas) ?></h1>
            <p>Modifica los datos de la venta</p>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <!-- Alertas -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/RMIE/app/controllers/SaleController.php?accion=edit&id=<?= $venta->id_ventas ?>" id="formVenta">
                <!-- Grid Principal -->
                <div class="ventas-grid">
                    <!-- Columna Izquierda: Formulario -->
                    <div>
                        <!-- Sección Cliente -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-user"></i> Información del Cliente
                            </div>
                            
                            <div class="form-group">
                                <label for="id_clientes">
                                    <i class="fas fa-users"></i> Cliente
                                </label>
                                <select class="form-select" id="id_clientes" name="id_clientes" required>
                                    <option value="">Seleccione un cliente</option>
                                    <?php if (isset($clientes) && is_array($clientes)): ?>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?= htmlspecialchars($cliente->id_clientes) ?>" 
                                                    <?= $venta->id_clientes == $cliente->id_clientes ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cliente->nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="nombre">
                                    <i class="fas fa-tag"></i> Nombre de la venta
                                </label>
                                <input type="text" 
                                       class="form-control"
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?= htmlspecialchars($venta->nombre) ?>"
                                       placeholder="Descripción de la venta"
                                       maxlength="45">
                            </div>
                            
                            <div class="form-group">
                                <label for="direccion">
                                    <i class="fas fa-map-marker-alt"></i> Dirección de entrega
                                </label>
                                <input type="text" 
                                       class="form-control"
                                       id="direccion" 
                                       name="direccion" 
                                       value="<?= htmlspecialchars($venta->direccion) ?>"
                                       placeholder="Dirección de entrega"
                                       maxlength="45">
                            </div>
                        </div>

                        <!-- Sección Producto -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-box"></i> Información del Producto
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-cubes"></i> Productos *
                                </label>
                                <div class="productos-info-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; color: white;">
                                    <i class="fas fa-info-circle"></i> Selecciona los productos para esta venta
                                </div>
                                <div class="productos-checkbox-container" style="max-height: 300px; overflow-y: auto; border: 2px solid #667eea; border-radius: 10px; padding: 1rem; background: white;">
                                    <?php if (isset($productos) && is_array($productos)): ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <div class="checkbox-item" style="margin-bottom: 0.8rem;">
                                                <input type="checkbox" 
                                                       class="checkbox-input" 
                                                       id="producto_<?= $producto->id_productos ?>" 
                                                       name="id_productos[]" 
                                                       value="<?= htmlspecialchars($producto->id_productos) ?>"
                                                       data-precio="<?= htmlspecialchars($producto->precio_unitario) ?>"
                                                       data-stock="<?= htmlspecialchars($producto->stock) ?>"
                                                       <?= in_array($producto->id_productos, $productos_asignados_ids ?? []) ? 'checked' : '' ?>
                                                       style="display: none;">
                                                <label for="producto_<?= $producto->id_productos ?>" 
                                                       class="checkbox-label-edit"
                                                       style="display: flex; align-items: center; padding: 0.8rem; border: 2px solid #e0e0e0; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; background: white;">
                                                    <span class="checkbox-custom-edit" 
                                                          style="width: 24px; height: 24px; border: 2px solid #667eea; border-radius: 4px; margin-right: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; flex-shrink: 0;">
                                                        <i class="fas fa-check" style="color: white; font-size: 14px; display: none;"></i>
                                                    </span>
                                                    <span style="flex-grow: 1; font-weight: 500; color: #333;">
                                                        <?= htmlspecialchars($producto->nombre) ?>
                                                        <small style="display: block; color: #666; font-size: 0.85em;">
                                                            Stock: <?= htmlspecialchars($producto->stock) ?> | 
                                                            Precio: $<?= number_format($producto->precio_unitario, 2) ?>
                                                        </small>
                                                    </span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted" style="display: block; margin-top: 0.5rem;">
                                    <span id="productos-seleccionados-count">
                                        <?= count($productos_asignados_ids ?? []) ?>
                                    </span> producto(s) seleccionado(s)
                                </small>
                            </div>
                            
                            <div class="form-group" style="display: none;">
                                <label for="cantidad">
                                    <i class="fas fa-sort-numeric-up"></i> Cantidad
                                </label>
                                <input type="number" 
                                       class="form-control"
                                       id="cantidad" 
                                       name="cantidad" 
                                       min="1"
                                       value="<?= htmlspecialchars($venta->cantidad) ?>"
                                       placeholder="Cantidad">
                            </div>
                            
                            <div class="form-group">
                                <label for="fecha_venta">
                                    <i class="fas fa-calendar"></i> Fecha de venta
                                </label>
                                <input type="date" 
                                       class="form-control"
                                       id="fecha_venta" 
                                       name="fecha_venta" 
                                       required
                                       value="<?= htmlspecialchars($venta->fecha_venta) ?>">
                            </div>
                        </div>

                        <!-- Sección Cálculos -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-calculator"></i> Cálculos y Estado
                            </div>
                            
                            <div class="form-group">
                                <label for="precio_unitario">
                                    <i class="fas fa-dollar-sign"></i> Precio unitario
                                </label>
                                <input type="number" 
                                       class="form-control"
                                       id="precio_unitario" 
                                       name="precio_unitario" 
                                       required 
                                       step="0.01"
                                       min="0"
                                       value="<?= htmlspecialchars($venta->precio_unitario) ?>">
                                <small class="text-muted">Original: $<?= number_format($venta->precio_unitario, 2) ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="total">
                                    <i class="fas fa-money-bill"></i> Total
                                </label>
                                <input type="number" 
                                       class="form-control"
                                       id="total" 
                                       name="total" 
                                       required 
                                       step="0.01"
                                       value="<?= htmlspecialchars($venta->total) ?>"
                                       readonly>
                                <small class="text-muted">Original: $<?= number_format($venta->total, 2) ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="estado">
                                    <i class="fas fa-info-circle"></i> Estado
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="pendiente" <?= $venta->estado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="procesando" <?= $venta->estado === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                                    <option value="completada" <?= $venta->estado === 'completada' ? 'selected' : '' ?>>Completada</option>
                                    <option value="cancelada" <?= $venta->estado === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="num_doc">
                                    <i class="fas fa-user-tie"></i> Usuario responsable
                                </label>
                                <select class="form-select" id="num_doc" name="num_doc" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php if (isset($usuarios) && is_array($usuarios)): ?>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= htmlspecialchars($usuario->num_doc) ?>" 
                                                    <?= $venta->num_doc == $usuario->num_doc ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Resumen -->
                    <div>
                        <div class="ventas-summary">
                            <div class="summary-header">
                                <h5><i class="fas fa-chart-line"></i> Resumen de la Venta</h5>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">Producto:</span>
                                <span class="summary-value" id="producto-nombre">-</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">Stock disponible:</span>
                                <span class="summary-value" id="stock-disponible">-</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">Precio unitario:</span>
                                <span class="summary-value" id="precio-mostrar">$0.00</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">Cantidad:</span>
                                <span class="summary-value" id="cantidad-mostrar">0</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">Total a pagar:</span>
                                <span class="summary-value" id="total-mostrar">$0.00</span>
                            </div>
                        </div>

                        <!-- Estado Actual -->
                        <div class="info-alert">
                            <div class="current-status">
                                <span class="label">Estado actual:</span>
                                <span class="badge-status status-<?= strtolower($venta->estado) ?>">
                                    <?php
                                    $estados = [
                                        'pendiente' => ['text' => 'Pendiente', 'icon' => 'fas fa-clock'],
                                        'procesando' => ['text' => 'Procesando', 'icon' => 'fas fa-spinner'],
                                        'completada' => ['text' => 'Completada', 'icon' => 'fas fa-check-circle'],
                                        'cancelada' => ['text' => 'Cancelada', 'icon' => 'fas fa-times-circle']
                                    ];
                                    $estadoInfo = $estados[$venta->estado] ?? ['text' => $venta->estado, 'icon' => 'fas fa-question'];
                                    ?>
                                    <i class="<?= $estadoInfo['icon'] ?>"></i>
                                    <?= $estadoInfo['text'] ?>
                                </span>
                            </div>
                            <div style="margin-top: 1rem;">
                                <strong><i class="fas fa-history"></i> Información Histórica:</strong>
                                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                                    <li>Fecha de Venta: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($venta->fecha_venta))) ?></li>
                                    <li>ID: #<?= htmlspecialchars($venta->id_ventas) ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong>
                            <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                                <li>Los cambios pueden afectar el inventario</li>
                                <li>Verifique el stock antes de modificar</li>
                                <li>Los cambios de estado pueden ser irreversibles</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="ventas-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Venta
                    </button>
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.checkbox-input');
        const form = document.getElementById('formVenta');
        const countSpan = document.getElementById('productos-seleccionados-count');
        
        // Estilo para checkboxes
        const style = document.createElement('style');
        style.textContent = `
            .checkbox-input:checked + .checkbox-label-edit {
                border-color: #667eea !important;
                background: linear-gradient(135deg, #f0f4ff 0%, #e8efff 100%) !important;
            }
            .checkbox-input:checked + .checkbox-label-edit .checkbox-custom-edit {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-color: #667eea;
            }
            .checkbox-input:checked + .checkbox-label-edit .checkbox-custom-edit i {
                display: block !important;
            }
            .checkbox-label-edit:hover {
                border-color: #667eea !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            }
            .productos-checkbox-container::-webkit-scrollbar {
                width: 8px;
            }
            .productos-checkbox-container::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            .productos-checkbox-container::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 10px;
            }
        `;
        document.head.appendChild(style);
        
        // Actualizar contador
        function actualizarContador() {
            const checked = document.querySelectorAll('.checkbox-input:checked').length;
            countSpan.textContent = checked;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', actualizarContador);
        });
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            if (!document.getElementById('id_clientes').value) {
                e.preventDefault();
                alert('Debe seleccionar un cliente');
                return;
            }
            
            const productosSeleccionados = document.querySelectorAll('.checkbox-input:checked').length;
            if (productosSeleccionados === 0) {
                e.preventDefault();
                alert('Debe seleccionar al menos un producto');
                return;
            }
        });
        
        // Ocultar alertas después de 5 segundos
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
