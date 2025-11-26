<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Producto - RMIE</title>
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
            padding: 20px;
        }
        
        .main-container {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
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
        
        .section-title {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            font-size: 1.4rem;
            opacity: 0.8;
        }
        
        .form-row {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .form-row-2 {
            grid-template-columns: 1fr 1fr;
        }
        
        .form-row-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }
        
        .form-row-4 {
            grid-template-columns: 1fr 1fr 1fr 1fr;
        }
        
        @media (max-width: 768px) {
            .form-row-2,
            .form-row-3,
            .form-row-4 {
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
        
        .char-counter {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-align: right;
            margin-top: 5px;
        }
        
        .form-section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            background: linear-gradient(135deg, #00d4ff 0%, #667eea 100%);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 212, 255, 0.4);
            background: linear-gradient(135deg, #00b8d4 0%, #5a67d8 100%);
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
        }
        
        /* Animaciones */
        .form-section-card {
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }
        
        .form-section-card:nth-child(1) {
            animation-delay: 0.1s;
        }
        
        .form-section-card:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .form-section-card:nth-child(3) {
            animation-delay: 0.3s;
        }
        
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
                <i class="fas fa-box"></i>
                Crear Nuevo Producto
            </h1>
            <p class="header-subtitle">Complete todos los campos para agregar un nuevo producto al inventario</p>
        </div>

        <!-- Form -->
        <div class="form-section">
            <form action="/RMIE/app/controllers/ProductController.php?accion=create" method="POST" id="productForm">
                
                <!-- Información Básica -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Información Básica
                    </div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-floating-modern">
                            <input type="text" 
                                   class="form-control-modern" 
                                   id="nombre" 
                                   name="nombre" 
                                   placeholder=" "
                                   maxlength="100"
                                   required>
                            <label for="nombre">
                                <i class="fas fa-tag"></i>
                                Nombre del Producto
                            </label>
                            <div class="char-counter">
                                <span id="nombre-count">0</span>/100 caracteres
                            </div>
                        </div>

                        <div class="form-floating-modern">
                            <input type="text" 
                                   class="form-control-modern" 
                                   id="descripcion" 
                                   name="descripcion" 
                                   placeholder=" "
                                   maxlength="200">
                            <label for="descripcion">
                                <i class="fas fa-align-left"></i>
                                Descripción (Opcional)
                            </label>
                            <div class="char-counter">
                                <span id="descripcion-count">0</span>/200 caracteres
                            </div>
                        </div>
                    </div>

                    <div class="form-row form-row-2">
                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="categoria_id" 
                                    name="categoria_id" 
                                    required>
                                <option value="">Seleccione una categoría</option>
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat->id_categoria) ?>">
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="categoria_id">
                                <i class="fas fa-folder"></i>
                                Categoría
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="subcategoria_id" 
                                    name="subcategoria_id">
                                <option value="">Seleccione primero una categoría</option>
                            </select>
                            <label for="subcategoria_id">
                                <i class="fas fa-layer-group"></i>
                                Subcategoría (Opcional)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Información de Inventario -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-warehouse"></i>
                        Inventario y Datos Básicos
                    </div>
                    
                    <div class="form-row form-row-3">
                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="stock" 
                                   name="stock" 
                                   placeholder=" "
                                   min="0"
                                   required>
                            <label for="stock">
                                <i class="fas fa-cubes"></i>
                                Stock Inicial
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="text" 
                                   class="form-control-modern" 
                                   id="marca" 
                                   name="marca" 
                                   placeholder=" "
                                   maxlength="50">
                            <label for="marca">
                                <i class="fas fa-trademark"></i>
                                Marca
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="valor_unitario" 
                                   name="valor_unitario" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0">
                            <label for="valor_unitario">
                                <i class="fas fa-tag"></i>
                                Valor Unitario
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Información de Precios -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-dollar-sign"></i>
                        Precios
                    </div>
                    
                    <div class="form-row form-row-3">
                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="precio_unitario" 
                                   name="precio_unitario" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0"
                                   required>
                            <label for="precio_unitario">
                                <i class="fas fa-money-bill"></i>
                                Precio Unitario
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="precio_por_mayor" 
                                   name="precio_por_mayor" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0">
                            <label for="precio_por_mayor">
                                <i class="fas fa-coins"></i>
                                Precio por Mayor
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="precio_compra" 
                                   name="precio_compra" 
                                   placeholder=" "
                                   step="0.01"
                                   min="0">
                            <label for="precio_compra">
                                <i class="fas fa-shopping-cart"></i>
                                Precio de Compra
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Fechas
                    </div>
                    
                    <div class="form-row form-row-3">
                        <div class="form-floating-modern">
                            <input type="date" 
                                   class="form-control-modern" 
                                   id="fecha_entrada" 
                                   name="fecha_entrada" 
                                   placeholder=" "
                                   required>
                            <label for="fecha_entrada">
                                <i class="fas fa-calendar-plus"></i>
                                Fecha de Entrada
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="date" 
                                   class="form-control-modern" 
                                   id="fecha_fabricacion" 
                                   name="fecha_fabricacion" 
                                   placeholder=" ">
                            <label for="fecha_fabricacion">
                                <i class="fas fa-industry"></i>
                                Fecha de Fabricación
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <input type="date" 
                                   class="form-control-modern" 
                                   id="fecha_caducidad" 
                                   name="fecha_caducidad" 
                                   placeholder=" ">
                            <label for="fecha_caducidad">
                                <i class="fas fa-calendar-times"></i>
                                Fecha de Vencimiento
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Proveedor y Usuario -->
                <div class="form-section-card">
                    <div class="section-title">
                        <i class="fas fa-users"></i>
                        Proveedor y Usuario Responsable
                    </div>
                    
                    <div class="form-row form-row-2">
                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="id_proveedores" 
                                    name="id_proveedores">
                                <option value="">Seleccione un proveedor</option>
                                <?php if (isset($proveedores) && is_array($proveedores)): ?>
                                    <?php foreach ($proveedores as $prov): ?>
                                        <option value="<?= htmlspecialchars($prov->id_proveedores ?? '') ?>">
                                            <?= htmlspecialchars($prov->nombre_distribuidor ?? 'Sin nombre') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="id_proveedores">
                                <i class="fas fa-truck"></i>
                                Proveedor (Opcional)
                            </label>
                        </div>

                        <div class="form-floating-modern">
                            <select class="form-select-modern" 
                                    id="num_doc" 
                                    name="num_doc"
                                    required>
                                <option value="">Seleccione un usuario responsable</option>
                                <?php if (isset($usuarios) && is_array($usuarios)): ?>
                                    <?php foreach ($usuarios as $user): ?>
                                        <option value="<?= htmlspecialchars($user->num_doc ?? '') ?>">
                                            <?= htmlspecialchars(($user->nombres ?? '') . ' ' . ($user->apellidos ?? '')) ?> 
                                            (<?= htmlspecialchars($user->rol ?? 'Sin rol') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="num_doc">
                                <i class="fas fa-user"></i>
                                Usuario Responsable *
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Buttons -->
        <div class="buttons-section">
            <button type="submit" form="productForm" class="btn-modern btn-create">
                <i class="fas fa-save"></i>
                CREAR PRODUCTO
            </button>
            <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn-modern btn-cancel">
                <i class="fas fa-times"></i>
                CANCELAR
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contador de caracteres
        function updateCharCounter(inputId, counterId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            
            if (input && counter) {
                input.addEventListener('input', function() {
                    const currentLength = this.value.length;
                    counter.textContent = currentLength;
                    
                    if (currentLength > maxLength * 0.8) {
                        counter.style.color = '#ff6b6b';
                    } else {
                        counter.style.color = 'rgba(255, 255, 255, 0.7)';
                    }
                });
            }
        }

        // Inicializar contadores
        updateCharCounter('nombre', 'nombre-count', 100);
        updateCharCounter('descripcion', 'descripcion-count', 200);

        // Cargar subcategorías basado en categoría seleccionada
        document.getElementById('categoria_id').addEventListener('change', function() {
            const subcategoriaSelect = document.getElementById('subcategoria_id');
            subcategoriaSelect.innerHTML = '<option value="">Cargando subcategorías...</option>';
            
            if (this.value) {
                // Llamada AJAX para cargar subcategorías reales
                fetch('/RMIE/app/controllers/SubcategoryController.php?accion=getByCategory&categoria_id=' + this.value)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">Seleccione una subcategoría</option>';
                        if (data && data.length > 0) {
                            data.forEach(subcategoria => {
                                options += `<option value="${subcategoria.id_subcategoria}">${subcategoria.nombre}</option>`;
                            });
                        }
                        subcategoriaSelect.innerHTML = options;
                    })
                    .catch(error => {
                        console.error('Error cargando subcategorías:', error);
                        subcategoriaSelect.innerHTML = '<option value="">Error cargando subcategorías</option>';
                    });
            } else {
                subcategoriaSelect.innerHTML = '<option value="">Seleccione primero una categoría</option>';
            }
        });

        // Validación del formulario
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const stock = document.getElementById('stock').value;
            const precioCompra = parseFloat(document.getElementById('precio_compra').value);
            const precioVenta = parseFloat(document.getElementById('precio_venta').value);
            
            if (nombre.length < 3) {
                e.preventDefault();
                alert('El nombre del producto debe tener al menos 3 caracteres');
                return;
            }
            
            if (stock < 0) {
                e.preventDefault();
                alert('El stock no puede ser negativo');
                return;
            }
            
            if (precioCompra >= precioVenta) {
                e.preventDefault();
                alert('El precio de venta debe ser mayor al precio de compra');
                return;
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

        // Establecer fecha actual por defecto (espera DOM y comprueba existencia)
        document.addEventListener('DOMContentLoaded', function() {
            const fecha = document.getElementById('fecha_entrada');
            if (!fecha) return;

            // Si el input soporta valueAsDate, úsalo; si no, aplicar fallback a YYYY-MM-DD
            try {
                if ('valueAsDate' in fecha) {
                    fecha.valueAsDate = new Date();
                } else {
                    const d = new Date();
                    const yyyy = d.getFullYear();
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const dd = String(d.getDate()).padStart(2, '0');
                    fecha.value = `${yyyy}-${mm}-${dd}`;
                }
            } catch (e) {
                // En caso de cualquier error, aplicar formato seguro
                const d = new Date();
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                fecha.value = `${yyyy}-${mm}-${dd}`;
            }
        });
    </script>
</body>
</html>