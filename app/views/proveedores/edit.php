<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor - RMIE</title>
    <link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
    <!-- Bootstrap 5.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
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
            --transition: opacity 0.2s ease;
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
            color: var(--text-primary);
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
            box-shadow: var(--shadow-light);
        }

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
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.8);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2.2rem;
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
        }

        .form-header h1 {
            position: relative;
            z-index: 1;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.3rem;
        }

        .form-header p {
            position: relative;
            z-index: 1;
            opacity: 0.9;
            font-size: 1.05rem;
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
            padding: 1.8rem;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .form-section:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding-bottom: 0.3rem;
            border-bottom: 2px solid rgba(102, 126, 234, 0.12);
        }

        .section-title i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.2rem;
        }

        .ventas-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 2rem;
            align-items: start;
        }

        .form-group { margin-bottom: 1.2rem; }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.98rem;
        }

        .form-group label i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1rem;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 1rem;
            transition: var(--transition);
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.97);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.07);
            outline: none;
        }

        .form-control::placeholder { color: rgba(77, 85, 108, 0.55); }

        .ventas-summary {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
            padding: 1.4rem;
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
            background: var(--secondary-gradient);
            border-radius: 2px;
        }

        .summary-header { text-align: center; margin-bottom: 1rem; }

        .summary-header h5 { color: var(--text-primary); font-weight: 700; font-size: 1.05rem; margin-bottom: 0.4rem; }

        .summary-item { display:flex; justify-content:space-between; align-items:center; padding:0.7rem 0; border-bottom:1px solid rgba(255,255,255,0.06); }

        .summary-item:last-child { border-bottom:none; font-weight:700; font-size:1.05rem; color:var(--text-primary); background: rgba(255,255,255,0.06); margin-top:0.8rem; padding:0.8rem; border-radius:8px; }

        .summary-label { color: var(--text-secondary); font-weight:600; display:flex; align-items:center; gap:0.5rem; }
        .summary-value { color: var(--text-primary); font-weight:700; }

        .btn { border-radius: 12px; padding: 12px 28px; font-weight:700; }

        .btn-success { background: var(--success-gradient); color: white; box-shadow: 0 6px 20px rgba(79,172,254,0.15); }
        .btn-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }

        .ventas-buttons { display:flex; justify-content:center; gap:1.2rem; margin-top:1.8rem; padding-top:1.2rem; border-top:1px solid rgba(255,255,255,0.12); }

        .info-alert { background: rgba(23,162,184,0.08); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border:1px solid rgba(23,162,184,0.12); border-radius:12px; color:var(--text-primary); padding:1rem; }

        .alert-danger { background: rgba(220,53,69,0.08); border-left:4px solid #dc3545; }

        .animate-fade-in { animation: fadeIn 0.5s ease-out; }

        @keyframes fadeIn { from { opacity:0; transform:translateY(12px);} to { opacity:1; transform:none; } }

        @media (max-width: 992px) { .ventas-grid { grid-template-columns: 1fr; } }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .form-header h1 { font-size: 1.6rem; }
            .form-content { padding: 1rem; }
            .btn { width: 100%; }
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(255,255,255,0.06); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--secondary-gradient); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-gradient); }

        /* Estilos para productos */
        .productos-selection {
            max-height: 400px;
            overflow-y: auto;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .producto-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 15px;
            transition: var(--transition);
            cursor: pointer;
            height: 100%;
        }

        .producto-card:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(102, 126, 234, 0.4);
            box-shadow: var(--shadow-light);
        }

        .producto-card.selected {
            background: rgba(102, 126, 234, 0.15);
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3);
        }

        .producto-card .form-check-input {
            margin-top: 0;
            margin-right: 10px;
            transform: scale(1.2);
        }

        .producto-card .form-check-label {
            width: 100%;
            cursor: pointer;
        }

        .producto-info {
            margin-left: 25px;
        }

        .producto-nombre {
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .producto-descripcion {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .producto-details {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .producto-details .badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
    </style>
</head>
<body>
    <div class="glass-container animate-fade-in">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="modern-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/ProviderController.php?accion=index">Proveedores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Editar Proveedor: <?= htmlspecialchars($proveedor->nombre_distribuidor ?? 'Sin nombre') ?></h1>
            <p>Actualice la información del proveedor en el sistema</p>
        </div>

        <div class="form-content">
            <!-- Mostrar errores si existen -->
            <?php if (isset($error)): ?>
                <div class="alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/RMIE/app/controllers/ProviderController.php?accion=edit&id=<?= $proveedor->id_proveedores ?>" id="formEditarProveedor">
                <div class="form-section">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="section-title"><i class="fas fa-building"></i> Información de la Empresa</h5>
                            <div class="form-group">
                                <label for="nombre_distribuidor"><i class="fas fa-truck"></i> Nombre del Distribuidor/Empresa:</label>
                                <input type="text" id="nombre_distribuidor" name="nombre_distribuidor" required 
                                       value="<?= htmlspecialchars($proveedor->nombre_distribuidor ?? '') ?>" placeholder="Ingrese el nombre de la empresa" maxlength="100" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="estado"><i class="fas fa-info-circle"></i> Estado del Proveedor:</label>
                                <select id="estado" name="estado" required class="form-select">
                                    <option value="">Seleccione un estado</option>
                                    <option value="activo" <?= ($proveedor->estado ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= ($proveedor->estado ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    <option value="pendiente" <?= ($proveedor->estado ?? '') === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="section-title"><i class="fas fa-address-book"></i> Información de Contacto</h5>
                            <div class="form-group">
                                <label for="correo"><i class="fas fa-envelope"></i> Correo Electrónico:</label>
                                <input type="email" id="correo" name="correo" required value="<?= htmlspecialchars($proveedor->correo ?? '') ?>" placeholder="ejemplo@empresa.com" maxlength="100" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="cel_proveedor"><i class="fas fa-phone"></i> Número de Celular:</label>
                                <input type="tel" id="cel_proveedor" name="cel_proveedor" required value="<?= htmlspecialchars($proveedor->cel_proveedor ?? '') ?>" placeholder="Ej: +57 300 123 4567" maxlength="20" pattern="[\+]?[0-9\s\-\(\)]+" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="section-title"><i class="fas fa-map-marker-alt"></i> Ubicación</h5>
                            <div class="form-group">
                                <label for="ubicacion"><i class="fas fa-map-marker-alt"></i> Ubicación/Dirección:</label>
                                <textarea id="ubicacion" name="ubicacion" rows="3" placeholder="Ingrese la dirección completa del proveedor (ciudad, estado, dirección específica)" maxlength="255" class="form-control"><?= htmlspecialchars($proveedor->ubicacion ?? '') ?></textarea>
                                <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Este campo es opcional pero recomendado para ubicar al proveedor</small>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="section-title"><i class="fas fa-box"></i> Productos del Proveedor</h5>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Gestión de Productos:</strong> Seleccione los productos que este proveedor suministra.
                            </div>
                            
                            <?php if (!empty($productosDisponibles)): ?>
                                <!-- Filtro de búsqueda -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="filtroProductos"><i class="fas fa-search"></i> Filtrar productos por nombre:</label>
                                        <input type="text" id="filtroProductos" class="form-control" placeholder="Escriba el nombre del producto para filtrar..." autocomplete="off">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Escriba parte del nombre para encontrar productos rápidamente
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="productos-selection">
                                    <div class="row">
                                        <?php 
                                        // Crear array de IDs de productos del proveedor para fácil verificación
                                        $productosProveedorIds = [];
                                        if (!empty($productosDelProveedor)) {
                                            foreach ($productosDelProveedor as $producto) {
                                                // Los productos ahora vienen como objetos directos de Provider::getProductosByProveedor
                                                if (is_object($producto)) {
                                                    $productosProveedorIds[] = $producto->id_productos;
                                                }
                                            }
                                        }
                                        
                                        foreach ($productosDisponibles as $producto): 
                                            // Verificar si es un array con 'obj' o directamente un objeto
                                            if (is_array($producto) && isset($producto['obj'])) {
                                                $productoObj = $producto['obj'];
                                            } else {
                                                $productoObj = $producto;
                                            }
                                            
                                            $isSelected = in_array($productoObj->id_productos, $productosProveedorIds);
                                        ?>
                                            <div class="col-md-6 col-lg-4 mb-3 producto-item" data-nombre="<?= htmlspecialchars(strtolower($productoObj->nombre)) ?>">
                                                <div class="form-check producto-card <?= $isSelected ? 'selected' : '' ?>">
                                                    <input type="checkbox" 
                                                           class="form-check-input" 
                                                           id="producto_<?= $productoObj->id_productos ?>" 
                                                           name="productos[]" 
                                                           value="<?= $productoObj->id_productos ?>"
                                                           <?= $isSelected ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="producto_<?= $productoObj->id_productos ?>">
                                                        <div class="producto-info">
                                                            <h6 class="producto-nombre"><?= htmlspecialchars($productoObj->nombre) ?></h6>
                                                            <p class="producto-descripcion"><?= htmlspecialchars($productoObj->descripcion ?? 'Sin descripción') ?></p>
                                                            <div class="producto-details">
                                                                <span class="badge bg-primary">Stock: <?= htmlspecialchars($productoObj->stock ?? '0') ?></span>
                                                                <span class="badge bg-secondary">$<?= number_format($productoObj->precio_unitario ?? 0, 2) ?></span>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    No hay productos disponibles en el sistema. 
                                    <a href="/RMIE/app/controllers/ProductController.php?accion=create" class="alert-link">Crear nuevo producto</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="section-title"><i class="fas fa-chart-line"></i> Estado Actual del Proveedor</h5>
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center mb-2">
                                    <strong><i class="fas fa-id-badge"></i> ID del Proveedor:</strong>
                                    <span class="badge bg-secondary ms-2">#<?= htmlspecialchars($proveedor->id_proveedores ?? 'N/A') ?></span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <strong><i class="fas fa-info-circle"></i> Estado Actual:</strong>
                                    <?php
                                    $estado = strtolower($proveedor->estado ?? 'pendiente');
                                    $claseEstado = '';
                                    $iconoEstado = '';
                                    switch ($estado) {
                                        case 'activo': $claseEstado = 'estado-activo'; $iconoEstado = 'fas fa-check-circle'; break;
                                        case 'inactivo': $claseEstado = 'estado-inactivo'; $iconoEstado = 'fas fa-times-circle'; break;
                                        case 'pendiente': $claseEstado = 'estado-pendiente'; $iconoEstado = 'fas fa-clock'; break;
                                        default: $claseEstado = 'estado-pendiente'; $iconoEstado = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <span class="estado-badge <?= $claseEstado ?> ms-2"><i class="<?= $iconoEstado ?>"></i> <?= ucfirst($estado) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Información Adicional</h5>
                            <div class="info-alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Importante:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Verifique que los datos de contacto estén actualizados</li>
                                    <li>Cambiar el estado puede afectar las operaciones comerciales</li>
                                    <li>Los cambios se aplicarán inmediatamente</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="proveedores-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Proveedor
                    </button>
                    <a href="/RMIE/app/controllers/ProviderController.php?accion=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para validación -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formEditarProveedor');
        const correoInput = document.getElementById('correo');
        const telefonoInput = document.getElementById('cel_proveedor');
        const nombreInput = document.getElementById('nombre_distribuidor');
        const estadoSelect = document.getElementById('estado');
        
        // Validación de email en tiempo real
        correoInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.setCustomValidity('Por favor ingrese un correo electrónico válido');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
        
        // Formateo de teléfono
        telefonoInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            // Formato colombiano: 300 123 4567
            if (value.length >= 7) {
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
            } else if (value.length >= 4) {
                value = value.replace(/(\d{3})(\d{3})/, '$1 $2');
            }
            
            this.value = value;
        });
        
        // Validación del nombre de la empresa
        nombreInput.addEventListener('input', function() {
            this.value = this.value.replace(/^\s+/, ''); // Quitar espacios al inicio
        });
        
        // Confirmación al cambiar estado
        estadoSelect.addEventListener('change', function() {
            const estadoAnterior = '<?= $proveedor->estado ?? "" ?>';
            const estadoNuevo = this.value;
            
            if (estadoAnterior !== estadoNuevo && estadoNuevo === 'inactivo') {
                if (!confirmAction('acción general')) {
                    this.value = estadoAnterior;
                }
            }
        });
        
        // Validación antes del envío
        form.addEventListener('submit', function(e) {
            const correo = correoInput.value.trim();
            const telefono = telefonoInput.value.trim();
            const nombre = nombreInput.value.trim();
            const estado = estadoSelect.value;
            
            if (!nombre) {
                e.preventDefault();
                alert('El nombre del distribuidor es requerido');
                nombreInput.focus();
                return false;
            }
            
            if (!correo || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
                e.preventDefault();
                alert('Por favor ingrese un correo electrónico válido');
                correoInput.focus();
                return false;
            }
            
            if (!telefono || telefono.replace(/\D/g, '').length < 7) {
                e.preventDefault();
                alert('Por favor ingrese un número de teléfono válido (mínimo 7 dígitos)');
                telefonoInput.focus();
                return false;
            }
            
            if (!estado) {
                e.preventDefault();
                alert('Por favor seleccione un estado para el proveedor');
                estadoSelect.focus();
                return false;
            }
            
            // Confirmación final
            if (!confirmAction('acción general')) {
                e.preventDefault();
                return false;
            }
            
            // Si llegamos aquí, todas las validaciones pasaron
            // Mostrar indicador de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
            
            // En caso de error, restaurar el botón después de un tiempo
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 10000);
            
            return true;
        });
        
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

        // Gestión de selección de productos
        const productosCheckboxes = document.querySelectorAll('input[name="productos[]"]');
        
        productosCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const card = this.closest('.producto-card');
                if (this.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });

            // También permitir seleccionar haciendo clic en la tarjeta
            const card = checkbox.closest('.producto-card');
            card.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });

        // Botones para seleccionar/deseleccionar todos los productos
        const productosSection = document.querySelector('.productos-selection');
        if (productosSection && productosCheckboxes.length > 0) {
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'text-center mb-3';
            buttonContainer.innerHTML = `
                <button type="button" class="btn btn-sm btn-outline-primary me-2" id="selectAllProducts">
                    <i class="fas fa-check-square"></i> Seleccionar Todos
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllProducts">
                    <i class="fas fa-square"></i> Deseleccionar Todos
                </button>
            `;
            productosSection.insertBefore(buttonContainer, productosSection.firstChild);

            document.getElementById('selectAllProducts').addEventListener('click', function() {
                const productosVisibles = document.querySelectorAll('.producto-item:not([style*="display: none"])');
                productosVisibles.forEach(function(item) {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            document.getElementById('deselectAllProducts').addEventListener('click', function() {
                const productosVisibles = document.querySelectorAll('.producto-item:not([style*="display: none"])');
                productosVisibles.forEach(function(item) {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });
        }

        // Funcionalidad de filtrado de productos por nombre
        const filtroProductos = document.getElementById('filtroProductos');
        if (filtroProductos) {
            filtroProductos.addEventListener('input', function() {
                const filtro = this.value.toLowerCase().trim();
                const productosItems = document.querySelectorAll('.producto-item');
                let productosVisibles = 0;
                
                productosItems.forEach(function(item) {
                    const nombreProducto = item.getAttribute('data-nombre');
                    
                    if (filtro === '' || nombreProducto.includes(filtro)) {
                        item.style.display = 'block';
                        productosVisibles++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Mostrar mensaje si no hay productos visibles
                let mensajeSinResultados = document.getElementById('mensajeSinResultados');
                if (productosVisibles === 0 && filtro !== '') {
                    if (!mensajeSinResultados) {
                        mensajeSinResultados = document.createElement('div');
                        mensajeSinResultados.id = 'mensajeSinResultados';
                        mensajeSinResultados.className = 'alert alert-warning text-center';
                        mensajeSinResultados.innerHTML = '<i class="fas fa-search"></i> No se encontraron productos que coincidan con "' + this.value + '"';
                        productosSection.querySelector('.row').appendChild(mensajeSinResultados);
                    } else {
                        mensajeSinResultados.innerHTML = '<i class="fas fa-search"></i> No se encontraron productos que coincidan con "' + this.value + '"';
                        mensajeSinResultados.style.display = 'block';
                    }
                } else if (mensajeSinResultados) {
                    mensajeSinResultados.style.display = 'none';
                }
                
                // Actualizar botones de seleccionar todos considerando solo productos visibles
                const selectAllBtn = document.getElementById('selectAllProducts');
                const deselectAllBtn = document.getElementById('deselectAllProducts');
                
                if (selectAllBtn && deselectAllBtn) {
                    if (productosVisibles === 0) {
                        selectAllBtn.disabled = true;
                        deselectAllBtn.disabled = true;
                    } else {
                        selectAllBtn.disabled = false;
                        deselectAllBtn.disabled = false;
                        
                        // Actualizar texto de botones con contador
                        selectAllBtn.innerHTML = '<i class="fas fa-check-square"></i> Seleccionar Todos (' + productosVisibles + ')';
                        deselectAllBtn.innerHTML = '<i class="fas fa-square"></i> Deseleccionar Todos (' + productosVisibles + ')';
                    }
                }
            });
        }
    });
    </script>
</body>
</html>
