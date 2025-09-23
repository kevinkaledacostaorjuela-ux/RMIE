<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="clientes-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/ClientController.php?accion=index">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
            </ol>
        </nav>

        <h1><i class="fas fa-user-plus"></i> Registrar Nuevo Cliente</h1>
        
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
        
        <div class="clientes-form">
            <form method="POST" action="/RMIE/app/controllers/ClientController.php?accion=create" id="formCliente">
                <div class="row">
                    <!-- Información básica -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user"></i> Información Básica</h5>
                        
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-user"></i> Nombre completo:
                                <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   required 
                                   maxlength="100"
                                   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                                   placeholder="Nombre completo del cliente">
                        </div>
                        
                        <div class="form-group">
                            <label for="descripcion">
                                <i class="fas fa-file-alt"></i> Descripción:
                            </label>
                            <textarea id="descripcion" 
                                      name="descripcion" 
                                      rows="3"
                                      maxlength="255"
                                      placeholder="Información adicional sobre el cliente (opcional)"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                            <small class="form-text text-muted">Opcional: Información adicional, empresa, cargo, etc.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_locales">
                                <i class="fas fa-store"></i> Local asignado:
                                <span class="required">*</span>
                            </label>
                            <select id="id_locales" name="id_locales" required>
                                <option value="">Seleccione un local</option>
                                <?php if (isset($locales)): ?>
                                    <?php foreach ($locales as $local): ?>
                                        <option value="<?= $local->id_locales ?>" 
                                                <?= (isset($_POST['id_locales']) && $_POST['id_locales'] == $local->id_locales) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($local->nombre_local) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted">Local donde el cliente realizará sus compras principalmente</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-toggle-on"></i> Estado inicial:
                            </label>
                            <select id="estado" name="estado">
                                <option value="activo" <?= (isset($_POST['estado']) && $_POST['estado'] === 'activo') ? 'selected' : 'selected' ?>>Activo</option>
                                <option value="inactivo" <?= (isset($_POST['estado']) && $_POST['estado'] === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                            <small class="form-text text-muted">Por defecto, los nuevos clientes se crean como activos</small>
                        </div>
                    </div>
                    
                    <!-- Información de contacto -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-address-book"></i> Información de Contacto</h5>
                        
                        <div class="form-group">
                            <label for="correo">
                                <i class="fas fa-envelope"></i> Correo electrónico:
                                <span class="required">*</span>
                            </label>
                            <input type="email" 
                                   id="correo" 
                                   name="correo" 
                                   required 
                                   maxlength="100"
                                   value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                                   placeholder="ejemplo@correo.com">
                            <small class="form-text text-muted">Correo único para identificar al cliente</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="cel_cliente">
                                <i class="fas fa-phone"></i> Teléfono/Celular:
                            </label>
                            <input type="tel" 
                                   id="cel_cliente" 
                                   name="cel_cliente" 
                                   maxlength="20"
                                   value="<?= htmlspecialchars($_POST['cel_cliente'] ?? '') ?>"
                                   placeholder="Número de teléfono móvil">
                            <small class="form-text text-muted">Opcional: Número de contacto del cliente</small>
                        </div>
                        
                        <!-- Vista previa del cliente -->
                        <div class="client-preview">
                            <h6><i class="fas fa-eye"></i> Vista Previa</h6>
                            <div class="preview-card">
                                <div class="client-avatar-large" id="previewAvatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="preview-info">
                                    <div class="preview-name" id="previewName">Nombre del Cliente</div>
                                    <div class="preview-email" id="previewEmail">correo@ejemplo.com</div>
                                    <div class="preview-phone" id="previewPhone" style="display: none;">
                                        <i class="fas fa-phone"></i> <span id="previewPhoneText"></span>
                                    </div>
                                    <div class="preview-local" id="previewLocal">
                                        <i class="fas fa-store"></i> <span id="previewLocalText">Seleccione un local</span>
                                    </div>
                                    <div class="preview-status" id="previewStatus">
                                        <span class="status-badge status-activo">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información importante -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>El correo electrónico debe ser único</li>
                                <li>Los campos marcados con * son obligatorios</li>
                                <li>El cliente se registrará con la fecha actual</li>
                                <li>Puede cambiar el estado después de crear el cliente</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="clientes-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Cliente
                    </button>
                    <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="reset" class="btn btn-outline-secondary" onclick="limpiarFormulario()">
                        <i class="fas fa-broom"></i> Limpiar
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
        const form = document.getElementById('formCliente');
        
        // Vista previa en tiempo real
        function actualizarVistaPrevia() {
            const nombre = document.getElementById('nombre').value;
            const correo = document.getElementById('correo').value;
            const telefono = document.getElementById('cel_cliente').value;
            const localSelect = document.getElementById('id_locales');
            const estado = document.getElementById('estado').value;
            
            // Actualizar avatar
            const avatar = document.getElementById('previewAvatar');
            if (nombre) {
                avatar.innerHTML = nombre.charAt(0).toUpperCase();
            } else {
                avatar.innerHTML = '<i class="fas fa-user"></i>';
            }
            
            // Actualizar nombre
            document.getElementById('previewName').textContent = nombre || 'Nombre del Cliente';
            
            // Actualizar correo
            document.getElementById('previewEmail').textContent = correo || 'correo@ejemplo.com';
            
            // Actualizar teléfono
            const phoneDiv = document.getElementById('previewPhone');
            const phoneText = document.getElementById('previewPhoneText');
            if (telefono) {
                phoneText.textContent = telefono;
                phoneDiv.style.display = 'block';
            } else {
                phoneDiv.style.display = 'none';
            }
            
            // Actualizar local
            const localText = document.getElementById('previewLocalText');
            if (localSelect.value && localSelect.options[localSelect.selectedIndex]) {
                localText.textContent = localSelect.options[localSelect.selectedIndex].text;
            } else {
                localText.textContent = 'Seleccione un local';
            }
            
            // Actualizar estado
            const statusDiv = document.getElementById('previewStatus');
            if (estado === 'activo') {
                statusDiv.innerHTML = `<span class="status-badge status-activo">
                    <i class="fas fa-check-circle"></i> Activo
                </span>`;
            } else {
                statusDiv.innerHTML = `<span class="status-badge status-inactivo">
                    <i class="fas fa-times-circle"></i> Inactivo
                </span>`;
            }
        }
        
        // Agregar listeners para vista previa
        ['nombre', 'correo', 'cel_cliente', 'id_locales', 'estado'].forEach(function(fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', actualizarVistaPrevia);
                field.addEventListener('change', actualizarVistaPrevia);
            }
        });
        
        // Ejecutar vista previa inicial
        actualizarVistaPrevia();
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            const correo = document.getElementById('correo').value;
            const nombre = document.getElementById('nombre').value;
            const local = document.getElementById('id_locales').value;
            
            // Validar correo
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(correo)) {
                e.preventDefault();
                alert('Por favor ingrese un correo electrónico válido');
                document.getElementById('correo').focus();
                return;
            }
            
            // Validar nombre
            if (nombre.trim().length < 2) {
                e.preventDefault();
                alert('El nombre debe tener al menos 2 caracteres');
                document.getElementById('nombre').focus();
                return;
            }
            
            // Validar local
            if (!local) {
                e.preventDefault();
                alert('Por favor seleccione un local');
                document.getElementById('id_locales').focus();
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
    
    // Función para limpiar formulario
    function limpiarFormulario() {
        setTimeout(function() {
            document.getElementById('previewAvatar').innerHTML = '<i class="fas fa-user"></i>';
            document.getElementById('previewName').textContent = 'Nombre del Cliente';
            document.getElementById('previewEmail').textContent = 'correo@ejemplo.com';
            document.getElementById('previewPhone').style.display = 'none';
            document.getElementById('previewLocalText').textContent = 'Seleccione un local';
            document.getElementById('previewStatus').innerHTML = `<span class="status-badge status-activo">
                <i class="fas fa-check-circle"></i> Activo
            </span>`;
        }, 10);
    }
    </script>
    
    <!-- Estilos adicionales -->
    <style>
    .client-preview {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .preview-card {
        text-align: center;
    }
    
    .client-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
    
    .preview-email {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .preview-phone {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .preview-local {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
    }
    
    .required {
        color: #dc3545;
        font-weight: bold;
    }
    </style>
</body>
</html>
