<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ruta - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Animaciones de entrada */
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
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        
        /* Efectos visuales adicionales */
        .form-section {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .info-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body>
    <?php
    // Capturar mensajes de sesión si existen
    $error_message = $_SESSION['error'] ?? '';
    $success_message = $_SESSION['success'] ?? '';
    
    // Limpiar mensajes después de capturarlos
    unset($_SESSION['error'], $_SESSION['success']);
    ?>
    
    <div class="container">
        <div class="rutas-container">
            <!-- Breadcrumb -->
            <div class="rutas-breadcrumb animate-fade-in">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/RMIE/app/views/dashboard.php">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="/RMIE/app/controllers/RouteController.php?accion=index">
                                <i class="fas fa-route"></i> Rutas
                            </a>
                        </li>
                        <li class="breadcrumb-item current">
                            <i class="fas fa-edit"></i> Editar Ruta #<?= $route['id_ruta'] ?? 'N/A' ?>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Header mejorado -->
            <div class="page-header animate-fade-in animate-delay-1">
                <div class="header-content">
                    <h1><i class="fas fa-edit pulse"></i> Editar Ruta de Entrega</h1>
                    <p>Modifica la información de la ruta y visualiza los cambios en tiempo real</p>
                </div>
            </div>

            <!-- Mensajes de éxito o error -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success animate-fade-in" style="background: rgba(40, 167, 69, 0.1); color: #155724; border-left: 4px solid #28a745; padding: 1rem; margin: 1rem 0; border-radius: 8px;">
                    <i class="fas fa-check-circle"></i>
                    <strong>¡Éxito!</strong> <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger animate-fade-in" style="background: rgba(220, 53, 69, 0.1); color: #721c24; border-left: 4px solid #dc3545; padding: 1rem; margin: 1rem 0; border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($route) && $route): ?>
                <!-- Información actual de la ruta mejorada -->
                <div class="ruta-info-panel animate-fade-in animate-delay-2">
                    <h3><i class="fas fa-info-circle"></i> Información Actual de la Ruta</h3>
                    <div class="current-info-grid">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">ID de Ruta</div>
                                <div class="info-value">#<?= htmlspecialchars($route['id_ruta']) ?></div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Dirección Actual</div>
                                <div class="info-value" title="<?= htmlspecialchars($route['direccion']) ?>">
                                    <?= strlen($route['direccion']) > 50 ? substr(htmlspecialchars($route['direccion']), 0, 47) . '...' : htmlspecialchars($route['direccion']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Local Destino</div>
                                <div class="info-value"><?= htmlspecialchars($route['nombre_local']) ?></div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Cliente</div>
                                <div class="info-value"><?= htmlspecialchars($route['nombre_cliente']) ?></div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">ID Cliente</div>
                                <div class="info-value">#<?= htmlspecialchars($route['id_clientes']) ?></div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">ID Venta</div>
                                <div class="info-value">#<?= htmlspecialchars($route['id_ventas']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de edición reorganizado -->
                <div class="rutas-form animate-fade-in animate-delay-3">
                    <form action="/RMIE/app/controllers/RouteController.php?accion=edit&id=<?= $route['id_ruta'] ?>" method="POST" id="editRouteForm">
                        
                        <!-- Sección 1: Información de Ubicación -->
                        <div class="form-section">
                            <h3><i class="fas fa-map-marker-alt"></i> Información de Ubicación</h3>
                            
                            <div class="form-group">
                                <label for="direccion">
                                    <i class="fas fa-map-marker-alt"></i> Dirección Completa *
                                </label>
                                <textarea 
                                    name="direccion" 
                                    id="direccion" 
                                    required
                                    maxlength="200"
                                    placeholder="Ej: Calle 123 # 45-67, Barrio Centro, Bogotá"
                                    class="form-control"
                                    rows="3"><?= htmlspecialchars($route['direccion']) ?></textarea>
                                <small class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Incluye calle, número, barrio y ciudad (mínimo 5 caracteres)
                                </small>
                                <div class="char-counter">
                                    <span id="direccion-counter"><?= strlen($route['direccion']) ?></span>/200 caracteres
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Información del Destino -->
                        <div class="form-row">
                            <div class="form-section">
                                <h3><i class="fas fa-store"></i> Información del Local</h3>
                                
                                <div class="form-group">
                                    <label for="nombre_local">
                                        <i class="fas fa-store"></i> Nombre del Local *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="nombre_local" 
                                        id="nombre_local" 
                                        required
                                        minlength="2"
                                        maxlength="100"
                                        value="<?= htmlspecialchars($route['nombre_local']) ?>"
                                        placeholder="Ej: Tienda El Éxito Centro"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Nombre comercial del establecimiento de destino
                                    </small>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3><i class="fas fa-user"></i> Información del Cliente</h3>
                                
                                <div class="form-group">
                                    <label for="nombre_cliente">
                                        <i class="fas fa-user"></i> Nombre del Cliente *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="nombre_cliente" 
                                        id="nombre_cliente" 
                                        required
                                        minlength="2"
                                        maxlength="100"
                                        value="<?= htmlspecialchars($route['nombre_cliente']) ?>"
                                        placeholder="Ej: Juan Pérez García"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Nombre completo de la persona de contacto
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Referencias del Sistema -->
                        <div class="form-row">
                            <div class="form-section">
                                <h3><i class="fas fa-user-tag"></i> ID del Cliente</h3>
                                
                                <div class="form-group">
                                    <label for="id_clientes">
                                        <i class="fas fa-user-tag"></i> Identificador del Cliente *
                                    </label>
                                    <input 
                                        type="number" 
                                        name="id_clientes" 
                                        id="id_clientes" 
                                        required
                                        min="1"
                                        value="<?= htmlspecialchars($route['id_clientes']) ?>"
                                        placeholder="Ej: 123"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Identificador único del cliente en el sistema
                                    </small>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3><i class="fas fa-shopping-cart"></i> ID de la Venta</h3>
                                
                                <div class="form-group">
                                    <label for="id_ventas">
                                        <i class="fas fa-shopping-cart"></i> Identificador de la Venta *
                                    </label>
                                    <input 
                                        type="number" 
                                        name="id_ventas" 
                                        id="id_ventas" 
                                        required
                                        min="1"
                                        value="<?= htmlspecialchars($route['id_ventas']) ?>"
                                        placeholder="Ej: 456"
                                        class="form-control">
                                    <small class="form-text">
                                        <i class="fas fa-info-circle"></i>
                                        Número de venta asociada a esta ruta de entrega
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 4: Vista previa de cambios -->
                        <div class="form-section">
                            <h3><i class="fas fa-eye"></i> Vista Previa de Cambios</h3>
                            <div class="changes-preview" id="changesPreview">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Modifica los campos y verás aquí un resumen de los cambios realizados en tiempo real.
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción mejorados -->
                        <div class="form-actions">
                            <button type="submit" class="btn-rutas" id="submitBtn">
                                <i class="fas fa-save"></i> Actualizar Ruta
                            </button>
                            <button type="button" class="btn-outline" id="resetBtn">
                                <i class="fas fa-undo"></i> Restaurar Valores
                            </button>
                            <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a Rutas
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Panel de ayuda y precauciones mejorado -->
                <div class="ruta-info-panel animate-fade-in animate-delay-4">
                    <h3><i class="fas fa-exclamation-triangle"></i> Precauciones Importantes</h3>
                    <div class="warning-grid">
                        <div class="warning-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Dirección de Entrega:</strong><br>
                                Verifica que la nueva dirección sea correcta y esté completa para evitar entregas fallidas. Incluye referencias claras.
                            </div>
                        </div>
                        <div class="warning-item">
                            <i class="fas fa-link"></i>
                            <div>
                                <strong>Referencias del Sistema:</strong><br>
                                Los cambios en IDs de cliente y venta pueden afectar la relación con otros registros. Verifica antes de guardar.
                            </div>
                        </div>
                        <div class="warning-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Estado de Entrega:</strong><br>
                                Si la ruta ya está en proceso de entrega, coordina los cambios con el equipo de reparto.
                            </div>
                        </div>
                        <div class="warning-item">
                            <i class="fas fa-save"></i>
                            <div>
                                <strong>Guardado Automático:</strong><br>
                                Los cambios se aplicarán inmediatamente al confirmar. No hay función de deshacer después de guardar.
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Error: Ruta no encontrada -->
                <div class="error-state">
                    <div class="error-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3>Ruta No Encontrada</h3>
                    <p>La ruta que intentas editar no existe o ha sido eliminada.</p>
                    <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn-rutas">
                        <i class="fas fa-arrow-left"></i> Volver a Rutas
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($route) && $route): ?>
                // Datos originales para comparación
                const originalData = {
                    direccion: <?= json_encode($route['direccion']) ?>,
                    nombre_local: <?= json_encode($route['nombre_local']) ?>,
                    nombre_cliente: <?= json_encode($route['nombre_cliente']) ?>,
                    id_clientes: <?= json_encode($route['id_clientes']) ?>,
                    id_ventas: <?= json_encode($route['id_ventas']) ?>
                };

                // Referencias a elementos del formulario
                const form = document.getElementById('editRouteForm');
                const direccionField = document.getElementById('direccion');
                const localField = document.getElementById('nombre_local');
                const clienteField = document.getElementById('nombre_cliente');
                const idClienteField = document.getElementById('id_clientes');
                const idVentaField = document.getElementById('id_ventas');
                const submitBtn = document.getElementById('submitBtn');

                // ===============================
                // CONTADOR DE CARACTERES DINÁMICO
                // ===============================
                direccionField.addEventListener('input', function() {
                    const counter = document.getElementById('direccion-counter');
                    const length = this.value.length;
                    counter.textContent = length;
                    
                    // Cambio de color según cantidad de caracteres
                    if (length > 180) {
                        counter.style.color = '#dc3545';
                        counter.style.fontWeight = 'bold';
                    } else if (length > 150) {
                        counter.style.color = '#ffc107';
                        counter.style.fontWeight = '600';
                    } else {
                        counter.style.color = '#28a745';
                        counter.style.fontWeight = '500';
                    }
                });

                // ===============================
                // VALIDACIÓN EN TIEMPO REAL
                // ===============================
                function validateField(field, minLength, maxLength, fieldName) {
                    const value = field.value.trim();
                    let isValid = true;
                    
                    // Remover clases anteriores
                    field.classList.remove('is-valid', 'is-invalid');
                    
                    if (value.length < minLength) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else if (value.length > maxLength) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.add('is-valid');
                    }
                    
                    return isValid;
                }

                // Validaciones en tiempo real para cada campo
                direccionField.addEventListener('input', () => {
                    validateField(direccionField, 5, 200, 'Dirección');
                    showChanges();
                });

                localField.addEventListener('input', () => {
                    validateField(localField, 2, 100, 'Nombre del Local');
                    showChanges();
                });

                clienteField.addEventListener('input', () => {
                    validateField(clienteField, 2, 100, 'Nombre del Cliente');
                    showChanges();
                });

                idClienteField.addEventListener('input', () => {
                    const value = parseInt(idClienteField.value);
                    idClienteField.classList.remove('is-valid', 'is-invalid');
                    
                    if (isNaN(value) || value <= 0) {
                        idClienteField.classList.add('is-invalid');
                    } else {
                        idClienteField.classList.add('is-valid');
                    }
                    showChanges();
                });

                idVentaField.addEventListener('input', () => {
                    const value = parseInt(idVentaField.value);
                    idVentaField.classList.remove('is-valid', 'is-invalid');
                    
                    if (isNaN(value) || value <= 0) {
                        idVentaField.classList.add('is-invalid');
                    } else {
                        idVentaField.classList.add('is-valid');
                    }
                    showChanges();
                });

                // ===============================
                // VISTA PREVIA DE CAMBIOS MEJORADA
                // ===============================
                function showChanges() {
                    const changes = [];
                    const changesContainer = document.getElementById('changesPreview');

                    // Detectar cambios en cada campo
                    const fieldChanges = [
                        { field: 'Dirección', current: direccionField.value, original: originalData.direccion, icon: 'fas fa-map-marker-alt' },
                        { field: 'Local', current: localField.value, original: originalData.nombre_local, icon: 'fas fa-store' },
                        { field: 'Cliente', current: clienteField.value, original: originalData.nombre_cliente, icon: 'fas fa-user' },
                        { field: 'ID Cliente', current: idClienteField.value, original: originalData.id_clientes, icon: 'fas fa-user-tag' },
                        { field: 'ID Venta', current: idVentaField.value, original: originalData.id_ventas, icon: 'fas fa-shopping-cart' }
                    ];

                    fieldChanges.forEach(item => {
                        if (item.current !== item.original) {
                            changes.push(item);
                        }
                    });

                    if (changes.length === 0) {
                        changesContainer.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Sin cambios detectados.</strong><br>
                                Modifica los campos para ver una vista previa de los cambios.
                            </div>
                        `;
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.6';
                    } else {
                        let changesHtml = `
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Cambios detectados (${changes.length}):</strong>
                                Los siguientes campos serán actualizados:
                            </div>
                        `;

                        changes.forEach((change, index) => {
                            changesHtml += `
                                <div class="change-item" style="animation-delay: ${index * 0.1}s;">
                                    <i class="${change.icon}" style="color: #667eea; margin-right: 8px;"></i>
                                    <strong>${change.field}:</strong><br>
                                    <div style="margin-left: 20px; margin-top: 5px;">
                                        <span class="old-value">
                                            <i class="fas fa-arrow-right" style="color: #dc3545;"></i> 
                                            Actual: "${change.original}"
                                        </span><br>
                                        <span class="new-value">
                                            <i class="fas fa-arrow-right" style="color: #28a745;"></i> 
                                            Nuevo: "${change.current}"
                                        </span>
                                    </div>
                                </div>
                            `;
                        });

                        changesContainer.innerHTML = changesHtml;
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                    }
                }

                // ===============================
                // BOTÓN DE RESTAURAR MEJORADO
                // ===============================
                document.getElementById('resetBtn').addEventListener('click', function() {
                    const confirmMessage = `¿Estás seguro de que deseas restaurar todos los valores originales?
                    
Esta acción descartará todos los cambios realizados y no se puede deshacer.`;

                    if (confirm(confirmMessage)) {
                        // Restaurar valores con animación
                        direccionField.value = originalData.direccion;
                        localField.value = originalData.nombre_local;
                        clienteField.value = originalData.nombre_cliente;
                        idClienteField.value = originalData.id_clientes;
                        idVentaField.value = originalData.id_ventas;
                        
                        // Limpiar validaciones
                        [direccionField, localField, clienteField, idClienteField, idVentaField].forEach(field => {
                            field.classList.remove('is-valid', 'is-invalid');
                        });
                        
                        // Actualizar vista de cambios
                        showChanges();
                        
                        // Mostrar mensaje de confirmación
                        const changesContainer = document.getElementById('changesPreview');
                        changesContainer.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-check-circle"></i>
                                <strong>Valores restaurados.</strong><br>
                                Se han restaurado todos los valores originales de la ruta.
                            </div>
                        `;
                        
                        setTimeout(() => {
                            showChanges();
                        }, 2000);
                    }
                });

                // ===============================
                // VALIDACIÓN SIMPLIFICADA DEL FORMULARIO
                // ===============================
                form.addEventListener('submit', function(e) {
                    console.log('=== ENVÍO DE FORMULARIO ===');
                    
                    // Obtener valores actuales
                    const direccion = direccionField.value.trim();
                    const local = localField.value.trim();
                    const cliente = clienteField.value.trim();
                    const idCliente = parseInt(idClienteField.value);
                    const idVenta = parseInt(idVentaField.value);
                    
                    console.log('Datos a enviar:', {
                        direccion,
                        local,
                        cliente,
                        idCliente,
                        idVenta
                    });

                    // Validaciones básicas únicamente
                    if (direccion.length < 5) {
                        alert('La dirección debe tener al menos 5 caracteres');
                        e.preventDefault();
                        return false;
                    }
                    
                    if (local.length < 2) {
                        alert('El nombre del local debe tener al menos 2 caracteres');
                        e.preventDefault();
                        return false;
                    }
                    
                    if (cliente.length < 2) {
                        alert('El nombre del cliente debe tener al menos 2 caracteres');
                        e.preventDefault();
                        return false;
                    }

                    // Mostrar estado de carga
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando ruta...';
                    submitBtn.disabled = true;
                    
                    console.log('✅ Formulario válido, enviando...');
                    return true;
                });

                // ===============================
                // INICIALIZACIÓN
                // ===============================
                
                // Inicializar vista de cambios
                showChanges();
                
                // Validar campos iniciales
                setTimeout(() => {
                    validateField(direccionField, 5, 200, 'Dirección');
                    validateField(localField, 2, 100, 'Nombre del Local');
                    validateField(clienteField, 2, 100, 'Nombre del Cliente');
                }, 500);

                // Mostrar mensaje de carga completada
                console.log('✅ Formulario de edición de rutas cargado correctamente');
                
            <?php endif; ?>
        });
    </script>
</body>
</html>
