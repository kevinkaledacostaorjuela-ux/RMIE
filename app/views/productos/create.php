<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="productos-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/ProductController.php?accion=index">Productos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Agregar</li>
            </ol>
        </nav>

        <h1><i class="fas fa-plus-circle"></i> Agregar Nuevo Producto</h1>
        
        <div class="productos-form">
            <form method="POST" action="/RMIE/app/controllers/ProductController.php?accion=create" id="formProducto">
                <div class="row">
                    <!-- Información básica -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle"></i> Información Básica</h5>
                        
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-box"></i> Nombre del Producto:
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   required 
                                   placeholder="Ingrese el nombre del producto"
                                   maxlength="45">
                        </div>
                        
                        <div class="form-group">
                            <label for="descripcion">
                                <i class="fas fa-align-left"></i> Descripción:
                            </label>
                            <input type="text" 
                                   id="descripcion" 
                                   name="descripcion" 
                                   required 
                                   placeholder="Descripción del producto"
                                   maxlength="45">
                        </div>
                        
                        <div class="form-group">
                            <label for="marca">
                                <i class="fas fa-trademark"></i> Marca:
                            </label>
                            <input type="text" 
                                   id="marca" 
                                   name="marca" 
                                   required 
                                   placeholder="Marca del producto"
                                   maxlength="45">
                        </div>
                        
                        <div class="form-group">
                            <label for="stock">
                                <i class="fas fa-cubes"></i> Stock:
                            </label>
                            <input type="number" 
                                   id="stock" 
                                   name="stock" 
                                   required 
                                   min="0"
                                   placeholder="Cantidad en stock">
                        </div>
                    </div>
                    
                    <!-- Fechas -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-calendar"></i> Fechas</h5>
                        
                        <div class="form-group">
                            <label for="fecha_entrada">
                                <i class="fas fa-sign-in-alt"></i> Fecha de Entrada:
                            </label>
                            <input type="date" 
                                   id="fecha_entrada" 
                                   name="fecha_entrada" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_fabricacion">
                                <i class="fas fa-industry"></i> Fecha de Fabricación:
                            </label>
                            <input type="date" 
                                   id="fecha_fabricacion" 
                                   name="fecha_fabricacion" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_caducidad">
                                <i class="fas fa-exclamation-triangle"></i> Fecha de Caducidad:
                            </label>
                            <input type="date" 
                                   id="fecha_caducidad" 
                                   name="fecha_caducidad" 
                                   required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Precios -->
                    <div class="col-md-4">
                        <h5><i class="fas fa-dollar-sign"></i> Precios</h5>
                        
                        <div class="form-group">
                            <label for="precio_unitario">
                                Precio Unitario:
                            </label>
                            <input type="number" 
                                   id="precio_unitario" 
                                   name="precio_unitario" 
                                   required 
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                        </div>
                        
                        <div class="form-group">
                            <label for="precio_por_mayor">
                                Precio por Mayor:
                            </label>
                            <input type="number" 
                                   id="precio_por_mayor" 
                                   name="precio_por_mayor" 
                                   required 
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                        </div>
                        
                        <div class="form-group">
                            <label for="valor_unitario">
                                Valor Unitario:
                            </label>
                            <input type="number" 
                                   id="valor_unitario" 
                                   name="valor_unitario" 
                                   required 
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                        </div>
                    </div>
                    
                    <!-- Categorización -->
                    <div class="col-md-4">
                        <h5><i class="fas fa-tags"></i> Categorización</h5>
                        
                        <div class="form-group">
                            <label for="id_categoria">
                                <i class="fas fa-folder"></i> Categoría:
                            </label>
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione una categoría</option>
                                <?php if (isset($categorias) && is_array($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat->id_categoria) ?>">
                                            <?= htmlspecialchars($cat->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_subcategoria">
                                <i class="fas fa-layer-group"></i> Subcategoría:
                            </label>
                            <select id="id_subcategoria" name="id_subcategoria" required>
                                <option value="">Seleccione una subcategoría</option>
                                <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
                                    <?php foreach ($subcategorias as $sub): ?>
                                        <option value="<?= htmlspecialchars($sub['obj']->id_subcategoria) ?>">
                                            <?= htmlspecialchars($sub['obj']->nombre) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_proveedor">
                                <i class="fas fa-truck"></i> Proveedor:
                            </label>
                            <select id="id_proveedor" name="id_proveedor" required>
                                <option value="">Seleccione un proveedor</option>
                                <?php if (isset($proveedores) && is_array($proveedores)): ?>
                                    <?php foreach ($proveedores as $prov): ?>
                                        <option value="<?= htmlspecialchars($prov->id_proveedores) ?>">
                                            <?= htmlspecialchars($prov->nombre_distribuidor) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_usuario">
                                <i class="fas fa-user"></i> Usuario Responsable:
                            </label>
                            <select id="id_usuario" name="id_usuario" required>
                                <option value="">Seleccione un usuario</option>
                                <?php if (isset($usuarios) && is_array($usuarios)): ?>
                                    <?php foreach ($usuarios as $user): ?>
                                        <option value="<?= htmlspecialchars($user->num_doc) ?>">
                                            <?= htmlspecialchars($user->nombres . ' ' . $user->apellidos) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="subcategorias-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                    <a href="/RMIE/app/controllers/ProductController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript para validación y carga de subcategorías -->
    <script>
    document.getElementById('id_categoria').addEventListener('change', function() {
        const categoriaId = this.value;
        const subcategoriaSelect = document.getElementById('id_subcategoria');
        
        // Limpiar subcategorías
        subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
        
        if (categoriaId) {
            // Aquí puedes hacer una llamada AJAX para cargar subcategorías por categoría
            // Por ahora, mostramos todas las subcategorías disponibles
            <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
                <?php foreach ($subcategorias as $sub): ?>
                    if (<?= $sub['obj']->id_categoria ?> == categoriaId) {
                        subcategoriaSelect.innerHTML += '<option value="<?= htmlspecialchars($sub['obj']->id_subcategoria) ?>"><?= htmlspecialchars($sub['obj']->nombre) ?></option>';
                    }
                <?php endforeach; ?>
            <?php endif; ?>
        }
    });
    
    // Validación de fechas
    document.getElementById('fecha_caducidad').addEventListener('change', function() {
        const fechaFabricacion = document.getElementById('fecha_fabricacion').value;
        const fechaCaducidad = this.value;
        
        if (fechaFabricacion && fechaCaducidad) {
            if (fechaCaducidad <= fechaFabricacion) {
                alert('La fecha de caducidad debe ser posterior a la fecha de fabricación');
                this.value = '';
            }
        }
    });
    
    // Validación de precios
    document.getElementById('precio_unitario').addEventListener('change', function() {
        const precioUnitario = parseFloat(this.value);
        const precioMayor = parseFloat(document.getElementById('precio_por_mayor').value);
        
        if (precioMayor && precioUnitario && precioMayor >= precioUnitario) {
            alert('El precio por mayor debe ser menor al precio unitario');
            document.getElementById('precio_por_mayor').value = '';
        }
    });
    
    // Formateo de fechas por defecto
    document.addEventListener('DOMContentLoaded', function() {
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_entrada').value = hoy;
    });
    </script>
</body>


