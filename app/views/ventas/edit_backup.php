<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="ventas-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/SaleController.php?accion=index">Ventas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-edit"></i> Editar Venta #<?= htmlspecialchars($venta->id_ventas) ?></h1>
        
        <!-- Mostrar errores si existen -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Mostrar éxito si existe -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="ventas-form">
            <form method="POST" action="/RMIE/app/controllers/SaleController.php?accion=edit&id=<?= $venta->id_ventas ?>" id="formVenta">
                <div class="row">
                    <!-- Información del cliente -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user"></i> Información del Cliente</h5>
                        
                        <div class="form-group">
                            <label for="id_clientes">
                                <i class="fas fa-users"></i> Cliente:
                            </label>
                            <select id="id_clientes" name="id_clientes" required>
                                <option value="">Seleccione un cliente</option>
                                <?php if (isset($clientes) && is_array($clientes)): ?>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?= htmlspecialchars($cliente->id_clientes) ?>" 
                                                <?= $venta->id_clientes == $cliente->id_clientes ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cliente->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-tag"></i> Nombre de la venta:
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= htmlspecialchars($venta->nombre) ?>"
                                   placeholder="Descripción de la venta"
                                   maxlength="45">
                        </div>
                        
                        <div class="form-group">
                            <label for="direccion">
                                <i class="fas fa-map-marker-alt"></i> Dirección de entrega:
                            </label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="<?= htmlspecialchars($venta->direccion) ?>"
                                   placeholder="Dirección de entrega"
                                   maxlength="45">
                        </div>
                        
                        <!-- Información histórica -->
                        <div class="historical-info">
                            <h6><i class="fas fa-history"></i> Información Histórica</h6>
                            <div class="info-item">
                                <span class="label">Fecha de creación:</span>
                                <span class="value"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($venta->fecha_creacion))) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">ID de la venta:</span>
                                <span class="value">#<?= htmlspecialchars($venta->id_ventas) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del producto -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-box"></i> Información del Producto</h5>
                        
                        <div class="form-group">
                            <label for="id_productos">
                                <i class="fas fa-cubes"></i> Producto:
                            </label>
                            <select id="id_productos" name="id_productos" required>
                                <option value="">Seleccione un producto</option>
                                <?php if (isset($productos) && is_array($productos)): ?>
                                    <?php foreach ($productos as $producto): ?>
                                        <option value="<?= htmlspecialchars($producto->id_productos) ?>" 
                                                data-precio="<?= htmlspecialchars($producto->precio_unitario) ?>"
                                                data-stock="<?= htmlspecialchars($producto->stock) ?>"
                                                <?= $venta->id_productos == $producto->id_productos ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($producto->nombre) ?> 
                                            (Stock: <?= htmlspecialchars($producto->stock) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="cantidad">
                                <i class="fas fa-sort-numeric-up"></i> Cantidad:
                            </label>
                            <input type="number" 
                                   id="cantidad" 
                                   name="cantidad" 
                                   required 
                                   min="1"
                                   value="<?= htmlspecialchars($venta->cantidad) ?>"
                                   placeholder="Cantidad a vender">
                            <small class="form-text text-muted">Cantidad original: <?= htmlspecialchars($venta->cantidad) ?></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_venta">
                                <i class="fas fa-calendar"></i> Fecha de venta:
                            </label>
                            <input type="date" 
                                   id="fecha_venta" 
                                   name="fecha_venta" 
                                   required
                                   value="<?= htmlspecialchars($venta->fecha_venta) ?>">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Precios y cálculos -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-calculator"></i> Cálculos</h5>
                        
                        <div class="form-group">
                            <label for="precio_unitario">
                                <i class="fas fa-dollar-sign"></i> Precio unitario:
                            </label>
                            <input type="number" 
                                   id="precio_unitario" 
                                   name="precio_unitario" 
                                   required 
                                   step="0.01"
                                   min="0"
                                   value="<?= htmlspecialchars($venta->precio_unitario) ?>"
                                   placeholder="0.00">
                            <small class="form-text text-muted">Precio original: $<?= number_format($venta->precio_unitario, 2) ?></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="total">
                                <i class="fas fa-money-bill"></i> Total:
                            </label>
                            <input type="number" 
                                   id="total" 
                                   name="total" 
                                   required 
                                   step="0.01"
                                   min="0"
                                   value="<?= htmlspecialchars($venta->total) ?>"
                                   placeholder="0.00"
                                   readonly>
                            <small class="form-text text-muted">Total original: $<?= number_format($venta->total, 2) ?></small>
                        </div>
                        
                        <!-- Resumen de cálculo -->
                        <div class="calculation-summary">
                            <div class="summary-item">
                                <span>Producto seleccionado:</span>
                                <span id="producto-nombre">-</span>
                            </div>
                            <div class="summary-item">
                                <span>Stock disponible:</span>
                                <span id="stock-disponible">-</span>
                            </div>
                            <div class="summary-item">
                                <span>Precio unitario:</span>
                                <span id="precio-mostrar">$0.00</span>
                            </div>
                            <div class="summary-item">
                                <span>Cantidad:</span>
                                <span id="cantidad-mostrar">0</span>
                            </div>
                            <div class="summary-item summary-total">
                                <span>Total a pagar:</span>
                                <span id="total-mostrar">$0.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado y usuario -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-cog"></i> Configuración</h5>
                        
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-info-circle"></i> Estado de la venta:
                            </label>
                            <select id="estado" name="estado" required>
                                <option value="pendiente" <?= $venta->estado === 'pendiente' ? 'selected' : '' ?>>
                                    Pendiente
                                </option>
                                <option value="procesando" <?= $venta->estado === 'procesando' ? 'selected' : '' ?>>
                                    Procesando
                                </option>
                                <option value="completada" <?= $venta->estado === 'completada' ? 'selected' : '' ?>>
                                    Completada
                                </option>
                                <option value="cancelada" <?= $venta->estado === 'cancelada' ? 'selected' : '' ?>>
                                    Cancelada
                                </option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="num_doc">
                                <i class="fas fa-user-tie"></i> Usuario responsable:
                            </label>
                            <select id="num_doc" name="num_doc" required>
                                <option value="">Seleccione un usuario</option>
                                <?php if (isset($usuarios) && is_array($usuarios)): ?>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= htmlspecialchars($usuario->num_doc) ?>" 
                                                <?= $venta->num_doc == $usuario->num_doc ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Estado visual -->
                        <div class="status-display">
                            <div class="current-status">
                                <span class="label">Estado actual:</span>
                                <span class="badge-status status-<?= strtolower($venta->estado) ?>">
                                    <?php
                                    $estados = [
                                        'pendiente' => ['text' => 'Pendiente', 'icon' => 'fas fa-clock'],
                                        'procesando' => ['text' => 'Procesando', 'icon' => 'fas fa-spinner'],
                                        'completada' => ['text' => 'Completada', 'icon' => 'fas fa-check-circle'],
                                        'cancelada' => ['text' => 'Cancelada', 'icon' => 'fas fa-times-circle']
                                    ];
                                    $estadoInfo = $estados[$venta->estado] ?? ['text' => $venta->estado, 'icon' => 'fas fa-question'];
                                    ?>
                                    <i class="<?= $estadoInfo['icon'] ?>"></i>
                                    <?= $estadoInfo['text'] ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Los cambios en cantidad pueden afectar el inventario</li>
                                <li>Verifique el stock antes de aumentar la cantidad</li>
                                <li>El cambio de estado puede ser irreversible</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="ventas-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Venta
                    </button>
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()" title="Imprimir venta">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para validación y cálculos -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productoSelect = document.getElementById('id_productos');
        const cantidadInput = document.getElementById('cantidad');
        const precioInput = document.getElementById('precio_unitario');
        const totalInput = document.getElementById('total');
        const form = document.getElementById('formVenta');
        
        // Cantidad original para validación de stock
        const cantidadOriginal = <?= $venta->cantidad ?>;
        
        // Actualizar información del producto
        function actualizarProducto() {
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            
            if (selectedOption.value) {
                const precio = parseFloat(selectedOption.dataset.precio || 0);
                const stock = parseInt(selectedOption.dataset.stock || 0);
                const nombreProducto = selectedOption.text.split(' (Stock:')[0];
                
                // En edición, no actualizamos automáticamente el precio
                // pero sí mostramos la información
                
                // Actualizar resumen
                document.getElementById('producto-nombre').textContent = nombreProducto;
                document.getElementById('stock-disponible').textContent = stock + ' unidades';
                
                // Solo actualizar precio si está vacío
                if (!precioInput.value || precioInput.value == '0') {
                    precioInput.value = precio.toFixed(2);
                }
                
                actualizarResumen();
            } else {
                // Limpiar resumen
                document.getElementById('producto-nombre').textContent = '-';
                document.getElementById('stock-disponible').textContent = '-';
                document.getElementById('precio-mostrar').textContent = '$0.00';
                document.getElementById('cantidad-mostrar').textContent = '0';
                document.getElementById('total-mostrar').textContent = '$0.00';
            }
        }
        
        // Actualizar resumen visual
        function actualizarResumen() {
            const cantidad = parseInt(cantidadInput.value || 0);
            const precio = parseFloat(precioInput.value || 0);
            
            document.getElementById('precio-mostrar').textContent = '$' + precio.toFixed(2);
            document.getElementById('cantidad-mostrar').textContent = cantidad;
            document.getElementById('total-mostrar').textContent = '$' + (cantidad * precio).toFixed(2);
        }
        
        // Calcular total
        function calcularTotal() {
            const cantidad = parseInt(cantidadInput.value || 0);
            const precio = parseFloat(precioInput.value || 0);
            const total = cantidad * precio;
            
            totalInput.value = total.toFixed(2);
            actualizarResumen();
        }
        
        // Validar stock considerando la cantidad original
        function validarStock() {
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            if (selectedOption.value) {
                const stockActual = parseInt(selectedOption.dataset.stock || 0);
                const cantidadNueva = parseInt(cantidadInput.value || 0);
                
                // El stock disponible incluye la cantidad original de esta venta
                const stockDisponible = stockActual + cantidadOriginal;
                
                if (cantidadNueva > stockDisponible) {
                    alert(`La cantidad solicitada (${cantidadNueva}) excede el stock disponible (${stockDisponible})`);
                    cantidadInput.value = stockDisponible;
                    calcularTotal();
                }
            }
        }
        
        // Event listeners
        productoSelect.addEventListener('change', actualizarProducto);
        cantidadInput.addEventListener('input', function() {
            calcularTotal();
            validarStock();
        });
        precioInput.addEventListener('input', calcularTotal);
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            const cliente = document.getElementById('id_clientes').value;
            const producto = productoSelect.value;
            const cantidad = parseInt(cantidadInput.value || 0);
            const usuario = document.getElementById('num_doc').value;
            const precio = parseFloat(precioInput.value || 0);
            
            if (!cliente) {
                e.preventDefault();
                alert('Debe seleccionar un cliente');
                return;
            }
            
            if (!producto) {
                e.preventDefault();
                alert('Debe seleccionar un producto');
                return;
            }
            
            if (cantidad <= 0) {
                e.preventDefault();
                alert('La cantidad debe ser mayor a 0');
                return;
            }
            
            if (precio <= 0) {
                e.preventDefault();
                alert('El precio debe ser mayor a 0');
                return;
            }
            
            if (!usuario) {
                e.preventDefault();
                alert('Debe seleccionar un usuario responsable');
                return;
            }
            
            // Validar stock final
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            const stockActual = parseInt(selectedOption.dataset.stock || 0);
            const stockDisponible = stockActual + cantidadOriginal;
            if (cantidad > stockDisponible) {
                e.preventDefault();
                alert(`No hay suficiente stock. Disponible: ${stockDisponible}, Solicitado: ${cantidad}`);
                return;
            }
            
            // Confirmar cambios importantes
            if (cantidad !== cantidadOriginal) {
                if (!confirm(`¿Está seguro de cambiar la cantidad de ${cantidadOriginal} a ${cantidad}?`)) {
                    e.preventDefault();
                    return;
                }
            }
        });
        
        // Inicializar
        actualizarProducto();
        
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
        .ventas-buttons { display: none; }
        .alert { display: none; }
        nav { display: none; }
        .btn { display: none; }
        body { margin: 0; }
        .ventas-container { margin: 0; padding: 20px; }
    </style>
</body>
</html>
