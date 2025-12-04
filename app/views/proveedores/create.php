<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Proveedor - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        /* Estilos para dropdowns personalizados */
        .categoria-dropdown,
        .subcategoria-dropdown {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 0 0 10px 10px !important;
            z-index: 9999 !important;
            position: absolute !important;
        }
        
        .dropdown-option {
            background-color: white;
            transition: background-color 0.2s ease;
        }
        
        .dropdown-option:hover {
            background-color: #e3f2fd !important;
        }
        
        .dropdown-option:last-child {
            border-bottom: none !important;
        }
        
        /* Asegurar que los contenedores padre tengan posición relativa */
        .filter-container {
            position: relative;
            z-index: 1;
        }
        
        /* Asegurar que el texto sea visible en los inputs */
        #filtro_categoria_input,
        #filtro_subcategoria_input {
            text-align: left !important;
            text-indent: 0 !important;
            padding: 10px 40px 10px 15px !important;
            font-size: 14px;
            line-height: 1.4;
            margin: 0 !important;
            border: 2px solid rgba(255, 255, 255, 0.5) !important;
            box-sizing: border-box !important;
            vertical-align: top !important;
        }
        
        /* Estados específicos para subcategoría */
        #filtro_subcategoria_input:not([disabled]) {
            background: rgba(255, 255, 255, 0.9) !important;
            color: #333 !important;
            cursor: text !important;
        }
        
        #filtro_subcategoria_input[disabled] {
            background: rgba(255, 255, 255, 0.5) !important;
            color: #999 !important;
            cursor: not-allowed !important;
        }
        
        /* Limpiar cualquier contenido pseudo */
        #filtro_categoria_input:before,
        #filtro_categoria_input:after,
        #filtro_subcategoria_input:before,
        #filtro_subcategoria_input:after {
            content: none !important;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
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
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .header-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-title i {
            font-size: 2.8rem;
            opacity: 0.9;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-section {
            padding: 40px 30px;
        }
        
        .section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
        }
        
        .section-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .section-title i {
            font-size: 1.5rem;
            opacity: 0.9;
        }
        
        .form-row {
            display: grid;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-row-2 {
            grid-template-columns: 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2 {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        .form-floating-modern {
            position: relative;
            margin-bottom: 20px;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .form-control-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-select-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-textarea-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            resize: vertical;
            min-height: 120px;
        }
        
        .form-textarea-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
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
        
        .required {
            color: #ff6b6b;
            font-weight: bold;
        }
        
        .form-control-modern:focus ~ label,
        .form-control-modern:not(:placeholder-shown) ~ label,
        .form-select-modern:focus ~ label,
        .form-select-modern:not([value=""]) ~ label,
        .form-textarea-modern:focus ~ label,
        .form-textarea-modern:not(:placeholder-shown) ~ label {
            top: 2px;
            font-size: 12px;
            color: #667eea;
        }
        
        .preview-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        
        .provider-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(255, 152, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .provider-avatar-large:hover {
            transform: scale(1.05);
        }
        
        .preview-name {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .preview-email {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .preview-phone {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
        }
        
        .status-activo {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .status-inactivo {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .status-pendiente {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        }
        
        /* CSS para grupo de checkboxes de productos */
        .productos-checkbox-group {
            background-color: rgba(255, 255, 255, 0.95) !important;
            border: 2px solid rgba(255, 255, 255, 0.6) !important;
            border-radius: 12px !important;
            padding: 15px !important;
            min-height: 120px !important;
            max-height: 350px !important;
            overflow-y: auto !important;
            box-sizing: border-box !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
        }

        .productos-checkbox-group:hover {
            border-color: rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2) !important;
        }

        .productos-checkbox-group:focus-within {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3), 0 5px 15px rgba(102, 126, 234, 0.2) !important;
        }

        .producto-item {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 8px 10px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }

        .producto-item:hover {
            background-color: rgba(102, 126, 234, 0.08) !important;
        }

        .producto-checkbox {
            position: absolute !important;
            opacity: 0 !important;
            cursor: pointer !important;
            width: 0 !important;
            height: 0 !important;
        }

        .producto-label {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            cursor: pointer !important;
            margin: 0 !important;
            flex: 1 !important;
            user-select: none !important;
        }

        .checkbox-custom {
            width: 22px !important;
            height: 22px !important;
            border: 2px solid #667eea !important;
            border-radius: 6px !important;
            background-color: white !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            transition: all 0.2s ease !important;
        }

        .producto-checkbox:checked + .producto-label .checkbox-custom {
            background-color: #667eea !important;
            border-color: #667eea !important;
            box-shadow: inset 0 0 0 3px white !important;
        }

        .producto-checkbox:checked + .producto-label .checkbox-custom::after {
            content: '✓' !important;
            color: white !important;
            font-size: 14px !important;
            font-weight: bold !important;
        }

        .producto-nombre {
            font-weight: 500 !important;
            color: #333 !important;
            font-size: 14px !important;
        }

        .producto-id {
            font-size: 12px !important;
            color: #999 !important;
        }

        .producto-checkbox:checked + .producto-label .producto-nombre {
            color: #667eea !important;
            font-weight: 600 !important;
        }

        .productos-count {
            font-size: 12px !important;
            color: rgba(102, 126, 234, 0.7) !important;
            font-weight: 400 !important;
            margin-left: 8px !important;
        }

        .producto-item.hidden {
            display: none !important;
        }

        .producto-item.visible {
            display: flex !important;
        }

        #buscarProducto {
            transition: all 0.3s ease !important;
        }

        #buscarProducto:focus {
            outline: none !important;
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2) !important;
            background: rgba(255, 255, 255, 0.95) !important;
        }

        #buscarProducto::placeholder {
            color: #999 !important;
        }

        .no-results-message {
            text-align: center;
            padding: 30px 15px;
            color: #999;
            font-size: 14px;
            display: none;
        }

        .no-results-message.show {
            display: block;
        }
            margin: 0 4px;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }
        
        .info-panel {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .info-panel h6 {
            color: #ffc107;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-panel ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .info-panel li {
            margin-bottom: 5px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .buttons-section {
            padding: 20px 30px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 15px 35px;
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
            backdrop-filter: blur(10px);
            min-width: 180px;
            justify-content: center;
        }
        
        .btn-create {
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 152, 0, 0.4);
            background: linear-gradient(135deg, #f57c00 0%, #e64a19 100%);
            color: white;
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
        
        .alert-modern {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .character-count {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            text-align: right;
            margin-top: 5px;
        }
        
        .form-help {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-help i {
            color: #ffc107;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 0 10px;
            }
            
            .form-container {
                border-radius: 20px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .section-card {
                padding: 20px;
                margin-bottom: 20px;
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
<body>
    <div class="main-container">
        <div class="form-container">
            <!-- Header -->
            <div class="header-section">
                <h1 class="header-title">
                    <i class="fas fa-plus-circle"></i>
                    Agregar Nuevo Proveedor
                </h1>
                <p class="header-subtitle">Complete la información para registrar un nuevo proveedor en el sistema</p>
            </div>

            <!-- Error Alert -->
            <?php if (isset($error)): ?>
                <div class="form-section">
                    <div class="alert-modern">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="form-section">
                <form method="POST" action="/RMIE/app/controllers/ProviderController.php?accion=create" id="formProveedor">
                    
                    <div class="form-row form-row-2">
                        <!-- Información de la Empresa -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-building"></i>
                                Información de la Empresa
                            </div>
                            
                            <div class="form-floating-modern">
                                <input type="text" 
                                       class="form-control-modern" 
                                       id="nombre_distribuidor" 
                                       name="nombre_distribuidor" 
                                       placeholder=" "
                                       maxlength="100"
                                       required
                                       value="<?= htmlspecialchars($_POST['nombre_distribuidor'] ?? '') ?>">
                                <label for="nombre_distribuidor">
                                    <i class="fas fa-truck"></i>
                                    Nombre del Distribuidor/Empresa <span class="required">*</span>
                                </label>
                                <div class="character-count">
                                    <span id="nombre-count">0</span>/100
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Nombre comercial o razón social de la empresa
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <select class="form-select-modern" 
                                        id="estado" 
                                        name="estado" 
                                        required>
                                    <option value="">Seleccione un estado</option>
                                    <option value="activo" <?= ($_POST['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>🟢 Activo</option>
                                    <option value="inactivo" <?= ($_POST['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>🔴 Inactivo</option>
                                    <option value="pendiente" <?= ($_POST['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>🟡 Pendiente</option>
                                </select>
                                <label for="estado">
                                    <i class="fas fa-toggle-on"></i>
                                    Estado del Proveedor <span class="required">*</span>
                                </label>
                            </div>

                            <div class="form-floating-modern">
                                <textarea class="form-textarea-modern" 
                                          id="ubicacion" 
                                          name="ubicacion" 
                                          placeholder=" "
                                          maxlength="255"><?= htmlspecialchars($_POST['ubicacion'] ?? '') ?></textarea>
                                <label for="ubicacion">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Dirección Completa (Opcional)
                                </label>
                                <div class="character-count">
                                    <span id="ubicacion-count">0</span>/255
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Campo opcional pero recomendado para facilitar entregas y visitas
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto y Vista Previa -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-address-book"></i>
                                Contacto y Vista Previa
                            </div>
                            
                            <div class="form-floating-modern">
                                <input type="email" 
                                       class="form-control-modern" 
                                       id="correo" 
                                       name="correo" 
                                       placeholder=" "
                                       maxlength="100"
                                       required
                                       value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                                <label for="correo">
                                    <i class="fas fa-envelope"></i>
                                    Correo Electrónico <span class="required">*</span>
                                </label>
                                <div class="character-count">
                                    <span id="correo-count">0</span>/100
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-shield-alt"></i>
                                    Será utilizado para comunicaciones oficiales
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <input type="tel" 
                                       class="form-control-modern" 
                                       id="cel_proveedor" 
                                       name="cel_proveedor" 
                                       placeholder=" "
                                       maxlength="20"
                                       required
                                       value="<?= htmlspecialchars($_POST['cel_proveedor'] ?? '') ?>">
                                <label for="cel_proveedor">
                                    <i class="fas fa-phone"></i>
                                    Número de Celular <span class="required">*</span>
                                </label>
                                <div class="character-count">
                                    <span id="cel_proveedor-count">0</span>/20
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-mobile-alt"></i>
                                    Incluya código de país si es internacional
                                </div>
                            </div>

                            <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 20px; padding: 30px; margin-bottom: 30px; border: 1px solid rgba(255, 255, 255, 0.1);">
                                <h5 style="color: white; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-boxes"></i>
                                    Productos a asociar <span class="productos-count" id="productosCount" style="font-size: 12px; color: rgba(102, 126, 234, 0.7); font-weight: 400; margin-left: 8px;">(0 seleccionados)</span>
                                </h5>
                                
                                <!-- Filtros de Categoría y Subcategoría -->
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                    <div class="filter-container" style="position: relative; z-index: 10;">
                                        <label style="color: white; font-size: 14px; margin-bottom: 5px; display: block;">
                                            <i class="fas fa-layer-group"></i> Filtrar por Categoría
                                        </label>
                                        <div style="position: relative;">
                                            <input type="text" 
                                                   id="filtro_categoria_input" 
                                                   class="form-control-modern" 
                                                   placeholder="🔍 Buscar o seleccionar categoría..."
                                                   autocomplete="off"
                                                   style="width: 100%; padding: 10px 40px 10px 15px; background: rgba(255, 255, 255, 0.9); border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 10px; color: #333; text-indent: 0; padding-left: 15px;">
                                            <div id="dropdown_categoria" class="categoria-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid rgba(255, 255, 255, 0.5); border-top: none; border-radius: 0 0 10px 10px; max-height: 200px; overflow-y: auto; z-index: 9999;">
                                                <div class="dropdown-option" data-value="" style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;">Todas las categorías</div>
                                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                                    <?php foreach ($categorias as $categoria): ?>
                                                        <div class="dropdown-option" 
                                                             data-value="<?= htmlspecialchars($categoria->id_categoria ?? $categoria['id_categoria'] ?? '') ?>"
                                                             style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;">
                                                            <?= htmlspecialchars($categoria->nombre ?? $categoria['nombre'] ?? '') ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                            <i class="fas fa-chevron-down" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #666; pointer-events: none;"></i>
                                        </div>
                                        <input type="hidden" id="filtro_categoria" value="">
                                    </div>
                                    <div class="filter-container" style="position: relative; z-index: 9;">
                                        <label style="color: white; font-size: 14px; margin-bottom: 5px; display: block;">
                                            <i class="fas fa-tags"></i> Filtrar por Subcategoría
                                        </label>
                                        <div style="position: relative;">
                                            <input type="text" 
                                                   id="filtro_subcategoria_input" 
                                                   class="form-control-modern" 
                                                   placeholder="Seleccione primero una categoría..."
                                                   autocomplete="off"
                                                   style="width: 100%; padding: 10px 40px 10px 15px; background: rgba(255, 255, 255, 0.9); border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 10px; color: #333; text-indent: 0; padding-left: 15px;"
                                                   disabled>
                                            <div id="dropdown_subcategoria" class="subcategoria-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid rgba(255, 255, 255, 0.5); border-top: none; border-radius: 0 0 10px 10px; max-height: 200px; overflow-y: auto; z-index: 9999;">
                                                <div class="dropdown-option" data-value="" style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;">Seleccione primero una categoría</div>
                                            </div>
                                            <i class="fas fa-chevron-down" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #666; pointer-events: none;"></i>
                                        </div>
                                        <input type="hidden" id="filtro_subcategoria" value="">
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 15px;">
                                    <input type="text" 
                                           id="buscarProducto" 
                                           placeholder="🔍 Buscar productos por nombre..." 
                                           class="form-control-modern"
                                           style="width: 100%; padding: 12px 15px; background: rgba(255, 255, 255, 0.9); border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 10px; font-size: 14px; color: #333;">
                                </div>
                                
                                <div class="productos-checkbox-group">
                                    <?php if (!empty($productos) && is_array($productos)): ?>
                                        <?php foreach ($productos as $prod): ?>
                                            <?php $selected = (isset($_POST['productos']) && is_array($_POST['productos']) && in_array($prod->id_productos, array_map('intval', $_POST['productos']))) ? 'checked' : ''; ?>
                                            <div class="producto-item visible" 
                                                 data-nombre="<?= strtolower(htmlspecialchars($prod->nombre)) ?>"
                                                 data-categoria-id="<?= htmlspecialchars($prod->id_categoria ?? '') ?>"
                                                 data-subcategoria-id="<?= htmlspecialchars($prod->id_subcategoria ?? '') ?>">
                                                <input type="checkbox" 
                                                       id="prod_<?= $prod->id_productos ?>" 
                                                       name="productos[]" 
                                                       value="<?= $prod->id_productos ?>" 
                                                       class="producto-checkbox"
                                                       <?= $selected ?>>
                                                <label for="prod_<?= $prod->id_productos ?>" class="producto-label">
                                                    <span class="checkbox-custom"></span>
                                                    <span class="producto-nombre"><?= htmlspecialchars($prod->nombre) ?></span>
                                                    <span class="producto-id">(ID: <?= $prod->id_productos ?>)</span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="no-results-message" id="noResultsMessage">
                                            <i class="fas fa-search" style="font-size: 24px; opacity: 0.5; margin-bottom: 10px;"></i>
                                            <p>No se encontraron productos que coincidan con tu búsqueda</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert-modern" style="margin: 0; padding: 15px;">
                                            <i class="fas fa-info-circle"></i> No hay productos disponibles
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-help" style="margin-top: 15px;">
                                    <i class="fas fa-info-circle"></i>
                                    Seleccione los productos que este proveedor suministra. Puede seleccionar uno o varios productos.
                                </div>
                            </div>

                            <!-- Vista Previa -->
                            <div class="preview-section">
                                <h6 style="color: rgba(255, 255, 255, 0.9); margin-bottom: 20px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fas fa-eye"></i> Vista Previa del Proveedor
                                </h6>
                                
                                <div class="provider-avatar-large" id="previewAvatar">
                                    <i class="fas fa-truck"></i>
                                </div>
                                
                                <div class="preview-name" id="previewName">Nombre del Proveedor</div>
                                
                                <div class="preview-email" id="previewEmail">
                                    <i class="fas fa-envelope"></i>
                                    <span>proveedor@empresa.com</span>
                                </div>
                                
                                <div class="preview-phone" id="previewPhone">
                                    <i class="fas fa-phone"></i>
                                    <span id="previewPhoneText">+57 300 123 4567</span>
                                </div>
                                
                                <div class="preview-status" id="previewStatus">
                                    <span class="status-badge status-activo">
                                        <i class="fas fa-check-circle"></i> Activo
                                    </span>
                                </div>
                            </div>

                            <div class="info-panel">
                                <h6><i class="fas fa-lightbulb"></i> Consejos Útiles</h6>
                                <ul>
                                    <li>Use un nombre claro y descriptivo para la empresa</li>
                                    <li>Verifique el correo electrónico antes de guardar</li>
                                    <li>El estado "Pendiente" es útil para proveedores en evaluación</li>
                                    <li>La dirección completa facilita las entregas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Buttons -->
            <div class="buttons-section">
                <button type="submit" form="formProveedor" class="btn-modern btn-create">
                    <i class="fas fa-save"></i>
                    REGISTRAR PROVEEDOR
                </button>
                <a href="/RMIE/app/controllers/ProviderController.php?accion=index" class="btn-modern btn-cancel">
                    <i class="fas fa-times"></i>
                    CANCELAR
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formProveedor');
            
            // El select nativo se maneja automáticamente por el navegador
            // No necesita JavaScript adicional
            
            // Contadores de caracteres
            function setupCharacterCount(inputId, countId, maxLength) {
                const input = document.getElementById(inputId);
                const counter = document.getElementById(countId);
                
                if (input && counter) {
                    function updateCount() {
                        const count = input.value.length;
                        counter.textContent = count;
                        counter.style.color = count > maxLength * 0.8 ? '#ff9800' : 'rgba(255, 255, 255, 0.7)';
                    }
                    
                    input.addEventListener('input', updateCount);
                    updateCount(); // Inicializar
                }
            }
            
            setupCharacterCount('nombre_distribuidor', 'nombre-count', 100);
            setupCharacterCount('correo', 'correo-count', 100);
            setupCharacterCount('cel_proveedor', 'cel_proveedor-count', 20);
            setupCharacterCount('ubicacion', 'ubicacion-count', 255);
            
            // Contador de productos seleccionados
            const productosCheckboxes = document.querySelectorAll('.producto-checkbox');
            const productosCount = document.getElementById('productosCount');
            
            if (productosCheckboxes && productosCount) {
                function updateProductosCount() {
                    const selectedCount = Array.from(productosCheckboxes).filter(cb => cb.checked).length;
                    productosCount.textContent = `(${selectedCount} seleccionado${selectedCount !== 1 ? 's' : ''})`;
                    
                    // Cambiar color según selección
                    if (selectedCount > 0) {
                        productosCount.style.color = '#667eea';
                    } else {
                        productosCount.style.color = 'rgba(102, 126, 234, 0.5)';
                    }
                }
                
                productosCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateProductosCount);
                });
                updateProductosCount(); // Inicializar
            }
            
            // Filtro de búsqueda de productos
            const buscarProductoInput = document.getElementById('buscarProducto');
            const productoItems = document.querySelectorAll('.producto-item');
            const noResultsMessage = document.getElementById('noResultsMessage');
            
            if (buscarProductoInput && productoItems.length > 0) {
                buscarProductoInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    let visibleCount = 0;
                    
                    productoItems.forEach(item => {
                        const nombreProducto = item.getAttribute('data-nombre') || '';
                        
                        if (searchTerm === '' || nombreProducto.includes(searchTerm)) {
                            item.classList.remove('hidden');
                            item.classList.add('visible');
                            visibleCount++;
                        } else {
                            item.classList.remove('visible');
                            item.classList.add('hidden');
                        }
                    });
                    
                    // Mostrar/ocultar mensaje de "no hay resultados"
                    if (noResultsMessage) {
                        if (visibleCount === 0 && searchTerm !== '') {
                            noResultsMessage.classList.add('show');
                        } else {
                            noResultsMessage.classList.remove('show');
                        }
                    }
                });
            }
            
            // Filtros de categoría y subcategoría
            const filtroCategoria = document.getElementById('filtro_categoria');
            const filtroSubcategoria = document.getElementById('filtro_subcategoria');
            
            // Datos de subcategorías por categoría (se cargarán dinámicamente)
            const subcategoriasPorCategoria = <?= json_encode($subcategorias_por_categoria ?? []) ?>;
            console.log('Subcategorías disponibles:', subcategoriasPorCategoria);
            
            // Referencias a los nuevos elementos unificados
            const categoriaInput = document.getElementById('filtro_categoria_input');
            const categoriaDropdown = document.getElementById('dropdown_categoria');
            const categoriaHidden = document.getElementById('filtro_categoria');
            
            // Función para limpiar y establecer valor del input
            function setInputValue(input, value) {
                if (!input) return;
                input.value = '';
                input.blur();
                input.focus();
                input.value = value.trim();
                input.blur();
            }
            
            const subcategoriaInput = document.getElementById('filtro_subcategoria_input');
            const subcategoriaDropdown = document.getElementById('dropdown_subcategoria');
            const subcategoriaHidden = document.getElementById('filtro_subcategoria');
            
            // Función para manejar el dropdown de categorías
            function setupCategoriaDropdown() {
                if (!categoriaInput || !categoriaDropdown) return;
                
                const options = categoriaDropdown.querySelectorAll('.dropdown-option');
                let filteredOptions = Array.from(options);
                
                // Mostrar dropdown al hacer clic o escribir
                categoriaInput.addEventListener('focus', () => {
                    categoriaDropdown.style.display = 'block';
                });
                
                categoriaInput.addEventListener('click', () => {
                    categoriaDropdown.style.display = 'block';
                });
                
                // Filtrar opciones mientras se escribe
                categoriaInput.addEventListener('input', () => {
                    const busqueda = categoriaInput.value.toLowerCase().trim();
                    let hasVisibleOptions = false;
                    
                    options.forEach(option => {
                        const texto = option.textContent.toLowerCase();
                        const coincide = busqueda === '' || texto.includes(busqueda);
                        option.style.display = coincide ? 'block' : 'none';
                        if (coincide) hasVisibleOptions = true;
                    });
                    
                    // Solo mostrar dropdown si hay opciones visibles
                    categoriaDropdown.style.display = hasVisibleOptions ? 'block' : 'none';
                    
                    // Si el texto coincide exactamente con una opción, seleccionarla
                    if (busqueda.length > 0) {
                        const exactMatch = Array.from(options).find(opt => 
                            opt.textContent.toLowerCase() === busqueda
                        );
                        if (exactMatch && exactMatch.style.display !== 'none') {
                            const value = exactMatch.getAttribute('data-value');
                            categoriaHidden.value = value;
                            cargarSubcategorias();
                            aplicarFiltros();
                        }
                    } else {
                        // Si se borra todo el texto, limpiar selección
                        categoriaHidden.value = '';
                        cargarSubcategorias();
                        aplicarFiltros();
                    }
                });
                
                // Manejar selección de opciones
                options.forEach(option => {
                    option.addEventListener('click', () => {
                        const value = option.getAttribute('data-value');
                        const text = option.textContent.trim(); // Limpiar espacios
                        
                        // Usar función específica para limpiar y establecer valor
                        setInputValue(categoriaInput, text);
                        categoriaHidden.value = value;
                        categoriaDropdown.style.display = 'none';
                        
                        console.log('Categoría seleccionada:', text, 'ID:', value);
                        
                        // Cargar subcategorías
                        cargarSubcategorias();
                        
                        // Forzar habilitación del campo de subcategoría
                        setTimeout(() => {
                            if (subcategoriaInput) {
                                subcategoriaInput.disabled = false;
                                subcategoriaInput.removeAttribute('disabled');
                                console.log('Subcategoría forzadamente habilitada');
                            }
                        }, 100);
                        
                        aplicarFiltros();
                    });
                    
                    // Hover effects
                    option.addEventListener('mouseenter', () => {
                        option.style.backgroundColor = '#f0f0f0';
                    });
                    option.addEventListener('mouseleave', () => {
                        option.style.backgroundColor = '';
                    });
                });
                
                // Manejar navegación con teclado
                categoriaInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        categoriaDropdown.style.display = 'none';
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        const visibleOptions = Array.from(options).filter(opt => 
                            opt.style.display !== 'none'
                        );
                        if (visibleOptions.length > 0) {
                            // Seleccionar la primera opción visible
                            const firstOption = visibleOptions[0];
                            const value = firstOption.getAttribute('data-value');
                            const text = firstOption.textContent;
                            
                            setInputValue(categoriaInput, text);
                            categoriaHidden.value = value;
                            categoriaDropdown.style.display = 'none';
                            
                            cargarSubcategorias();
                            
                            // Forzar habilitación del campo de subcategoría
                            setTimeout(() => {
                                if (subcategoriaInput) {
                                    subcategoriaInput.disabled = false;
                                    subcategoriaInput.removeAttribute('disabled');
                                    console.log('Subcategoría habilitada después de Enter');
                                }
                            }, 100);
                            
                            aplicarFiltros();
                        }
                    }
                });
                
                // Cerrar dropdown al hacer clic fuera
                document.addEventListener('click', (e) => {
                    if (!categoriaInput.contains(e.target) && !categoriaDropdown.contains(e.target)) {
                        categoriaDropdown.style.display = 'none';
                    }
                });
            }
            
            // Función para manejar el dropdown de subcategorías
            function setupSubcategoriaDropdown() {
                if (!subcategoriaInput || !subcategoriaDropdown) return;
                
                // Mostrar dropdown al hacer clic o escribir (solo si está habilitado)
                subcategoriaInput.addEventListener('focus', (e) => {
                    console.log('Focus en subcategoría, disabled:', subcategoriaInput.disabled);
                    if (!subcategoriaInput.disabled && !subcategoriaInput.hasAttribute('disabled')) {
                        subcategoriaDropdown.style.display = 'block';
                    }
                });
                
                subcategoriaInput.addEventListener('click', (e) => {
                    console.log('Click en subcategoría, disabled:', subcategoriaInput.disabled);
                    if (!subcategoriaInput.disabled && !subcategoriaInput.hasAttribute('disabled')) {
                        subcategoriaDropdown.style.display = 'block';
                    } else {
                        console.log('Subcategoría deshabilitada, no se puede abrir dropdown');
                    }
                });
                
                // Filtrar opciones mientras se escribe
                subcategoriaInput.addEventListener('input', () => {
                    if (subcategoriaInput.disabled) return;
                    
                    const busqueda = subcategoriaInput.value.toLowerCase().trim();
                    const options = subcategoriaDropdown.querySelectorAll('.dropdown-option');
                    let hasVisibleOptions = false;
                    
                    options.forEach(option => {
                        const texto = option.textContent.toLowerCase();
                        const coincide = busqueda === '' || texto.includes(busqueda);
                        option.style.display = coincide ? 'block' : 'none';
                        if (coincide) hasVisibleOptions = true;
                    });
                    
                    // Solo mostrar dropdown si hay opciones visibles
                    subcategoriaDropdown.style.display = hasVisibleOptions ? 'block' : 'none';
                    
                    // Si el texto coincide exactamente con una opción, seleccionarla
                    if (busqueda.length > 0) {
                        const exactMatch = Array.from(options).find(opt => 
                            opt.textContent.toLowerCase() === busqueda
                        );
                        if (exactMatch && exactMatch.style.display !== 'none') {
                            const value = exactMatch.getAttribute('data-value');
                            subcategoriaHidden.value = value;
                            aplicarFiltros();
                        }
                    } else {
                        // Si se borra todo el texto, limpiar selección
                        subcategoriaHidden.value = '';
                        aplicarFiltros();
                    }
                });
                
                // Manejar navegación con teclado
                subcategoriaInput.addEventListener('keydown', (e) => {
                    if (subcategoriaInput.disabled) return;
                    
                    if (e.key === 'Escape') {
                        subcategoriaDropdown.style.display = 'none';
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        const options = subcategoriaDropdown.querySelectorAll('.dropdown-option');
                        const visibleOptions = Array.from(options).filter(opt => 
                            opt.style.display !== 'none'
                        );
                        if (visibleOptions.length > 0) {
                            // Seleccionar la primera opción visible
                            const firstOption = visibleOptions[0];
                            const value = firstOption.getAttribute('data-value');
                            const text = firstOption.textContent;
                            
                            setInputValue(subcategoriaInput, text);
                            subcategoriaHidden.value = value;
                            subcategoriaDropdown.style.display = 'none';
                            
                            aplicarFiltros();
                        }
                    }
                });
                
                // Cerrar dropdown al hacer clic fuera
                document.addEventListener('click', (e) => {
                    if (!subcategoriaInput.contains(e.target) && !subcategoriaDropdown.contains(e.target)) {
                        subcategoriaDropdown.style.display = 'none';
                    }
                });
            }
            
            // Función para aplicar todos los filtros
            function aplicarFiltros() {
                const searchTerm = buscarProductoInput ? buscarProductoInput.value.toLowerCase().trim() : '';
                const categoriaId = categoriaHidden ? categoriaHidden.value : '';
                const subcategoriaId = subcategoriaHidden ? subcategoriaHidden.value : '';
                
                let visibleCount = 0;
                
                productoItems.forEach(item => {
                    const nombreProducto = item.getAttribute('data-nombre') || '';
                    const itemCategoriaId = item.getAttribute('data-categoria-id') || '';
                    const itemSubcategoriaId = item.getAttribute('data-subcategoria-id') || '';
                    
                    let mostrar = true;
                    
                    // Filtrar por texto
                    if (searchTerm && !nombreProducto.includes(searchTerm)) {
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
                        item.classList.remove('hidden');
                        item.classList.add('visible');
                        visibleCount++;
                    } else {
                        item.classList.remove('visible');
                        item.classList.add('hidden');
                    }
                });
                
                // Mostrar/ocultar mensaje de "no hay resultados"
                if (noResultsMessage) {
                    if (visibleCount === 0 && (searchTerm || categoriaId || subcategoriaId)) {
                        noResultsMessage.classList.add('show');
                    } else {
                        noResultsMessage.classList.remove('show');
                    }
                }
            }
            
            // Event listeners para los filtros
            if (buscarProductoInput) {
                buscarProductoInput.removeEventListener('input', buscarProductoInput.listener);
                buscarProductoInput.addEventListener('input', aplicarFiltros);
            }
            
            // Función para cargar subcategorías
            function cargarSubcategorias() {
                const categoriaId = categoriaHidden.value;
                console.log('Cargando subcategorías para categoría ID:', categoriaId);
                console.log('Datos disponibles para esta categoría:', subcategoriasPorCategoria[categoriaId]);
                
                // Limpiar subcategorías
                subcategoriaDropdown.innerHTML = '<div class="dropdown-option" data-value="" style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;">Todas las subcategorías</div>';
                
                if (categoriaId && categoriaId !== '') {
                    // Habilitar subcategorías siempre que haya una categoría seleccionada
                    subcategoriaInput.disabled = false;
                    subcategoriaInput.removeAttribute('disabled');
                    subcategoriaInput.placeholder = "🔍 Buscar o seleccionar subcategoría...";
                    subcategoriaInput.style.backgroundColor = "rgba(255, 255, 255, 0.9)";
                    subcategoriaInput.style.color = "#333";
                    subcategoriaInput.value = "";
                    subcategoriaHidden.value = "";
                    
                    console.log('Habilitando subcategorías para categoría:', categoriaId);
                    
                    const subcategorias = subcategoriasPorCategoria[categoriaId] || [];
                    console.log('Subcategorías encontradas:', subcategorias);
                    console.log('Cantidad de subcategorías:', subcategorias.length);
                    
                    if (subcategorias.length === 0) {
                        console.warn('No se encontraron subcategorías para la categoría', categoriaId);
                        // Mostrar mensaje de que no hay subcategorías
                        subcategoriaDropdown.innerHTML = '<div class="dropdown-option" data-value="" style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee; color: #999;">No hay subcategorías disponibles</div>';
                    } else {
                        subcategorias.forEach(subcat => {
                        const div = document.createElement('div');
                        div.className = 'dropdown-option';
                        div.setAttribute('data-value', subcat.id);
                        div.style.cssText = 'padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;';
                        div.textContent = subcat.nombre;
                        
                        // Añadir event listeners a la nueva opción
                        div.addEventListener('click', () => {
                            console.log('Seleccionando subcategoría:', subcat.nombre);
                            setInputValue(subcategoriaInput, subcat.nombre);
                            subcategoriaHidden.value = subcat.id;
                            subcategoriaDropdown.style.display = 'none';
                            aplicarFiltros();
                        });
                        
                        div.addEventListener('mouseenter', () => {
                            div.style.backgroundColor = '#e3f2fd';
                        });
                        div.addEventListener('mouseleave', () => {
                            div.style.backgroundColor = '';
                        });
                        
                        subcategoriaDropdown.appendChild(div);
                    });
                    }
                    
                    // Re-añadir event listener a "Todas las subcategorías" (si existe)
                    const todasOption = subcategoriaDropdown.querySelector('.dropdown-option[data-value=""]');
                    if (todasOption) {
                        todasOption.addEventListener('click', () => {
                            console.log('Seleccionando todas las subcategorías');
                            setInputValue(subcategoriaInput, 'Todas las subcategorías');
                            subcategoriaHidden.value = '';
                            subcategoriaDropdown.style.display = 'none';
                            aplicarFiltros();
                        });
                        
                        todasOption.addEventListener('mouseenter', () => {
                            todasOption.style.backgroundColor = '#e3f2fd';
                        });
                        todasOption.addEventListener('mouseleave', () => {
                            todasOption.style.backgroundColor = '';
                        });
                    }
                } else {
                    // Si no hay categoría seleccionada, deshabilitar subcategorías
                    subcategoriaInput.disabled = true;
                    subcategoriaInput.setAttribute('disabled', 'disabled');
                    subcategoriaInput.placeholder = "Seleccione primero una categoría...";
                    subcategoriaInput.style.backgroundColor = "rgba(255, 255, 255, 0.5)";
                    subcategoriaInput.style.color = "#999";
                    subcategoriaInput.value = "";
                    subcategoriaHidden.value = "";
                    subcategoriaDropdown.innerHTML = '<div class="dropdown-option" data-value="" style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;">Seleccione primero una categoría</div>';
                }
            }
            
            // Función para inicializar valores por defecto
            function inicializarFiltros() {
                if (categoriaInput) {
                    categoriaInput.value = '';
                    categoriaInput.placeholder = '🔍 Buscar o seleccionar categoría...';
                }
                if (subcategoriaInput) {
                    subcategoriaInput.value = '';
                    subcategoriaInput.placeholder = 'Seleccione primero una categoría...';
                }
                if (categoriaHidden) {
                    categoriaHidden.value = '';
                }
                if (subcategoriaHidden) {
                    subcategoriaHidden.value = '';
                }
            }
            
            // Inicializar los dropdowns unificados
            setupCategoriaDropdown();
            setupSubcategoriaDropdown();
            inicializarFiltros();
            
            // Vista previa en tiempo real
            function actualizarVistaPrevia() {
                const nombre = document.getElementById('nombre_distribuidor').value;
                const correo = document.getElementById('correo').value;
                const telefono = document.getElementById('cel_proveedor').value;
                const estado = document.getElementById('estado').value;
                
                // Actualizar avatar
                const avatar = document.getElementById('previewAvatar');
                if (nombre) {
                    avatar.innerHTML = nombre.charAt(0).toUpperCase();
                } else {
                    avatar.innerHTML = '<i class="fas fa-truck"></i>';
                }
                
                // Actualizar nombre
                document.getElementById('previewName').textContent = nombre || 'Nombre del Proveedor';
                
                // Actualizar correo
                const emailSpan = document.querySelector('#previewEmail span');
                emailSpan.textContent = correo || 'proveedor@empresa.com';
                
                // Actualizar teléfono
                const phoneText = document.getElementById('previewPhoneText');
                phoneText.textContent = telefono || '+57 300 123 4567';
                
                // Actualizar estado
                const statusDiv = document.getElementById('previewStatus');
                let statusHtml = '';
                
                switch(estado) {
                    case 'activo':
                        statusHtml = `<span class="status-badge status-activo">
                            <i class="fas fa-check-circle"></i> Activo
                        </span>`;
                        break;
                    case 'inactivo':
                        statusHtml = `<span class="status-badge status-inactivo">
                            <i class="fas fa-times-circle"></i> Inactivo
                        </span>`;
                        break;
                    case 'pendiente':
                        statusHtml = `<span class="status-badge status-pendiente">
                            <i class="fas fa-clock"></i> Pendiente
                        </span>`;
                        break;
                    default:
                        statusHtml = `<span class="status-badge status-activo">
                            <i class="fas fa-check-circle"></i> Activo
                        </span>`;
                }
                
                statusDiv.innerHTML = statusHtml;
            }
            
            // Agregar listeners para vista previa
            ['nombre_distribuidor', 'correo', 'cel_proveedor', 'estado'].forEach(function(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', actualizarVistaPrevia);
                    field.addEventListener('change', actualizarVistaPrevia);
                }
            });
            
            // Validación del formulario
            form.addEventListener('submit', function(e) {
                const nombre = document.getElementById('nombre_distribuidor').value;
                const correo = document.getElementById('correo').value;
                const telefono = document.getElementById('cel_proveedor').value;
                const estado = document.getElementById('estado').value;
                
                const errors = [];
                
                // Validar nombre
                if (nombre.trim().length < 2) {
                    errors.push('El nombre del proveedor debe tener al menos 2 caracteres');
                }
                
                // Validar correo
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(correo)) {
                    errors.push('Por favor ingrese un correo electrónico válido');
                }
                
                // Validar teléfono
                if (telefono.trim().length < 7) {
                    errors.push('El número de teléfono debe tener al menos 7 caracteres');
                }
                
                // Validar estado
                if (!estado) {
                    errors.push('Por favor seleccione un estado para el proveedor');
                }
                
                if (errors.length > 0) {
                    e.preventDefault();
                    showValidationErrors(errors);
                }
            });
            
            function showValidationErrors(errors) {
                // Remover alertas previas
                const existingAlert = document.querySelector('.alert-validation');
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                const alertHtml = `
                    <div class="alert-modern alert-validation" style="margin-bottom: 20px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Por favor corrija los siguientes errores:</strong>
                            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                                ${errors.map(error => `<li>${error}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                `;
                
                form.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Scroll al inicio del formulario
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Auto-remover después de 8 segundos
                setTimeout(() => {
                    const alert = document.querySelector('.alert-validation');
                    if (alert) {
                        alert.style.transition = 'all 0.5s ease';
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 8000);
            }
            
            // Efectos visuales
            document.querySelectorAll('.form-control-modern, .form-select-modern, .form-textarea-modern').forEach(input => {
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
            
            // Inicializar vista previa
            actualizarVistaPrevia();
        });
    </script>
</body>
</html>