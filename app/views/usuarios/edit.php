<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - RMIE</title>
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
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-user-edit"></i> Editar Usuario: <?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?></h1>
        
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
                <div class="row">
                    <!-- Información personal -->
                    <div class="col-md-6">
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
                            <select id="tipo_doc" name="tipo_doc" required>
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
                                   value="<?= htmlspecialchars($usuario->apellidos) ?>"
                                   placeholder="Apellidos completos">
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
                                   value="<?= htmlspecialchars($usuario->correo) ?>"
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
                                   value="<?= htmlspecialchars($usuario->num_cel) ?>"
                                   placeholder="Número de teléfono móvil">
                        </div>
                        
                        <div class="form-group">
                            <label for="rol">
                                <i class="fas fa-user-tag"></i> Rol del usuario:
                            </label>
                            <select id="rol" name="rol" required>
                                <option value="">Seleccione el rol</option>
                                <option value="coordinador" <?= $usuario->rol === 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                                <option value="admin" <?= $usuario->rol === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>
                        
                        <!-- Estado actual -->
                        <div class="status-display">
                            <div class="current-status">
                                <span class="label">Rol actual:</span>
                                <span class="role-badge role-<?= strtolower($usuario->rol) ?>">
                                    <?php if ($usuario->rol === 'admin'): ?>
                                        <i class="fas fa-user-shield"></i> Administrador
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
                                   placeholder="Nueva contraseña (opcional)">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmar_contrasena">
                                <i class="fas fa-key"></i> Confirmar nueva contraseña:
                            </label>
                            <input type="password" 
                                   id="confirmar_contrasena" 
                                   name="confirmar_contrasena" 
                                   minlength="6"
                                   placeholder="Confirmar nueva contraseña">
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
                    <div class="col-md-6">
                        <h5><i class="fas fa-eye"></i> Vista Previa</h5>
                        
                        <div class="user-preview">
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
                <div class="usuarios-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Usuario
                    </button>
                    <a href="/RMIE/app/controllers/UserController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()" title="Imprimir información del usuario">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
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
