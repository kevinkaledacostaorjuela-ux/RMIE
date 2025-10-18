<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Alerta - RMIE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .main-container {
            max-width: 800px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .header-title {
            color: white;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-title i {
            font-size: 2.5rem;
            opacity: 0.9;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-section {
            padding: 40px 30px;
        }
        
        .alert-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .alert-type-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .alert-type-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2);
        }
        
        .alert-type-card.selected {
            border-color: #00d4ff;
            background: rgba(0, 212, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.3);
        }
        
        .alert-type-icon {
            font-size: 2.5rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 15px;
        }
        
        .alert-type-title {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .alert-type-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .form-row {
            display: grid;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .form-row-2 {
            grid-template-columns: 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2 {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        .form-floating-modern {
            position: relative;
            margin-bottom: 20px;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .form-control-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-select-modern {
            width: 100%;
            padding: 18px 15px 8px 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
        }
        
        .form-select-modern:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .form-floating-modern label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: rgba(102, 126, 234, 0.8);
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-floating-modern label i {
            font-size: 16px;
        }
        
        .form-control-modern:focus ~ label,
        .form-control-modern:not(:placeholder-shown) ~ label,
        .form-select-modern:focus ~ label,
        .form-select-modern:not([value=""]) ~ label {
            top: 2px;
            font-size: 12px;
            color: #667eea;
        }
        
        .form-section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: none;
        }
        
        .form-section-card.active {
            display: block;
            animation: slideIn 0.5s ease-out;
        }
        
        .section-title {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .buttons-section {
            padding: 20px 30px 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            min-width: 160px;
            justify-content: center;
        }
        
        .btn-create {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
            background: linear-gradient(135deg, #ff5252 0%, #ff7043 100%);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
            color: white;
        }
        
        .info-panel {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.4);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .info-panel h6 {
            color: #ffc107;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                border-radius: 20px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 1.8rem;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .buttons-section {
                padding: 20px 20px 30px;
                flex-direction: column;
            }
            
            .btn-modern {
                width: 100%;
            }
            
            .alert-types {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animaciones */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="header-section">
            <h1 class="header-title">
                <i class="fas fa-exclamation-triangle"></i>
                Crear Nueva Alerta
            </h1>
            <p class="header-subtitle">Configure alertas automáticas para mantener control del inventario</p>
        </div>

        <!-- Form -->
        <div class="form-section">
            <form action="/RMIE/app/controllers/AlertController.php?action=create" method="POST" id="alertForm">
                
                <!-- Tipos de Alerta -->
                <div class="alert-types">
                    <div class="alert-type-card" data-type="stock">
                        <div class="alert-type-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="alert-type-title">Alerta de Stock Bajo</div>
                        <div class="alert-type-desc">Se activa cuando el inventario está por debajo del mínimo establecido</div>
                    </div>
                    
                    <div class="alert-type-card" data-type="expiration">
                        <div class="alert-type-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <div class="alert-type-title">Alerta de Vencimiento</div>
                        <div class="alert-type-desc">Se activa cuando los productos están próximos a vencer</div>
                    </div>
                </div>

                <input type="hidden" id="alert_type" name="alert_type" required>

                <!-- Formulario para Stock Bajo -->
                <div class="form-section-card" id="stock-form">
                    <div class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Configuración de Alerta de Stock
                    </div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="producto_stock" 
                                    name="id_productos" 
                                    required>
                                <option value="">Seleccione un producto</option>
                                <?php if (isset($productos) && !empty($productos)): ?>
                                    <?php foreach ($productos as $prod): ?>
                                        <option value="<?= htmlspecialchars($prod->id_productos) ?>">
                                            <?= htmlspecialchars($prod->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="producto_stock">
                                <i class="fas fa-box"></i>
                                Producto a Monitorear
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="cantidad_minima" 
                                   name="cantidad_minima" 
                                   placeholder=" "
                                   min="1"
                                   required>
                            <label for="cantidad_minima">
                                <i class="fas fa-sort-numeric-down"></i>
                                Cantidad Mínima
                            </label>
                        </div>
                    </div>

                    <div class="form-floating-modern">
                        <select class="form-select-modern" 
                                id="cliente_stock" 
                                name="id_clientes" 
                                required>
                            <option value="">Seleccione un cliente</option>
                            <?php if (isset($clientes) && !empty($clientes)): ?>
                                <?php foreach ($clientes as $cli): ?>
                                    <option value="<?= htmlspecialchars($cli->id_clientes) ?>">
                                        <?= htmlspecialchars($cli->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label for="cliente_stock">
                            <i class="fas fa-user"></i>
                            Cliente Responsable
                        </label>
                    </div>

                    <div class="info-panel">
                        <h6><i class="fas fa-info-circle"></i> Información</h6>
                        <p>Esta alerta se activará automáticamente cuando el stock del producto seleccionado sea igual o menor a la cantidad mínima especificada. El cliente responsable recibirá notificaciones.</p>
                    </div>
                </div>

                <!-- Formulario para Vencimiento -->
                <div class="form-section-card" id="expiration-form">
                    <div class="section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Configuración de Alerta de Vencimiento
                    </div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="producto_expiration" 
                                    name="id_productos" 
                                    required>
                                <option value="">Seleccione un producto</option>
                                <?php if (isset($productos) && !empty($productos)): ?>
                                    <?php foreach ($productos as $prod): ?>
                                        <option value="<?= htmlspecialchars($prod->id_productos) ?>">
                                            <?= htmlspecialchars($prod->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="producto_expiration">
                                <i class="fas fa-box"></i>
                                Producto a Monitorear
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="date" 
                                   class="form-control-modern" 
                                   id="fecha_caducidad" 
                                   name="fecha_caducidad" 
                                   placeholder=" "
                                   required>
                            <label for="fecha_caducidad">
                                <i class="fas fa-calendar-times"></i>
                                Fecha de Caducidad
                            </label>
                        </div>
                    </div>

                    <div class="form-floating-modern">
                        <select class="form-select-modern" 
                                id="cliente_expiration" 
                                name="id_clientes" 
                                required>
                            <option value="">Seleccione un cliente</option>
                            <?php if (isset($clientes) && !empty($clientes)): ?>
                                <?php foreach ($clientes as $cli): ?>
                                    <option value="<?= htmlspecialchars($cli->id_clientes) ?>">
                                        <?= htmlspecialchars($cli->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label for="cliente_expiration">
                            <i class="fas fa-user"></i>
                            Cliente Responsable
                        </label>
                    </div>

                    <div class="info-panel">
                        <h6><i class="fas fa-info-circle"></i> Información</h6>
                        <p>Esta alerta se activará antes de la fecha de caducidad especificada. El sistema notificará al cliente responsable con suficiente antelación para tomar las medidas necesarias.</p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Buttons -->
        <div class="buttons-section">
            <button type="submit" form="alertForm" class="btn-modern btn-create">
                <i class="fas fa-bell"></i>
                CREAR ALERTA
            </button>
            <a href="/RMIE/app/controllers/AlertController.php" class="btn-modern btn-cancel">
                <i class="fas fa-times"></i>
                CANCELAR
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Manejo de tipos de alerta
        document.querySelectorAll('.alert-type-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remover selección anterior
                document.querySelectorAll('.alert-type-card').forEach(c => c.classList.remove('selected'));
                document.querySelectorAll('.form-section-card').forEach(f => f.classList.remove('active'));
                
                // Seleccionar nueva opción
                this.classList.add('selected');
                const type = this.dataset.type;
                document.getElementById('alert_type').value = type;
                
                // Mostrar formulario correspondiente
                if (type === 'stock') {
                    document.getElementById('stock-form').classList.add('active');
                    // Deshabilitar campos del otro formulario
                    document.getElementById('producto_expiration').removeAttribute('required');
                    document.getElementById('fecha_caducidad').removeAttribute('required');
                    document.getElementById('cliente_expiration').removeAttribute('required');
                    // Habilitar campos de este formulario
                    document.getElementById('producto_stock').setAttribute('required', '');
                    document.getElementById('cantidad_minima').setAttribute('required', '');
                    document.getElementById('cliente_stock').setAttribute('required', '');
                } else if (type === 'expiration') {
                    document.getElementById('expiration-form').classList.add('active');
                    // Deshabilitar campos del otro formulario
                    document.getElementById('producto_stock').removeAttribute('required');
                    document.getElementById('cantidad_minima').removeAttribute('required');
                    document.getElementById('cliente_stock').removeAttribute('required');
                    // Habilitar campos de este formulario
                    document.getElementById('producto_expiration').setAttribute('required', '');
                    document.getElementById('fecha_caducidad').setAttribute('required', '');
                    document.getElementById('cliente_expiration').setAttribute('required', '');
                }
            });
        });

        // Validación del formulario
        document.getElementById('alertForm').addEventListener('submit', function(e) {
            const alertType = document.getElementById('alert_type').value;
            
            if (!alertType) {
                e.preventDefault();
                alert('Por favor, seleccione un tipo de alerta');
                return;
            }
            
            if (alertType === 'stock') {
                const cantidadMinima = document.getElementById('cantidad_minima').value;
                if (!cantidadMinima || cantidadMinima < 1) {
                    e.preventDefault();
                    alert('La cantidad mínima debe ser mayor a 0');
                    return;
                }
            } else if (alertType === 'expiration') {
                const fechaCaducidad = new Date(document.getElementById('fecha_caducidad').value);
                const hoy = new Date();
                if (fechaCaducidad <= hoy) {
                    e.preventDefault();
                    alert('La fecha de caducidad debe ser futura');
                    return;
                }
            }
        });

        // Efectos visuales
        document.querySelectorAll('.form-control-modern, .form-select-modern').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Manejo de labels para selects
        document.querySelectorAll('.form-select-modern').forEach(select => {
            select.addEventListener('change', function() {
                const label = this.parentElement.querySelector('label');
                if (this.value) {
                    label.style.top = '2px';
                    label.style.fontSize = '12px';
                    label.style.color = '#667eea';
                } else {
                    label.style.top = '12px';
                    label.style.fontSize = '14px';
                    label.style.color = 'rgba(102, 126, 234, 0.8)';
                }
            });
        });

        // Establecer fecha mínima para vencimiento (mañana)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('fecha_caducidad').min = tomorrow.toISOString().split('T')[0];
    </script>
</body>
</html>