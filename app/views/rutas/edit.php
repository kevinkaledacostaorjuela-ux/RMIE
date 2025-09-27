<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ruta - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="rutas-container">
            <!-- Breadcrumb -->
            <div class="rutas-breadcrumb">
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

            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1><i class="fas fa-edit"></i> Editar Ruta</h1>
                    <p>Modifica la información de la ruta de entrega</p>
                </div>
            </div>

            <?php if (isset($route) && $route): ?>
                <!-- Información actual de la ruta -->
                <div class="ruta-info-panel">
                    <h3><i class="fas fa-info-circle"></i> Información Actual</h3>
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
                                <div class="info-value"><?= htmlspecialchars($route['direccion']) ?></div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Local</div>
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
                    </div>
                </div>

                <!-- Formulario de edición -->
                <div class="rutas-form">
                    <form action="/RMIE/app/controllers/RouteController.php?accion=edit&id=<?= $route['id_ruta'] ?>" method="POST" id="editRouteForm">
                        <div class="form-row">
                            <!-- Información de ubicación -->
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

                            <!-- Información del destino -->
                            <div class="form-section">
                                <h3><i class="fas fa-users"></i> Información del Destino</h3>
                                
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

                        <div class="form-row">
                            <!-- IDs de referencias -->
                            <div class="form-section">
                                <h3><i class="fas fa-link"></i> Referencias del Sistema</h3>
                                
                                <div class="form-group">
                                    <label for="id_clientes">
                                        <i class="fas fa-user-tag"></i> ID del Cliente *
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

                                <div class="form-group">
                                    <label for="id_ventas">
                                        <i class="fas fa-shopping-cart"></i> ID de la Venta *
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

                            <!-- Comparación de cambios -->
                            <div class="form-section">
                                <h3><i class="fas fa-exchange-alt"></i> Resumen de Cambios</h3>
                                <div class="changes-preview" id="changesPreview">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Modifica los campos y verás aquí un resumen de los cambios realizados.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="form-actions">
                            <button type="submit" class="btn-rutas" id="submitBtn">
                                <i class="fas fa-save"></i> Actualizar Ruta
                            </button>
                            <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="button" class="btn-outline" id="resetBtn">
                                <i class="fas fa-undo"></i> Restaurar Valores
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Panel de ayuda -->
                <div class="ruta-info-panel">
                    <h3><i class="fas fa-exclamation-triangle"></i> Precauciones</h3>
                    <div class="warning-grid">
                        <div class="warning-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <strong>Dirección:</strong> Verifica que la nueva dirección sea correcta para evitar entregas fallidas.
                        </div>
                        <div class="warning-item">
                            <i class="fas fa-link"></i>
                            <strong>Referencias:</strong> Los cambios en IDs pueden afectar la relación con otros registros.
                        </div>
                        <div class="warning-item">
                            <i class="fas fa-clock"></i>
                            <strong>Entrega:</strong> Si la ruta ya está en proceso, coordina los cambios con el equipo de entrega.
                        </div>
                        <div class="warning-item">
                            <i class="fas fa-save"></i>
                            <strong>Guardado:</strong> Los cambios se aplicarán inmediatamente al confirmar la actualización.
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
                const originalData = {
                    direccion: <?= json_encode($route['direccion']) ?>,
                    nombre_local: <?= json_encode($route['nombre_local']) ?>,
                    nombre_cliente: <?= json_encode($route['nombre_cliente']) ?>,
                    id_clientes: <?= json_encode($route['id_clientes']) ?>,
                    id_ventas: <?= json_encode($route['id_ventas']) ?>
                };

                const form = document.getElementById('editRouteForm');
                const direccionField = document.getElementById('direccion');
                const localField = document.getElementById('nombre_local');
                const clienteField = document.getElementById('nombre_cliente');
                const idClienteField = document.getElementById('id_clientes');
                const idVentaField = document.getElementById('id_ventas');

                // Contador de caracteres para dirección
                direccionField.addEventListener('input', function() {
                    const counter = document.getElementById('direccion-counter');
                    counter.textContent = this.value.length;
                    
                    if (this.value.length > 180) {
                        counter.style.color = '#dc3545';
                    } else if (this.value.length > 150) {
                        counter.style.color = '#ffc107';
                    } else {
                        counter.style.color = '#28a745';
                    }
                });

                // Función para mostrar cambios
                function showChanges() {
                    const changes = [];
                    const changesContainer = document.getElementById('changesPreview');

                    if (direccionField.value !== originalData.direccion) {
                        changes.push({
                            field: 'Dirección',
                            old: originalData.direccion,
                            new: direccionField.value
                        });
                    }

                    if (localField.value !== originalData.nombre_local) {
                        changes.push({
                            field: 'Local',
                            old: originalData.nombre_local,
                            new: localField.value
                        });
                    }

                    if (clienteField.value !== originalData.nombre_cliente) {
                        changes.push({
                            field: 'Cliente',
                            old: originalData.nombre_cliente,
                            new: clienteField.value
                        });
                    }

                    if (idClienteField.value !== originalData.id_clientes) {
                        changes.push({
                            field: 'ID Cliente',
                            old: originalData.id_clientes,
                            new: idClienteField.value
                        });
                    }

                    if (idVentaField.value !== originalData.id_ventas) {
                        changes.push({
                            field: 'ID Venta',
                            old: originalData.id_ventas,
                            new: idVentaField.value
                        });
                    }

                    if (changes.length === 0) {
                        changesContainer.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No hay cambios realizados aún.
                            </div>
                        `;
                    } else {
                        let changesHtml = `
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Cambios detectados (${changes.length}):</strong>
                            </div>
                        `;

                        changes.forEach(change => {
                            changesHtml += `
                                <div class="change-item">
                                    <strong>${change.field}:</strong><br>
                                    <span class="old-value">Anterior: ${change.old}</span><br>
                                    <span class="new-value">Nuevo: ${change.new}</span>
                                </div>
                            `;
                        });

                        changesContainer.innerHTML = changesHtml;
                    }
                }

                // Detectar cambios en tiempo real
                [direccionField, localField, clienteField, idClienteField, idVentaField].forEach(field => {
                    field.addEventListener('input', showChanges);
                });

                // Botón reset
                document.getElementById('resetBtn').addEventListener('click', function() {
                    if (confirm('¿Estás seguro de que deseas restaurar todos los valores originales?')) {
                        direccionField.value = originalData.direccion;
                        localField.value = originalData.nombre_local;
                        clienteField.value = originalData.nombre_cliente;
                        idClienteField.value = originalData.id_clientes;
                        idVentaField.value = originalData.id_ventas;
                        showChanges();
                    }
                });

                // Validación del formulario
                form.addEventListener('submit', function(e) {
                    let isValid = true;
                    const errors = [];

                    // Validar dirección
                    if (direccionField.value.trim().length < 5) {
                        errors.push('La dirección debe tener al menos 5 caracteres');
                        isValid = false;
                    }

                    // Validar nombre del local
                    if (localField.value.trim().length < 2) {
                        errors.push('El nombre del local debe tener al menos 2 caracteres');
                        isValid = false;
                    }

                    // Validar nombre del cliente
                    if (clienteField.value.trim().length < 2) {
                        errors.push('El nombre del cliente debe tener al menos 2 caracteres');
                        isValid = false;
                    }

                    // Validar IDs
                    if (parseInt(idClienteField.value) <= 0) {
                        errors.push('El ID del cliente debe ser un número positivo');
                        isValid = false;
                    }

                    if (parseInt(idVentaField.value) <= 0) {
                        errors.push('El ID de la venta debe ser un número positivo');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        alert('Por favor corrige los siguientes errores:\\n\\n' + errors.join('\\n'));
                        return false;
                    }

                    // Confirmación antes de enviar
                    const hasChanges = direccionField.value !== originalData.direccion ||
                                     localField.value !== originalData.nombre_local ||
                                     clienteField.value !== originalData.nombre_cliente ||
                                     idClienteField.value !== originalData.id_clientes ||
                                     idVentaField.value !== originalData.id_ventas;

                    if (!hasChanges) {
                        alert('No se han realizado cambios en la ruta.');
                        e.preventDefault();
                        return false;
                    }

                    const confirmMessage = `¿Confirmas la actualización de esta ruta?\\n\\n` +
                        `Los cambios se aplicarán inmediatamente.`;

                    if (!confirm(confirmMessage)) {
                        e.preventDefault();
                        return false;
                    }

                    // Mostrar estado de carga
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
                    submitBtn.disabled = true;
                });

                // Inicializar vista de cambios
                showChanges();
            <?php endif; ?>
        });
    </script>
</body>
</html>
