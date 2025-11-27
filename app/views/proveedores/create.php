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

                            <div class="form-floating-modern">
                                <label for="productos" style="left:15px; top:12px;"> 
                                    <i class="fas fa-boxes"></i>
                                    Productos a asociar (opcional)
                                </label>
                                <select class="form-select-modern" id="productos" name="productos[]" multiple size="6">
                                    <?php if (!empty($productos) && is_array($productos)): ?>
                                        <?php foreach ($productos as $prod): ?>
                                            <?php $selected = (isset($_POST['productos']) && is_array($_POST['productos']) && in_array($prod->id_productos, array_map('intval', $_POST['productos']))) ? 'selected' : ''; ?>
                                            <option value="<?= htmlspecialchars($prod->id_productos) ?>" <?= $selected ?>><?= htmlspecialchars($prod->nombre) ?> (ID: <?= htmlspecialchars($prod->id_productos) ?>)</option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No hay productos disponibles</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-help">
                                    <i class="fas fa-info-circle"></i>
                                    Seleccione uno o varios productos para vincularlos a este proveedor.
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