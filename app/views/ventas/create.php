<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta - RMIE</title>
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

        /* Resumen de venta mejorado */
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
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem -1rem -1rem -1rem;
            padding: 1rem 2rem;
            border-radius: 0 0 12px 12px;
        }

        .summary-item:hover:not(:last-child) {
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

        .ventas-buttons {
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
            color: var(--text-secondary);
        }

        .info-alert li {
            margin-bottom: 0.5rem;
        }

        /* Alertas de error */
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #dc3545;
        }

        .alert-danger i {
            color: #dc3545;
            margin-right: 0.5rem;
        }

        /* Animaciones */
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

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .ventas-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .form-header h1 {
                font-size: 1.8rem;
            }
            
            .form-content {
                padding: 1rem;
            }
            
            .form-section {
                padding: 1.5rem;
            }
            
            .ventas-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
                min-width: unset;
            }
        }

        /* Estilos del carrito */
        #carrito-container {
            min-height: 200px;
        }

        .carrito-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: var(--transition);
            animation: slideInRight 0.3s ease-out;
        }

        .carrito-item:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .carrito-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .carrito-item-header strong {
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .btn-eliminar {
            background: rgba(220, 53, 69, 0.8);
            border: none;
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            color: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-eliminar:hover {
            background: rgba(220, 53, 69, 1);
            transform: scale(1.1);
        }

        .carrito-item-body {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .carrito-item-info {
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .carrito-item-info i {
            margin-right: 0.3rem;
        }

        .carrito-item-cantidad {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-cantidad {
            background: rgba(102, 126, 234, 0.8);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 0.8rem;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .btn-cantidad:hover {
            background: rgba(102, 126, 234, 1);
            transform: scale(1.1);
        }

        .cantidad-input {
            width: 80px;
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 0.5rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .carrito-item-subtotal {
            display: flex;
            justify-content: space-between;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
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

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-gradient);
        }
    </style>
</head>
<body>
    <div class="glass-container animate-fade-in">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="modern-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/views/dashboard.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index">
                        <i class="fas fa-shopping-cart"></i> Ventas
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-plus-circle"></i> Nueva Venta
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="form-header">
            <h1><i class="fas fa-cash-register"></i> Registrar Nueva Venta</h1>
            <p>Complete los datos para registrar una nueva venta en el sistema</p>
        </div>

        <!-- Content -->
        <div class="form-content">
            <!-- Mostrar mensajes de error de sesión -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Mostrar errores si existen -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/RMIE/app/controllers/SaleController.php?accion=create" id="formVenta">
                <div class="ventas-grid">
                    <!-- Formulario principal -->
                    <div>
                        <!-- Información del cliente -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-user-tie"></i> Información del Cliente
                            </h5>
                            
                            <div class="form-group">
                                <label for="id_clientes">
                                    <i class="fas fa-users"></i> Cliente:
                                </label>
                                <select id="id_clientes" name="id_clientes" class="form-select" required>
                                    <option value="">Seleccione un cliente</option>
                                    <?php if (isset($clientes) && is_array($clientes)): ?>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?= htmlspecialchars($cliente->id_clientes) ?>" 
                                                    <?= ($_POST['id_clientes'] ?? '') == $cliente->id_clientes ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cliente->nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Información del producto -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-box"></i> Seleccionar Productos para la Venta
                            </h5>
                            
                            <!-- Filtros en cascada: Categoría → Subcategoría -->
                            <div class="form-group">
                                <label for="filtro_categoria">
                                    <i class="fas fa-filter"></i> Filtrar por Categoría
                                </label>
                                <select id="filtro_categoria" class="form-select">
                                    <option value="">Todas las categorías</option>
                                    <?php if (isset($categorias) && is_array($categorias)): ?>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?= htmlspecialchars($categoria->id_categoria) ?>">
                                                <?= htmlspecialchars($categoria->nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="filtro_subcategoria">
                                    <i class="fas fa-filter"></i> Filtrar por Subcategoría
                                </label>
                                <select id="filtro_subcategoria" class="form-select" disabled>
                                    <option value="">Seleccione primero una categoría</option>
                                </select>
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
                                           id="buscar_producto" 
                                           class="form-control" 
                                           placeholder="🔍 Buscar producto por nombre..."
                                           autocomplete="off">
                                </div>
                                <div class="productos-checkbox-container-create" style="max-height: 400px; overflow-y: auto; border: 2px solid #667eea; border-radius: 10px; padding: 1rem; background: white;">
                                    <?php if (isset($productos) && is_array($productos)): ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <div class="checkbox-item producto-item" 
                                                 data-categoria-id="<?= htmlspecialchars($producto->id_categoria ?? '') ?>"
                                                 data-subcategoria-id="<?= htmlspecialchars($producto->id_subcategoria ?? '') ?>"
                                                 style="margin-bottom: 0.8rem;">
                                                <input type="checkbox" 
                                                       class="checkbox-input-create" 
                                                       id="producto_<?= $producto->id_productos ?>" 
                                                       name="id_productos[]" 
                                                       value="<?= htmlspecialchars($producto->id_productos) ?>"
                                                       data-precio="<?= htmlspecialchars($producto->precio_unitario) ?>"
                                                       data-stock="<?= htmlspecialchars($producto->stock) ?>"
                                                       data-nombre="<?= htmlspecialchars($producto->nombre) ?>"
                                                       style="display: none;">
                                                <label for="producto_<?= $producto->id_productos ?>" 
                                                       class="checkbox-label-create"
                                                       style="display: flex; align-items: center; padding: 0.8rem; border: 2px solid #e0e0e0; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; background: white;">
                                                    <span class="checkbox-custom-create" 
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
                                    <span id="productos-seleccionados-count">0</span> producto(s) seleccionado(s)
                                </small>
                            </div>
                        </div>

                        <!-- Sección eliminada: Carrito de productos -->
                        <div class="form-section" style="display: none;">
                            <h5 class="section-title">
                                <i class="fas fa-shopping-cart"></i> Carrito de Compras
                            </h5>
                            
                            <div id="carrito-container">
                                <div id="carrito-vacio" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                    <p>No hay productos en el carrito</p>
                                </div>
                                <div id="carrito-items"></div>
                            </div>
                        </div>                        <!-- Configuración de la venta -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-cogs"></i> Configuración de la Venta
                            </h5>
                            
                            <div class="form-group">
                                <label for="fecha_venta">
                                    <i class="fas fa-calendar-alt"></i> Fecha de Venta:
                                </label>
                                <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" 
                                       required value="<?= htmlspecialchars($_POST['fecha_venta'] ?? date('Y-m-d')) ?>">
                                <small class="form-text text-muted">Fecha en que se realiza la venta</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="estado">
                                    <i class="fas fa-flag"></i> Estado:
                                </label>
                                <select id="estado" name="estado" class="form-select" required>
                                    <option value="pendiente" <?= ($_POST['estado'] ?? 'pendiente') === 'pendiente' ? 'selected' : '' ?>>
                                        Pendiente
                                    </option>
                                    <option value="procesando" <?= ($_POST['estado'] ?? '') === 'procesando' ? 'selected' : '' ?>>
                                        Procesando
                                    </option>
                                    <option value="completada" <?= ($_POST['estado'] ?? '') === 'completada' ? 'selected' : '' ?>>
                                        Completada
                                    </option>
                                    <option value="cancelada" <?= ($_POST['estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>
                                        Cancelada
                                    </option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="num_doc">
                                    <i class="fas fa-user-shield"></i> Usuario Responsable:
                                </label>
                                <select id="num_doc" name="num_doc" class="form-select" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php if (isset($usuarios) && is_array($usuarios)): ?>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= htmlspecialchars($usuario->num_doc) ?>" 
                                                    <?= ($_POST['num_doc'] ?? '') == $usuario->num_doc ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de la venta -->
                    <div class="ventas-summary">
                        <div class="summary-header">
                            <h5><i class="fas fa-receipt"></i> Resumen de la Venta</h5>
                        </div>
                          <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-calendar-alt"></i> Fecha de Venta:
                            </span>
                            <span class="summary-value" id="fecha-mostrar"><?= date('d/m/Y') ?></span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-box"></i> Producto:
                            </span>
                            <span class="summary-value" id="producto-nombre">-</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-warehouse"></i> Stock Disponible:
                            </span>
                            <span class="summary-value" id="stock-disponible">-</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-sort-numeric-up"></i> Cantidad:
                            </span>
                            <span class="summary-value" id="cantidad-mostrar">0</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-boxes"></i> Productos:
                            </span>
                            <span class="summary-value" id="productos-count">0</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-sort-numeric-up"></i> Items:
                            </span>
                            <span class="summary-value" id="items-count">0</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-calculator"></i> Total a Pagar:
                            </span>
                            <span class="summary-value" id="total-mostrar">$0.00</span>
                        </div>                        <!-- Información adicional -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información Importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Agregue uno o más productos al carrito</li>
                                <li>Verifique el stock disponible de cada producto</li>
                                <li>El total se calcula automáticamente</li>
                                <li>Una vez procesada, la venta afectará el inventario</li>
                                <li>Puede modificar cantidades en el carrito</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="ventas-buttons">
                    <button type="submit" class="btn btn-success" id="btn-registrar">
                        <i class="fas fa-cash-register"></i> Registrar Venta
                    </button>
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
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
    
    <!-- JavaScript simplificado con checkboxes -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.checkbox-input-create');
        const form = document.getElementById('formVenta');
        const countSpan = document.getElementById('productos-seleccionados-count');
        const btnRegistrar = document.getElementById('btn-registrar');
        
        // Elementos de filtros
        const buscarProducto = document.getElementById('buscar_producto');
        const filtroCategoria = document.getElementById('filtro_categoria');
        const filtroSubcategoria = document.getElementById('filtro_subcategoria');
        const productosItems = document.querySelectorAll('.producto-item');
        
        // Verificar que los elementos existen
        if (!form || !btnRegistrar) {
            console.error('Elementos del formulario no encontrados');
            return;
        }
        
        // FILTRADO EN CASCADA: Búsqueda → Categoría → Subcategoría → Productos
        
        // Cuando se escribe en el buscador
        if (buscarProducto) {
            buscarProducto.addEventListener('input', function() {
                aplicarFiltros();
            });
        }
        
        // Cuando cambia la categoría
        if (filtroCategoria) {
            filtroCategoria.addEventListener('change', function() {
                const categoriaId = this.value;
                
                // Limpiar y resetear subcategorías
                filtroSubcategoria.innerHTML = '<option value="">Todas las subcategorías</option>';
                
                if (categoriaId) {
                    // Habilitar subcategorías y cargar las correspondientes
                    filtroSubcategoria.disabled = false;
                    
                    const subcategorias = subcategoriasPorCategoria[categoriaId] || [];
                    subcategorias.forEach(subcat => {
                        const option = document.createElement('option');
                        option.value = subcat.id;
                        option.textContent = subcat.nombre;
                        filtroSubcategoria.appendChild(option);
                    });
                } else {
                    // Si no hay categoría seleccionada, deshabilitar subcategorías
                    filtroSubcategoria.disabled = true;
                    filtroSubcategoria.innerHTML = '<option value="">Seleccione primero una categoría</option>';
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
                const checkbox = item.querySelector('.checkbox-input-create');
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
            const container = document.querySelector('.productos-checkbox-container-create');
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
        
        // Estilo para checkboxes
        const style = document.createElement('style');
        style.textContent = `
            .checkbox-input-create:checked + .checkbox-label-create {
                border-color: #667eea !important;
                background: linear-gradient(135deg, #f0f4ff 0%, #e8efff 100%) !important;
            }
            .checkbox-input-create:checked + .checkbox-label-create .checkbox-custom-create {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-color: #667eea;
            }
            .checkbox-input-create:checked + .checkbox-label-create .checkbox-custom-create i {
                display: block !important;
            }
            .checkbox-label-create:hover {
                border-color: #667eea !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            }
            .productos-checkbox-container-create::-webkit-scrollbar {
                width: 8px;
            }
            .productos-checkbox-container-create::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            .productos-checkbox-container-create::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 10px;
            }
        `;
        document.head.appendChild(style);
        
        // Actualizar contador y resumen
        function actualizarResumen() {
            const checked = document.querySelectorAll('.checkbox-input-create:checked');
            const count = checked.length;
            
            if (countSpan) {
                countSpan.textContent = count;
            }
            
            // Calcular total
            let total = 0;
            checked.forEach(checkbox => {
                const precio = parseFloat(checkbox.dataset.precio || 0);
                total += precio;
            });
            
            const productosCount = document.getElementById('productos-count');
            const itemsCount = document.getElementById('items-count');
            const totalMostrar = document.getElementById('total-mostrar');
            
            if (productosCount) productosCount.textContent = count;
            if (itemsCount) itemsCount.textContent = count;
            if (totalMostrar) totalMostrar.textContent = '$' + total.toFixed(2);
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', actualizarResumen);
        });
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const cliente = document.getElementById('id_clientes');
            const fecha = document.getElementById('fecha_venta');
            const usuario = document.getElementById('num_doc');
            const productosSeleccionados = document.querySelectorAll('.checkbox-input-create:checked').length;
            
            let errors = [];
            
            if (!cliente || !cliente.value) errors.push('Debe seleccionar un cliente');
            if (!fecha || !fecha.value) errors.push('Debe seleccionar una fecha');
            if (!usuario || !usuario.value) errors.push('Debe seleccionar un usuario responsable');
            if (productosSeleccionados === 0) errors.push('Debe seleccionar al menos un producto');
            
            if (errors.length > 0) {
                alert(errors.join('\\n'));
                return;
            }
            
            // Deshabilitar botón
            btnRegistrar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            btnRegistrar.disabled = true;
            
            // Enviar formulario
            form.submit();
        });
        
        // Inicializar resumen
        actualizarResumen();
    });
    </script>

    <style>
        /* Estilos adicionales para las alertas personalizadas */
        .custom-alert {
            background: rgba(255, 193, 7, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #ffc107;
            position: relative;
            animation: slideInDown 0.3s ease-out;
        }

        .custom-alert.alert-warning {
            border-left-color: #ff6b35;
            border-color: rgba(255, 107, 53, 0.3);
            background: rgba(255, 107, 53, 0.1);
        }

        .custom-alert i {
            color: #ffc107;
            margin-right: 0.7rem;
        }

        .custom-alert.alert-warning i {
            color: #ff6b35;
        }

        .custom-alert .btn-close {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text-secondary);
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</body>
</html>