<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta - RMIE</title>
    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.0.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --shadow-light: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --shadow-medium: 0 15px 35px 0 rgba(31, 38, 135, 0.2);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: var(--border-radius);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-medium);
            transition: var(--transition);
            max-width: 1200px;
            margin: 0 auto;
        }

        .glass-container:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        /* Breadcrumb moderno */
        .modern-breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .breadcrumb {
            margin: 0;
            background: none;
        }

        .breadcrumb-item a {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #f0f0f0;
            transform: translateX(2px);
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.8);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .form-header h1 {
            position: relative;
            z-index: 1;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            position: relative;
            z-index: 1;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-content {
            padding: 2rem;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(102, 126, 234, 0.3);
        }

        .section-title i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.4rem;
        }

        .ventas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .form-group {
            margin-bottom: 1.8rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .form-group label i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: var(--transition);
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(77, 85, 108, 0.6);
        }

        /* Resumen de venta mejorado */
        .ventas-summary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            height: fit-content;
        }

        .ventas-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--success-gradient);
            border-radius: 2px;
        }

        .summary-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .summary-header h5 {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem -1rem -1rem -1rem;
            padding: 1rem 2rem;
            border-radius: 0 0 12px 12px;
        }

        .summary-item:hover:not(:last-child) {
            transform: translateX(5px);
            padding-left: 10px;
            background: rgba(255, 255, 255, 0.05);
        }

        .summary-label {
            color: var(--text-secondary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-value {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Botones mejorados */
        .btn {
            border-radius: 12px;
            padding: 14px 35px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
            min-width: 180px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
        }

        .btn-success:hover {
            box-shadow: 0 12px 30px rgba(79, 172, 254, 0.6);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .btn-secondary:hover {
            box-shadow: 0 12px 30px rgba(108, 117, 125, 0.6);
        }

        .ventas-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Alerta informativa */
        .info-alert {
            background: rgba(23, 162, 184, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(23, 162, 184, 0.3);
            border-radius: 12px;
            border-left: 4px solid #17a2b8;
            color: var(--text-primary);
            padding: 1.5rem;
            margin-top: 1.5rem;
            transition: var(--transition);
        }

        .info-alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23, 162, 184, 0.2);
        }

        .info-alert i {
            color: #17a2b8;
            margin-right: 0.7rem;
            font-size: 1.1rem;
        }

        .info-alert strong {
            color: var(--text-primary);
        }

        .info-alert ul {
            margin-left: 1.5rem;
            margin-top: 1rem;
            color: var(--text-secondary);
        }

        .info-alert li {
            margin-bottom: 0.5rem;
        }

        /* Alertas de error */
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #dc3545;
        }

        .alert-danger i {
            color: #dc3545;
            margin-right: 0.5rem;
        }

        /* Animaciones */
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .ventas-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .form-header h1 {
                font-size: 1.8rem;
            }
            
            .form-content {
                padding: 1rem;
            }
            
            .form-section {
                padding: 1.5rem;
            }
            
            .ventas-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
                min-width: unset;
            }
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-gradient);
        }
    </style>
</head>
<body>
    <div class="glass-container animate-fade-in">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="modern-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/views/dashboard.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index">
                        <i class="fas fa-shopping-cart"></i> Ventas
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-plus-circle"></i> Nueva Venta
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="form-header">
            <h1><i class="fas fa-cash-register"></i> Registrar Nueva Venta</h1>
            <p>Complete los datos para registrar una nueva venta en el sistema</p>
        </div>

        <!-- Content -->
        <div class="form-content">
            <!-- Mostrar errores si existen -->
            <?php if (isset($error)): ?>
                <div class="alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/RMIE/app/controllers/SaleController.php?accion=create" id="formVenta">
                <div class="ventas-grid">
                    <!-- Formulario principal -->
                    <div>
                        <!-- Información del cliente -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-user-tie"></i> Información del Cliente
                            </h5>
                            
                            <div class="form-group">
                                <label for="id_clientes">
                                    <i class="fas fa-users"></i> Cliente:
                                </label>
                                <select id="id_clientes" name="id_clientes" class="form-select" required>
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
                        </div>

                        <!-- Información del producto -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-box"></i> Información del Producto
                            </h5>
                            
                            <div class="form-group">
                                <label for="id_productos">
                                    <i class="fas fa-cubes"></i> Producto:
                                </label>
                                <select id="id_productos" name="id_productos" class="form-select" required>
                                    <option value="">Seleccione un producto</option>
                                    <?php if (isset($productos) && is_array($productos)): ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <option value="<?= htmlspecialchars($producto->id_productos) ?>" 
                                                    data-precio="<?= htmlspecialchars($producto->precio_unitario) ?>"
                                                    data-stock="<?= htmlspecialchars($producto->stock) ?>"
                                                    <?= ($_POST['id_productos'] ?? '') == $producto->id_productos ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($producto->nombre_producto) ?> (Stock: <?= htmlspecialchars($producto->stock) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="cantidad">
                                    <i class="fas fa-sort-numeric-up"></i> Cantidad:
                                </label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control" 
                                       min="1" step="1" required value="<?= htmlspecialchars($_POST['cantidad'] ?? '1') ?>">
                            </div>

                            <div class="form-group">
                                <label for="precio_unitario">
                                    <i class="fas fa-dollar-sign"></i> Precio Unitario:
                                </label>
                                <input type="number" id="precio_unitario" name="precio_unitario" class="form-control" 
                                       step="0.01" min="0" readonly value="<?= htmlspecialchars($_POST['precio_unitario'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="total">
                                    <i class="fas fa-calculator"></i> Total:
                                </label>
                                <input type="number" id="total" name="total" class="form-control" 
                                       step="0.01" readonly value="<?= htmlspecialchars($_POST['total'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Configuración de la venta -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-cogs"></i> Configuración de la Venta
                            </h5>
                            
                            <div class="form-group">
                                <label for="estado">
                                    <i class="fas fa-flag"></i> Estado:
                                </label>
                                <select id="estado" name="estado" class="form-select" required>
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
                                    <i class="fas fa-user-shield"></i> Usuario Responsable:
                                </label>
                                <select id="num_doc" name="num_doc" class="form-select" required>
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
                        </div>
                    </div>

                    <!-- Resumen de la venta -->
                    <div class="ventas-summary">
                        <div class="summary-header">
                            <h5><i class="fas fa-receipt"></i> Resumen de la Venta</h5>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-box"></i> Producto:
                            </span>
                            <span class="summary-value" id="producto-nombre">-</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-warehouse"></i> Stock Disponible:
                            </span>
                            <span class="summary-value" id="stock-disponible">-</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-dollar-sign"></i> Precio Unitario:
                            </span>
                            <span class="summary-value" id="precio-mostrar">$0.00</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-sort-numeric-up"></i> Cantidad:
                            </span>
                            <span class="summary-value" id="cantidad-mostrar">0</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="summary-label">
                                <i class="fas fa-calculator"></i> Total a Pagar:
                            </span>
                            <span class="summary-value" id="total-mostrar">$0.00</span>
                        </div>

                        <!-- Información adicional -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información Importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>El precio se carga automáticamente del producto seleccionado</li>
                                <li>Verifique siempre el stock disponible antes de procesar</li>
                                <li>El total se calcula automáticamente al cambiar la cantidad</li>
                                <li>Una vez procesada, la venta afectará el inventario</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="ventas-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-cash-register"></i> Registrar Venta
                    </button>
                    <a href="/RMIE/app/controllers/SaleController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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
                
                // Actualizar resumen con animación
                updateSummaryWithAnimation('producto-nombre', nombreProducto);
                updateSummaryWithAnimation('stock-disponible', stock + ' unidades');
                updateSummaryWithAnimation('precio-mostrar', '$' + precio.toFixed(2));
                
                // Calcular total
                calcularTotal();
            } else {
                // Limpiar campos
                precioInput.value = '';
                totalInput.value = '';
                
                // Limpiar resumen
                updateSummaryWithAnimation('producto-nombre', '-');
                updateSummaryWithAnimation('stock-disponible', '-');
                updateSummaryWithAnimation('precio-mostrar', '$0.00');
                updateSummaryWithAnimation('cantidad-mostrar', '0');
                updateSummaryWithAnimation('total-mostrar', '$0.00');
            }
        }
        
        // Actualizar resumen con animación
        function updateSummaryWithAnimation(elementId, newValue) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.transform = 'scale(0.9)';
                element.style.opacity = '0.5';
                
                setTimeout(() => {
                    element.textContent = newValue;
                    element.style.transform = 'scale(1)';
                    element.style.opacity = '1';
                }, 150);
            }
        }
        
        // Calcular total
        function calcularTotal() {
            const cantidad = parseInt(cantidadInput.value || 0);
            const precio = parseFloat(precioInput.value || 0);
            const total = cantidad * precio;
            
            totalInput.value = total.toFixed(2);
            
            // Actualizar resumen con animación
            updateSummaryWithAnimation('cantidad-mostrar', cantidad.toString());
            updateSummaryWithAnimation('total-mostrar', '$' + total.toFixed(2));
        }
        
        // Validar stock
        function validarStock() {
            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            if (selectedOption.value) {
                const stock = parseInt(selectedOption.dataset.stock || 0);
                const cantidad = parseInt(cantidadInput.value || 0);
                
                if (cantidad > stock) {
                    // Mostrar alerta mejorada
                    showCustomAlert(`La cantidad solicitada (${cantidad}) excede el stock disponible (${stock})`, 'warning');
                    cantidadInput.value = stock;
                    calcularTotal();
                }
            }
        }
        
        // Mostrar alerta personalizada
        function showCustomAlert(message, type = 'info') {
            // Remover alertas existentes
            const existingAlerts = document.querySelectorAll('.custom-alert');
            existingAlerts.forEach(alert => alert.remove());
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `custom-alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            const formContent = document.querySelector('.form-content');
            formContent.insertBefore(alertDiv, formContent.firstChild);
            
            // Auto-ocultar después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
        
        // Event listeners con efectos mejorados
        productoSelect.addEventListener('change', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                actualizarProducto();
            }, 100);
        });
        
        cantidadInput.addEventListener('input', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                calcularTotal();
                validarStock();
            }, 100);
        });
        
        // Validación del formulario mejorada
        form.addEventListener('submit', function(e) {
            const cliente = document.getElementById('id_clientes').value;
            const producto = productoSelect.value;
            const cantidad = parseInt(cantidadInput.value || 0);
            const usuario = document.getElementById('num_doc').value;
            
            let errors = [];
            
            if (!cliente) errors.push('Debe seleccionar un cliente');
            if (!producto) errors.push('Debe seleccionar un producto');
            if (cantidad <= 0) errors.push('La cantidad debe ser mayor a 0');
            if (!usuario) errors.push('Debe seleccionar un usuario responsable');
            
            // Validar stock final
            if (producto) {
                const selectedOption = productoSelect.options[productoSelect.selectedIndex];
                const stock = parseInt(selectedOption.dataset.stock || 0);
                if (cantidad > stock) {
                    errors.push(`Stock insuficiente. Disponible: ${stock}, Solicitado: ${cantidad}`);
                }
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                showCustomAlert(errors.join('<br>'), 'warning');
                return;
            }
            
            // Animación de envío
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            submitBtn.disabled = true;
        });
        
        // Inicializar si hay un producto pre-seleccionado
        if (productoSelect.value) {
            actualizarProducto();
        }
        
        // Auto-ocultar alertas de error después de 8 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-danger');
            alerts.forEach(function(alert) {
                alert.style.transition = 'all 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 300);
            });
        }, 8000);

        // Efectos de hover en los campos del formulario
        const formFields = document.querySelectorAll('.form-control, .form-select');
        formFields.forEach(field => {
            field.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
            });
            
            field.addEventListener('mouseleave', function() {
                if (!this.matches(':focus')) {
                    this.style.transform = 'translateY(0)';
                }
            });
            
            field.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
    </script>

    <style>
        /* Estilos adicionales para las alertas personalizadas */
        .custom-alert {
            background: rgba(255, 193, 7, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #ffc107;
            position: relative;
            animation: slideInDown 0.3s ease-out;
        }

        .custom-alert.alert-warning {
            border-left-color: #ff6b35;
            border-color: rgba(255, 107, 53, 0.3);
            background: rgba(255, 107, 53, 0.1);
        }

        .custom-alert i {
            color: #ffc107;
            margin-right: 0.7rem;
        }

        .custom-alert.alert-warning i {
            color: #ff6b35;
        }

        .custom-alert .btn-close {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text-secondary);
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</body>
</html>