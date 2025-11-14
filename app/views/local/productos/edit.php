<?php
// Edit product view - unified and clean design
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - RMIE</title>
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
                        <label for="id_categoria">Categoría</label>
                        <select id="id_categoria" name="id_categoria" required>
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
                    <div class="form-group">
                        <label for="id_subcategoria">Subcategoría (opcional)</label>
                        <select id="id_subcategoria" name="id_subcategoria">
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
                        <label for="id_proveedor">Proveedor (opcional)</label>
                        <select id="id_proveedor" name="id_proveedor">
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
                        <label for="id_usuario">Usuario responsable</label>
                        <select id="id_usuario" name="id_usuario" required>
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

        // Cargar subcategorías vía AJAX
        function loadSubcategories(categoriaId, selectedId) {
            const subcategoriaSelect = document.getElementById('id_subcategoria');
            if (!subcategoriaSelect) return;
            
            subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
            
            if (!categoriaId) {
                subcategoriaSelect.innerHTML = '<option value="">Sin subcategoría</option>';
                return;
            }

            fetch('/RMIE/app/controllers/SubcategoryController.php?accion=getByCategory&categoria_id=' + categoriaId)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    let options = '<option value="">Sin subcategoría</option>';
                    data.forEach(function(subcategoria) {
                        const selected = subcategoria.id_subcategoria == selectedId ? 'selected' : '';
                        options += '<option value="' + subcategoria.id_subcategoria + '" ' + selected + '>' + subcategoria.nombre + '</option>';
                    });
                    subcategoriaSelect.innerHTML = options;
                })
                .catch(function(error) {
                    console.error('Error cargando subcategorías:', error);
                    subcategoriaSelect.innerHTML = '<option value="">Error cargando subcategorías</option>';
                });
        }

        // Event listener para cambio de categoría
        document.getElementById('id_categoria').addEventListener('change', function() {
            loadSubcategories(this.value, null);
        });

        // Cargar subcategorías inicialmente
        document.addEventListener('DOMContentLoaded', function() {
            const categoriaActual = document.getElementById('id_categoria').value;
            const subcategoriaActual = <?= $producto->id_subcategoria ?? 'null' ?>;
            if (categoriaActual) {
                loadSubcategories(categoriaActual, subcategoriaActual);
            }
        });

        // Validaciones del formulario
        document.getElementById('formEditarProducto').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const categoria = document.getElementById('id_categoria').value;
            const usuario = document.getElementById('id_usuario').value;

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
