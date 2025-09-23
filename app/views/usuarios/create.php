<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="usuarios-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/UserController.php?accion=index">Usuarios</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>

        <h1><i class="fas fa-user-plus"></i> Registrar Nuevo Usuario</h1>
        
        <!-- Mostrar errores si existen -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="usuarios-form">
            <form method="POST" action="/RMIE/app/controllers/UserController.php?accion=create" id="formUsuario">
                <div class="row">
                    <!-- Información personal -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user"></i> Información Personal</h5>
                        
                        <div class="form-group">
                            <label for="tipo_doc">
                                <i class="fas fa-id-card"></i> Tipo de documento:
                            </label>
                            <select id="tipo_doc" name="tipo_doc" required>
                                <option value="">Seleccione el tipo</option>
                                <option value="CC" <?= ($_POST['tipo_doc'] ?? '') === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                <option value="TI" <?= ($_POST['tipo_doc'] ?? '') === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                <option value="CE" <?= ($_POST['tipo_doc'] ?? '') === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                <option value="PA" <?= ($_POST['tipo_doc'] ?? '') === 'PA' ? 'selected' : '' ?>>Pasaporte</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="num_doc">
                                <i class="fas fa-hashtag"></i> Número de documento:
                            </label>
                            <input type="number" 
                                   id="num_doc" 
                                   name="num_doc" 
                                   required 
                                   min="1"
                                   value="<?= htmlspecialchars($_POST['num_doc'] ?? '') ?>"
                                   placeholder="Ingrese el número de documento">
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
                                   value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>"
                                   placeholder="Nombres completos">
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
                                   value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>"
                                   placeholder="Apellidos completos">
                        </div>
                    </div>
                    
                    <!-- Información de contacto -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-address-book"></i> Información de Contacto</h5>
                        
                        <div class="form-group">
                            <label for="correo">
                                <i class="fas fa-envelope"></i> Correo electrónico:
                            </label>
                            <input type="email" 
                                   id="correo" 
                                   name="correo" 
                                   required 
                                   maxlength="45"
                                   value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                                   placeholder="ejemplo@correo.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="num_cel">
                                <i class="fas fa-phone"></i> Número de celular:
                            </label>
                            <input type="tel" 
                                   id="num_cel" 
                                   name="num_cel" 
                                   maxlength="45"
                                   value="<?= htmlspecialchars($_POST['num_cel'] ?? '') ?>"
                                   placeholder="Número de teléfono móvil">
                        </div>
                        
                        <div class="form-group">
                            <label for="rol">
                                <i class="fas fa-user-tag"></i> Rol del usuario:
                            </label>
                            <select id="rol" name="rol" required>
                                <option value="">Seleccione el rol</option>
                                <option value="coordinador" <?= ($_POST['rol'] ?? '') === 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                                <option value="admin" <?= ($_POST['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>
                        
                        <!-- Descripción de roles -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Roles disponibles:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Coordinador:</strong> Puede gestionar ventas y consultar reportes</li>
                                <li><strong>Administrador:</strong> Acceso completo al sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Configuración de seguridad -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-lock"></i> Configuración de Seguridad</h5>
                        
                        <div class="form-group">
                            <label for="contrasena">
                                <i class="fas fa-key"></i> Contraseña:
                            </label>
                            <input type="password" 
                                   id="contrasena" 
                                   name="contrasena" 
                                   required 
                                   minlength="6"
                                   placeholder="Mínimo 6 caracteres">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmar_contrasena">
                                <i class="fas fa-key"></i> Confirmar contraseña:
                            </label>
                            <input type="password" 
                                   id="confirmar_contrasena" 
                                   name="confirmar_contrasena" 
                                   required 
                                   minlength="6"
                                   placeholder="Repita la contraseña">
                        </div>
                        
                        <!-- Requisitos de contraseña -->
                        <div class="password-requirements">
                            <h6><i class="fas fa-shield-alt"></i> Requisitos de Contraseña</h6>
                            <ul>
                                <li>Mínimo 6 caracteres</li>
                                <li>Se recomienda incluir mayúsculas, minúsculas y números</li>
                                <li>Evite usar información personal</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Vista previa del usuario -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-eye"></i> Vista Previa</h5>
                        
                        <div class="user-preview">
                            <div class="preview-card">
                                <div class="user-avatar-large" id="previewAvatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="preview-info">
                                    <div class="preview-name" id="previewName">Nombre del Usuario</div>
                                    <div class="preview-doc" id="previewDoc">Documento</div>
                                    <div class="preview-email" id="previewEmail">correo@ejemplo.com</div>
                                    <div class="preview-role" id="previewRole">
                                        <span class="role-badge role-coordinador">
                                            <i class="fas fa-user-tie"></i> Rol
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Verifique toda la información antes de crear el usuario</li>
                                <li>El documento no podrá modificarse después</li>
                                <li>El usuario recibirá sus credenciales por correo</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="usuarios-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Crear Usuario
                    </button>
                    <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
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
                const roleClass = rol === 'admin' ? 'role-admin' : 'role-coordinador';
                const roleIcon = rol === 'admin' ? 'fas fa-user-shield' : 'fas fa-user-tie';
                const roleText = rol === 'admin' ? 'Administrador' : 'Coordinador';
                
                roleElement.innerHTML = `<span class="role-badge ${roleClass}">
                    <i class="${roleIcon}"></i> ${roleText}
                </span>`;
            } else {
                roleElement.innerHTML = `<span class="role-badge role-coordinador">
                    <i class="fas fa-user-tie"></i> Rol
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
        
        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-danger');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }
            });
        }, 5000);
        
        // Inicializar vista previa
        actualizarVistaPrevia();
    });
    </script>
    
    <style>
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
