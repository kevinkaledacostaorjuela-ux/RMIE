<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - RMIE</title>
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
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-user-edit"></i> Editar Cliente: <?= htmlspecialchars($cliente->nombre) ?></h1>
        
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
            <form method="POST" action="/RMIE/app/controllers/ClientController.php?accion=edit&id=<?= $cliente->id_clientes ?>" id="formCliente">
                <div class="row">
                    <!-- Información básica -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user"></i> Información Básica</h5>
                        
                        <div class="form-group">
                            <label for="id_clientes">
                                <i class="fas fa-hashtag"></i> ID del cliente:
                            </label>
                            <input type="text" 
                                   id="id_clientes" 
                                   value="<?= htmlspecialchars($cliente->id_clientes) ?>"
                                   readonly
                                   class="form-control-plaintext"
                                   style="background-color: #f8f9fa; cursor: not-allowed;">
                            <small class="form-text text-muted">El ID del cliente no puede modificarse</small>
                        </div>
                        
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
                                   value="<?= htmlspecialchars($cliente->nombre) ?>"
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
                                      placeholder="Información adicional sobre el cliente (opcional)"><?= htmlspecialchars($cliente->descripcion) ?></textarea>
                            <small class="form-text text-muted">Información adicional, empresa, cargo, etc.</small>
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
                                                <?= ($cliente->id_locales == $local->id_locales) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($local->nombre_local) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted">Local actual: <strong><?= htmlspecialchars($cliente->local_nombre) ?></strong></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-toggle-on"></i> Estado:
                            </label>
                            <select id="estado" name="estado">
                                <option value="activo" <?= $cliente->estado === 'activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="inactivo" <?= $cliente->estado === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        
                        <!-- Información histórica -->
                        <div class="historical-info">
                            <h6><i class="fas fa-history"></i> Información del Registro</h6>
                            <div class="info-item">
                                <span class="label">Cliente registrado:</span>
                                <span class="value">
                                    <?php if ($cliente->fecha_creacion): ?>
                                        <?= date('d/m/Y H:i', strtotime($cliente->fecha_creacion)) ?>
                                    <?php else: ?>
                                        No disponible
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">ID del cliente:</span>
                                <span class="value"><?= htmlspecialchars($cliente->id_clientes) ?></span>
                            </div>
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
                                   value="<?= htmlspecialchars($cliente->correo) ?>"
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
                                   value="<?= htmlspecialchars($cliente->cel_cliente) ?>"
                                   placeholder="Número de teléfono móvil">
                        </div>
                        
                        <!-- Estado actual -->
                        <div class="status-display">
                            <div class="current-status">
                                <span class="label">Estado actual:</span>
                                <span class="status-badge status-<?= $cliente->estado ?>">
                                    <?php if ($cliente->estado === 'activo'): ?>
                                        <i class="fas fa-check-circle"></i> Activo
                                    <?php else: ?>
                                        <i class="fas fa-times-circle"></i> Inactivo
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Vista previa del cliente -->
                        <div class="client-preview">
                            <h6><i class="fas fa-eye"></i> Vista Previa</h6>
                            <div class="preview-card">
                                <div class="client-avatar-large" id="previewAvatar">
                                    <?= strtoupper(substr($cliente->nombre, 0, 1)) ?>
                                </div>
                                <div class="preview-info">
                                    <div class="preview-name" id="previewName"><?= htmlspecialchars($cliente->nombre) ?></div>
                                    <div class="preview-email" id="previewEmail"><?= htmlspecialchars($cliente->correo) ?></div>
                                    <div class="preview-phone" id="previewPhone" <?= !$cliente->cel_cliente ? 'style="display: none;"' : '' ?>>
                                        <i class="fas fa-phone"></i> <span id="previewPhoneText"><?= htmlspecialchars($cliente->cel_cliente) ?></span>
                                    </div>
                                    <div class="preview-local" id="previewLocal">
                                        <i class="fas fa-store"></i> <span id="previewLocalText"><?= htmlspecialchars($cliente->local_nombre) ?></span>
                                    </div>
                                    <div class="preview-status" id="previewStatus">
                                        <span class="status-badge status-<?= $cliente->estado ?>">
                                            <?php if ($cliente->estado === 'activo'): ?>
                                                <i class="fas fa-check-circle"></i> Activo
                                            <?php else: ?>
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información importante -->
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Los cambios se aplicarán inmediatamente</li>
                                <li>El correo debe seguir siendo único</li>
                                <li>El ID del cliente no se puede modificar</li>
                                <li>Verifique la información antes de guardar</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="clientes-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Cliente
                    </button>
                    <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()" title="Imprimir información del cliente">
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
            } else {!
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
    </script>
    
    <!-- Estilos para impresión -->
    <style media="print">
        .clientes-buttons { display: none; }
        .alert { display: none; }
        nav { display: none; }
        .btn { display: none; }
        body { margin: 0; }
        .clientes-container { margin: 0; padding: 20px; }
        
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
