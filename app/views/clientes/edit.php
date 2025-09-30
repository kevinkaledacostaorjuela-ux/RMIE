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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/styles.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .clientes-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Breadcrumb moderno */
        .subcategorias-breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .breadcrumb {
            margin: 0;
            background: transparent;
        }

        .breadcrumb-item a {
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #ffd700;
            transform: translateY(-1px);
        }

        .breadcrumb-item.active {
            color: #ffd700;
            font-weight: 600;
        }

        /* Título principal */
        h1 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        h1 i {
            margin-right: 15px;
            color: #ffd700;
        }

        /* Formulario moderno */
        .clientes-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Títulos de sección */
        h5 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
            display: flex;
            align-items: center;
        }

        h5 i {
            margin-right: 10px;
            color: #764ba2;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .form-group label i {
            margin-right: 8px;
            color: #667eea;
            width: 16px;
        }

        .required {
            color: #dc3545;
            margin-left: 5px;
        }

        /* Inputs modernos */
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 18px;
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Texto de ayuda */
        .form-text {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }

        /* Campo de solo lectura */
        .form-control-plaintext {
            background-color: #f8f9fa !important;
            cursor: not-allowed !important;
            color: #6c757d !important;
            border: 2px solid #e9ecef !important;
        }

        /* Información histórica */
        .historical-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
            margin-top: 25px;
            border: 1px solid #dee2e6;
        }

        .historical-info h6 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .historical-info h6 i {
            margin-right: 8px;
            color: #764ba2;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item .label {
            font-weight: 600;
            color: #495057;
        }

        .info-item .value {
            color: #667eea;
            font-weight: 500;
        }

        /* Alertas modernas */
        .alert {
            border-radius: 15px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }

        .alert-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
        }

        /* Botones modernos */
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-modern {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 150px;
        }

        .btn-modern i {
            margin-right: 8px;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(81, 207, 102, 0.4);
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(81, 207, 102, 0.6);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(135deg, #868e96 0%, #6c757d 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(134, 142, 150, 0.4);
        }

        .btn-secondary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(134, 142, 150, 0.6);
            color: white;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .clientes-container {
                padding: 10px;
            }

            .clientes-form {
                padding: 20px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-modern {
                min-width: 100%;
            }
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .clientes-form {
            animation: fadeInUp 0.6s ease;
        }

        /* Estado actual */
        .status-display {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }

        .current-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .current-status .label {
            font-weight: 600;
            color: #495057;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .status-badge i {
            margin-right: 5px;
        }

        .status-badge.status-activo {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
        }

        .status-badge.status-inactivo {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }

        /* Vista previa del cliente */
        .client-preview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            margin-top: 25px;
            color: white;
        }

        .client-preview h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .client-preview h6 i {
            margin-right: 8px;
            color: #ffd700;
        }

        .preview-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .client-avatar-large {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            color: #333;
            margin-right: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .preview-info {
            flex: 1;
        }

        .preview-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #fff;
        }

        .preview-email {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 8px;
        }

        .preview-phone,
        .preview-local {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }

        .preview-phone i,
        .preview-local i {
            margin-right: 5px;
            color: #ffd700;
        }

        .preview-status {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="clientes-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
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
                        
                        <!-- Campos adicionales -->
                        <div class="form-group">
                            <label for="direccion">
                                <i class="fas fa-map-marker-alt"></i> Dirección:
                            </label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   maxlength="200"
                                   value="<?= htmlspecialchars($cliente->direccion ?? '') ?>"
                                   placeholder="Dirección completa del cliente">
                            <small class="form-text text-muted">Dirección de residencia o trabajo</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ciudad">
                                        <i class="fas fa-city"></i> Ciudad:
                                    </label>
                                    <input type="text" 
                                           id="ciudad" 
                                           name="ciudad" 
                                           maxlength="100"
                                           value="<?= htmlspecialchars($cliente->ciudad ?? '') ?>"
                                           placeholder="Ciudad">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">
                                        <i class="fas fa-birthday-cake"></i> Fecha de Nacimiento:
                                    </label>
                                    <input type="date" 
                                           id="fecha_nacimiento" 
                                           name="fecha_nacimiento" 
                                           value="<?= htmlspecialchars($cliente->fecha_nacimiento ?? '') ?>"
                                           max="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="preferencias">
                                <i class="fas fa-heart"></i> Preferencias del Cliente:
                            </label>
                            <textarea id="preferencias" 
                                      name="preferencias" 
                                      rows="2"
                                      maxlength="500"
                                      placeholder="Preferencias de productos, observaciones especiales, etc."><?= htmlspecialchars($cliente->preferencias ?? '') ?></textarea>
                            <small class="form-text text-muted">Información sobre gustos, alergias, preferencias especiales</small>
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
                <div class="btn-group">
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fas fa-save"></i> Actualizar Cliente
                    </button>
                    <a href="/RMIE/app/controllers/ClientController.php?accion=index" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn-modern btn-primary-modern" onclick="window.print()" title="Imprimir información del cliente">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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
        
        /* Vista previa adicional */
        .additional-preview-info {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        
        .preview-address, .preview-birth {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .preview-address i, .preview-birth i {
            color: #007bff;
            width: 12px;
        }
        
        /* Mejoras en campos del formulario */
        .form-group {
            position: relative;
        }
        
        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            transform: translateY(-20px) scale(0.85);
            color: #007bff;
        }
        
        .character-counter {
            position: absolute;
            right: 10px;
            bottom: 5px;
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .character-counter.warning {
            color: #ffc107;
        }
        
        .character-counter.danger {
            color: #dc3545;
        }
        
        /* Animaciones suaves para cambios */
        .preview-card {
            transition: all 0.3s ease;
        }
        
        .preview-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        /* Estados de validación mejorados */
        .is-valid {
            border-color: #198754;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.4.4c.2.2.5.2.7 0L7.7 3.3a.5.5 0 0 0-.7-.7L3.4 6.01.8 3.4a.5.5 0 1 0-.7.7l1.5 1.63z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4m0 0 1.4 1.4m-1.4-1.4L5.8 7.4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        /* Indicador de guardado automático */
        .auto-save-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            border-radius: 25px;
            background: rgba(25, 135, 84, 0.9);
            color: white;
            font-size: 0.875rem;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            z-index: 1050;
        }
        
        .auto-save-indicator.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .auto-save-indicator.error {
            background: rgba(220, 53, 69, 0.9);
        }
        
        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Indicador de guardado automático -->
    <div id="autoSaveIndicator" class="auto-save-indicator">
        <i class="fas fa-save me-2"></i>
        <span id="saveMessage">Guardando...</span>
    </div>

    <!-- Scripts mejorados -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formCliente');
            const inputs = form.querySelectorAll('input, select, textarea');
            const autoSaveIndicator = document.getElementById('autoSaveIndicator');
            const saveMessage = document.getElementById('saveMessage');
            
            let autoSaveTimeout;
            let originalData = new FormData(form);
            
            // Función para mostrar notificación
            function showNotification(message, type = 'success') {
                autoSaveIndicator.className = `auto-save-indicator show ${type === 'error' ? 'error' : ''}`;
                saveMessage.innerHTML = type === 'loading' ? 
                    '<span class="loading-spinner"></span>' + message :
                    '<i class="fas fa-' + (type === 'error' ? 'exclamation-circle' : 'check') + ' me-2"></i>' + message;
                
                if (type !== 'loading') {
                    setTimeout(() => {
                        autoSaveIndicator.classList.remove('show');
                    }, 3000);
                }
            }
            
            // Función para actualizar vista previa
            function updatePreview() {
                const nombre = document.getElementById('nombre').value;
                const correo = document.getElementById('correo').value;
                const celular = document.getElementById('cel_cliente').value;
                const estado = document.getElementById('estado').value;
                const direccion = document.getElementById('direccion')?.value || '';
                const ciudad = document.getElementById('ciudad')?.value || '';
                const localSelect = document.getElementById('id_locales');
                const localName = localSelect.options[localSelect.selectedIndex]?.text || 'No asignado';
                
                // Actualizar elementos básicos
                const previewName = document.getElementById('previewName') || document.querySelector('.preview-name');
                const previewEmail = document.getElementById('previewEmail') || document.querySelector('.preview-email');
                const previewPhone = document.getElementById('previewPhone') || document.querySelector('.preview-phone');
                const previewLocal = document.getElementById('previewLocalText');
                const previewAvatar = document.getElementById('previewAvatar');
                
                if (previewName) previewName.textContent = nombre || 'Nombre del Cliente';
                if (previewEmail) previewEmail.textContent = correo || 'correo@ejemplo.com';
                
                // Mostrar/ocultar teléfono
                if (previewPhone) {
                    const phoneText = document.getElementById('previewPhoneText');
                    if (celular) {
                        previewPhone.style.display = 'block';
                        if (phoneText) phoneText.textContent = celular;
                    } else {
                        previewPhone.style.display = 'none';
                    }
                }
                
                // Actualizar local
                if (previewLocal) {
                    previewLocal.textContent = localName !== 'Seleccione un local' ? localName : 'No asignado';
                }
                
                // Actualizar avatar
                if (previewAvatar && nombre) {
                    previewAvatar.textContent = nombre.charAt(0).toUpperCase();
                }
                
                // Actualizar estado
                const statusElements = document.querySelectorAll('.status-badge');
                statusElements.forEach(element => {
                    element.className = `status-badge status-${estado}`;
                    element.innerHTML = estado === 'activo' ? 
                        '<i class="fas fa-check-circle"></i> Activo' : 
                        '<i class="fas fa-times-circle"></i> Inactivo';
                });
                
                // Actualizar información adicional en tiempo real
                updateAdditionalInfo();
            }
            
            // Función para actualizar información adicional
            function updateAdditionalInfo() {
                const direccion = document.getElementById('direccion')?.value || '';
                const ciudad = document.getElementById('ciudad')?.value || '';
                const fechaNacimiento = document.getElementById('fecha_nacimiento')?.value || '';
                
                // Calcular edad si hay fecha de nacimiento
                let edadText = '';
                if (fechaNacimiento) {
                    const hoy = new Date();
                    const nacimiento = new Date(fechaNacimiento);
                    let edad = hoy.getFullYear() - nacimiento.getFullYear();
                    const mesActual = hoy.getMonth();
                    const mesNacimiento = nacimiento.getMonth();
                    
                    if (mesActual < mesNacimiento || (mesActual === mesNacimiento && hoy.getDate() < nacimiento.getDate())) {
                        edad--;
                    }
                    
                    if (edad >= 0 && edad < 120) {
                        edadText = ` (${edad} años)`;
                    }
                }
                
                // Crear o actualizar información adicional en la vista previa
                let additionalInfo = document.getElementById('additionalPreviewInfo');
                if (!additionalInfo) {
                    additionalInfo = document.createElement('div');
                    additionalInfo.id = 'additionalPreviewInfo';
                    additionalInfo.className = 'additional-preview-info';
                    document.querySelector('.preview-info').appendChild(additionalInfo);
                }
                
                let infoHTML = '';
                if (direccion || ciudad) {
                    infoHTML += '<div class="preview-address"><i class="fas fa-map-marker-alt"></i> ';
                    if (direccion && ciudad) {
                        infoHTML += `${direccion}, ${ciudad}`;
                    } else {
                        infoHTML += direccion || ciudad;
                    }
                    infoHTML += '</div>';
                }
                
                if (fechaNacimiento) {
                    const fechaFormateada = new Date(fechaNacimiento).toLocaleDateString('es-ES');
                    infoHTML += `<div class="preview-birth"><i class="fas fa-birthday-cake"></i> ${fechaFormateada}${edadText}</div>`;
                }
                
                additionalInfo.innerHTML = infoHTML;
            }
            
            // Función de guardado automático
            function autoSave() {
                const currentData = new FormData(form);
                let hasChanges = false;
                
                // Verificar si hay cambios
                for (let [key, value] of currentData.entries()) {
                    if (originalData.get(key) !== value) {
                        hasChanges = true;
                        break;
                    }
                }
                
                if (!hasChanges) return;
                
                showNotification('Guardando cambios...', 'loading');
                
                // Simular guardado (en una implementación real, harías una petición AJAX)
                setTimeout(() => {
                    // Aquí irían las validaciones
                    const email = currentData.get('correo');
                    const nombre = currentData.get('nombre');
                    
                    if (!nombre.trim()) {
                        showNotification('El nombre es requerido', 'error');
                        return;
                    }
                    
                    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                        showNotification('Email inválido', 'error');
                        return;
                    }
                    
                    originalData = new FormData(form);
                    showNotification('Cambios guardados automáticamente', 'success');
                }, 1000);
            }
            
            // Agregar contadores de caracteres
            const textFields = ['nombre', 'descripcion', 'correo', 'cel_cliente', 'direccion', 'ciudad', 'preferencias'];
            textFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field && field.hasAttribute('maxlength')) {
                    addCharacterCounter(field);
                }
            });
            
            // Función para agregar contador de caracteres
            function addCharacterCounter(field) {
                const maxLength = field.getAttribute('maxlength');
                if (!maxLength) return;
                
                const counter = document.createElement('div');
                counter.className = 'character-counter';
                field.parentNode.appendChild(counter);
                
                function updateCounter() {
                    const currentLength = field.value.length;
                    counter.textContent = `${currentLength}/${maxLength}`;
                    
                    // Cambiar color basado en porcentaje usado
                    const percentage = (currentLength / maxLength) * 100;
                    counter.className = 'character-counter';
                    
                    if (percentage > 90) {
                        counter.classList.add('danger');
                    } else if (percentage > 75) {
                        counter.classList.add('warning');
                    }
                }
                
                field.addEventListener('input', updateCounter);
                updateCounter(); // Inicializar
            }
            
            // Escuchar cambios en los campos
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    updatePreview();
                    
                    // Validación en tiempo real
                    validateField(this);
                    
                    // Programar guardado automático
                    clearTimeout(autoSaveTimeout);
                    autoSaveTimeout = setTimeout(autoSave, 2000); // Guardar después de 2 segundos sin cambios
                });
                
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                // Mejorar experiencia con campos de fecha
                if (input.type === 'date') {
                    input.addEventListener('change', function() {
                        const today = new Date();
                        const selectedDate = new Date(this.value);
                        
                        if (selectedDate > today) {
                            this.setCustomValidity('La fecha no puede ser futura');
                            validateField(this);
                        } else {
                            this.setCustomValidity('');
                            validateField(this);
                        }
                    });
                }
            });
            
            // Función de validación de campos
            function validateField(field) {
                const value = field.value.trim();
                const fieldName = field.name;
                let isValid = true;
                let errorMessage = '';
                
                // Limpiar clases anteriores
                field.classList.remove('is-valid', 'is-invalid');
                
                // Validaciones específicas
                switch(fieldName) {
                    case 'nombre':
                        if (!value) {
                            isValid = false;
                            errorMessage = 'El nombre es requerido';
                        } else if (value.length < 2) {
                            isValid = false;
                            errorMessage = 'El nombre debe tener al menos 2 caracteres';
                        }
                        break;
                        
                    case 'correo':
                        if (!value) {
                            isValid = false;
                            errorMessage = 'El correo es requerido';
                        } else if (!value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                            isValid = false;
                            errorMessage = 'Formato de correo inválido';
                        }
                        break;
                        
                    case 'cel_cliente':
                        if (value && !value.match(/^[\d\-\+\(\)\s]+$/)) {
                            isValid = false;
                            errorMessage = 'Formato de teléfono inválido';
                        }
                        break;
                        
                    case 'direccion':
                        if (value && value.length < 10) {
                            isValid = false;
                            errorMessage = 'La dirección debe ser más específica';
                        }
                        break;
                        
                    case 'ciudad':
                        if (value && value.length < 2) {
                            isValid = false;
                            errorMessage = 'Nombre de ciudad muy corto';
                        }
                        break;
                        
                    case 'fecha_nacimiento':
                        if (value) {
                            const today = new Date();
                            const birthDate = new Date(value);
                            const age = today.getFullYear() - birthDate.getFullYear();
                            
                            if (birthDate > today) {
                                isValid = false;
                                errorMessage = 'La fecha de nacimiento no puede ser futura';
                            } else if (age > 120) {
                                isValid = false;
                                errorMessage = 'Edad no válida';
                            }
                        }
                        break;
                        
                    case 'id_locales':
                        if (!value) {
                            isValid = false;
                            errorMessage = 'Debe seleccionar un local';
                        }
                        break;
                }
                
                // Aplicar clases de validación
                field.classList.add(isValid ? 'is-valid' : 'is-invalid');
                
                // Mostrar mensaje de error
                let feedbackDiv = field.parentNode.querySelector('.invalid-feedback');
                if (!isValid) {
                    if (!feedbackDiv) {
                        feedbackDiv = document.createElement('div');
                        feedbackDiv.className = 'invalid-feedback';
                        field.parentNode.appendChild(feedbackDiv);
                    }
                    feedbackDiv.textContent = errorMessage;
                } else if (feedbackDiv) {
                    feedbackDiv.remove();
                }
                
                return isValid;
            }
            
            // Validar formulario completo antes del envío
            form.addEventListener('submit', function(e) {
                let isFormValid = true;
                
                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isFormValid = false;
                    }
                });
                
                if (!isFormValid) {
                    e.preventDefault();
                    showNotification('Por favor corrige los errores antes de guardar', 'error');
                    return;
                }
                
                showNotification('Guardando cliente...', 'loading');
            });
            
            // Inicializar vista previa
            updatePreview();
            
            // Atajo de teclado para guardar (Ctrl+S)
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    form.submit();
                }
            });
            
            // Confirmar antes de salir si hay cambios sin guardar
            window.addEventListener('beforeunload', function(e) {
                const currentData = new FormData(form);
                let hasUnsavedChanges = false;
                
                for (let [key, value] of currentData.entries()) {
                    if (originalData.get(key) !== value) {
                        hasUnsavedChanges = true;
                        break;
                    }
                }
                
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '¿Estás seguro de que quieres salir sin guardar los cambios?';
                }
            });
        });
    </script>
</body>
</html>
