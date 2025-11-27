<?php
$errors = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Local - RMIE</title>
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

        .locales-grid {
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

        /* Resumen del local */
        .locales-summary {
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

        .locales-summary::before {
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

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
        }

        .locales-buttons {
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
        }

        .status-activo {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
            color: #155724;
        }

        .status-inactivo {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #721c24;
        }

        @media (max-width: 768px) {
            .locales-grid {
                grid-template-columns: 1fr;
            }
            
            .locales-buttons {
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
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/LocalController.php?accion=index">Locales</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Local</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Editar Local #<?php echo $local->id_locales; ?></h1>
            <p>Modifica la información del local</p>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <!-- Alertas -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Se encontraron los siguientes errores:</strong>
                    <ul style="margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/RMIE/app/controllers/LocalController.php?accion=update&id=<?php echo $local->id_locales; ?>" id="formLocal">
                <!-- Grid Principal -->
                <div class="locales-grid">
                    <!-- Columna Izquierda: Formulario -->
                    <div>
                        <!-- Sección Información Básica -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-building"></i> Información Básica
                            </div>
                            
                            <div class="form-group">
                                <label for="nombre">
                                    <i class="fas fa-store"></i> Nombre del Local
                                </label>
                                <input type="text" 
                                       class="form-control"
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?php echo htmlspecialchars($local->nombre_local); ?>"
                                       placeholder="Ingrese el nombre del local"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="direccion">
                                    <i class="fas fa-map-marker-alt"></i> Dirección
                                </label>
                                <input type="text" 
                                       class="form-control"
                                       id="direccion" 
                                       name="direccion" 
                                       value="<?php echo htmlspecialchars($local->direccion); ?>"
                                       placeholder="Dirección completa del local"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="telefono">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="tel" 
                                       class="form-control"
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?php echo htmlspecialchars($local->cel_local); ?>"
                                       placeholder="Número de contacto"
                                       required>
                            </div>
                        </div>

                        <!-- Sección Ubicación -->
                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-map"></i> Ubicación
                            </div>
                            
                            <div class="form-group">
                                <label for="localidad">
                                    <i class="fas fa-city"></i> Localidad
                                </label>
                                <input type="text" 
                                       class="form-control"
                                       id="localidad" 
                                       name="localidad" 
                                       value="<?php echo htmlspecialchars($local->localidad ?? ''); ?>"
                                       placeholder="Ciudad o localidad"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="barrio">
                                    <i class="fas fa-map-marked-alt"></i> Barrio
                                </label>
                                <input type="text" 
                                       class="form-control"
                                       id="barrio" 
                                       name="barrio" 
                                       value="<?php echo htmlspecialchars($local->barrio ?? ''); ?>"
                                       placeholder="Barrio o sector"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="estado">
                                    <i class="fas fa-toggle-on"></i> Estado
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="activo" <?php echo $local->estado === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                    <option value="inactivo" <?php echo $local->estado === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Resumen -->
                    <div>
                        <div class="locales-summary">
                            <div class="summary-header">
                                <h5><i class="fas fa-info-circle"></i> Información del Local</h5>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-hashtag"></i> ID:</span>
                                <span class="summary-value">#<?php echo $local->id_locales; ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-store"></i> Nombre:</span>
                                <span class="summary-value" id="nombre-mostrar"><?php echo htmlspecialchars($local->nombre_local); ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-map-marker-alt"></i> Dirección:</span>
                                <span class="summary-value" id="direccion-mostrar"><?php echo htmlspecialchars($local->direccion); ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-phone"></i> Teléfono:</span>
                                <span class="summary-value" id="telefono-mostrar"><?php echo htmlspecialchars($local->cel_local); ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label"><i class="fas fa-toggle-on"></i> Estado:</span>
                                <span class="summary-value">
                                    <span class="status-badge status-<?php echo strtolower($local->estado); ?>" id="estado-mostrar">
                                        <i class="fas fa-<?php echo $local->estado === 'activo' ? 'check-circle' : 'times-circle'; ?>"></i>
                                        <?php echo ucfirst($local->estado); ?>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Info del local -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información histórica:</strong>
                            <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                                <li>Creado: <?php echo isset($local->fecha_creacion) ? date('d/m/Y H:i', strtotime($local->fecha_creacion)) : 'No disponible'; ?></li>
                                <li>Los cambios se aplicarán inmediatamente</li>
                                <li>Verifica la información antes de guardar</li>
                            </ul>
                        </div>

                        <div class="info-alert" style="margin-top: 1rem; border-left-color: #ffc107; background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3);">
                            <i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i>
                            <strong>Importante:</strong>
                            <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                                <li>Desactivar un local puede afectar operaciones</li>
                                <li>Verifica que los datos sean correctos</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="locales-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="/RMIE/app/controllers/LocalController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-warning" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Resetear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const nombreInput = document.getElementById('nombre');
        const direccionInput = document.getElementById('direccion');
        const telefonoInput = document.getElementById('telefono');
        const estadoSelect = document.getElementById('estado');
        const form = document.getElementById('formLocal');
        
        // Valores originales
        const originalValues = {
            nombre: nombreInput.value,
            direccion: direccionInput.value,
            telefono: telefonoInput.value,
            estado: estadoSelect.value
        };
        
        function actualizarNombre() {
            document.getElementById('nombre-mostrar').textContent = nombreInput.value || '-';
        }
        
        function actualizarDireccion() {
            document.getElementById('direccion-mostrar').textContent = direccionInput.value || '-';
        }
        
        function actualizarTelefono() {
            document.getElementById('telefono-mostrar').textContent = telefonoInput.value || '-';
        }
        
        function actualizarEstado() {
            const estado = estadoSelect.value;
            const badge = document.getElementById('estado-mostrar');
            badge.className = 'status-badge status-' + estado;
            badge.innerHTML = '<i class="fas fa-' + (estado === 'activo' ? 'check-circle' : 'times-circle') + '"></i> ' + 
                              estado.charAt(0).toUpperCase() + estado.slice(1);
        }
        
        function resetForm() {
            nombreInput.value = originalValues.nombre;
            direccionInput.value = originalValues.direccion;
            telefonoInput.value = originalValues.telefono;
            estadoSelect.value = originalValues.estado;
            
            actualizarNombre();
            actualizarDireccion();
            actualizarTelefono();
            actualizarEstado();
        }
        
        // Event listeners
        nombreInput.addEventListener('input', actualizarNombre);
        direccionInput.addEventListener('input', actualizarDireccion);
        telefonoInput.addEventListener('input', actualizarTelefono);
        estadoSelect.addEventListener('change', actualizarEstado);
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            if (!nombreInput.value.trim()) {
                e.preventDefault();
                alert('El nombre del local es obligatorio');
                nombreInput.focus();
                return;
            }
            
            if (!direccionInput.value.trim()) {
                e.preventDefault();
                alert('La dirección es obligatoria');
                direccionInput.focus();
                return;
            }
            
            if (!telefonoInput.value.trim()) {
                e.preventDefault();
                alert('El teléfono es obligatorio');
                telefonoInput.focus();
                return;
            }
        });
        
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-danger, .alert-success');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Hacer resetForm global
        window.resetForm = resetForm;
    });
    </script>
</body>
</html>
