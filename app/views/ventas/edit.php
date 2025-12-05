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
            grid-template-columns: 1fr;
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
            gap: 2rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            flex-wrap: wrap;
            grid-column: 1 / -1;
            width: 100%;
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
                justify-content: center;
                gap: 1rem;
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
                                <label for="cliente_unificado_edit">
                                    <i class="fas fa-users"></i> Cliente
                                </label>
                                <div class="search-dropdown-container" style="position: relative;">
                                    <input type="text" 
                                           id="cliente_unificado_edit" 
                                           class="form-control search-dropdown-input" 
                                           placeholder="🔍 Buscar y seleccionar cliente..."
                                           autocomplete="off"
                                           required
                                           style="border: 2px solid #667eea; font-size: 0.9rem; padding-right: 40px;">
                                    <i class="fas fa-chevron-down search-dropdown-arrow" 
                                       style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #667eea; cursor: pointer;"></i>
                                    <div id="cliente_dropdown_edit" class="search-dropdown-menu" 
                                         style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #667eea; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 1000; display: none;">
                                        <?php if (isset($clientes) && is_array($clientes)): ?>
                                            <?php foreach ($clientes as $cliente): ?>
                                                <div class="dropdown-option" 
                                                     data-value="<?= htmlspecialchars($cliente->id_clientes) ?>"
                                                     data-nombre="<?= htmlspecialchars(strtolower($cliente->nombre)) ?>"
                                                     data-selected="<?= $venta->id_clientes == $cliente->id_clientes ? 'true' : 'false' ?>"
                                                     style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;">
                                                    <i class="fas fa-user"></i> <?= htmlspecialchars($cliente->nombre) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Campo hidden para almacenar el valor seleccionado -->
                                    <input type="hidden" id="id_clientes" name="id_clientes" value="<?= htmlspecialchars($venta->id_clientes) ?>" required>
                                </div>
                            </div>
                            
                            <!-- Sección Producto -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-box"></i> Información del Producto
                            </div>
                            
                            <!-- Filtros en cascada: Categoría → Subcategoría -->
                            <div class="form-group">
                                <label for="categoria_unificado_edit">
                                    <i class="fas fa-filter"></i> Filtrar por Categoría
                                </label>
                                <div class="search-dropdown-container" style="position: relative;">
                                    <input type="text" 
                                           id="categoria_unificado_edit" 
                                           class="form-control search-dropdown-input" 
                                           placeholder="🔍 Buscar y seleccionar categoría..."
                                           autocomplete="off"
                                           style="border: 2px solid #667eea; font-size: 0.9rem; padding-right: 40px;">
                                    <i class="fas fa-chevron-down search-dropdown-arrow" 
                                       style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #667eea; cursor: pointer;"></i>
                                    <div id="categoria_dropdown_edit" class="search-dropdown-menu" 
                                         style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #667eea; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 1000; display: none;">
                                        <div class="dropdown-option" data-value="" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;">
                                            <i class="fas fa-list"></i> Todas las categorías
                                        </div>
                                        <?php if (isset($categorias) && is_array($categorias)): ?>
                                            <?php foreach ($categorias as $categoria): ?>
                                                <div class="dropdown-option" 
                                                     data-value="<?= htmlspecialchars($categoria->id_categoria) ?>"
                                                     data-nombre="<?= htmlspecialchars(strtolower($categoria->nombre)) ?>"
                                                     style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;">
                                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($categoria->nombre) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Campo hidden para almacenar el valor seleccionado -->
                                    <input type="hidden" id="filtro_categoria_edit" value="">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="subcategoria_unificado_edit">
                                    <i class="fas fa-filter"></i> Filtrar por Subcategoría
                                </label>
                                <div class="search-dropdown-container" style="position: relative;">
                                    <input type="text" 
                                           id="subcategoria_unificado_edit" 
                                           class="form-control search-dropdown-input" 
                                           placeholder="Seleccione primero una categoría..."
                                           autocomplete="off"
                                           disabled
                                           style="border: 2px solid #667eea; font-size: 0.9rem; padding-right: 40px;">
                                    <i class="fas fa-chevron-down search-dropdown-arrow" 
                                       style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #667eea; cursor: pointer;"></i>
                                    <div id="subcategoria_dropdown_edit" class="search-dropdown-menu" 
                                         style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #667eea; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 999; display: none;">
                                        <div class="dropdown-option" data-value="" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;">
                                            <i class="fas fa-list"></i> Todas las subcategorías
                                        </div>
                                    </div>
                                    <!-- Campo hidden para almacenar el valor seleccionado -->
                                    <input type="hidden" id="filtro_subcategoria_edit" value="">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-cubes"></i> Productos *
                                </label>
                                <div class="productos-info-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; color: white;">
                                    <i class="fas fa-info-circle"></i> Selecciona los productos para esta venta
                                </div>
                                
                                <!-- Búsqueda por nombre -->
                                <div style="margin-bottom: 1rem;">
                                    <input type="text" 
                                           id="buscar_producto_edit" 
                                           class="form-control" 
                                           placeholder="🔍 Buscar producto por nombre..."
                                           autocomplete="off">
                                </div>
                                
                                <div class="productos-checkbox-container" style="max-height: 300px; overflow-y: auto; border: 2px solid #667eea; border-radius: 10px; padding: 1rem; background: white;">
                                    <?php if (isset($productos) && is_array($productos)): ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <div class="checkbox-item producto-item-edit" 
                                                 data-categoria-id="<?= htmlspecialchars($producto->id_categoria ?? '') ?>"
                                                 data-subcategoria-id="<?= htmlspecialchars($producto->id_subcategoria ?? '') ?>"
                                                 style="margin-bottom: 0.8rem;">
                                                <input type="checkbox" 
                                                       class="checkbox-input" 
                                                       id="producto_<?= $producto->id_productos ?>" 
                                                       name="id_productos[]" 
                                                       value="<?= htmlspecialchars($producto->id_productos) ?>"
                                                       data-precio="<?= htmlspecialchars($producto->precio_unitario) ?>"
                                                       data-stock="<?= htmlspecialchars($producto->stock) ?>"
                                                       data-nombre="<?= htmlspecialchars($producto->nombre) ?>"
                                                       <?= in_array($producto->id_productos, $productos_asignados_ids ?? []) ? 'checked' : '' ?>
                                                       style="display: none;">
                                                      <label for="producto_<?= $producto->id_productos ?>" 
                                                          class="checkbox-label-edit"
                                                          style="display: flex; align-items: center; padding: 0.8rem; border: 2px solid #e0e0e0; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; background: white; gap: 12px;">
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
                                                    <?php
                                                        // cantidad inicial desde productos asignados
                                                        $cantidad_inicial = 1;
                                                        if (!empty($venta->productos_asignados)) {
                                                            foreach ($venta->productos_asignados as $pa) {
                                                                $pa_arr = (array)$pa;
                                                                if (($pa_arr['id_productos'] ?? null) == $producto->id_productos) {
                                                                    $cantidad_inicial = intval($pa_arr['cantidad'] ?? 1);
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <div class="cantidad-input-wrapper" style="margin-left: auto; display: inline-flex; align-items: center; gap: 8px;">
                                                        <label for="cantidad_<?= $producto->id_productos ?>" style="font-size: 0.85rem; color: #555; margin: 0;">Cantidad</label>
                                                        <input type="number"
                                                               id="cantidad_<?= $producto->id_productos ?>"
                                                               name="cantidades[<?= $producto->id_productos ?>]"
                                                               class="form-control cantidad-input"
                                                               style="width: 90px; padding: 6px 8px;"
                                                               min="1"
                                                               <?= intval($producto->stock) > 0 ? 'max="'.intval($producto->stock).'"' : '' ?>
                                                               value="<?= $cantidad_inicial ?>">
                                                    </div>
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

                    <!-- Información Importante -->
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
    
    <!-- Datos para filtros en cascada -->
    <script>
    // Preparar datos de subcategorías por categoría
    const subcategoriasPorCategoria = {
        <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
            <?php 
            $subcats_by_cat = [];
            foreach ($subcategorias as $subcat_data) {
                $subcat = $subcat_data['obj'] ?? null;
                if ($subcat && isset($subcat->id_categoria)) {
                    $cat_id = $subcat->id_categoria;
                    if (!isset($subcats_by_cat[$cat_id])) {
                        $subcats_by_cat[$cat_id] = [];
                    }
                    $subcats_by_cat[$cat_id][] = [
                        'id' => $subcat->id_subcategoria,
                        'nombre' => $subcat->nombre
                    ];
                }
            }
            foreach ($subcats_by_cat as $cat_id => $subcats): ?>
                '<?= $cat_id ?>': <?= json_encode($subcats) ?>,
            <?php endforeach; ?>
        <?php endif; ?>
    };
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.checkbox-input');
        const form = document.getElementById('formVenta');
        const countSpan = document.getElementById('productos-seleccionados-count');
        
        // Elementos de filtros
        const buscarProducto = document.getElementById('buscar_producto_edit');
        const categoriaUnificado = document.getElementById('categoria_unificado_edit');
        const subcategoriaUnificado = document.getElementById('subcategoria_unificado_edit');
        const categoriaDropdown = document.getElementById('categoria_dropdown_edit');
        const subcategoriaDropdown = document.getElementById('subcategoria_dropdown_edit');
        const filtroCategoria = document.getElementById('filtro_categoria_edit');
        const filtroSubcategoria = document.getElementById('filtro_subcategoria_edit');
        const productosItems = document.querySelectorAll('.producto-item-edit');
        
        // FILTRADO EN CASCADA: Búsqueda → Categoría → Subcategoría → Productos
        
        // Función para crear dropdown unificado
        function crearDropdownUnificado(inputElement, dropdownElement, hiddenInput, placeholder) {
            let opcionesOriginales = [];
            
            // Guardar opciones originales
            function guardarOpciones() {
                opcionesOriginales = Array.from(dropdownElement.querySelectorAll('.dropdown-option')).map(opt => ({
                    value: opt.getAttribute('data-value') || '',
                    text: opt.textContent.trim(),
                    element: opt.cloneNode(true)
                }));
            }
            
            // Filtrar opciones basado en texto de búsqueda
            function filtrarOpciones(busqueda) {
                dropdownElement.innerHTML = '';
                
                opcionesOriginales.forEach(opcion => {
                    if (!busqueda || opcion.text.toLowerCase().includes(busqueda.toLowerCase())) {
                        const newElement = opcion.element.cloneNode(true);
                        newElement.addEventListener('click', () => seleccionarOpcion(opcion.value, opcion.text));
                        dropdownElement.appendChild(newElement);
                    }
                });
            }
            
            // Seleccionar una opción
            function seleccionarOpcion(value, text) {
                hiddenInput.value = value;
                inputElement.value = value ? text.replace(/.*?\s/, '') : ''; // Quitar icono del texto
                dropdownElement.style.display = 'none';
                
                // Disparar evento de cambio
                hiddenInput.dispatchEvent(new Event('change'));
            }
            
            // Event listeners
            inputElement.addEventListener('input', function() {
                filtrarOpciones(this.value);
                dropdownElement.style.display = 'block';
            });
            
            inputElement.addEventListener('focus', function() {
                filtrarOpciones(this.value);
                dropdownElement.style.display = 'block';
            });
            
            inputElement.addEventListener('blur', function() {
                setTimeout(() => {
                    dropdownElement.style.display = 'none';
                }, 150);
            });
            
            // Click en la flecha
            const arrow = inputElement.parentNode.querySelector('.search-dropdown-arrow');
            if (arrow) {
                arrow.addEventListener('click', function() {
                    if (dropdownElement.style.display === 'none' || !dropdownElement.style.display) {
                        filtrarOpciones('');
                        dropdownElement.style.display = 'block';
                        inputElement.focus();
                    } else {
                        dropdownElement.style.display = 'none';
                    }
                });
            }
            
            // Inicializar
            guardarOpciones();
            
            return { guardarOpciones, filtrarOpciones, seleccionarOpcion };
        }
        
        // Inicializar dropdowns unificados
        const categoriaDropdownController = crearDropdownUnificado(
            categoriaUnificado, 
            categoriaDropdown, 
            filtroCategoria, 
            '🔍 Buscar y seleccionar categoría...'
        );
        
        const subcategoriaDropdownController = crearDropdownUnificado(
            subcategoriaUnificado, 
            subcategoriaDropdown, 
            filtroSubcategoria, 
            '🔍 Buscar y seleccionar subcategoría...'
        );
        
        // Inicializar dropdown de clientes
        const clienteUnificado = document.getElementById('cliente_unificado_edit');
        const clienteDropdown = document.getElementById('cliente_dropdown_edit');
        const clienteHidden = document.getElementById('id_clientes');
        
        if (clienteUnificado && clienteDropdown && clienteHidden) {
            const clienteDropdownController = crearDropdownUnificado(
                clienteUnificado, 
                clienteDropdown, 
                clienteHidden, 
                '🔍 Buscar y seleccionar cliente...'
            );
            
            // Establecer el valor inicial si hay un cliente seleccionado
            const selectedOption = clienteDropdown.querySelector('.dropdown-option[data-selected="true"]');
            if (selectedOption) {
                const clienteNombre = selectedOption.textContent.trim().replace(/.*?\s/, ''); // Quitar icono
                clienteUnificado.value = clienteNombre;
            }
        }
        
        // Cuando se escribe en el buscador de productos
        if (buscarProducto) {
            buscarProducto.addEventListener('input', function() {
                aplicarFiltros();
            });
        }
        
        // Cuando cambia la categoría
        if (filtroCategoria) {
            filtroCategoria.addEventListener('change', function() {
                const categoriaId = this.value;
                
                // Limpiar subcategoría seleccionada
                filtroSubcategoria.value = '';
                subcategoriaUnificado.value = '';
                
                if (categoriaId) {
                    // Habilitar subcategorías y cargar las correspondientes
                    subcategoriaUnificado.disabled = false;
                    subcategoriaUnificado.placeholder = '🔍 Buscar y seleccionar subcategoría...';
                    
                    // Recrear opciones de subcategorías en el dropdown
                    subcategoriaDropdown.innerHTML = '<div class="dropdown-option" data-value="" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;"><i class="fas fa-list"></i> Todas las subcategorías</div>';
                    
                    const subcategorias = subcategoriasPorCategoria[categoriaId] || [];
                    subcategorias.forEach(subcat => {
                        const option = document.createElement('div');
                        option.className = 'dropdown-option';
                        option.setAttribute('data-value', subcat.id);
                        option.style.cssText = 'padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;';
                        option.innerHTML = `<i class="fas fa-tag"></i> ${subcat.nombre}`;
                        option.addEventListener('click', () => {
                            filtroSubcategoria.value = subcat.id;
                            subcategoriaUnificado.value = subcat.nombre;
                            subcategoriaDropdown.style.display = 'none';
                            filtroSubcategoria.dispatchEvent(new Event('change'));
                        });
                        subcategoriaDropdown.appendChild(option);
                    });
                    
                    // Reinicializar las opciones del dropdown de subcategorías
                    setTimeout(() => {
                        if (subcategoriaDropdownController && subcategoriaDropdownController.guardarOpciones) {
                            subcategoriaDropdownController.guardarOpciones();
                        }
                    }, 100);
                } else {
                    // Si no hay categoría seleccionada, deshabilitar subcategorías
                    subcategoriaUnificado.disabled = true;
                    subcategoriaUnificado.placeholder = 'Seleccione primero una categoría...';
                    subcategoriaDropdown.innerHTML = '<div class="dropdown-option" data-value="" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;"><i class="fas fa-list"></i> Seleccione primero una categoría</div>';
                }
                
                // Aplicar filtro de productos
                aplicarFiltros();
            });
        }
        
        // Cuando cambia la subcategoría
        if (filtroSubcategoria) {
            filtroSubcategoria.addEventListener('change', function() {
                aplicarFiltros();
            });
        }
        
        // Función para aplicar filtros a los productos
        function aplicarFiltros() {
            const textoBusqueda = buscarProducto ? buscarProducto.value.toLowerCase().trim() : '';
            const categoriaId = filtroCategoria ? filtroCategoria.value : '';
            const subcategoriaId = filtroSubcategoria ? filtroSubcategoria.value : '';
            
            let productosVisibles = 0;
            
            productosItems.forEach(item => {
                const itemCategoriaId = item.dataset.categoriaId || '';
                const itemSubcategoriaId = item.dataset.subcategoriaId || '';
                const checkbox = item.querySelector('.checkbox-input');
                const nombreProducto = checkbox ? (checkbox.dataset.nombre || '').toLowerCase() : '';
                
                let mostrar = true;
                
                // Filtrar por búsqueda de texto
                if (textoBusqueda && !nombreProducto.includes(textoBusqueda)) {
                    mostrar = false;
                }
                
                // Filtrar por categoría
                if (categoriaId && itemCategoriaId !== categoriaId) {
                    mostrar = false;
                }
                
                // Filtrar por subcategoría
                if (subcategoriaId && itemSubcategoriaId !== subcategoriaId) {
                    mostrar = false;
                }
                
                // Mostrar u ocultar el producto
                if (mostrar) {
                    item.style.display = '';
                    productosVisibles++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Mostrar mensaje si no hay productos
            const container = document.querySelector('.productos-checkbox-container');
            let mensajeNoProductos = container.querySelector('.no-productos-mensaje');
            
            if (productosVisibles === 0) {
                if (!mensajeNoProductos) {
                    mensajeNoProductos = document.createElement('div');
                    mensajeNoProductos.className = 'no-productos-mensaje text-center text-muted py-4';
                    mensajeNoProductos.innerHTML = '<i class="fas fa-box-open fa-3x mb-3"></i><p>No hay productos en esta categoría/subcategoría</p>';
                    container.appendChild(mensajeNoProductos);
                }
            } else {
                if (mensajeNoProductos) {
                    mensajeNoProductos.remove();
                }
            }
        }
        
        // Estilos para checkboxes y campos de búsqueda
        const style = document.createElement('style');
        style.textContent = `
            /* Estilos para dropdowns unificados */
            .search-dropdown-input {
                transition: all 0.3s ease;
                border-radius: 8px !important;
                box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
            }
            
            .search-dropdown-input:focus {
                border-color: #667eea !important;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
                transform: translateY(-1px);
            }
            
            .search-dropdown-input:disabled {
                background-color: #f8f9fa;
                opacity: 0.6;
                cursor: not-allowed;
            }
            
            .search-dropdown-menu {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                border-radius: 0 0 8px 8px !important;
            }
            
            .dropdown-option {
                transition: all 0.2s ease;
            }
            
            .dropdown-option:hover {
                background-color: #f0f4ff !important;
                color: #667eea !important;
                transform: translateX(5px);
            }
            
            .dropdown-option:last-child {
                border-bottom: none !important;
            }
            
            .search-dropdown-arrow {
                transition: transform 0.3s ease;
            }
            
            .search-dropdown-container:hover .search-dropdown-arrow {
                transform: translateY(-50%) scale(1.1);
            }
            
            /* Mejorar apariencia de selects cuando están deshabilitados */
            select:disabled {
                background-color: #f8f9fa;
                opacity: 0.7;
                cursor: not-allowed;
            }
            
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

        // Evitar que al interactuar con cantidad se active el toggle del checkbox
        document.querySelectorAll('.cantidad-input').forEach(inp => {
            inp.addEventListener('click', e => e.stopPropagation());
            inp.addEventListener('mousedown', e => e.stopPropagation());
            inp.addEventListener('touchstart', e => e.stopPropagation());
            // Validación de stock y recálculo de total
            const clampCantidad = (input) => {
                const min = parseInt(input.min || '1', 10);
                const max = parseInt(input.max || '0', 10);
                let val = parseInt(input.value || '1', 10);
                if (isNaN(val) || val < min) val = min;
                if (max > 0 && val > max) val = max;
                input.value = val;
                // Feedback visual cuando se alcanza el máximo
                if (max > 0 && val === max) {
                    input.style.borderColor = '#dc3545';
                    input.style.boxShadow = '0 0 0 4px rgba(220, 53, 69, 0.15)';
                    input.title = 'Cantidad limitada por stock disponible';
                } else {
                    input.style.borderColor = '';
                    input.style.boxShadow = '';
                    input.title = '';
                }
            };
            const recomputeTotal = () => {
                const totalField = document.getElementById('total');
                if (!totalField) return;
                let total = 0;
                document.querySelectorAll('.checkbox-input').forEach(cb => {
                    if (cb.checked) {
                        const pid = cb.value;
                        const qtyInput = document.getElementById(`cantidad_${pid}`);
                        const qty = qtyInput ? parseInt(qtyInput.value || '1', 10) : 1;
                        const price = parseFloat(cb.dataset.precio || '0');
                        total += (qty * price);
                    }
                });
                totalField.value = total.toFixed(2);
            };

            ['input','change','blur','keyup'].forEach(evt => {
                inp.addEventListener(evt, () => { clampCantidad(inp); recomputeTotal(); });
            });
        });

        // Recalcular total al cambiar selección de productos
        document.querySelectorAll('.checkbox-input').forEach(cb => {
            cb.addEventListener('change', () => {
                const pid = cb.value;
                const qtyInput = document.getElementById(`cantidad_${pid}`);
                if (cb.checked && qtyInput) {
                    // Asegurar mínimo 1 al seleccionar
                    if (!qtyInput.value || parseInt(qtyInput.value, 10) < 1) qtyInput.value = 1;
                }
                // Recalcular total
                let total = 0;
                document.querySelectorAll('.checkbox-input').forEach(c => {
                    if (c.checked) {
                        const p = c.dataset.precio ? parseFloat(c.dataset.precio) : 0;
                        const qEl = document.getElementById(`cantidad_${c.value}`);
                        const q = qEl ? parseInt(qEl.value || '1', 10) : 1;
                        total += (q * p);
                    }
                });
                const totalField = document.getElementById('total');
                if (totalField) totalField.value = total.toFixed(2);
                actualizarContador();
            });
        });

        // Recalcular total inicial
        (function initTotal(){
            let total = 0;
            document.querySelectorAll('.checkbox-input').forEach(c => {
                if (c.checked) {
                    const p = c.dataset.precio ? parseFloat(c.dataset.precio) : 0;
                    const qEl = document.getElementById(`cantidad_${c.value}`);
                    const q = qEl ? parseInt(qEl.value || '1', 10) : 1;
                    total += (q * p);
                }
            });
            const totalField = document.getElementById('total');
            if (totalField) totalField.value = total.toFixed(2);
        })();
        
        // Actualizar contador inicial
        actualizarContador();
        
        // Los datos se guardan automáticamente en los controladores de dropdown
        
        // Aplicar filtros iniciales
        aplicarFiltros();
        
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
