<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Proveedor - RMIE</title>
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
                <li class="breadcrumb-item active" aria-current="page">Agregar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-plus-circle"></i> Agregar Nuevo Proveedor</h1>
        
        <!-- Mostrar errores si existen -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="proveedores-form">
            <form method="POST" action="/RMIE/app/controllers/ProviderController.php?accion=create" id="formProveedor">
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
                                   value="<?= htmlspecialchars($_POST['nombre_distribuidor'] ?? '') ?>"
                                   placeholder="Ingrese el nombre de la empresa"
                                   maxlength="100">
                        </div>
                        
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-info-circle"></i> Estado del Proveedor:
                            </label>
                            <select id="estado" name="estado" required>
                                <option value="">Seleccione un estado</option>
                                <option value="activo" <?= ($_POST['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>
                                    <i class="fas fa-check-circle"></i> Activo
                                </option>
                                <option value="inactivo" <?= ($_POST['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </option>
                                <option value="pendiente" <?= ($_POST['estado'] ?? '') === 'pendiente' ? 'selected' : '' ?>>
                                    <i class="fas fa-clock"></i> Pendiente
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
                                   value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
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
                                   value="<?= htmlspecialchars($_POST['cel_proveedor'] ?? '') ?>"
                                   placeholder="Ej: +57 300 123 4567"
                                   maxlength="20"
                                   pattern="[\+]?[0-9\s\-\(\)]+">
                        </div>
                    </div>
                </div>
                
                <!-- Información adicional -->
                <div class="row">
                    <div class="col-12">
                        <h5><i class="fas fa-clipboard-list"></i> Información Adicional</h5>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> Asegúrese de que toda la información sea correcta antes de guardar. 
                            Los datos de contacto serán utilizados para comunicaciones comerciales.
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="proveedores-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Proveedor
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
        const form = document.getElementById('formProveedor');
        const correoInput = document.getElementById('correo');
        const telefonoInput = document.getElementById('cel_proveedor');
        const nombreInput = document.getElementById('nombre_distribuidor');
        
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
