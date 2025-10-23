<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - RMIE</title>
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
        
        .form-control-modern.is-valid {
            border-color: #28a745;
            background: rgba(255, 255, 255, 0.95);
        }
        
        .form-control-modern.is-invalid {
            border-color: #dc3545;
            background: rgba(255, 255, 255, 0.95);
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
        
        .user-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
            transition: all 0.3s ease;
        }
        
        .user-avatar-large:hover {
            transform: scale(1.05);
        }
        
        .preview-name {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .preview-doc {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 8px;
        }
        
        .preview-email {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 15px;
        }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            margin: 5px;
        }
        
        .role-admin {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }
        
        .role-coordinador {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }
        
        .role-auxiliar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
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
        
        .warning-panel {
            background: rgba(255, 152, 0, 0.2);
            border: 1px solid rgba(255, 152, 0, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
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
        
        .password-requirements {
            background: rgba(13, 202, 240, 0.2);
            border: 1px solid rgba(13, 202, 240, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .password-requirements h6 {
            color: #0dcaf0;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
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
                    <i class="fas fa-user-plus"></i>
                    Registrar Nuevo Usuario
                </h1>
                <p class="header-subtitle">Complete la información para crear una nueva cuenta de usuario en el sistema</p>
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
                <form method="POST" action="/RMIE/app/controllers/UserController.php?accion=create" id="formUsuario">
                    
                    <!-- Información Personal -->
                    <div class="section-card">
                        <div class="section-title">
                            <i class="fas fa-user"></i>
                            Información Personal
                        </div>
                        
                        <div class="form-row form-row-2">
                            <div class="form-floating-modern">
                                <select class="form-select-modern" 
                                        id="tipo_doc" 
                                        name="tipo_doc" 
                                        required>
                                    <option value="">Seleccione el tipo</option>
                                    <option value="CC" <?= ($_POST['tipo_doc'] ?? '') === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                    <option value="TI" <?= ($_POST['tipo_doc'] ?? '') === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                    <option value="CE" <?= ($_POST['tipo_doc'] ?? '') === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                    <option value="PA" <?= ($_POST['tipo_doc'] ?? '') === 'PA' ? 'selected' : '' ?>>Pasaporte</option>
                                </select>
                                <label for="tipo_doc">
                                    <i class="fas fa-id-card"></i>
                                    Tipo de Documento
                                </label>
                            </div>

                            <div class="form-floating-modern">
                                <input type="number" 
                                       class="form-control-modern" 
                                       id="num_doc" 
                                       name="num_doc" 
                                       placeholder=" "
                                       min="1"
                                       required
                                       value="<?= htmlspecialchars($_POST['num_doc'] ?? '') ?>">
                                <label for="num_doc">
                                    <i class="fas fa-hashtag"></i>
                                    Número de Documento
                                </label>
                            </div>
                        </div>

                        <div class="form-row form-row-2">
                            <div class="form-floating-modern">
                                <input type="text" 
                                       class="form-control-modern" 
                                       id="nombres" 
                                       name="nombres" 
                                       placeholder=" "
                                       maxlength="45"
                                       required
                                       value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>">
                                <label for="nombres">
                                    <i class="fas fa-user"></i>
                                    Nombres
                                </label>
                                <div class="character-count">
                                    <span id="nombres-count">0</span>/45
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <input type="text" 
                                       class="form-control-modern" 
                                       id="apellidos" 
                                       name="apellidos" 
                                       placeholder=" "
                                       maxlength="45"
                                       required
                                       value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>">
                                <label for="apellidos">
                                    <i class="fas fa-user"></i>
                                    Apellidos
                                </label>
                                <div class="character-count">
                                    <span id="apellidos-count">0</span>/45
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="section-card">
                        <div class="section-title">
                            <i class="fas fa-address-book"></i>
                            Información de Contacto
                        </div>
                        
                        <div class="form-row form-row-2">
                            <div class="form-floating-modern">
                                <input type="email" 
                                       class="form-control-modern" 
                                       id="correo" 
                                       name="correo" 
                                       placeholder=" "
                                       maxlength="45"
                                       required
                                       value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                                <label for="correo">
                                    <i class="fas fa-envelope"></i>
                                    Correo Electrónico
                                </label>
                                <div class="character-count">
                                    <span id="correo-count">0</span>/45
                                </div>
                            </div>

                            <div class="form-floating-modern">
                                <input type="tel" 
                                       class="form-control-modern" 
                                       id="num_cel" 
                                       name="num_cel" 
                                       placeholder=" "
                                       maxlength="45"
                                       value="<?= htmlspecialchars($_POST['num_cel'] ?? '') ?>">
                                <label for="num_cel">
                                    <i class="fas fa-phone"></i>
                                    Número de Celular
                                </label>
                                <div class="character-count">
                                    <span id="num_cel-count">0</span>/45
                                </div>
                            </div>
                        </div>

                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="rol" 
                                    name="rol" 
                                    required>
                                <option value="">Seleccione el rol</option>
                                <option value="coordinador" <?= ($_POST['rol'] ?? '') === 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                                <option value="admin" <?= ($_POST['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                <option value="auxiliar" <?= ($_POST['rol'] ?? '') === 'auxiliar' ? 'selected' : '' ?>>Auxiliar</option>
                            </select>
                            <label for="rol">
                                <i class="fas fa-user-tag"></i>
                                Rol del Usuario
                            </label>
                        </div>

                        <div class="info-panel">
                            <h6><i class="fas fa-info-circle"></i> Roles Disponibles</h6>
                            <ul>
                                <li><strong>Coordinador:</strong> Puede gestionar ventas y consultar reportes</li>
                                <li><strong>Administrador:</strong> Acceso completo al sistema</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Configuración de Seguridad y Vista Previa -->
                    <div class="form-row form-row-2">
                        <!-- Configuración de Seguridad -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-lock"></i>
                                Configuración de Seguridad
                            </div>
                            
                            <div class="form-floating-modern">
                                <input type="password" 
                                       class="form-control-modern" 
                                       id="contrasena" 
                                       name="contrasena" 
                                       placeholder=" "
                                       minlength="6"
                                       required>
                                <label for="contrasena">
                                    <i class="fas fa-key"></i>
                                    Contraseña
                                </label>
                            </div>

                            <div class="form-floating-modern">
                                <input type="password" 
                                       class="form-control-modern" 
                                       id="confirmar_contrasena" 
                                       name="confirmar_contrasena" 
                                       placeholder=" "
                                       minlength="6"
                                       required>
                                <label for="confirmar_contrasena">
                                    <i class="fas fa-key"></i>
                                    Confirmar Contraseña
                                </label>
                            </div>

                            <div class="password-requirements">
                                <h6><i class="fas fa-shield-alt"></i> Requisitos de Contraseña</h6>
                                <ul>
                                    <li>Mínimo 6 caracteres</li>
                                    <li>Se recomienda incluir mayúsculas, minúsculas y números</li>
                                    <li>Evite usar información personal</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Vista Previa -->
                        <div class="section-card">
                            <div class="section-title">
                                <i class="fas fa-eye"></i>
                                Vista Previa del Usuario
                            </div>
                            
                            <div class="preview-section">
                                <div class="user-avatar-large" id="previewAvatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="preview-name" id="previewName">Nombre del Usuario</div>
                                <div class="preview-doc" id="previewDoc">Documento</div>
                                <div class="preview-email" id="previewEmail">correo@ejemplo.com</div>
                                <div class="preview-role" id="previewRole">
                                    <span class="role-badge role-coordinador">
                                        <i class="fas fa-user-tie"></i> Rol
                                    </span>
                                </div>
                            </div>

                            <div class="warning-panel">
                                <h6><i class="fas fa-exclamation-triangle"></i> Importante</h6>
                                <ul>
                                    <li>Verifique toda la información antes de crear el usuario</li>
                                    <li>El documento no podrá modificarse después</li>
                                    <li>El usuario recibirá sus credenciales por correo</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Buttons -->
            <div class="buttons-section">
                <button type="submit" form="formUsuario" class="btn-modern btn-create">
                    <i class="fas fa-save"></i>
                    CREAR USUARIO
                </button>
                <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn-modern btn-cancel">
                    <i class="fas fa-times"></i>
                    CANCELAR
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formUsuario');
            const contrasenaInput = document.getElementById('contrasena');
            const confirmarInput = document.getElementById('confirmar_contrasena');
            
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
            
            setupCharacterCount('nombres', 'nombres-count', 45);
            setupCharacterCount('apellidos', 'apellidos-count', 45);
            setupCharacterCount('correo', 'correo-count', 45);
            setupCharacterCount('num_cel', 'num_cel-count', 45);
            
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
                    let roleClass, roleIcon, roleText;
                    switch(rol) {
                        case 'admin':
                            roleClass = 'role-admin';
                            roleIcon = 'fas fa-user-shield';
                            roleText = 'Administrador';
                            break;
                        case 'coordinador':
                            roleClass = 'role-coordinador';
                            roleIcon = 'fas fa-user-tie';
                            roleText = 'Coordinador';
                            break;
                        case 'auxiliar':
                            roleClass = 'role-auxiliar';
                            roleIcon = 'fas fa-user';
                            roleText = 'Auxiliar';
                            break;
                        default:
                            roleClass = 'role-coordinador';
                            roleIcon = 'fas fa-user';
                            roleText = 'Usuario';
                    }
                    
                    roleElement.innerHTML = `<span class="role-badge ${roleClass}">
                        <i class="${roleIcon}"></i> ${roleText}
                    </span>`;
                } else {
                    roleElement.innerHTML = `<span class="role-badge role-auxiliar">
                        <i class="fas fa-user"></i> Seleccionar Rol
                    </span>`;
                }
            }
            
            // Agregar listeners para vista previa
            ['nombres', 'apellidos', 'tipo_doc', 'num_doc', 'correo', 'rol'].forEach(function(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', actualizarVistaPrevia);
                    field.addEventListener('change', actualizarVistaPrevia);
                }
            });
            
            // Validación de contraseñas en tiempo real
            function validarContrasenas() {
                const contrasena = contrasenaInput.value;
                const confirmar = confirmarInput.value;
                
                // Limpiar estilos previos
                contrasenaInput.classList.remove('is-valid', 'is-invalid');
                confirmarInput.classList.remove('is-valid', 'is-invalid');
                
                if (contrasena.length >= 6) {
                    contrasenaInput.classList.add('is-valid');
                } else if (contrasena.length > 0) {
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
            
            contrasenaInput.addEventListener('input', validarContrasenas);
            confirmarInput.addEventListener('input', validarContrasenas);
            
            // Validación del formulario
            form.addEventListener('submit', function(e) {
                const contrasena = contrasenaInput.value;
                const confirmar = confirmarInput.value;
                const numDoc = document.getElementById('num_doc').value;
                const correo = document.getElementById('correo').value;
                
                // Validar contraseñas
                if (contrasena !== confirmar) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden');
                    confirmarInput.focus();
                    return;
                }
                
                if (contrasena.length < 6) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 6 caracteres');
                    contrasenaInput.focus();
                    return;
                }
                
                // Validar documento
                if (numDoc.length < 7) {
                    e.preventDefault();
                    alert('El número de documento debe tener al menos 7 dígitos');
                    document.getElementById('num_doc').focus();
                    return;
                }
                
                // Validar correo
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(correo)) {
                    e.preventDefault();
                    alert('Por favor ingrese un correo electrónico válido');
                    document.getElementById('correo').focus();
                    return;
                }
            });
            
            // Efectos visuales
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
            
            // Inicializar vista previa
            actualizarVistaPrevia();
        });
    </script>
</body>
</html>