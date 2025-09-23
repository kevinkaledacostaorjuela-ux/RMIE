<?php
// Verificar que las variables necesarias estén definidas
if (!isset($cliente)) {
    die('Error: Información del cliente no disponible');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - RMIE</title>
    <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="clientes-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="../../controllers/ClientController.php?accion=index">Clientes</a></li>
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
            <form method="POST" action="../../controllers/ClientController.php?accion=edit&id=<?= $cliente->id_clientes ?>" id="formCliente">
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
                                   value="<?= htmlspecialchars($cliente->nombre ?? '') ?>"
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
                                      placeholder="Información adicional sobre el cliente (opcional)"><?= htmlspecialchars($cliente->descripcion ?? '') ?></textarea>
                            <small class="form-text text-muted">Información adicional, empresa, cargo, etc.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_locales">
                                <i class="fas fa-store"></i> Local asignado:
                                <span class="required">*</span>
                            </label>
                            <select id="id_locales" name="id_locales" required>
                                <option value="">Seleccione un local</option>
                                <?php if (isset($locales) && is_array($locales)): ?>
                                    <?php foreach ($locales as $local): ?>
                                        <option value="<?= $local->id_locales ?>" 
                                                <?= ($cliente->id_locales == $local->id_locales) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($local->nombre_local) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay locales disponibles</option>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted">Local actual: <strong><?= htmlspecialchars($cliente->local_nombre ?? 'No asignado') ?></strong></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-toggle-on"></i> Estado:
                            </label>
                            <select id="estado" name="estado">
                                <option value="activo" <?= ($cliente->estado ?? 'activo') === 'activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="inactivo" <?= ($cliente->estado ?? 'activo') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        
                        <!-- Información histórica -->
                        <div class="historical-info">
                            <h6><i class="fas fa-history"></i> Información del Registro</h6>
                            <div class="info-item">
                                <span class="label">Cliente registrado:</span>
                                <span class="value">
                                    <?php if (isset($cliente->fecha_creacion) && $cliente->fecha_creacion): ?>
                                        <?= date('d/m/Y H:i', strtotime($cliente->fecha_creacion)) ?>
                                    <?php else: ?>
                                        No disponible
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">ID del cliente:</span>
                                <span class="value"><?= htmlspecialchars($cliente->id_clientes ?? 'N/A') ?></span>
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
                                   value="<?= htmlspecialchars($cliente->correo ?? '') ?>"
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
                                   value="<?= htmlspecialchars($cliente->cel_cliente ?? '') ?>"
                                   placeholder="Número de teléfono móvil">
                        </div>
                        
                        <!-- Estado actual -->
                        <div class="status-display">
                            <div class="current-status">
                                <span class="label">Estado actual:</span>
                                <span class="status-badge status-<?= $cliente->estado ?? 'activo' ?>">
                                    <?php if (($cliente->estado ?? 'activo') === 'activo'): ?>
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
                                    <?= strtoupper(substr($cliente->nombre ?? 'C', 0, 1)) ?>
                                </div>
                                <div class="preview-info">
                                    <div class="preview-name" id="previewName"><?= htmlspecialchars($cliente->nombre ?? '') ?></div>
                                    <div class="preview-email" id="previewEmail"><?= htmlspecialchars($cliente->correo ?? '') ?></div>
                                    <div class="preview-phone" id="previewPhone" <?= !($cliente->cel_cliente ?? '') ? 'style="display: none;"' : '' ?>>
                                        <i class="fas fa-phone"></i> <span id="previewPhoneText"><?= htmlspecialchars($cliente->cel_cliente ?? '') ?></span>
                                    </div>
                                    <div class="preview-local" id="previewLocal">
                                        <i class="fas fa-store"></i> <span id="previewLocalText"><?= htmlspecialchars($cliente->local_nombre ?? 'No asignado') ?></span>
                                    </div>
                                    <div class="preview-status" id="previewStatus">
                                        <span class="status-badge status-<?= $cliente->estado ?? 'activo' ?>">
                                            <?php if (($cliente->estado ?? 'activo') === 'activo'): ?>
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
                    <a href="../../controllers/ClientController.php?accion=index" class="btn btn-secondary">
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
        
        // Agregar listeners para vista previa y validación en tiempo real
        ['nombre', 'correo', 'cel_cliente', 'id_locales', 'estado'].forEach(function(fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', function() {
                    actualizarVistaPrevia();
                    validarCampoEnTiempoReal(field);
                });
                field.addEventListener('change', function() {
                    actualizarVistaPrevia();
                    validarCampoEnTiempoReal(field);
                });
            }
        });
        
        // Validación en tiempo real
        function validarCampoEnTiempoReal(field) {
            const fieldId = field.id;
            const value = field.value.trim();
            
            // Remover clases de validación anteriores
            field.classList.remove('is-valid', 'is-invalid');
            
            // Validar según el campo
            let isValid = true;
            
            switch(fieldId) {
                case 'nombre':
                    isValid = value.length >= 2;
                    break;
                case 'correo':
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    isValid = emailPattern.test(value);
                    break;
                case 'id_locales':
                    isValid = value !== '';
                    break;
                default:
                    isValid = true;
            }
            
            // Aplicar clase de validación
            field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        }
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            const correo = document.getElementById('correo').value.trim();
            const nombre = document.getElementById('nombre').value.trim();
            const local = document.getElementById('id_locales').value;
            
            let errors = [];
            
            // Validar nombre
            if (nombre.length < 2) {
                errors.push('El nombre debe tener al menos 2 caracteres');
            }
            
            // Validar correo
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(correo)) {
                errors.push('Por favor ingrese un correo electrónico válido');
            }
            
            // Validar local
            if (!local) {
                errors.push('Por favor seleccione un local');
            }
            
            // Si hay errores, mostrarlos y prevenir envío
            if (errors.length > 0) {
                e.preventDefault();
                alert('Por favor corrija los siguientes errores:\n\n' + errors.join('\n'));
                
                // Enfocar el primer campo con error
                if (nombre.length < 2) {
                    document.getElementById('nombre').focus();
                } else if (!emailPattern.test(correo)) {
                    document.getElementById('correo').focus();
                } else if (!local) {
                    document.getElementById('id_locales').focus();
                }
                return;
            }
            
            // Mostrar mensaje de confirmación
            if (!confirm('¿Está seguro de que desea actualizar la información del cliente?')) {
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
        
        /* Estilos para validación en tiempo real */
        .form-control.is-valid,
        .form-select.is-valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }
        
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .form-control:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
</body>
</html>
