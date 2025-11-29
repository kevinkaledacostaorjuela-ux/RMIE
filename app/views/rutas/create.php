<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Ruta - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
        
        .form-row-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2, .form-row-3 {
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
            margin-top: 20px;
        }
        
        .route-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(156, 39, 176, 0.3);
            transition: all 0.3s ease;
        }
        
        .route-avatar-large:hover {
            transform: scale(1.05);
        }
        
        .preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .preview-item:last-child {
            border-bottom: none;
        }
        
        .preview-label {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .preview-value {
            font-weight: 500;
            color: white;
            max-width: 60%;
            text-align: right;
            word-break: break-word;
        }
        
        .info-panel {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
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
        
        .warning-panel {
            background: rgba(255, 152, 0, 0.2);
            border: 1px solid rgba(255, 152, 0, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .warning-panel h6 {
            color: #ff9800;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
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
            background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(156, 39, 176, 0.4);
            background: linear-gradient(135deg, #8e24aa 0%, #5e35b1 100%);
            color: white;
        }
        
        .btn-create:disabled {
            background: linear-gradient(135deg, #9e9e9e 0%, #757575 100%);
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
        
        .btn-clear {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-clear:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 193, 7, 0.4);
            background: linear-gradient(135deg, #e0a800 0%, #dc6309 100%);
            color: white;
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
            color: #e1bee7;
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

        /* Select2 Personalizados */
        .select2-container--bootstrap-5 .select2-selection {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 15px !important;
            padding: 10px 12px !important;
            min-height: 55px !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--bootstrap-5 .select2-selection:focus-within {
            border-color: rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3) !important;
        }

        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: rgba(255, 255, 255, 0.8) !important;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            padding: 0 !important;
            color: #2d3748 !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            color: white !important;
            font-weight: 500 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            margin: 4px !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove {
            color: white !important;
            margin-right: 6px !important;
            font-weight: bold !important;
            cursor: pointer !important;
        }

        .select2-container--bootstrap-5 .select2-selection__choice__remove:hover {
            opacity: 0.8 !important;
        }

        .select2-dropdown--below {
            border-radius: 15px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            margin-top: 5px !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            background: rgba(255, 255, 255, 0.95) !important;
            border-radius: 15px !important;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 12px 15px !important;
            color: #2d3748 !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
        }

        .select2-search--dropdown .select2-search__field {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 10px !important;
            padding: 12px 15px !important;
            color: #2d3748 !important;
            font-weight: 500 !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #667eea !important;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2) !important;
        }

        .select2-container--open .select2-dropdown--below {
            border-top: none !important;
            border-radius: 0 0 15px 15px !important;
        }

        /* Mejorar placeholders de búsqueda */
        .select2-search__field::placeholder {
            color: #6c757d !important;
            font-weight: 500 !important;
            font-size: 14px !important;
        }
        
        .select2-search__field::-webkit-input-placeholder {
            color: #6c757d !important;
            font-weight: 500 !important;
        }
        
        .select2-search__field::-moz-placeholder {
            color: #6c757d !important;
            font-weight: 500 !important;
        }

        /* Limpiar elementos duplicados */
        .search-label,
        .select2-search-helper,
        .select2-dropdown .search-tooltip,
        .select2-dropdown .search-overlay {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <!-- Header -->
            <div class="header-section">
                <h1 class="header-title">
                    <i class="fas fa-route"></i>
                    Crear Nueva Ruta
                </h1>
                <p class="header-subtitle">Registra una nueva ruta de entrega en el sistema RMIE</p>
            </div>

            <!-- Warning Panel -->
            <?php if (empty($available_clients) || empty($available_sales) || empty($available_locals)): ?>
                <div class="form-section">
                    <div class="warning-panel">
                        <h6><i class="fas fa-exclamation-triangle"></i> Atención</h6>
                        <p>
                            <?php if (empty($available_clients)): ?>
                                No hay clientes disponibles. Debes crear al menos un cliente antes de poder crear rutas.
                            <?php elseif (empty($available_sales)): ?>
                                No hay ventas disponibles. Debes crear al menos una venta antes de poder crear rutas.
                            <?php elseif (empty($available_locals)): ?>
                                No hay locales disponibles. Debes crear al menos un local antes de poder crear rutas.
                            <?php else: ?>
                                No hay datos disponibles. Verifica que existan clientes, locales y ventas en el sistema.
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Estado de la Ruta -->
                    <div class="form-row">
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-traffic-light"></i>
                                Estado de la Ruta
                            </div>
                            <div class="form-floating-modern">
                                <select class="form-select-modern" id="estado" name="estado" required>
                                    <option value="activo">Activo</option>
                                    <option value="pendiente">Pendiente</option>
                                </select>
                                <label for="estado">
                                    <i class="fas fa-traffic-light"></i>
                                    Estado <span class="required">*</span>
                                </label>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Selecciona si la ruta estará activa o pendiente al crearla
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="form-section">
                    <div class="info-panel">
                        <h6><i class="fas fa-info-circle"></i> Información</h6>
                        <p>Complete todos los campos para registrar la nueva ruta. La dirección debe ser clara y específica para facilitar las entregas.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="form-section">
                <form action="/RMIE/app/controllers/RouteController.php?accion=create" method="POST" id="createRouteForm">
                    <div class="section-card" style="margin-bottom:30px;">
                        <div class="section-title">
                            <i class="fas fa-calendar-day"></i>
                            Día Planificado (Opcional)
                        </div>
                        <div class="form-floating-modern">
                            <select class="form-select-modern" id="dia_plan" name="dia_plan">
                                <option value="">-- Sin especificar --</option>
                                <?php if(isset($dias_predeterminados)): ?>
                                    <?php foreach($dias_predeterminados as $d): ?>
                                        <option value="<?= $d ?>" <?= (($_POST['dia_plan'] ?? '') === $d) ? 'selected' : '' ?>><?= $d ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="dia_plan">
                                <i class="fas fa-calendar-week"></i>
                                Día sugerido
                            </label>
                            <div class="form-help">
                                <i class="fas fa-info-circle"></i>
                                Selecciona un día para sugerir clientes según tu planificación guardada.
                            </div>
                        </div>
                        <div class="form-help" style="margin-top:10px; font-size:0.75rem; color:#fff;">
                            <i class="fas fa-lightbulb"></i> Al elegir un día se resaltarán en el listado los clientes asignados a ese día.
                        </div>
                    </div>

                                        <div class="form-floating-modern">
                                            <select class="form-select-modern" id="estado" name="estado" required>
                                                <option value="activa">Activa</option>
                                                <option value="pendiente">Pendiente</option>
                                            </select>
                                            <label for="estado">
                                                <i class="fas fa-toggle-on"></i>
                                                Estado de la Ruta <span class="required">*</span>
                                            </label>
                                            <div class="form-help">
                                                <i class="fas fa-info-circle"></i>
                                                Selecciona el estado inicial de la ruta
                                            </div>
                                        </div>
                    
                    <!-- Panel Unificado -->
                    <div class="section-card">
                        <div class="section-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Información de Ubicación y Referencias del Sistema
                        </div>
                        
                        <div class="form-floating-modern">
                            <select class="form-select-modern select2-search" 
                                    id="id_locales" 
                                    name="id_locales[]" 
                                    multiple
                                    required>
                                <?php if (!empty($available_locals)): ?>
                                    <?php foreach ($available_locals as $local): ?>
                                        <option value="<?= htmlspecialchars($local['id_locales']) ?>"
                                                data-direccion="<?= htmlspecialchars($local['direccion']) ?>"
                                                data-localidad="<?= htmlspecialchars($local['localidad'] ?? '') ?>"
                                                data-barrio="<?= htmlspecialchars($local['barrio'] ?? '') ?>">
                                            <?= htmlspecialchars($local['nombre_local']) ?>
                                            <?php if (!empty($local['localidad'])): ?>
                                                - <?= htmlspecialchars($local['localidad']) ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No hay locales disponibles</option>
                                <?php endif; ?>
                            </select>
                            <label for="id_locales">
                                <i class="fas fa-store"></i>
                                Locales <span class="required">*</span>
                            </label>
                            <div class="form-help">
                                <i class="fas fa-database"></i>
                                Selecciona uno o más locales
                            </div>
                        </div>
                        
                        <div class="form-floating-modern">
                            <select class="form-select-modern select2-search" 
                                    id="id_clientes" 
                                    name="id_clientes[]" 
                                    multiple
                                    required>
                                <?php if (!empty($available_clients)): ?>
                                    <?php foreach ($available_clients as $client): ?>
                                        <?php
                                            $resaltado = '';
                                            if (!empty($planificacion_usuario) && !empty($_POST['dia_plan']) && isset($planificacion_usuario[$_POST['dia_plan']])) {
                                                if (in_array($client['id_clientes'], $planificacion_usuario[$_POST['dia_plan']])) {
                                                    $resaltado = 'data-resaltado="1"';
                                                }
                                            }
                                        ?>
                                        <option value="<?= htmlspecialchars($client['id_clientes']) ?>" <?= $resaltado ?>>
                                            <?= htmlspecialchars($client['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No hay clientes disponibles</option>
                                <?php endif; ?>
                            </select>
                            <label for="id_clientes">
                                <i class="fas fa-user"></i>
                                Clientes <span class="required">*</span>
                            </label>
                            <div class="form-help">
                                <i class="fas fa-database"></i>
                                Selecciona uno o más clientes
                            </div>
                        </div>

                        <div class="form-floating-modern">
                            <select class="form-select-modern select2-search" 
                                    id="id_ventas" 
                                    name="id_ventas[]" 
                                    multiple
                                    required>
                                <?php if (!empty($available_sales)): ?>
                                    <?php foreach ($available_sales as $sale): ?>
                                        <option value="<?= htmlspecialchars($sale['id_ventas']) ?>">
                                            <?= htmlspecialchars($sale['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No hay ventas disponibles</option>
                                <?php endif; ?>
                            </select>
                            <label for="id_ventas">
                                <i class="fas fa-chart-line"></i>
                                Ventas <span class="required">*</span>
                            </label>
                            <div class="form-help">
                                <i class="fas fa-box"></i>
                                Selecciona uno o más ventas para esta ruta
                            </div>
                        </div>

                        <!-- Vista Previa -->
                        <div class="preview-section">
                            <div style="text-align: center; margin-bottom: 20px;">
                                <div class="route-avatar-large" id="previewAvatar">
                                    <i class="fas fa-route"></i>
                                </div>
                                <h6 style="color: rgba(255, 255, 255, 0.9); margin: 0;">
                                    Vista Previa de la Ruta
                                </h6>
                            </div>
                            
                            <div class="preview-item">
                                <span class="preview-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Dirección:
                                </span>
                                <span class="preview-value" id="preview-direccion">No especificada</span>
                            </div>
                            
                            <div class="preview-item">
                                <span class="preview-label">
                                    <i class="fas fa-store"></i>
                                    Local:
                                </span>
                                <span class="preview-value" id="preview-local">No especificado</span>
                            </div>
                            
                            <div class="preview-item">
                                <span class="preview-label">
                                    <i class="fas fa-users"></i>
                                    Cliente Sistema:
                                </span>
                                <span class="preview-value" id="preview-id-cliente">No seleccionado</span>
                            </div>
                            
                            <div class="preview-item">
                                <span class="preview-label">
                                    <i class="fas fa-shopping-cart"></i>
                                    Venta:
                                </span>
                                <span class="preview-value" id="preview-id-venta">No seleccionada</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Buttons -->
            <div class="buttons-section">
                <button type="submit" form="createRouteForm" class="btn-modern btn-create" id="submitBtn"
                        <?php if (empty($available_clients) || empty($available_sales) || empty($available_locals)): ?>disabled<?php endif; ?>>
                    <i class="fas fa-save"></i>
                    <?php if (empty($available_clients) || empty($available_sales) || empty($available_locals)): ?>
                        NO SE PUEDE CREAR RUTA
                    <?php else: ?>
                        CREAR RUTA
                    <?php endif; ?>
                </button>
                <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn-modern btn-cancel">
                    <i class="fas fa-times"></i>
                    CANCELAR
                </a>
                <?php if (!empty($available_clients) && !empty($available_sales) && !empty($available_locals)): ?>
                    <button type="button" class="btn-modern btn-clear" id="resetBtn">
                        <i class="fas fa-undo"></i>
                        LIMPIAR
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (requerido por Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2 en el campo de locales
            $('#id_locales').select2({
                theme: 'bootstrap-5',
                placeholder: 'Busca el local aquí...',
                language: 'es',
                allowClear: true,
                width: '100%',
                templateResult: formatOption,
                templateSelection: formatSelection,
                matcher: matchCustom
            });

            // Inicializar Select2 en el campo de clientes
            $('#id_clientes').select2({
                theme: 'bootstrap-5',
                placeholder: 'Busca el cliente aquí...',
                language: 'es',
                allowClear: true,
                width: '100%',
                templateResult: formatOption,
                templateSelection: formatSelection,
                matcher: matchCustom
            });

            // Inicializar Select2 en el campo de ventas
            $('#id_ventas').select2({
                theme: 'bootstrap-5',
                placeholder: 'Busca la venta aquí...',
                language: 'es',
                allowClear: true,
                width: '100%',
                templateResult: formatOption,
                templateSelection: formatSelection,
                matcher: matchCustom
            });

            // Configurar el placeholder del campo de búsqueda dentro del dropdown
            $(document).on('select2:open', function(e) {
                var $element = $(e.target);
                var elementId = $element.attr('id');
                
                setTimeout(function() {
                    var $dropdown = $element.data('select2').$dropdown;
                    var $field = $dropdown.find('.select2-search__field');
                    
                    if ($field.length > 0) {
                        var placeholder = '';
                        
                        if (elementId === 'id_locales') {
                            placeholder = 'Búsqueda de local aquí';
                        } else if (elementId === 'id_clientes') {
                            placeholder = 'Búsqueda de cliente aquí';
                        } else if (elementId === 'id_ventas') {
                            placeholder = 'Búsqueda de venta aquí';
                        }
                        
                        $field.attr('placeholder', placeholder);
                        $field.focus();
                    }
                }, 50);
            });

            // Función para formatear opciones en el dropdown
            function formatOption(data) {
                if (!data.id) {
                    return data.text;
                }

                const $option = $('<span>' + data.text + '</span>');
                return $option;
            }

            // Función para formatear la selección
            function formatSelection(data) {
                if (!data.id) {
                    return data.text;
                }
                return data.text;
            }

            // Función personalizada de búsqueda
            function matchCustom(params, data) {
                // Si no hay búsqueda, mostrar todo
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Búsqueda en el texto
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return data;
                }

                return null;
            }

            const form = document.getElementById('createRouteForm');
            const idLocalesField = document.getElementById('id_locales');
            const idClienteField = document.getElementById('id_clientes');
            const idVentaField = document.getElementById('id_ventas');
            const diaPlanField = document.getElementById('dia_plan');

            // Resaltar clientes asignados al día seleccionado
            function resaltarClientesDia() {
                const dia = diaPlanField.value;
                // Limpiar clases anteriores
                $('#id_clientes option').each(function(){
                    $(this).removeClass('cliente-dia');
                });
                if (dia && window.PLANIFICACION && window.PLANIFICACION[dia]) {
                    const ids = window.PLANIFICACION[dia];
                    ids.forEach(id => {
                        $('#id_clientes option[value="'+id+'"]').addClass('cliente-dia');
                    });
                }
                // Forzar refresco visual en Select2
                $('#id_clientes').trigger('change.select2');
            }

            // Inyectar planificación desde PHP
            window.PLANIFICACION = <?= json_encode($planificacion_usuario ?? []) ?>;

            // Listener cambio día
            diaPlanField.addEventListener('change', function(){
                resaltarClientesDia();
            });

            // Estilo para clientes resaltados
            const styleTag = document.createElement('style');
            styleTag.innerHTML = '.select2-results__option.cliente-dia, #id_clientes option.cliente-dia {background: linear-gradient(135deg,#43e97b,#38f9d7)!important;color:#fff!important;font-weight:600;}';
            document.head.appendChild(styleTag);

            // Botón para preseleccionar todos los clientes del día
            const helperDiv = document.createElement('div');
            helperDiv.style.marginTop = '10px';
            helperDiv.innerHTML = '<button type="button" id="btnPreselectDia" class="btn btn-sm btn-success" style="border-radius:8px;"><i class="fas fa-magic"></i> Usar clientes del día</button>';
            $('#id_clientes').parent().append(helperDiv);
            document.getElementById('btnPreselectDia').addEventListener('click', function(){
                const dia = diaPlanField.value;
                if (dia && window.PLANIFICACION && window.PLANIFICACION[dia]) {
                    $('#id_clientes').val(window.PLANIFICACION[dia].map(String)).trigger('change');
                } else {
                    alert('No hay clientes asignados para el día seleccionado o no has elegido un día.');
                }
            });

            // Inicial al cargar
            resaltarClientesDia();

            // Vista previa en tiempo real
            function updatePreview() {
                const avatar = document.getElementById('previewAvatar');
                const selectedLocales = $(idLocalesField).val() || [];
                
                if (selectedLocales.length > 0) {
                    const firstLocalOption = $(idLocalesField).find('option[value="' + selectedLocales[0] + '"]');
                    const firstLocalText = firstLocalOption.text();
                    const firstChar = firstLocalText.charAt(0).toUpperCase();
                    avatar.innerHTML = firstChar;
                } else {
                    avatar.innerHTML = '<i class="fas fa-route"></i>';
                }
                
                // Actualizar dirección desde el primer local seleccionado
                if (selectedLocales.length > 0) {
                    const selectedOption = $(idLocalesField).find('option[value="' + selectedLocales[0] + '"]');
                    const localDireccion = selectedOption.attr('data-direccion') || '';
                    const localidad = selectedOption.attr('data-localidad') || '';
                    const barrio = selectedOption.attr('data-barrio') || '';
                    
                    let direccionCompleta = localDireccion;
                    if (barrio) direccionCompleta += ', ' + barrio;
                    if (localidad) direccionCompleta += ', ' + localidad;
                    
                    document.getElementById('preview-direccion').textContent = 
                        direccionCompleta || 'No especificada';
                    
                    const selectedLocalText = selectedOption.text();
                    document.getElementById('preview-local').textContent = selectedLocalText;
                } else {
                    document.getElementById('preview-direccion').textContent = 'No especificada';
                    document.getElementById('preview-local').textContent = 'No seleccionado';
                }
                
                // Para clientes del sistema
                const selectedClientes = $(idClienteField).val() || [];
                const clientesTexto = selectedClientes.length > 0 
                    ? $(idClienteField).find('option:selected').map(function() { return $(this).text(); }).get().join(', ')
                    : 'No seleccionado';
                document.getElementById('preview-id-cliente').textContent = clientesTexto;
                
                // Para ventas
                const selectedVentas = $(idVentaField).val() || [];
                const ventasTexto = selectedVentas.length > 0 
                    ? $(idVentaField).find('option:selected').map(function() { return $(this).text(); }).get().join(', ')
                    : 'No seleccionada';
                document.getElementById('preview-id-venta').textContent = ventasTexto;
            }

            // Actualizar vista previa cuando cambia Select2
            $(idLocalesField).on('change', updatePreview);
            $(idClienteField).on('change', updatePreview);
            $(idVentaField).on('change', updatePreview);

            // Validación del formulario
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const errors = [];

                const selectedLocales = $(idLocalesField).val() || [];
                const selectedClientes = $(idClienteField).val() || [];
                const selectedVentas = $(idVentaField).val() || [];

                if (selectedLocales.length === 0) {
                    errors.push('Debe seleccionar al menos un local');
                    isValid = false;
                }

                if (selectedClientes.length === 0) {
                    errors.push('Debe seleccionar al menos un cliente');
                    isValid = false;
                }

                if (selectedVentas.length === 0) {
                    errors.push('Debe seleccionar al menos una venta');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor corrige los siguientes errores:\n\n' + errors.join('\n'));
                    return false;
                }

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> CREANDO...';
                submitBtn.disabled = true;
            });

            const resetBtn = document.getElementById('resetBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    form.reset();
                    $(idLocalesField).val(null).trigger('change');
                    $(idClienteField).val(null).trigger('change');
                    $(idVentaField).val(null).trigger('change');
                    setTimeout(updatePreview, 10);
                });
            }

            // Inicializar vista previa
            updatePreview();
        });
    </script>
</body>
</html>