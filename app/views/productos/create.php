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
        
        .productos-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .subcategorias-breadcrumb {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 15px 30px;
            border-radius: 0;
        }
        
        .breadcrumb {
            background: transparent;
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
        }
        
        .breadcrumb-item {
            color: white;
            font-size: 14px;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            padding: 0 10px;
            color: white;
        }
        
        .breadcrumb-item a {
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            padding: 5px 10px;
            border-radius: 5px;
        }
        
        .breadcrumb-item a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .breadcrumb-item.active {
            font-weight: 600;
        }
        
        .productos-container h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .productos-container h1 i {
            margin-right: 15px;
        }
        
        .productos-form {
            padding: 40px;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .col-md-6,
        .col-md-4 {
            padding: 0 15px;
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        
        h5 {
            color: #667eea;
            font-size: 20px;
            font-weight: 600;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            display: flex;
            align-items: center;
        }
        
        h5 i {
            margin-right: 10px;
            font-size: 22px;
        }
        
        .form-group {
            margin-bottom: 25px;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        
        .form-group label i {
            margin-right: 8px;
            color: #667eea;
            width: 20px;
        }
        
        .optional-field {
            color: #95a5a6;
            font-weight: normal;
            font-size: 12px;
            font-style: italic;
            margin-left: 5px;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .form-group input:hover,
        .form-group select:hover {
            border-color: #764ba2;
        }
        
        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }
        
        .form-group select option[value=""] {
            color: #95a5a6;
            font-style: italic;
        }
        
        .subcategorias-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }
        
        .btn {
            padding: 14px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn i {
            font-size: 18px;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #868f96 0%, #596164 100%);
            color: white;
        }
        
        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(89, 97, 100, 0.4);
        }
        
        /* Animación para inputs con error */
        .form-group input.error,
        .form-group select.error {
            border-color: #e74c3c;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .col-md-6,
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .productos-container h1 {
                font-size: 24px;
                padding: 20px;
            }
            
            .productos-form {
                padding: 20px;
            }
            
            .subcategorias-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Estilo para campos de precio */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }
        
        /* Tooltip efecto */
        .form-group {
            position: relative;
        }
        
        .form-group input:focus + .tooltip,
        .form-group select:focus + .tooltip {
            opacity: 1;
            visibility: visible;
        }
        
        /* Indicador de campo requerido */
        .form-group label::after {
            content: "*";
            color: #e74c3c;
            margin-left: 5px;
            font-weight: bold;
        }
        
        .form-group:has(select:not([required])) label::after,
        .form-group:has(input:not([required])) label::after {
            content: "";
        }
    </style>
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
                                <i class="fas fa-truck"></i> Proveedor <span class="optional-field">(Opcional)</span>:
                            </label>
                            <select id="id_proveedor" name="id_proveedor">
                                <option value="">Seleccione un proveedor (opcional)</option>
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


