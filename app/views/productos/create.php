<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inicializar variables por defecto si no existen
if (!isset($categorias)) $categorias = [];
if (!isset($subcategorias)) $subcategorias = [];
if (!isset($proveedores)) $proveedores = [];
if (!isset($usuarios)) $usuarios = [];

// Obtener mensajes de sesión
$error_message = $_SESSION['error'] ?? ($error_message ?? '');
$success_message = $_SESSION['success'] ?? ($success_message ?? '');

// Limpiar mensajes de sesión
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="es" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Producto - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    
    <!-- Estilos críticos PRIMERO para evitar flash blanco -->
    <style>
        /* ESTILOS CRÍTICOS - CARGAN INMEDIATAMENTE */
        html, body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
        
        body {
            padding: 20px !important;
        }
        
        /* CONTENEDOR PRINCIPAL - CRÍTICO - SELECTORES SUPER ESPECÍFICOS */
        body > .main-container,
        div.main-container,
        .main-container {
            max-width: 1000px !important;
            width: 100% !important;
            margin: 0 auto !important;
            background: rgba(255, 255, 255, 0.95) !important;
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-radius: 30px !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3) !important;
            overflow: hidden !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            position: relative !important;
            z-index: 1 !important;
        }
        
        /* Prevenir flash blanco */
        * {
            box-sizing: border-box;
        }
    </style>
    
    <!-- Script inmediato para forzar estilos -->
    <script>
        // Ejecuta INMEDIATAMENTE, antes de que cargue el CSS externo
        (function() {
            const html = document.documentElement;
            const body = document.body || document.getElementsByTagName('body')[0];
            
            if (html) {
                html.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                html.style.minHeight = '100vh';
            }
            
            if (body) {
                body.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                body.style.minHeight = '100vh';
                body.style.padding = '20px';
                body.style.margin = '0';
                body.style.fontFamily = '"Segoe UI", Tahoma, Geneva, Verdana, sans-serif';
            }
            
            // Forzar estilos del contenedor INMEDIATAMENTE
            function forceContainerStyles() {
                const containers = document.querySelectorAll('.main-container, div.main-container');
                containers.forEach(function(container) {
                    if (container) {
                        container.style.setProperty('max-width', '1000px', 'important');
                        container.style.setProperty('width', '100%', 'important');
                        container.style.setProperty('margin', '0 auto', 'important');
                        container.style.setProperty('background', 'rgba(255, 255, 255, 0.95)', 'important');
                        container.style.setProperty('background-color', 'rgba(255, 255, 255, 0.95)', 'important');
                        container.style.setProperty('border-radius', '30px', 'important');
                        container.style.setProperty('border', '1px solid rgba(255, 255, 255, 0.2)', 'important');
                        container.style.setProperty('box-shadow', '0 25px 80px rgba(0, 0, 0, 0.3)', 'important');
                        container.style.setProperty('overflow', 'hidden', 'important');
                        container.style.setProperty('backdrop-filter', 'blur(10px)', 'important');
                        container.style.setProperty('position', 'relative', 'important');
                        container.style.setProperty('z-index', '1', 'important');
                    }
                });
            }
            
            // Ejecutar inmediatamente
            forceContainerStyles();
            
            // Ejecutar después de un micro-delay
            setTimeout(forceContainerStyles, 1);
            setTimeout(forceContainerStyles, 10);
            setTimeout(forceContainerStyles, 50);
            
            // Observer para detectar cuando se añade el contenedor
            if (typeof MutationObserver !== 'undefined') {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList') {
                            const containers = document.querySelectorAll('.main-container');
                            if (containers.length > 0) {
                                forceContainerStyles();
                            }
                        }
                    });
                });
                
                if (document.body) {
                    observer.observe(document.body, { childList: true, subtree: true });
                }
            }
        })();
    </script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Los estilos críticos ya están en el head, estos son complementarios */
        
        /* Animación para el contenedor */
        .main-container {
            animation: fadeInUp 0.6s ease-out !important;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-section {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .header-title {
            color: #2c3e50;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-title i {
            font-size: 2.5rem;
            opacity: 0.9;
        }
        
        .header-subtitle {
            color: #4a5568;
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-section {
            padding: 40px 30px;
        }
        
        .section-title {
            color: #667eea;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid rgba(102, 126, 234, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, transparent 100%);
            padding: 15px 15px 12px 15px;
            margin: -25px -25px 20px -25px;
            padding-left: 15px;
            border-radius: 20px 20px 0 0;
        }
        
        .section-title i {
            font-size: 1.4rem;
            color: #667eea;
        }
        
        .form-row {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .form-row-2 {
            grid-template-columns: 1fr 1fr;
        }
        
        .form-row-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }
        
        .form-row-4 {
            grid-template-columns: 1fr 1fr 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2,
            .form-row-3,
            .form-row-4 {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        .form-floating-modern {
            position: relative;
            margin-bottom: 20px;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #2c3e50;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }
        
        .form-control-modern:focus {
            outline: none;
            border-color: rgba(102, 126, 234, 0.8);
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }
        
        .form-select-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #2c3e50;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            appearance: none;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: rgba(102, 126, 234, 0.8);
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }
        
        .form-floating-modern label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: rgba(102, 126, 234, 0.8);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-floating-modern label i {
            font-size: 16px;
        }
        
        .form-control-modern:focus ~ label,
        .form-control-modern:not(:placeholder-shown) ~ label,
        .form-select-modern:focus ~ label,
        .form-select-modern:not([value=""]) ~ label {
            top: 2px;
            font-size: 12px;
            color: #2c3e50;
        }
        
        .char-counter {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-align: right;
            margin-top: 5px;
        }

        /* Estilos para alertas */
        .alert-modern {
            padding: 15px 20px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border-color: rgba(46, 204, 113, 0.4);
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border-color: rgba(231, 76, 60, 0.4);
        }

        /* Estilos para campos de búsqueda */
        .search-container {
            position: relative;
        }

        .search-input {
            cursor: pointer;
        }

        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .search-dropdown.show {
            display: block;
        }

        .search-option {
            padding: 12px 15px;
            cursor: pointer;
            color: #333;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-option:hover {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
        }

        .search-option.selected {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .search-option:last-child {
            border-bottom: none;
            border-radius: 0 0 12px 12px;
        }

        .search-option:first-child {
            border-radius: 12px 12px 0 0;
        }

        .search-option i {
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .search-input:focus + .search-dropdown {
            display: block;
        }

        .search-input.has-value {
            background: rgba(102, 126, 234, 0.1);
            border-color: #667eea;
        }
        
        .form-section-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(240, 245, 255, 0.98) 100%);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            border: 2px solid rgba(102, 126, 234, 0.15);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.08);
            transition: all 0.3s ease;
        }
        
        .form-section-card:hover {
            border-color: rgba(102, 126, 234, 0.25);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.12);
        }
        
        .buttons-section {
            padding: 20px 30px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            min-width: 160px;
            justify-content: center;
        }
        
        .btn-create {
            background: linear-gradient(135deg, #00d4ff 0%, #667eea 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 212, 255, 0.4);
            background: linear-gradient(135deg, #00b8d4 0%, #5a67d8 100%);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 10px !important;
                border-radius: 20px !important;
                background: rgba(255, 255, 255, 0.95) !important;
                box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3) !important;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 1.8rem;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .buttons-section {
                padding: 20px 20px 30px;
                flex-direction: column;
            }
            
            .btn-modern {
                width: 100%;
            }
        }
        
        /* Animaciones */
        .form-section-card {
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }
        
        .form-section-card:nth-child(1) {
            animation-delay: 0.1s;
        }
        
        .form-section-card:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .form-section-card:nth-child(3) {
            animation-delay: 0.3s;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; min-height: 100vh !important; padding: 20px !important; margin: 0 !important; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;">
    <div class="main-container">
        <!-- Header -->
        <div class="header-section">
            <h1 class="header-title">
                <i class="fas fa-plus-circle"></i>
                Crear Nuevo Producto
            </h1>
            <p class="header-subtitle">Complete todos los campos requeridos para agregar un nuevo producto al inventario</p>
            
            <!-- Mensajes de éxito y error -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-modern" style="margin-top: 20px;">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-modern" style="margin-top: 20px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Form -->
        <div class="form-section">
            <form action="/RMIE/app/controllers/ProductController.php?accion=create" method="POST" id="productForm">
                
                <!-- Información Básica -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Información Básica
                    </div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-floating-modern">
                            <input type="text" 
                                   class="form-control-modern" 
                                   id="nombre" 
                                   name="nombre" 
                                   placeholder=" "
                                   maxlength="100"
                                   required>
                            <label for="nombre">
                                <i class="fas fa-tag"></i>
                                Nombre del Producto
                            </label>
                            <div class="char-counter">
                                <span id="nombre-count">0</span>/100 caracteres
                            </div>
                        </div>

                        <div class="form-floating-modern">
                            <input type="text" 
                                   class="form-control-modern" 
                                   id="descripcion" 
                                   name="descripcion" 
                                   placeholder=" "
                                   maxlength="200">
                            <label for="descripcion">
                                <i class="fas fa-align-left"></i>
                                Descripción (Opcional)
                            </label>
                            <div class="char-counter">
                                <span id="descripcion-count">0</span>/200 caracteres
                            </div>
                        </div>
                    </div>

                    <div class="form-row form-row-2">
                        <!-- Campo de categoría con búsqueda -->
                        <div class="form-floating-modern search-container">
                            <input type="text" 
                                   class="form-control-modern search-input" 
                                   id="categoria_search" 
                                   placeholder=" "
                                   autocomplete="off"
                                   required>
                            <input type="hidden" 
                                   id="categoria_id" 
                                   name="categoria_id" 
                                   required>
                            <label for="categoria_search">
                                <i class="fas fa-folder"></i>
                                Categoría *
                            </label>
                            <div class="search-dropdown" id="categoria_dropdown">
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <div class="search-option" 
                                             data-value="<?= htmlspecialchars($cat->id_categoria) ?>"
                                             data-text="<?= htmlspecialchars($cat->nombre) ?>">
                                            <i class="fas fa-folder"></i>
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Campo de subcategoría con búsqueda -->
                        <div class="form-floating-modern search-container">
                            <input type="text" 
                                   class="form-control-modern search-input" 
                                   id="subcategoria_search" 
                                   placeholder=" "
                                   autocomplete="off"
                                   disabled>
                            <input type="hidden" 
                                   id="subcategoria_id" 
                                   name="subcategoria_id">
                            <label for="subcategoria_search">
                                <i class="fas fa-layer-group"></i>
                                Subcategoría (Opcional)
                            </label>
                            <div class="search-dropdown" id="subcategoria_dropdown">
                                <!-- Se llenarán dinámicamente según la categoría -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Inventario -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-warehouse"></i>
                        Inventario y Datos Básicos
                    </div>
                    
                    <div class="form-row form-row-3">
                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="stock" 
                                   name="stock" 
                                   placeholder=" "
                                   min="0"
                                   required>
                            <label for="stock">
                                <i class="fas fa-cubes"></i>
                                Stock Inicial
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="text" 
                                   class="form-control-modern" 
                                   id="marca" 
                                   name="marca" 
                                   placeholder=" "
                                   maxlength="50">
                            <label for="marca">
                                <i class="fas fa-trademark"></i>
                                Marca
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="valor_unitario" 
                                   name="valor_unitario" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0">
                            <label for="valor_unitario">
                                <i class="fas fa-tag"></i>
                                Valor Unitario
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Información de Precios -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-dollar-sign"></i>
                        Precios
                    </div>
                    
                    <div class="form-row form-row-3">
                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="precio_unitario" 
                                   name="precio_unitario" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0"
                                   required>
                            <label for="precio_unitario">
                                <i class="fas fa-money-bill"></i>
                                Precio Unitario
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="precio_por_mayor" 
                                   name="precio_por_mayor" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0">
                            <label for="precio_por_mayor">
                                <i class="fas fa-coins"></i>
                                Precio por Mayor
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="precio_compra" 
                                   name="precio_compra" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0">
                            <label for="precio_compra">
                                <i class="fas fa-shopping-cart"></i>
                                Precio de Compra
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Fechas
                    </div>
                    
                    <div class="form-row form-row-3">
                        <div class="form-floating-modern">
                            <input type="date" 
                                   class="form-control-modern" 
                                   id="fecha_entrada" 
                                   name="fecha_entrada" 
                                   placeholder=" "
                                   required>
                            <label for="fecha_entrada">
                                <i class="fas fa-calendar-plus"></i>
                                Fecha de Entrada
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="date" 
                                   class="form-control-modern" 
                                   id="fecha_fabricacion" 
                                   name="fecha_fabricacion" 
                                   placeholder=" ">
                            <label for="fecha_fabricacion">
                                <i class="fas fa-industry"></i>
                                Fecha de Fabricación
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="date" 
                                   class="form-control-modern" 
                                   id="fecha_caducidad" 
                                   name="fecha_caducidad" 
                                   placeholder=" ">
                            <label for="fecha_caducidad">
                                <i class="fas fa-calendar-times"></i>
                                Fecha de Vencimiento
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Proveedor y Usuario -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-users"></i>
                        Proveedor y Usuario Responsable
                    </div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="proveedor_id" 
                                    name="proveedor_id">
                                <option value="">Seleccione un proveedor</option>
                                <?php if (isset($proveedores) && is_array($proveedores)): ?>
                                    <?php foreach ($proveedores as $prov): ?>
                                        <option value="<?= htmlspecialchars($prov->id_proveedores ?? '') ?>">
                                            <?= htmlspecialchars($prov->nombre_distribuidor ?? 'Sin nombre') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="proveedor_id">
                                <i class="fas fa-truck"></i>
                                Proveedor (Opcional)
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="usuario_id" 
                                    name="usuario_id"
                                    required>
                                <option value="">Seleccione un usuario responsable</option>
                                <?php if (isset($usuarios) && is_array($usuarios)): ?>
                                    <?php foreach ($usuarios as $user): ?>
                                        <option value="<?= htmlspecialchars($user->num_doc ?? '') ?>">
                                            <?= htmlspecialchars(($user->nombres ?? '') . ' ' . ($user->apellidos ?? '')) ?> 
                                            (<?= htmlspecialchars($user->rol ?? 'Sin rol') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="usuario_id">
                                <i class="fas fa-user"></i>
                                Usuario Responsable *
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Buttons -->
        <div class="buttons-section">
            <button type="submit" form="productForm" class="btn-modern btn-create">
                <i class="fas fa-save"></i>
                CREAR PRODUCTO
            </button>
            <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn-modern btn-cancel">
                <i class="fas fa-times"></i>
                CANCELAR
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sistema de captura de errores simplificado
        window.addEventListener('error', function(event) {
            return true; // Previene que se muestre el error por defecto
        });
        
        window.addEventListener('unhandledrejection', function(event) {
            // Capturar promesas rechazadas silenciosamente
            event.preventDefault();
        });
    </script>
    <script>
        // Contador de caracteres
        function updateCharCounter(inputId, counterId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            
            if (input && counter) {
                input.addEventListener('input', function() {
                    const currentLength = this.value.length;
                    counter.textContent = currentLength;
                    
                    if (currentLength > maxLength * 0.8) {
                        counter.style.color = '#ff6b6b';
                    } else {
                        counter.style.color = 'rgba(255, 255, 255, 0.7)';
                    }
                });
            }
        }

        // Inicializar contadores
        updateCharCounter('nombre', 'nombre-count', 100);
        updateCharCounter('descripcion', 'descripcion-count', 200);

        // Funcionalidad de búsqueda para categorías
        function initializeSearchField(searchInputId, hiddenInputId, dropdownId, data) {
            const searchInput = document.getElementById(searchInputId);
            const hiddenInput = document.getElementById(hiddenInputId);
            const dropdown = document.getElementById(dropdownId);
            
            // Mostrar dropdown al hacer clic en el input
            searchInput.addEventListener('click', function() {
                dropdown.classList.add('show');
                filterOptions();
            });

            // Filtrar opciones mientras escribe
            searchInput.addEventListener('input', function() {
                filterOptions();
                dropdown.classList.add('show');
            });

            // Ocultar dropdown al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.search-container')) {
                    dropdown.classList.remove('show');
                }
            });

            // Función para filtrar opciones
            function filterOptions() {
                const filter = searchInput.value.toLowerCase();
                const options = dropdown.querySelectorAll('.search-option');
                
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }

            // Manejar selección de opción
            dropdown.addEventListener('click', function(e) {
                const option = e.target.closest('.search-option');
                if (option) {
                    const value = option.dataset.value;
                    const text = option.dataset.text;
                    
                    searchInput.value = text;
                    hiddenInput.value = value;
                    searchInput.classList.add('has-value');
                    dropdown.classList.remove('show');

                    // Si es categoría, actualizar subcategorías
                    if (searchInputId === 'categoria_search') {
                        loadSubcategories(value);
                    }
                }
            });

            // Limpiar si se borra el texto
            searchInput.addEventListener('keyup', function() {
                if (this.value === '') {
                    hiddenInput.value = '';
                    searchInput.classList.remove('has-value');
                    
                    if (searchInputId === 'categoria_search') {
                        clearSubcategories();
                    }
                }
            });
        }

        // Cargar subcategorías basado en categoría seleccionada (con manejo de errores)
        function loadSubcategories(categoriaId) {
            try {
                const subcategoriaSearch = document.getElementById('subcategoria_search');
                const subcategoriaHidden = document.getElementById('subcategoria_id');
                const subcategoriaDropdown = document.getElementById('subcategoria_dropdown');
                
                if (!subcategoriaSearch || !subcategoriaHidden || !subcategoriaDropdown) {
                    return; // Elementos no encontrados, salir silenciosamente
                }
                
                // Habilitar campo de subcategoría
                subcategoriaSearch.disabled = false;
                subcategoriaSearch.placeholder = 'Cargando subcategorías...';
                
                // Limpiar valores anteriores
                subcategoriaSearch.value = '';
                subcategoriaHidden.value = '';
                subcategoriaSearch.classList.remove('has-value');
            
            if (categoriaId) {
                // Llamada AJAX para cargar subcategorías
                fetch('/RMIE/app/controllers/SubcategoryController.php?accion=getByCategory&categoria_id=' + categoriaId)
                    .then(response => response.json())
                    .then(data => {
                        let optionsHtml = '';
                        if (data && data.length > 0) {
                            data.forEach(subcategoria => {
                                optionsHtml += `
                                    <div class="search-option" 
                                         data-value="${subcategoria.id_subcategoria}"
                                         data-text="${subcategoria.nombre}">
                                        <i class="fas fa-layer-group"></i>
                                        ${subcategoria.nombre}
                                    </div>
                                `;
                            });
                        } else {
                            optionsHtml = '<div class="search-option" data-value="" data-text="">No hay subcategorías disponibles</div>';
                        }
                        
                        subcategoriaDropdown.innerHTML = optionsHtml;
                        subcategoriaSearch.placeholder = ' ';
                        
                        // Reinicializar eventos para las nuevas opciones
                        initializeSearchField('subcategoria_search', 'subcategoria_id', 'subcategoria_dropdown');
                    })
                    .catch(error => {
                        // Error silencioso para evitar logs en consola
                        if (subcategoriaDropdown) {
                            subcategoriaDropdown.innerHTML = '<div class="search-option" data-value="" data-text="">Error cargando subcategorías</div>';
                        }
                        if (subcategoriaSearch) {
                            subcategoriaSearch.placeholder = ' ';
                        }
                    });
            }
            } catch (error) {
                // Error silencioso
            }
        }

        function clearSubcategories() {
            try {
                const subcategoriaSearch = document.getElementById('subcategoria_search');
                const subcategoriaHidden = document.getElementById('subcategoria_id');
                const subcategoriaDropdown = document.getElementById('subcategoria_dropdown');
                
                if (subcategoriaSearch) {
                    subcategoriaSearch.disabled = true;
                    subcategoriaSearch.placeholder = 'Seleccione primero una categoría';
                    subcategoriaSearch.value = '';
                    subcategoriaSearch.classList.remove('has-value');
                }
                
                if (subcategoriaHidden) {
                    subcategoriaHidden.value = '';
                }
                
                if (subcategoriaDropdown) {
                    subcategoriaDropdown.innerHTML = '';
                }
            } catch (error) {
                // Error silencioso
            }
        }

        // Inicialización segura cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Inicializar campos de búsqueda solo si existen
                if (document.getElementById('categoria_search')) {
                    initializeSearchField('categoria_search', 'categoria_id', 'categoria_dropdown');
                }
            } catch (error) {
                // Error silencioso para evitar mostrar en consola
            }
        });

        // Validación del formulario con manejo de errores
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('productForm');
            if (form) {
                form.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const stock = document.getElementById('stock').value;
            const precioCompra = parseFloat(document.getElementById('precio_compra').value);
            const precioVenta = parseFloat(document.getElementById('precio_venta').value);
            
            if (nombre.length < 3) {
                e.preventDefault();
                alert('El nombre del producto debe tener al menos 3 caracteres');
                return;
            }
            
            if (stock < 0) {
                e.preventDefault();
                alert('El stock no puede ser negativo');
                return;
            }
            
            if (precioCompra >= precioVenta) {
                e.preventDefault();
                alert('El precio de venta debe ser mayor al precio de compra');
                return;
            }
                });
            }
        });

        // Efectos visuales con manejo seguro
        document.addEventListener('DOMContentLoaded', function() {
            try {
                document.querySelectorAll('.form-control-modern, .form-select-modern').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
                });

                // Manejo de labels para selects
                document.querySelectorAll('.form-select-modern').forEach(select => {
            select.addEventListener('change', function() {
                const label = this.parentElement.querySelector('label');
                if (this.value) {
                    label.style.top = '2px';
                    label.style.fontSize = '12px';
                    label.style.color = '#667eea';
                } else {
                    label.style.top = '12px';
                    label.style.fontSize = '14px';
                    label.style.color = 'rgba(102, 126, 234, 0.8)';
                }
                });
                });
            } catch (error) {
                // Error silencioso para efectos visuales
            }
        });

        // Establecer fecha actual por defecto (con manejo robusto de errores)
        document.addEventListener('DOMContentLoaded', function() {
            const fecha = document.getElementById('fecha_entrada');
            if (!fecha) return;

            // Si el input soporta valueAsDate, úsalo; si no, aplicar fallback a YYYY-MM-DD
            try {
                if ('valueAsDate' in fecha) {
                    fecha.valueAsDate = new Date();
                } else {
                    const d = new Date();
                    const yyyy = d.getFullYear();
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const dd = String(d.getDate()).padStart(2, '0');
                    fecha.value = `${yyyy}-${mm}-${dd}`;
                }
            } catch (e) {
                // En caso de cualquier error, aplicar formato seguro
                const d = new Date();
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                fecha.value = `${yyyy}-${mm}-${dd}`;
            }
        });
    </script>
</body>
</html>