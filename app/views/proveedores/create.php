<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Proveedor - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px 0;
        }

        .container-modern {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .form-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
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

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }

        .form-header .subtitle {
            margin-top: 10px;
            opacity: 0.9;
            font-size: 1.1rem;
            position: relative;
            z-index: 2;
        }

        .breadcrumb-modern {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 30px;
            margin: 0;
            border-radius: 0;
        }

        .breadcrumb-modern .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb-modern .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-modern .breadcrumb-item.active {
            color: white;
        }

        .form-content {
            padding: 40px;
        }

        .section-card {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: var(--dark-color);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .form-group-modern {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label-modern {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label-modern i {
            color: var(--primary-color);
            width: 16px;
            text-align: center;
        }

        .form-control-modern {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            box-sizing: border-box;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-control-modern:valid {
            border-color: var(--success-color);
        }

        .form-control-modern.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .form-select-modern {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            box-sizing: border-box;
        }

        .form-select-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea-modern {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
            box-sizing: border-box;
        }

        .form-textarea-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-help {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-help i {
            color: var(--info-color);
        }

        .info-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: none;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .info-card .alert-content {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .info-card i {
            color: var(--info-color);
            font-size: 1.5rem;
            margin-top: 2px;
        }

        .info-card-text {
            flex: 1;
        }

        .info-card-text strong {
            color: var(--dark-color);
            font-weight: 600;
        }

        .btn-group-modern {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
        }

        .btn-modern {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            min-width: 150px;
            justify-content: center;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            color: white;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }

        .btn-success-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 10px 30px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(108, 117, 125, 0.4);
            color: white;
        }

        .alert-modern {
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger-modern {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .alert-danger-modern i {
            color: var(--danger-color);
            font-size: 1.2rem;
        }

        .floating-label {
            position: relative;
        }

        .floating-label .form-control-modern:focus + .floating-label-text,
        .floating-label .form-control-modern:not(:placeholder-shown) + .floating-label-text {
            transform: translateY(-25px) scale(0.8);
            color: var(--primary-color);
        }

        .floating-label-text {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            padding: 0 5px;
            transition: all 0.3s ease;
            pointer-events: none;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .container-modern {
                padding: 0 10px;
            }
            
            .form-content {
                padding: 20px;
            }
            
            .section-card {
                padding: 20px;
            }
            
            .form-header h1 {
                font-size: 2rem;
            }
            
            .btn-group-modern {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-modern {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="container-modern">
        <div class="form-wrapper">
            <!-- Header moderno con animación -->
            <div class="form-header">
                <nav aria-label="breadcrumb" class="breadcrumb-modern">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="/RMIE/app/views/dashboard.php">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="/RMIE/app/controllers/ProviderController.php?accion=index">
                                <i class="fas fa-truck"></i> Proveedores
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-plus"></i> Nuevo Proveedor
                        </li>
                    </ol>
                </nav>
                
                <h1><i class="fas fa-plus-circle"></i> Agregar Nuevo Proveedor</h1>
                <p class="subtitle">Complete la información para registrar un nuevo proveedor en el sistema</p>
            </div>

            <div class="form-content">
                <!-- Mostrar errores si existen -->
                <?php if (isset($error)): ?>
                    <div class="alert-modern alert-danger-modern">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/RMIE/app/controllers/ProviderController.php?accion=create" id="formProveedor">
                    <div class="row">
                        <!-- Información de la Empresa -->
                        <div class="col-lg-6">
                            <div class="section-card">
                                <h3 class="section-title">
                                    <i class="fas fa-building"></i>
                                    Información de la Empresa
                                </h3>
                                
                                <div class="form-group-modern">
                                    <label for="nombre_distribuidor" class="form-label-modern">
                                        <i class="fas fa-truck"></i>
                                        Nombre del Distribuidor/Empresa
                                    </label>
                                    <input type="text" 
                                           id="nombre_distribuidor" 
                                           name="nombre_distribuidor" 
                                           class="form-control-modern"
                                           required 
                                           value="<?= htmlspecialchars($_POST['nombre_distribuidor'] ?? '') ?>"
                                           placeholder="Ingrese el nombre de la empresa"
                                           maxlength="100">
                                    <div class="form-help">
                                        <i class="fas fa-info-circle"></i>
                                        Nombre comercial o razón social de la empresa
                                    </div>
                                </div>
                                
                                <div class="form-group-modern">
                                    <label for="estado" class="form-label-modern">
                                        <i class="fas fa-toggle-on"></i>
                                        Estado del Proveedor
                                    </label>
                                    <select id="estado" name="estado" class="form-select-modern" required>
                                        <option value="">Seleccione un estado</option>
                                        <option value="activo" <?= ($_POST['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>
                                            🟢 Activo
                                        </option>
                                        <option value="inactivo" <?= ($_POST['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>
                                            🔴 Inactivo
                                        </option>
                                        <option value="pendiente" <?= ($_POST['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>
                                            🟡 Pendiente
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información de Contacto -->
                        <div class="col-lg-6">
                            <div class="section-card">
                                <h3 class="section-title">
                                    <i class="fas fa-address-book"></i>
                                    Información de Contacto
                                </h3>
                                
                                <div class="form-group-modern">
                                    <label for="correo" class="form-label-modern">
                                        <i class="fas fa-envelope"></i>
                                        Correo Electrónico
                                    </label>
                                    <input type="email" 
                                           id="correo" 
                                           name="correo" 
                                           class="form-control-modern"
                                           required 
                                           value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                                           placeholder="ejemplo@empresa.com"
                                           maxlength="100">
                                    <div class="form-help">
                                        <i class="fas fa-shield-alt"></i>
                                        Será utilizado para comunicaciones oficiales
                                    </div>
                                </div>
                                
                                <div class="form-group-modern">
                                    <label for="cel_proveedor" class="form-label-modern">
                                        <i class="fas fa-phone"></i>
                                        Número de Celular
                                    </label>
                                    <input type="tel" 
                                           id="cel_proveedor" 
                                           name="cel_proveedor" 
                                           class="form-control-modern"
                                           required 
                                           value="<?= htmlspecialchars($_POST['cel_proveedor'] ?? '') ?>"
                                           placeholder="Ej: +57 300 123 4567"
                                           maxlength="20">
                                    <div class="form-help">
                                        <i class="fas fa-mobile-alt"></i>
                                        Incluya código de país si es internacional
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información Adicional -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="section-card">
                                <h3 class="section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Ubicación y Dirección
                                </h3>
                                
                                <div class="form-group-modern">
                                    <label for="ubicacion" class="form-label-modern">
                                        <i class="fas fa-map"></i>
                                        Dirección Completa
                                    </label>
                                    <textarea id="ubicacion" 
                                              name="ubicacion" 
                                              class="form-textarea-modern"
                                              placeholder="Ingrese la dirección completa del proveedor (ciudad, estado, dirección específica, referencias)"
                                              maxlength="255"><?= htmlspecialchars($_POST['ubicacion'] ?? '') ?></textarea>
                                    <div class="form-help">
                                        <i class="fas fa-info-circle"></i>
                                        Campo opcional pero recomendado para facilitar entregas y visitas
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="info-card">
                                <div class="alert-content">
                                    <i class="fas fa-lightbulb"></i>
                                    <div class="info-card-text">
                                        <strong>💡 Consejos Importantes:</strong>
                                        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                                            <li>Verifique que el correo sea válido</li>
                                            <li>El teléfono será usado para contacto directo</li>
                                            <li>La dirección facilita las entregas</li>
                                            <li>Revise toda la información antes de guardar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="btn-group-modern">
                        <button type="submit" class="btn-modern btn-success-modern" id="btnGuardar">
                            <i class="fas fa-save"></i> 
                            Guardar Proveedor
                        </button>
                        <a href="/RMIE/app/controllers/ProviderController.php?accion=index" 
                           class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> 
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript mejorado para validación y UX -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formProveedor');
        const correoInput = document.getElementById('correo');
        const telefonoInput = document.getElementById('cel_proveedor');
        const nombreInput = document.getElementById('nombre_distribuidor');
        const estadoSelect = document.getElementById('estado');
        const btnGuardar = document.getElementById('btnGuardar');
        
        // Animación de entrada para las tarjetas
        const cards = document.querySelectorAll('.section-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
        
        // Validación en tiempo real del email
        correoInput.addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (email) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
        });
        
        // Formateo automático de teléfono
        telefonoInput.addEventListener('input', function() {
            let value = this.value.replace(/[^\d+\s()-]/g, '');
            
            // Si comienza con +57, formatear estilo colombiano
            if (value.startsWith('+57')) {
                value = value.replace(/(\+57)\s?(\d{3})\s?(\d{3})\s?(\d{4})/, '$1 $2 $3 $4');
            } else if (value.length >= 10 && !value.startsWith('+')) {
                // Formatear número nacional
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
            }
            
            this.value = value;
            
            // Validación visual
            if (value.length >= 10) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (value.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
        });
        
        // Validación del nombre de empresa
        nombreInput.addEventListener('input', function() {
            // Eliminar espacios al inicio
            this.value = this.value.replace(/^\s+/, '');
            
            // Capitalizar primera letra de cada palabra
            this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
            
            // Validación visual
            if (this.value.trim().length >= 2) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
        });
        
        // Validación del estado
        estadoSelect.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
        
        // Efecto hover en botones
        const buttons = document.querySelectorAll('.btn-modern');
        buttons.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Validación completa antes del envío
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombre = nombreInput.value.trim();
            const correo = correoInput.value.trim();
            const telefono = telefonoInput.value.trim();
            const estado = estadoSelect.value;
            
            let errors = [];
            
            // Validar nombre
            if (!nombre || nombre.length < 2) {
                errors.push('• El nombre del distribuidor debe tener al menos 2 caracteres');
                nombreInput.classList.add('is-invalid');
            }
            
            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!correo || !emailRegex.test(correo)) {
                errors.push('• Ingrese un correo electrónico válido');
                correoInput.classList.add('is-invalid');
            }
            
            // Validar teléfono
            const telefonoLimpio = telefono.replace(/[^\d]/g, '');
            if (!telefono || telefonoLimpio.length < 7) {
                errors.push('• El número de teléfono debe tener al menos 7 dígitos');
                telefonoInput.classList.add('is-invalid');
            }
            
            // Validar estado
            if (!estado) {
                errors.push('• Seleccione un estado para el proveedor');
                estadoSelect.classList.add('is-invalid');
            }
            
            if (errors.length > 0) {
                // Mostrar errores con animación
                showErrorAlert(errors);
                return false;
            }
            
            // Si todo está válido, mostrar loading y enviar
            showLoadingState();
            
            // Simular delay para mostrar el loading
            setTimeout(() => {
                form.submit();
            }, 500);
        });
        
        function showErrorAlert(errors) {
            // Remover alerta anterior si existe
            const existingAlert = document.querySelector('.alert-validation');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alertHtml = `
                <div class="alert-modern alert-danger-modern alert-validation" style="margin-bottom: 20px;">
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
        
        function showLoadingState() {
            btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            btnGuardar.disabled = true;
            btnGuardar.style.opacity = '0.7';
            
            // Deshabilitar todos los campos
            [nombreInput, correoInput, telefonoInput, estadoSelect].forEach(field => {
                field.disabled = true;
            });
        }
        
        // Auto-ocultar alertas existentes después de 6 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-modern');
            alerts.forEach(function(alert) {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 6000);
        
        // Agregar efecto de focus mejorado
        const inputs = document.querySelectorAll('.form-control-modern, .form-select-modern, .form-textarea-modern');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.style.transform = 'translateY(0)';
            });
        });
        
        console.log('✅ Formulario de proveedores cargado con todas las mejoras UX');
    });
    </script>
</body>
</html>
