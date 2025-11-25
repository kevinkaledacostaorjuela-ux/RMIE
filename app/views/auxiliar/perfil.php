<?php
// Vista para modificar perfil del auxiliar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que sea auxiliar
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'auxiliar') {
    header('Location: ../../index.php');
    exit();
}

$rol = 'auxiliar';
$nombreCompleto = trim(($_SESSION['nombres'] ?? '') . ' ' . ($_SESSION['apellidos'] ?? ''));
if (empty($nombreCompleto)) {
    $nombreCompleto = 'Auxiliar';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - RMIE Auxiliar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/RMIE/public/css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-content {
            width: 100%;
        }
        .perfil-center-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 80vh;
        }
        .perfil-card {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }
        .card {
            box-shadow: 0 6px 32px 0 rgba(80, 80, 120, 0.13);
            border-radius: 18px;
        }
        .card-header {
            background: linear-gradient(120deg, #fa709a 0%, #fee140 100%) !important;
            min-height: 90px;
            padding: 18px 10px !important;
        }
        .dashboard-header {
            background: linear-gradient(120deg, #667eea 0%, #764ba2 100%);
            padding: 18px 0 10px 0;
            color: white;
            margin-bottom: 18px;
            border-radius: 0 0 22px 22px;
        }
        @media (max-width: 768px) {
            .perfil-card {
                max-width: 100%;
                margin: 0 5px;
            }
            .dashboard-header {
                font-size: 1.2rem;
                padding: 12px 0 6px 0;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <main class="main-content" style="margin: 0; max-width: 100%;">
        <!-- Header -->
        <div class="dashboard-header text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 18px 0 10px 0; color: white; margin-bottom: 18px; border-radius: 0 0 22px 22px;">
            <h1 style="margin: 0; font-weight: 700; font-size: 2rem;">
                <i class="fas fa-user-edit me-3"></i>Mi Perfil
            </h1>
            <p style="margin: 5px 0 0 0; font-size: 1rem; opacity: 0.9;">
                Edita tu información personal y cambia tu contraseña
            </p>
        </div>

        <div class="perfil-center-container" style="justify-content: flex-start;">
            <div class="perfil-card" style="max-width: 100%;">
                <!-- Mensajes de éxito o error -->
                <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <!-- Tarjeta del formulario -->
                <form method="POST" action="/RMIE/app/controllers/AuxiliarController.php?accion=modificar_perfil" id="formPerfil">
                    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                        <!-- Header de la tarjeta con gradiente -->
                        <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <div class="d-flex align-items-center justify-content-center">
                                <div style="font-size: 3rem; opacity: 0.9;">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="ms-3 text-start">
                                    <h4 class="mb-0" style="font-weight: 600;">
                                        <?php echo htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos); ?>
                                    </h4>
                                    <p class="mb-0" style="opacity: 0.9; font-size: 0.9rem;">
                                        <i class="fas fa-id-badge me-1"></i><?php echo htmlspecialchars($usuario->num_doc); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Información Personal -->
                            <div class="mb-3">
                                <h6 class="mb-3" style="color: #667eea; font-weight: 600;">
                                    <i class="fas fa-user me-2"></i>Información Personal
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombres" class="form-label fw-bold">
                                            <i class="fas fa-signature text-primary me-1"></i>Nombres *
                                        </label>
                                        <input type="text" 
                                               class="form-control bg-white border-primary" 
                                               id="nombres" 
                                               name="nombres" 
                                               value="<?php echo htmlspecialchars($usuario->nombres ?? ''); ?>"
                                               required
                                               style="border-radius: 10px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="apellidos" class="form-label fw-bold">
                                            <i class="fas fa-signature text-primary me-1"></i>Apellidos *
                                        </label>
                                        <input type="text" 
                                               class="form-control bg-white border-primary" 
                                               id="apellidos" 
                                               name="apellidos" 
                                               value="<?php echo htmlspecialchars($usuario->apellidos ?? ''); ?>"
                                               required
                                               style="border-radius: 10px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="correo" class="form-label fw-bold">
                                            <i class="fas fa-envelope text-info me-1"></i>Correo Electrónico *
                                        </label>
                                        <input type="email" 
                                               class="form-control bg-white border-primary" 
                                               id="correo" 
                                               name="correo" 
                                               value="<?php echo htmlspecialchars($usuario->correo ?? ''); ?>"
                                               required
                                               style="border-radius: 10px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="num_cel" class="form-label fw-bold">
                                            <i class="fas fa-phone text-success me-1"></i>Número de Celular
                                        </label>
                                        <input type="tel" 
                                               class="form-control bg-white border-primary" 
                                               id="num_cel" 
                                               name="num_cel" 
                                               value="<?php echo htmlspecialchars($usuario->num_cel ?? ''); ?>"
                                               style="border-radius: 10px;"
                                               pattern="[0-9]{10}"
                                               placeholder="10 dígitos">
                                    </div>
                                </div>
                            </div>
                            <!-- Información de la cuenta (solo lectura) -->
                            <div class="mb-3">
                                <h6 class="mb-3" style="color: #fa709a; font-weight: 600;">
                                    <i class="fas fa-id-card me-2"></i>Información de la Cuenta
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-fingerprint text-secondary me-1"></i>Número de Documento
                                        </label>
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               value="<?php echo htmlspecialchars($usuario->num_doc ?? ''); ?>"
                                               disabled
                                               style="border-radius: 10px; background-color: #f8f9fa;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-user-tag text-secondary me-1"></i>Rol
                                        </label>
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               value="<?php echo ucfirst($usuario->rol ?? 'auxiliar'); ?>"
                                               disabled
                                               style="border-radius: 10px; background-color: #f8f9fa;">
                                    </div>
                                </div>
                            </div>
                            <!-- Cambiar Contraseña -->
                            <div class="mb-3">
                                <h6 class="mb-3" style="color: #28a745; font-weight: 600;">
                                    <i class="fas fa-lock me-2"></i>Cambiar Contraseña (Opcional)
                                </h6>
                                <div class="alert alert-info" style="border-radius: 10px; padding: 10px;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Solo completa estos campos si deseas cambiar tu contraseña.</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nueva_contrasena" class="form-label fw-bold">
                                            <i class="fas fa-key text-warning me-1"></i>Nueva Contraseña
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control bg-white border-primary" 
                                                   id="nueva_contrasena" 
                                                   name="nueva_contrasena" 
                                                   minlength="6"
                                                   style="border-radius: 10px 0 0 10px;">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('nueva_contrasena')"
                                                    style="border-radius: 0 10px 10px 0;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirmar_contrasena" class="form-label fw-bold">
                                            <i class="fas fa-key text-warning me-1"></i>Confirmar Contraseña
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control bg-white border-primary" 
                                                   id="confirmar_contrasena" 
                                                   name="confirmar_contrasena"
                                                   style="border-radius: 10px 0 0 10px;">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('confirmar_contrasena')"
                                                    style="border-radius: 0 10px 10px 0;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="/RMIE/app/controllers/AuxiliarController.php?accion=dashboard" 
                                   class="btn btn-outline-primary px-4" 
                                   style="border-radius: 10px; font-weight: 600;">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                                </a>
                                <button type="submit" 
                                        class="btn px-4" 
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-weight: 600;">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validación de contraseñas
document.getElementById('formPerfil').addEventListener('submit', function(e) {
    const nuevaPass = document.getElementById('nueva_contrasena').value;
    const confirmarPass = document.getElementById('confirmar_contrasena').value;
    
    if (nuevaPass || confirmarPass) {
        if (nuevaPass !== confirmarPass) {
            e.preventDefault();
            alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
            return false;
        }
        
        if (nuevaPass.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres.');
            return false;
        }
    }
});

// Mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = event.currentTarget.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Auto-cerrar alertas después de 5 segundos
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
</body>
</html>
