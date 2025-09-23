<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="productos-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/views/dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Productos</li>
            </ol>
        </nav>

        <h1><i class="fas fa-box"></i> Gestión de Productos</h1>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/RMIE/app/views/dashboard.php" class="btn-productos btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al menú principal
            </a>
            <a href="/RMIE/app/controllers/ProductController.php?accion=create" class="btn-productos">
                <i class="fas fa-plus"></i> Agregar Producto
            </a>
        </div>
        <!-- Filtros -->
        <div class="productos-filters">
            <h5><i class="fas fa-filter"></i> Filtros de búsqueda</h5>
            <form method="GET" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Filtrar por Categoría:</label>
                        <select name="categoria" class="form-control">
                            <option value="">Todas las categorías</option>
                            <?php if (isset($categorias) && is_array($categorias)): ?>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat->id_categoria ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat->id_categoria ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Filtrar por Subcategoría:</label>
                        <select name="subcategoria" class="form-control">
                            <option value="">Todas las subcategorías</option>
                            <?php if (isset($subcategorias) && is_array($subcategorias)): ?>
                                <?php foreach ($subcategorias as $sub): ?>
                                    <option value="<?= $sub['obj']->id_subcategoria ?>" <?= isset($_GET['subcategoria']) && $_GET['subcategoria'] == $sub['obj']->id_subcategoria ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sub['obj']->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Filtrar por Stock:</label>
                        <select name="stock" class="form-control">
                            <option value="">Todos los niveles</option>
                            <option value="alto" <?= isset($_GET['stock']) && $_GET['stock'] == 'alto' ? 'selected' : '' ?>>Stock alto (>50)</option>
                            <option value="medio" <?= isset($_GET['stock']) && $_GET['stock'] == 'medio' ? 'selected' : '' ?>>Stock medio (10-50)</option>
                            <option value="bajo" <?= isset($_GET['stock']) && $_GET['stock'] == 'bajo' ? 'selected' : '' ?>>Stock bajo (<10)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn-productos" style="display: block; width: 100%;">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-productos">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-box"></i> Producto</th>
                        <th><i class="fas fa-info-circle"></i> Descripción</th>
                        <th><i class="fas fa-cubes"></i> Stock</th>
                        <th><i class="fas fa-dollar-sign"></i> Precio Unit.</th>
                        <th><i class="fas fa-dollar-sign"></i> Precio Mayor</th>
                        <th><i class="fas fa-calendar"></i> F. Caducidad</th>
                        <th><i class="fas fa-tag"></i> Marca</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($productos) && is_array($productos) && !empty($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($prod->id_productos) ?></span></td>
                            <td>
                                <strong><?= htmlspecialchars($prod->nombre) ?></strong>
                                <br><small class="text-muted"><?= htmlspecialchars($prod->descripcion) ?></small>
                            </td>
                            <td>
                                <small class="fecha-display">
                                    <strong>Entrada:</strong> <?= htmlspecialchars($prod->fecha_entrada) ?><br>
                                    <strong>Fabricación:</strong> <?= htmlspecialchars($prod->fecha_fabricacion) ?>
                                </small>
                            </td>
                            <td>
                                <?php 
                                $stock = (int)$prod->stock;
                                if ($stock > 50) {
                                    $stockClass = 'stock-alto';
                                } elseif ($stock >= 10) {
                                    $stockClass = 'stock-medio';
                                } else {
                                    $stockClass = 'stock-bajo';
                                }
                                ?>
                                <span class="stock-badge <?= $stockClass ?>">
                                    <?= htmlspecialchars($prod->stock) ?> unidades
                                </span>
                            </td>
                            <td><span class="precio-display">$<?= number_format($prod->precio_unitario, 2) ?></span></td>
                            <td><span class="precio-display">$<?= number_format($prod->precio_por_mayor, 2) ?></span></td>
                            <td>
                                <?php 
                                $fechaCaducidad = strtotime($prod->fecha_caducidad);
                                $fechaActual = time();
                                $diasRestantes = ($fechaCaducidad - $fechaActual) / (60 * 60 * 24);
                                $claseFecha = $diasRestantes <= 30 ? 'fecha-proxima' : 'fecha-normal';
                                ?>
                                <span class="fecha-caducidad <?= $claseFecha ?>">
                                    <?= date('d/m/Y', $fechaCaducidad) ?>
                                    <br><small><?= round($diasRestantes) ?> días</small>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="fas fa-trademark"></i> <?= htmlspecialchars($prod->marca) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/RMIE/app/controllers/ProductController.php?accion=edit&id=<?= urlencode($prod->id_productos) ?>" 
                                   class="btn-action btn-edit" title="Editar producto">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/RMIE/app/controllers/ProductController.php?accion=delete&id=<?= urlencode($prod->id_productos) ?>" 
                                   class="btn-action btn-delete" 
                                   onclick="return confirm('¿Está seguro de eliminar el producto &quot;<?= htmlspecialchars($prod->nombre) ?>&quot;?')"
                                   title="Eliminar producto">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">
                                <div class="no-data-message">
                                    <i class="fas fa-box fa-3x mb-3 text-muted"></i>
                                    <h5>No hay productos registrados</h5>
                                    <p>Comience agregando su primer producto haciendo clic en el botón "Agregar Producto"</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
            </table>
        </div>

        <!-- Información adicional -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Información:</strong> Los productos se organizan por categorías y subcategorías. El stock bajo (menos de 10 unidades) se muestra en rojo para facilitar la reposición.
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
