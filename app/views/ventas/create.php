<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Venta - RMIE</title>
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
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>

        <h1><i class="fas fa-plus-circle"></i> Registrar Nueva Venta</h1>
        
        <!-- Mostrar errores si existen -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="ventas-form">
            <form method="POST" action="/RMIE/app/controllers/SaleController.php?accion=create" id="formVenta">
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
                                                <?= ($_POST['id_clientes'] ?? '') == $cliente->id_clientes ? 'selected' : '' ?>>
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
                                   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
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
                                   value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>"
                                   placeholder="Dirección de entrega"
                                   maxlength="45">
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
                                                <?= ($_POST['id_productos'] ?? '') == $producto->id_productos ? 'selected' : '' ?>>
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
                                   value="<?= htmlspecialchars($_POST['cantidad'] ?? '1') ?>"
                                   placeholder="Cantidad a vender">
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_venta">
                                <i class="fas fa-calendar"></i> Fecha de venta:
                            </label>
                            <input type="date" 
                                   id="fecha_venta" 
                                   name="fecha_venta" 
                                   required
                                   value="<?= htmlspecialchars($_POST['fecha_venta'] ?? date('Y-m-d')) ?>">
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
                                   value="<?= htmlspecialchars($_POST['precio_unitario'] ?? '') ?>"
                                   placeholder="0.00"
                                   readonly>
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
                                   value="<?= htmlspecialchars($_POST['total'] ?? '') ?>"
                                   placeholder="0.00"
                                   readonly>
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
                                <option value="pendiente" <?= ($_POST['estado'] ?? 'pendiente') === 'pendiente' ? 'selected' : '' ?>>
                                    Pendiente
                                </option>
                                <option value="procesando" <?= ($_POST['estado'] ?? '') === 'procesando' ? 'selected' : '' ?>>
                                    Procesando
                                </option>
                                <option value="completada" <?= ($_POST['estado'] ?? '') === 'completada' ? 'selected' : '' ?>>
                                    Completada
                                </option>
                                <option value="cancelada" <?= ($_POST['estado'] ?? '') === 'cancelada' ? 'selected' : '' ?>>
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
                                                <?= ($_POST['num_doc'] ?? '') == $usuario->num_doc ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario->nombres . ' ' . $usuario->apellidos) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información:</strong>
                            <ul class="mb-0 mt-2">
                                <li>El precio se carga automáticamente del producto</li>
                                <li>Verifique el stock disponible antes de vender</li>
                                <li>El total se calcula automáticamente</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="ventas-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar Venta
                    </button>
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
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
        
        // Actualizar información del producto
        function actualizarProducto() {
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            
            if (selectedOption.value) {
                const precio = parseFloat(selectedOption.dataset.precio || 0);
                const stock = parseInt(selectedOption.dataset.stock || 0);
                const nombreProducto = selectedOption.text.split(' (Stock:')[0];
                
                // Actualizar campos
                precioInput.value = precio.toFixed(2);
                
                // Actualizar resumen
                document.getElementById('producto-nombre').textContent = nombreProducto;
                document.getElementById('stock-disponible').textContent = stock + ' unidades';
                document.getElementById('precio-mostrar').textContent = '$' + precio.toFixed(2);
                
                // Calcular total
                calcularTotal();
            } else {
                // Limpiar campos
                precioInput.value = '';
                totalInput.value = '';
                
                // Limpiar resumen
                document.getElementById('producto-nombre').textContent = '-';
                document.getElementById('stock-disponible').textContent = '-';
                document.getElementById('precio-mostrar').textContent = '$0.00';
                document.getElementById('cantidad-mostrar').textContent = '0';
                document.getElementById('total-mostrar').textContent = '$0.00';
            }
        }
        
        // Calcular total
        function calcularTotal() {
            const cantidad = parseInt(cantidadInput.value || 0);
            const precio = parseFloat(precioInput.value || 0);
            const total = cantidad * precio;
            
            totalInput.value = total.toFixed(2);
            
            // Actualizar resumen
            document.getElementById('cantidad-mostrar').textContent = cantidad;
            document.getElementById('total-mostrar').textContent = '$' + total.toFixed(2);
        }
        
        // Validar stock
        function validarStock() {
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            if (selectedOption.value) {
                const stock = parseInt(selectedOption.dataset.stock || 0);
                const cantidad = parseInt(cantidadInput.value || 0);
                
                if (cantidad > stock) {
                    alert(`La cantidad solicitada (${cantidad}) excede el stock disponible (${stock})`);
                    cantidadInput.value = stock;
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
        
        // Validación del formulario
        form.addEventListener('submit', function(e) {
            const cliente = document.getElementById('id_clientes').value;
            const producto = productoSelect.value;
            const cantidad = parseInt(cantidadInput.value || 0);
            const usuario = document.getElementById('num_doc').value;
            
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
            
            if (!usuario) {
                e.preventDefault();
                alert('Debe seleccionar un usuario responsable');
                return;
            }
            
            // Validar stock final
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            const stock = parseInt(selectedOption.dataset.stock || 0);
            if (cantidad > stock) {
                e.preventDefault();
                alert(`No hay suficiente stock. Disponible: ${stock}, Solicitado: ${cantidad}`);
                return;
            }
        });
        
        // Inicializar si hay un producto pre-seleccionado
        if (productoSelect.value) {
            actualizarProducto();
        }
        
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
</body>
</html>
