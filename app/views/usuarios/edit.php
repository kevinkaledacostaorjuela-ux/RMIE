<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
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

        /* Resumen de venta mejorado (usado para la vista previa) */
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
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/UserController.php?accion=index">Usuarios</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
        <div class="form-header">
            <h1><i class="fas fa-user-edit"></i> Editar Usuario: <?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?></h1>
            <p>Actualice los datos del usuario en el sistema</p>
        </div>

        <div class="form-content">
        
        <!-- Mostrar mensajes -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="usuarios-form">
            <form method="POST" action="/RMIE/app/controllers/UserController.php?accion=edit&id=<?= $usuario->num_doc ?>" id="formUsuario">
                <div class="ventas-grid">
                    <!-- Información personal -->
                    <div>
                        <h5><i class="fas fa-user"></i> Información Personal</h5>
                        
                        <div class="form-group">
                            <label for="num_doc">
                                <i class="fas fa-hashtag"></i> Número de documento:
                            </label>
                            <input type="number" 
                                id="num_doc" 
                                name="num_doc" 
                                value="<?= htmlspecialchars($usuario->num_doc) ?>"
                                readonly
                                class="form-control-plaintext"
                                style="background-color: #f8f9fa; cursor: not-allowed;">
                            <small class="form-text text-muted">El número de documento no puede modificarse</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo_doc">
                                <i class="fas fa-id-card"></i> Tipo de documento:
                            </label>
                            <select id="tipo_doc" name="tipo_doc" required class="form-select">
                                <option value="">Seleccione el tipo</option>
                                <option value="CC" <?= $usuario->tipo_doc === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                <option value="TI" <?= $usuario->tipo_doc === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                <option value="CE" <?= $usuario->tipo_doc === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                <option value="PA" <?= $usuario->tipo_doc === 'PA' ? 'selected' : '' ?>>Pasaporte</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nombres">
                                <i class="fas fa-user"></i> Nombres:
                            </label>
                            <input type="text" 
                                id="nombres" 
                                name="nombres" 
                                required 
                                maxlength="45"
                                value="<?= htmlspecialchars($usuario->nombres) ?>"
                                placeholder="Nombres completos" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="apellidos">
                                <i class="fas fa-user"></i> Apellidos:
                            </label>
                            <input type="text" 
                                id="apellidos" 
                                name="apellidos" 
                                required 
                                maxlength="45"
                                value="<?= htmlspecialchars($usuario->apellidos) ?>"
                                placeholder="Apellidos completos" class="form-control">
                        </div>
                        
                        <!-- Información histórica -->
                        <div class="historical-info">
                            <h6><i class="fas fa-history"></i> Información del Registro</h6>
                            <div class="info-item">
                                <span class="label">Usuario creado:</span>
                                <span class="value">
                                    <?php if ($usuario->fecha_creacion): ?>
                                        <?= date('d/m/Y H:i', strtotime($usuario->fecha_creacion)) ?>
                                    <?php else: ?>
                                        No disponible
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">ID del usuario:</span>
                                <span class="value"><?= htmlspecialchars($usuario->num_doc) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de contacto -->
                    <div>
                        <h5><i class="fas fa-address-book"></i> Información de Contacto</h5>
                        
                        <div class="form-group">
                            <label for="correo">
                                <i class="fas fa-envelope"></i> Correo electrónico:
                                <span class="text-warning"><i class="fas fa-edit" title="Campo editable"></i></span>
                            </label>
                            <input type="email" 
                                id="correo" 
                                name="correo" 
                                required 
                                maxlength="100"
                                value="<?= htmlspecialchars($usuario->correo) ?>"
                                placeholder="ejemplo@correo.com" 
                                class="form-control"
                                style="border: 2px solid #667eea; background-color: white;">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Ingrese una dirección de correo válida
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="num_cel">
                                <i class="fas fa-phone"></i> Número de celular:
                            </label>
                            <input type="tel" 
                                id="num_cel" 
                                name="num_cel" 
                                maxlength="45"
                                value="<?= htmlspecialchars($usuario->num_cel) ?>"
                                placeholder="Número de teléfono móvil" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="rol">
                                <i class="fas fa-user-tag"></i> Rol del usuario:
                            </label>
                            <select id="rol" name="rol" required class="form-select">
                                <option value="">Seleccione el rol</option>
                                <option value="auxiliar" <?= $usuario->rol === 'auxiliar' ? 'selected' : '' ?>>Auxiliar</option>
                            </select>
                        </div>
                        
                        <!-- Estado actual -->
                        <div class="status-display">
                            <div class="current-status">
                                <span class="label">Rol actual:</span>
                                <span class="role-badge role-<?= strtolower($usuario->rol) ?>">
                                    <?php if ($usuario->rol === 'auxiliar'): ?>
                                        <i class="fas fa-user"></i> Auxiliar
                                    <?php else: ?>
                                        <i class="fas fa-user-tie"></i> Coordinador
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Descripción de roles -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Roles disponibles:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Auxiliar:</strong> Funciones operativas y de apoyo</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Configuración de seguridad -->
                    <div>
                        <h5><i class="fas fa-lock"></i> Configuración de Seguridad</h5>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            <strong>Cambio de Contraseña:</strong> Deje los campos vacíos si no desea cambiar la contraseña actual.
                        </div>
                        
                        <div class="form-group">
                            <label for="contrasena">
                                <i class="fas fa-key"></i> Nueva contraseña:
                            </label>
                            <input type="password" 
                                id="contrasena" 
                                name="contrasena" 
                                minlength="6"
                                placeholder="Nueva contraseña (opcional)" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmar_contrasena">
                                <i class="fas fa-key"></i> Confirmar nueva contraseña:
                            </label>
                            <input type="password" 
                                id="confirmar_contrasena" 
                                name="confirmar_contrasena" 
                                minlength="6"
                                placeholder="Confirmar nueva contraseña" class="form-control">
                        </div>
                        
                        <!-- Requisitos de contraseña -->
                        <div class="password-requirements">
                            <h6><i class="fas fa-shield-alt"></i> Requisitos de Contraseña</h6>
                            <ul>
                                <li>Mínimo 6 caracteres</li>
                                <li>Se recomienda incluir mayúsculas, minúsculas y números</li>
                                <li>La contraseña actual se mantendrá si no se especifica una nueva</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Vista previa del usuario -->
                    <div>
                        <h5><i class="fas fa-eye"></i> Vista Previa</h5>
                        
                        <div class="ventas-summary user-preview">
                            <div class="preview-card">
                                <div class="user-avatar-large" id="previewAvatar">
                                    <?= strtoupper(substr($usuario->nombres, 0, 1)) ?>
                                </div>
                                <div class="preview-info">
                                    <div class="preview-name" id="previewName"><?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?></div>
                                    <div class="preview-doc" id="previewDoc"><?= htmlspecialchars($usuario->tipo_doc . ': ' . $usuario->num_doc) ?></div>
                                    <div class="preview-email" id="previewEmail"><?= htmlspecialchars($usuario->correo) ?></div>
                                    <div class="preview-role" id="previewRole">
                                        <span class="role-badge role-<?= strtolower($usuario->rol) ?>">
                                            <?php if ($usuario->rol === 'admin'): ?>
                                                <i class="fas fa-user-shield"></i> Administrador
                                            <?php else: ?>
                                                <i class="fas fa-user-tie"></i> Coordinador
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Los cambios se aplicarán inmediatamente</li>
                                <li>El número de documento no se puede modificar</li>
                                <li>Verifique la información antes de guardar</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="ventas-buttons usuarios-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Usuario
                    </button>
                    <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="window.print()" title="Imprimir información del usuario">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </form>
        </div>
        </div> <!-- .form-content -->
    </div> <!-- .glass-container -->
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para validación y vista previa -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formUsuario');
        const contrasenaInput = document.getElementById('contrasena');
        const confirmarInput = document.getElementById('confirmar_contrasena');
        
        // Vista previa en tiempo real
        function actualizarVistaPrevia() {
            const nombres = document.getElementById('nombres').value;
            const apellidos = document.getElementById('apellidos').value;
            const tipoDoc = document.getElementById('tipo_doc').value;
            const numDoc = document.getElementById('num_doc').value;
            const correo = document.getElementById('correo').value;
            const rol = document.getElementById('rol').value;
            
            // Actualizar avatar
            const avatar = document.getElementById('previewAvatar');
            if (nombres) {
                avatar.innerHTML = nombres.charAt(0).toUpperCase();
            } else {
                avatar.innerHTML = '<i class="fas fa-user"></i>';
            }
            
            // Actualizar nombre
            const nombreCompleto = (nombres + ' ' + apellidos).trim();
            document.getElementById('previewName').textContent = nombreCompleto || 'Nombre del Usuario';
            
            // Actualizar documento
            const docTexto = tipoDoc && numDoc ? `${tipoDoc}: ${numDoc}` : 'Documento';
            document.getElementById('previewDoc').textContent = docTexto;
            
            // Actualizar correo
            document.getElementById('previewEmail').textContent = correo || 'correo@ejemplo.com';
            
            // Actualizar rol
            const roleElement = document.getElementById('previewRole');
            if (rol) {
                const roleClass = rol === 'auxiliar' ? 'role-auxiliar' : 'role-coordinador';
                const roleIcon = rol === 'auxiliar' ? 'fas fa-user' : 'fas fa-user-tie';
                const roleText = rol === 'auxiliar' ? 'Auxiliar' : 'Coordinador';
                
                roleElement.innerHTML = `<span class="role-badge ${roleClass}">
                    <i class="${roleIcon}"></i> ${roleText}
                </span>`;
            }
        }
        
        // Agregar listeners para vista previa
        ['nombres', 'apellidos', 'tipo_doc', 'correo', 'rol'].forEach(function(fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', actualizarVistaPrevia);
                field.addEventListener('change', actualizarVistaPrevia);
            }
        });

        // Indicador visual para cambios en el correo
        const correoInput = document.getElementById('correo');
        const originalEmail = correoInput.value;
        
        correoInput.addEventListener('input', function() {
            if (this.value !== originalEmail) {
                this.style.borderColor = '#28a745';
                this.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                // Agregar icono de cambio si no existe
                if (!this.parentNode.querySelector('.email-changed-indicator')) {
                    const indicator = document.createElement('small');
                    indicator.className = 'email-changed-indicator text-success d-block mt-1';
                    indicator.innerHTML = '<i class="fas fa-check-circle"></i> Correo modificado - será actualizado al guardar';
                    this.parentNode.appendChild(indicator);
                }
            } else {
                this.style.borderColor = '#667eea';
                this.style.boxShadow = '';
                // Remover icono de cambio
                const indicator = this.parentNode.querySelector('.email-changed-indicator');
                if (indicator) indicator.remove();
            }
        });
        
        // Validación de correo en tiempo real
        correoInput.addEventListener('blur', function() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailPattern.test(this.value)) {
                this.style.borderColor = '#dc3545';
                this.setCustomValidity('Por favor ingrese un formato de correo válido');
            } else {
                this.setCustomValidity('');
                if (this.value !== originalEmail) {
                    this.style.borderColor = '#28a745';
                } else {
                    this.style.borderColor = '#667eea';
                }
            }
        });
        
        // Validación de contraseñas en tiempo real
        function validarContrasenas() {
            const contrasena = contrasenaInput.value;
            const confirmar = confirmarInput.value;
            
            // Limpiar estilos previos
            contrasenaInput.classList.remove('is-valid', 'is-invalid');
            confirmarInput.classList.remove('is-valid', 'is-invalid');
            
            // Solo validar si se está intentando cambiar la contraseña
            if (contrasena.length > 0 || confirmar.length > 0) {
                if (contrasena.length >= 6) {
                    contrasenaInput.classList.add('is-valid');
                } else {
                    contrasenaInput.classList.add('is-invalid');
                }
                
                if (confirmar.length > 0) {
                    if (contrasena === confirmar && contrasena.length >= 6) {
                        confirmarInput.classList.add('is-valid');
                    } else {
                        confirmarInput.classList.add('is-invalid');
                    }
                }
            }
        }
        
        contrasenaInput.addEventListener('input', validarContrasenas);
        confirmarInput.addEventListener('input', validarContrasenas);
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            const contrasena = contrasenaInput.value;
            const confirmar = confirmarInput.value;
            const correo = document.getElementById('correo').value;
            
            // Validar correo
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!correo || !emailPattern.test(correo)) {
                e.preventDefault();
                alert('Por favor ingrese una dirección de correo electrónico válida');
                document.getElementById('correo').focus();
                return;
            }
            
            // Validar contraseñas solo si se están cambiando
            if (contrasena.length > 0 || confirmar.length > 0) {
                if (contrasena !== confirmar) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden');
                    confirmarInput.focus();
                    return;
                }
                
                if (contrasena.length < 6) {
                    e.preventDefault();
                    alert('La nueva contraseña debe tener al menos 6 caracteres');
                    contrasenaInput.focus();
                    return;
                }
            }
            
            // Confirmación especial si se cambió el correo
            const correoActual = document.getElementById('correo').value;
            const correoOriginal = '<?= addslashes($usuario->correo) ?>';
            
            if (correoActual !== correoOriginal) {
                if (!confirmAction('acción general')) {
                    e.preventDefault();
                    return;
                }
            }
            
            // Confirmación final
            if (!confirmAction('acción general')) {
                e.preventDefault();
                return;
            }
        });
        
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-danger, .alert-success');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }
            });
        }, 5000);
    });
    </script>
    
    <!-- Estilos para impresión -->
    <style media="print">
        .usuarios-buttons { display: none; }
        .alert { display: none; }
        nav { display: none; }
        .btn { display: none; }
        body { margin: 0; }
        .usuarios-container { margin: 0; padding: 20px; }
        
        .user-preview {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .preview-card {
            text-align: center;
        }
        
        .user-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }
        
        .preview-info {
            text-align: center;
        }
        
        .preview-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .preview-doc {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .preview-email {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .is-valid {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
    </style>
</body>
</html>
