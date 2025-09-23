<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="proveedores-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/ProviderController.php?accion=index">Proveedores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-edit"></i> Editar Proveedor: <?= htmlspecialchars($proveedor->nombre_distribuidor ?? 'Sin nombre') ?></h1>
        
        <!-- Mostrar errores si existen -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="proveedores-form">
            <form method="POST" action="/RMIE/app/controllers/ProviderController.php?accion=edit&id=<?= $proveedor->id_proveedores ?>" id="formEditarProveedor">
                <div class="row">
                    <!-- Información de la empresa -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-building"></i> Información de la Empresa</h5>
                        
                        <div class="form-group">
                            <label for="nombre_distribuidor">
                                <i class="fas fa-truck"></i> Nombre del Distribuidor/Empresa:
                            </label>
                            <input type="text" 
                                   id="nombre_distribuidor" 
                                   name="nombre_distribuidor" 
                                   required 
                                   value="<?= htmlspecialchars($proveedor->nombre_distribuidor ?? '') ?>"
                                   placeholder="Ingrese el nombre de la empresa"
                                   maxlength="100">
                        </div>
                        
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-info-circle"></i> Estado del Proveedor:
                            </label>
                            <select id="estado" name="estado" required>
                                <option value="">Seleccione un estado</option>
                                <option value="activo" <?= ($proveedor->estado ?? '') === 'activo' ? 'selected' : '' ?>>
                                    Activo
                                </option>
                                <option value="inactivo" <?= ($proveedor->estado ?? '') === 'inactivo' ? 'selected' : '' ?>>
                                    Inactivo
                                </option>
                                <option value="pendiente" <?= ($proveedor->estado ?? '') === 'pendiente' ? 'selected' : '' ?>>
                                    Pendiente
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Información de contacto -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-address-book"></i> Información de Contacto</h5>
                        
                        <div class="form-group">
                            <label for="correo">
                                <i class="fas fa-envelope"></i> Correo Electrónico:
                            </label>
                            <input type="email" 
                                   id="correo" 
                                   name="correo" 
                                   required 
                                   value="<?= htmlspecialchars($proveedor->correo ?? '') ?>"
                                   placeholder="ejemplo@empresa.com"
                                   maxlength="100">
                        </div>
                        
                        <div class="form-group">
                            <label for="cel_proveedor">
                                <i class="fas fa-phone"></i> Número de Celular:
                            </label>
                            <input type="tel" 
                                   id="cel_proveedor" 
                                   name="cel_proveedor" 
                                   required 
                                   value="<?= htmlspecialchars($proveedor->cel_proveedor ?? '') ?>"
                                   placeholder="Ej: +57 300 123 4567"
                                   maxlength="20"
                                   pattern="[\+]?[0-9\s\-\(\)]+">
                        </div>
                    </div>
                </div>
                
                <!-- Información del estado actual -->
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-chart-line"></i> Estado Actual del Proveedor</h5>
                        
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center mb-2">
                                <strong><i class="fas fa-id-badge"></i> ID del Proveedor:</strong>
                                <span class="badge bg-secondary ms-2">#<?= htmlspecialchars($proveedor->id_proveedores ?? 'N/A') ?></span>
                            </div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <strong><i class="fas fa-info-circle"></i> Estado Actual:</strong>
                                <?php
                                $estado = strtolower($proveedor->estado ?? 'pendiente');
                                $claseEstado = '';
                                $iconoEstado = '';
                                
                                switch ($estado) {
                                    case 'activo':
                                        $claseEstado = 'estado-activo';
                                        $iconoEstado = 'fas fa-check-circle';
                                        break;
                                    case 'inactivo':
                                        $claseEstado = 'estado-inactivo';
                                        $iconoEstado = 'fas fa-times-circle';
                                        break;
                                    case 'pendiente':
                                        $claseEstado = 'estado-pendiente';
                                        $iconoEstado = 'fas fa-clock';
                                        break;
                                    default:
                                        $claseEstado = 'estado-pendiente';
                                        $iconoEstado = 'fas fa-question-circle';
                                }
                                ?>
                                <span class="estado-badge <?= $claseEstado ?> ms-2">
                                    <i class="<?= $iconoEstado ?>"></i> <?= ucfirst($estado) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="fas fa-clipboard-list"></i> Información Adicional</h5>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong> 
                            <ul class="mb-0 mt-2">
                                <li>Verifique que los datos de contacto estén actualizados</li>
                                <li>Cambiar el estado puede afectar las operaciones comerciales</li>
                                <li>Los cambios se aplicarán inmediatamente</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="proveedores-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Proveedor
                    </button>
                    <a href="/RMIE/app/controllers/ProviderController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para validación -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formEditarProveedor');
        const correoInput = document.getElementById('correo');
        const telefonoInput = document.getElementById('cel_proveedor');
        const nombreInput = document.getElementById('nombre_distribuidor');
        const estadoSelect = document.getElementById('estado');
        
        // Validación de email en tiempo real
        correoInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.setCustomValidity('Por favor ingrese un correo electrónico válido');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
        
        // Formateo de teléfono
        telefonoInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            // Formato colombiano: 300 123 4567
            if (value.length >= 7) {
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
            } else if (value.length >= 4) {
                value = value.replace(/(\d{3})(\d{3})/, '$1 $2');
            }
            
            this.value = value;
        });
        
        // Validación del nombre de la empresa
        nombreInput.addEventListener('input', function() {
            this.value = this.value.replace(/^\s+/, ''); // Quitar espacios al inicio
        });
        
        // Confirmación al cambiar estado
        estadoSelect.addEventListener('change', function() {
            const estadoAnterior = '<?= $proveedor->estado ?? "" ?>';
            const estadoNuevo = this.value;
            
            if (estadoAnterior !== estadoNuevo && estadoNuevo === 'inactivo') {
                if (!confirm('¿Está seguro de cambiar el estado a "Inactivo"?\n\nEsto puede afectar las operaciones comerciales con este proveedor.')) {
                    this.value = estadoAnterior;
                }
            }
        });
        
        // Validación antes del envío
        form.addEventListener('submit', function(e) {
            const correo = correoInput.value;
            const telefono = telefonoInput.value;
            const nombre = nombreInput.value.trim();
            
            if (!nombre) {
                e.preventDefault();
                alert('El nombre del distribuidor es requerido');
                nombreInput.focus();
                return;
            }
            
            if (!correo || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
                e.preventDefault();
                alert('Por favor ingrese un correo electrónico válido');
                correoInput.focus();
                return;
            }
            
            if (!telefono || telefono.replace(/\D/g, '').length < 7) {
                e.preventDefault();
                alert('Por favor ingrese un número de teléfono válido (mínimo 7 dígitos)');
                telefonoInput.focus();
                return;
            }
            
            // Confirmación final
            if (!confirm('¿Está seguro de actualizar la información de este proveedor?')) {
                e.preventDefault();
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
    });
    </script>
</body>
</html>
