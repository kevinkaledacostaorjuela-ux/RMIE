<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Ruta - RMIE</title>
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
                            <i class="fas fa-plus"></i> Nueva Ruta
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1><i class="fas fa-plus-circle"></i> Crear Nueva Ruta</h1>
                    <p>Registra una nueva ruta de entrega en el sistema</p>
                </div>
            </div>

            <!-- Tarjeta resumen visual superior -->
            <div class="ruta-visual-card" style="display:flex;align-items:center;justify-content:center;margin-bottom:2rem;">
                <div class="ruta-avatar-large" style="margin-right:2rem;">
                    <i class="fas fa-route" style="font-size:3rem;"></i>
                </div>
                <div>
                    <h2 style="margin:0;color:#fff;font-weight:700;">Nueva Ruta de Entrega</h2>
                    <p style="color:#e0e0e0;">Completa el formulario para registrar una nueva ruta en el sistema RMIE.</p>
                </div>
            </div>

            <!-- Separador visual -->
            <hr style="border:0;height:2px;background:linear-gradient(90deg,#667eea,#764ba2);margin:2rem 0;">

            <!-- Animación de entrada para el formulario -->
            <style>
                .rutas-form, .preview-card { opacity:0; transform:translateY(30px); transition:all 0.7s cubic-bezier(.4,0,.2,1); }
                .rutas-form.visible, .preview-card.visible { opacity:1; transform:translateY(0); }
                .preview-card .preview-item { display:flex;align-items:center;margin-bottom:1rem; }
                .preview-card .preview-item i { font-size:1.5rem;margin-right:1rem;color:#667eea; }
                .preview-card { box-shadow:0 8px 32px rgba(102,126,234,0.15); border-radius:18px; background:rgba(255,255,255,0.12); padding:2rem; }
                .ruta-visual-card { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); border-radius:30px; box-shadow:0 10px 40px rgba(102,126,234,0.25); padding:2rem 3rem; }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded',function(){
                    setTimeout(function(){
                        document.querySelector('.rutas-form').classList.add('visible');
                        document.querySelector('.preview-card').classList.add('visible');
                    },200);
                });
            </script>

            <!-- Información del formulario -->
            <div class="ruta-info-panel">
                <?php if (empty($available_clients) || empty($available_sales)): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atención:</strong> 
                        <?php if (empty($available_clients) && empty($available_sales)): ?>
                            No hay clientes ni ventas disponibles. Debes crear al menos un cliente y una venta antes de poder crear rutas.
                        <?php elseif (empty($available_clients)): ?>
                            No hay clientes disponibles. Debes crear al menos un cliente antes de poder crear rutas.
                        <?php else: ?>
                            No hay ventas disponibles. Debes crear al menos una venta antes de poder crear rutas.
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Información:</strong> Complete todos los campos para registrar la nueva ruta. 
                        La dirección debe ser clara y específica para facilitar las entregas.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Formulario -->
            <div class="rutas-form">
                <form action="/RMIE/app/controllers/RouteController.php?accion=create" method="POST" id="createRouteForm">
                    <div class="form-row">
                        <!-- Información de la Ruta -->
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
                                    rows="3"></textarea>
                                <small class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Incluye calle, número, barrio y ciudad (mínimo 5 caracteres)
                                </small>
                                <div class="char-counter">
                                    <span id="direccion-counter">0</span>/200 caracteres
                                </div>
                            </div>
                        </div>

                        <!-- Información del Local y Cliente -->
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
                        <!-- IDs de Referencias -->
                        <div class="form-section">
                            <h3><i class="fas fa-link"></i> Referencias del Sistema</h3>
                            
                            <div class="form-group">
                                <label for="id_clientes">
                                    <i class="fas fa-user-tag"></i> Cliente *
                                </label>
                                <select 
                                    name="id_clientes" 
                                    id="id_clientes" 
                                    required
                                    class="form-control">
                                    <option value="">Seleccionar cliente...</option>
                                    <?php if (!empty($available_clients)): ?>
                                        <?php foreach ($available_clients as $client): ?>
                                            <option value="<?= htmlspecialchars($client['id_clientes']) ?>">
                                                <?= htmlspecialchars($client['nombre']) ?> (ID: <?= htmlspecialchars($client['id_clientes']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay clientes disponibles</option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Selecciona el cliente de destino para esta ruta
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="id_ventas">
                                    <i class="fas fa-shopping-cart"></i> Venta Asociada *
                                </label>
                                <select 
                                    name="id_ventas" 
                                    id="id_ventas" 
                                    required
                                    class="form-control">
                                    <option value="">Seleccionar venta...</option>
                                    <?php if (!empty($available_sales)): ?>
                                        <?php foreach ($available_sales as $sale): ?>
                                            <option value="<?= htmlspecialchars($sale['id_ventas']) ?>">
                                                <?= htmlspecialchars($sale['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay ventas disponibles</option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Selecciona la venta que se entregará en esta ruta
                                </small>
                            </div>
                        </div>

                        <!-- Vista previa -->
                        <div class="form-section">
                            <h3><i class="fas fa-eye"></i> Vista Previa</h3>
                            <div class="preview-card" id="previewCard">
                                <div class="preview-item">
                                    <strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong>
                                    <span id="preview-direccion">No especificada</span>
                                </div>
                                <div class="preview-item">
                                    <strong><i class="fas fa-store"></i> Local:</strong>
                                    <span id="preview-local">No especificado</span>
                                </div>
                                <div class="preview-item">
                                    <strong><i class="fas fa-user"></i> Cliente:</strong>
                                    <span id="preview-cliente">No especificado</span>
                                </div>
                                <div class="preview-item">
                                    <strong><i class="fas fa-hashtag"></i> Cliente ID:</strong>
                                    <span id="preview-id-cliente">No especificado</span>
                                </div>
                                <div class="preview-item">
                                    <strong><i class="fas fa-shopping-cart"></i> Venta ID:</strong>
                                    <span id="preview-id-venta">No especificado</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="form-actions">
                        <button type="submit" class="btn-rutas" id="submitBtn" 
                                <?php if (empty($available_clients) || empty($available_sales)): ?>disabled<?php endif; ?>>
                            <i class="fas fa-save"></i> 
                            <?php if (empty($available_clients) || empty($available_sales)): ?>
                                No se puede crear ruta
                            <?php else: ?>
                                Crear Ruta
                            <?php endif; ?>
                        </button>
                        <a href="/RMIE/app/controllers/RouteController.php?accion=index" class="btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <?php if (!empty($available_clients) && !empty($available_sales)): ?>
                            <button type="reset" class="btn-outline" id="resetBtn">
                                <i class="fas fa-undo"></i> Limpiar Formulario
                            </button>
                        <?php endif; ?>
                    </div>
                </form>

                
            <!-- Panel de ayuda -->
            <div class="ruta-info-panel">
                <h3><i class="fas fa-question-circle"></i> Ayuda</h3>
                <div class="help-grid">
                    <div class="help-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>Dirección:</strong> Debe ser completa y específica para facilitar la ubicación del destino.
                    </div>
                    <div class="help-item">
                        <i class="fas fa-store"></i>
                        <strong>Nombre del Local:</strong> Usa el nombre comercial oficial del establecimiento.
                    </div>
                    <div class="help-item">
                        <i class="fas fa-user"></i>
                        <strong>Cliente:</strong> Persona de contacto responsable en el local de destino.
                    </div>
                    <div class="help-item">
                        <i class="fas fa-link"></i>
                        <strong>Referencias:</strong> Los IDs deben corresponder a registros existentes en el sistema.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createRouteForm');
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

            // Vista previa en tiempo real
            function updatePreview() {
                document.getElementById('preview-direccion').textContent = 
                    direccionField.value || 'No especificada';
                document.getElementById('preview-local').textContent = 
                    localField.value || 'No especificado';
                document.getElementById('preview-cliente').textContent = 
                    clienteField.value || 'No especificado';
                
                // Para cliente, mostrar el texto seleccionado
                const clienteSelect = document.getElementById('id_clientes');
                document.getElementById('preview-id-cliente').textContent = 
                    clienteSelect.options[clienteSelect.selectedIndex]?.text || 'No seleccionado';
                
                // Para venta, mostrar el texto seleccionado
                const ventaSelect = document.getElementById('id_ventas');
                document.getElementById('preview-id-venta').textContent = 
                    ventaSelect.options[ventaSelect.selectedIndex]?.text || 'No seleccionado';
            }

            // Actualizar vista previa en tiempo real
            [direccionField, localField, clienteField, idClienteField, idVentaField].forEach(field => {
                field.addEventListener('input', updatePreview);
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

                // Validar selecciones
                if (!idClienteField.value || idClienteField.value === '') {
                    errors.push('Debe seleccionar un cliente');
                    isValid = false;
                }

                if (!idVentaField.value || idVentaField.value === '') {
                    errors.push('Debe seleccionar una venta');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor corrige los siguientes errores:\\n\\n' + errors.join('\\n'));
                    return false;
                }

                // Confirmación antes de enviar
                const confirmMessage = `¿Confirmas la creación de esta ruta?\\n\\n` +
                    `Dirección: ${direccionField.value}\\n` +
                    `Local: ${localField.value}\\n` +
                    `Cliente: ${clienteField.value}`;

                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    return false;
                }

                // Mostrar estado de carga
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
                submitBtn.disabled = true;
            });

            // Botón reset
            document.getElementById('resetBtn').addEventListener('click', function() {
                setTimeout(updatePreview, 10);
            });

            // Inicializar vista previa
            updatePreview();
        });
    </script>
</body>
</html>
