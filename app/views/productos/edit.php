<?php
// Edit product view - unified and clean design
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Diseño moderno y limpio - versión unificada */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            color: #fff;
            min-height: 100vh;
        }
        
        .container-custom {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        
        .header .icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #4ecdc4;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-row.two-cols {
            grid-template-columns: 1fr 1fr;
        }
        
        .form-row.three-cols {
            grid-template-columns: 1fr 1fr 1fr;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        label {
            margin-bottom: 6px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }
        
        input, select {
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
            background: rgba(255, 255, 255, 0.15);
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        select option {
            background: #333;
            color: #fff;
        }
        
        /* Estilos para campos de búsqueda con dropdown */
        .search-container {
            position: relative;
            width: 100%;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .search-dropdown.show {
            display: block;
        }
        
        .search-option {
            padding: 12px 16px;
            color: #333;
            cursor: pointer;
            transition: background-color 0.2s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .search-option:last-child {
            border-bottom: none;
        }
        
        .search-option:hover {
            background: rgba(78, 205, 196, 0.1);
        }
        
        .search-option.selected {
            background: rgba(78, 205, 196, 0.2);
            font-weight: 600;
        }
        
        .hidden-select {
            display: none;
        }
        
        .char-count {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 4px;
        }
        
        .info-panel {
            background: rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .info-value {
            font-weight: 600;
            color: #4ecdc4;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            color: #fff;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.3);
            color: #fff;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            color: #fff;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .container-custom {
                padding: 20px;
            }
            
            .form-row.two-cols,
            .form-row.three-cols {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 8px;
            }
            
            .buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="header">
            <h1>
                <div class="icon">
                    <i class="fas fa-edit"></i>
                </div>
                Editar Producto
            </h1>
            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.7);">
                    ID: <?= htmlspecialchars($producto->id_productos ?? '') ?> — <?= htmlspecialchars($producto->nombre ?? '') ?>
                </p>

            <?php if (!empty($successMessage)): ?>
                <div style="margin-top:15px;padding:12px;border-radius:10px;background:linear-gradient(90deg,#2ecc71,#27ae60);color:#fff;font-weight:600;text-align:center;">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php elseif (!empty($errorMessage)): ?>
                <div style="margin-top:15px;padding:12px;border-radius:10px;background:linear-gradient(90deg,#e74c3c,#c0392b);color:#fff;font-weight:600;text-align:center;">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>
        </div>

        <form id="formEditarProducto" method="POST" action="/RMIE/app/controllers/ProductController.php?accion=edit&id=<?= $producto->id_productos ?>">
            
            <!-- Información básica -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i> Información básica
                </div>
                <div class="form-row two-cols">
                    <div class="form-group">
                        <label for="nombre">Nombre del producto</label>
                        <input type="text" id="nombre" name="nombre" required maxlength="100" 
                               value="<?= htmlspecialchars($producto->nombre ?? '') ?>">
                        <div class="char-count">
                            <span id="nombre-count"><?= strlen($producto->nombre ?? '') ?></span>/100 caracteres
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" required maxlength="200" 
                               value="<?= htmlspecialchars($producto->descripcion ?? '') ?>">
                        <div class="char-count">
                            <span id="descripcion-count"><?= strlen($producto->descripcion ?? '') ?></span>/200 caracteres
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorización -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-tags"></i> Categorización
                </div>
                <div class="form-row two-cols">
                    <div class="form-group">
                        <label for="categoria_search">Categoría</label>
                        <div class="search-container">
                            <input type="text" 
                                   id="categoria_search" 
                                   class="search-input" 
                                   placeholder="Buscar categoría..." 
                                   autocomplete="off"
                                   value="<?php if (isset($producto->id_categoria) && $producto->id_categoria): foreach ($categorias as $cat): if ($cat->id_categoria == $producto->id_categoria): echo htmlspecialchars($cat->nombre); break; endif; endforeach; endif; ?>">
                            <div class="search-dropdown" id="categoria_dropdown">
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <div class="search-option" 
                                             data-value="<?= htmlspecialchars($cat->id_categoria) ?>"
                                             <?= ($producto->id_categoria == $cat->id_categoria) ? 'data-selected="true"' : '' ?>>
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <select id="id_categoria" name="id_categoria" required class="hidden-select">
                                <option value="">Seleccione una categoría</option>
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat->id_categoria) ?>" 
                                                <?= ($producto->id_categoria == $cat->id_categoria) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subcategoria_search">Subcategoría (opcional)</label>
                        <div class="search-container">
                            <input type="text" 
                                   id="subcategoria_search" 
                                   class="search-input" 
                                   placeholder="Buscar subcategoría..." 
                                   autocomplete="off"
                                   value="<?php if (isset($producto->id_subcategoria) && $producto->id_subcategoria): foreach ($subcategorias as $sub): if ($sub['obj']->id_subcategoria == $producto->id_subcategoria): echo htmlspecialchars($sub['obj']->nombre); break; endif; endforeach; endif; ?>">
                            <div class="search-dropdown" id="subcategoria_dropdown">
                                <div class="search-option" data-value="">Sin subcategoría</div>
                                <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
                                    <?php foreach ($subcategorias as $sub): ?>
                                        <div class="search-option" 
                                             data-value="<?= htmlspecialchars($sub['obj']->id_subcategoria) ?>"
                                             <?= ($producto->id_subcategoria == $sub['obj']->id_subcategoria) ? 'data-selected="true"' : '' ?>>
                                            <?= htmlspecialchars($sub['obj']->nombre) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <select id="id_subcategoria" name="id_subcategoria" class="hidden-select">
                                <option value="">Sin subcategoría</option>
                                <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
                                    <?php foreach ($subcategorias as $sub): ?>
                                        <option value="<?= htmlspecialchars($sub['obj']->id_subcategoria) ?>" 
                                                <?= ($producto->id_subcategoria == $sub['obj']->id_subcategoria) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sub['obj']->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventario -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-warehouse"></i> Inventario
                </div>
                <div class="form-row three-cols">
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" step="1" 
                               value="<?= htmlspecialchars($producto->stock ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input type="text" id="marca" name="marca" maxlength="50" 
                               value="<?= htmlspecialchars($producto->marca ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="valor_unitario">Valor unitario</label>
                        <input type="number" id="valor_unitario" name="valor_unitario" min="0" step="0.01" 
                               value="<?= htmlspecialchars($producto->valor_unitario ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Precios -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-dollar-sign"></i> Precios
                </div>
                <div class="form-row two-cols">
                    <div class="form-group">
                        <label for="precio_unitario">Precio unitario</label>
                        <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" min="0" 
                               value="<?= htmlspecialchars($producto->precio_unitario ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="precio_por_mayor">Precio por mayor</label>
                        <input type="number" id="precio_por_mayor" name="precio_por_mayor" step="0.01" min="0" 
                               value="<?= htmlspecialchars($producto->precio_por_mayor ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i> Fechas
                </div>
                <div class="form-row three-cols">
                    <div class="form-group">
                        <label for="fecha_entrada">Fecha de entrada</label>
                        <input type="date" id="fecha_entrada" name="fecha_entrada" 
                               value="<?= htmlspecialchars($producto->fecha_entrada ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="fecha_fabricacion">Fecha de fabricación</label>
                        <input type="date" id="fecha_fabricacion" name="fecha_fabricacion" 
                               value="<?= htmlspecialchars($producto->fecha_fabricacion ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="fecha_caducidad">Fecha de vencimiento</label>
                        <input type="date" id="fecha_caducidad" name="fecha_caducidad" 
                               value="<?= htmlspecialchars($producto->fecha_caducidad ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Proveedor y usuario -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-users"></i> Proveedor y usuario responsable
                </div>
                <div class="form-row two-cols">
                    <div class="form-group">
                        <label for="id_proveedores">Proveedor (opcional)</label>
                        <select id="id_proveedores" name="id_proveedores">
                            <option value="">Sin proveedor</option>
                            <?php if (isset($proveedores) && is_array($proveedores)): ?>
                                <?php foreach ($proveedores as $prov): ?>
                                    <option value="<?= htmlspecialchars($prov->id_proveedores) ?>" 
                                            <?= ($producto->id_proveedores == $prov->id_proveedores) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($prov->nombre_distribuidor ?? 'Sin nombre') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="num_doc">Usuario responsable</label>
                        <select id="num_doc" name="num_doc" required>
                            <option value="">Seleccione usuario</option>
                            <?php if (isset($usuarios) && is_array($usuarios)): ?>
                                <?php foreach ($usuarios as $user): ?>
                                    <option value="<?= htmlspecialchars($user->num_doc) ?>" 
                                            <?= ($producto->num_doc == $user->num_doc) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(($user->nombre ?? ($user->nombres.' '.$user->apellidos)) ) ?>
                                        <?= isset($user->rol) ? ' ('.htmlspecialchars($user->rol).')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Panel de información -->
            <div class="info-panel">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i> Estado del producto
                </div>
                <div class="info-item">
                    <span class="info-label">Stock actual:</span>
                    <span class="info-value"><?= htmlspecialchars($producto->stock ?? 0) ?> unidades</span>
                </div>
                <?php
                    $diasCaducidad = isset($producto->fecha_caducidad) ? 
                        floor((strtotime($producto->fecha_caducidad) - time()) / 86400) : 0;
                ?>
                <div class="info-item">
                    <span class="info-label">Días hasta caducidad:</span>
                    <span class="info-value"><?= $diasCaducidad ?> días</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Valor del inventario:</span>
                    <span class="info-value">$<?= number_format((($producto->precio_unitario ?? 0) * ($producto->stock ?? 0)), 2) ?></span>
                </div>
            </div>

            <!-- Botones -->
            <div class="buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Producto
                </button>
                <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        // Contadores de caracteres
        function initCharCounter(inputId, counterId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            
            if (input && counter) {
                input.addEventListener('input', function() {
                    counter.textContent = this.value.length;
                    if (this.value.length > max * 0.8) {
                        counter.style.color = '#ff6b6b';
                    } else {
                        counter.style.color = 'rgba(255, 255, 255, 0.6)';
                    }
                });
            }
        }

        initCharCounter('nombre', 'nombre-count', 100);
        initCharCounter('descripcion', 'descripcion-count', 200);

        // Funcionalidad de búsqueda para dropdowns
        function setupSearchDropdown(inputId, dropdownId, hiddenSelectId) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            const hiddenSelect = document.getElementById(hiddenSelectId);
            
            if (!input || !dropdown || !hiddenSelect) return;
            
            // Función para filtrar opciones
            function filterOptions(searchText) {
                const options = dropdown.querySelectorAll('.search-option');
                const searchLower = searchText.toLowerCase();
                
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchLower)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }
            
            // Mostrar dropdown al hacer foco
            input.addEventListener('focus', function() {
                dropdown.classList.add('show');
                filterOptions(this.value);
            });
            
            // Filtrar mientras escribe
            input.addEventListener('input', function() {
                dropdown.classList.add('show');
                filterOptions(this.value);
            });
            
            // Manejar selección de opción
            dropdown.addEventListener('click', function(e) {
                if (e.target.classList.contains('search-option')) {
                    const value = e.target.dataset.value;
                    const text = e.target.textContent;
                    
                    input.value = text;
                    hiddenSelect.value = value;
                    dropdown.classList.remove('show');
                    
                    // Marcar opción como seleccionada
                    dropdown.querySelectorAll('.search-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    e.target.classList.add('selected');
                    
                    // Si es categoría, recargar subcategorías
                    if (inputId === 'categoria_search') {
                        loadSubcategoriasAJAX(value);
                    }
                }
            });
            
            // Ocultar dropdown al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }
        
        // Cargar subcategorías vía AJAX para el sistema de búsqueda
        function loadSubcategoriasAJAX(categoriaId) {
            const subcategoriaDropdown = document.getElementById('subcategoria_dropdown');
            const subcategoriaInput = document.getElementById('subcategoria_search');
            const subcategoriaSelect = document.getElementById('id_subcategoria');
            
            if (!subcategoriaDropdown) return;
            
            if (!categoriaId) {
                subcategoriaDropdown.innerHTML = '<div class="search-option" data-value="">Sin subcategoría</div>';
                subcategoriaInput.value = '';
                subcategoriaSelect.value = '';
                return;
            }

            fetch('/RMIE/app/controllers/SubcategoryController.php?accion=getByCategory&categoria_id=' + categoriaId)
                .then(response => response.json())
                .then(data => {
                    let options = '<div class="search-option" data-value="">Sin subcategoría</div>';
                    data.forEach(subcategoria => {
                        options += `<div class="search-option" data-value="${subcategoria.id_subcategoria}">${subcategoria.nombre}</div>`;
                    });
                    subcategoriaDropdown.innerHTML = options;
                    
                    // Limpiar selección actual
                    subcategoriaInput.value = '';
                    subcategoriaSelect.value = '';
                })
                .catch(error => {
                    console.error('Error cargando subcategorías:', error);
                    subcategoriaDropdown.innerHTML = '<div class="search-option" data-value="">Error cargando subcategorías</div>';
                });
        }
        
        // Cargar subcategorías vía AJAX (método tradicional para compatibilidad)
        function loadSubcategories(categoriaId, selectedId) {
            const subcategoriaSelect = document.getElementById('id_subcategoria');
            if (!subcategoriaSelect) return;
            
            if (!categoriaId) {
                subcategoriaSelect.innerHTML = '<option value="">Sin subcategoría</option>';
                return;
            }

            fetch('/RMIE/app/controllers/SubcategoryController.php?accion=getByCategory&categoria_id=' + categoriaId)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Sin subcategoría</option>';
                    data.forEach(subcategoria => {
                        const selected = subcategoria.id_subcategoria == selectedId ? 'selected' : '';
                        options += `<option value="${subcategoria.id_subcategoria}" ${selected}>${subcategoria.nombre}</option>`;
                    });
                    subcategoriaSelect.innerHTML = options;
                })
                .catch(error => {
                    console.error('Error cargando subcategorías:', error);
                    subcategoriaSelect.innerHTML = '<option value="">Error cargando subcategorías</option>';
                });
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar campos de búsqueda
            setupSearchDropdown('categoria_search', 'categoria_dropdown', 'id_categoria');
            setupSearchDropdown('subcategoria_search', 'subcategoria_dropdown', 'id_subcategoria');
            
            // Marcar opciones pre-seleccionadas
            document.querySelectorAll('.search-option[data-selected="true"]').forEach(option => {
                option.classList.add('selected');
            });
        });

        // Validaciones del formulario
        document.getElementById('formEditarProducto').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const categoria = document.getElementById('id_categoria').value;
            const usuario = document.getElementById('num_doc').value;

            if (nombre.length < 3) {
                e.preventDefault();
                alert('El nombre del producto debe tener al menos 3 caracteres');
                return;
            }

            if (!categoria) {
                e.preventDefault();
                alert('Debe seleccionar una categoría');
                return;
            }

            if (!usuario) {
                e.preventDefault();
                alert('Debe seleccionar un usuario responsable');
                return;
            }

            // Mostrar loading en el botón
            const submitBtn = this.querySelector('.btn-primary');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
