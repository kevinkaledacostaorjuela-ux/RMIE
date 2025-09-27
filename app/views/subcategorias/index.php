<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Subcategorías - RMIE</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="subcategorias-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="subcategorias-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/RMIE/app/controllers/MainController.php?action=dashboard"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Subcategorías</li>
            </ol>
        </nav>

        <h1><i class="fas fa-layer-group"></i> Gestión de Subcategorías</h1>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/RMIE/app/views/dashboard.php" class="btn-subcategorias btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al menú principal
            </a>
            <a href="/RMIE/app/controllers/SubcategoryController.php?accion=create" class="btn-subcategorias">
                <i class="fas fa-plus"></i> Agregar Subcategoría
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-subcategorias">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-tag"></i> Nombre</th>
                        <th><i class="fas fa-align-left"></i> Descripción</th>
                        <th><i class="fas fa-folder"></i> Categoría</th>
                        <th><i class="fas fa-calendar"></i> Fecha de Creación</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($subcategorias) && is_array($subcategorias) && !empty($subcategorias)): ?>
                        <?php foreach ($subcategorias as $sub): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($sub['obj']->id_subcategoria) ?></span></td>
                            <td><strong><?= htmlspecialchars($sub['obj']->nombre) ?></strong></td>
                            <td><?= htmlspecialchars($sub['obj']->descripcion) ?></td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-folder"></i> <?= htmlspecialchars($sub['categoria_nombre']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> 
                                    <?php 
                                    if (isset($sub['obj']->fecha_creacion) && !empty($sub['obj']->fecha_creacion)) {
                                        echo date('d/m/Y H:i', strtotime($sub['obj']->fecha_creacion));
                                    } else {
                                        echo 'No disponible';
                                    }
                                    ?>
                                </small>
                            </td>
                            <td>
                                <a href="/RMIE/app/controllers/SubcategoryController.php?accion=edit&id=<?= urlencode($sub['obj']->id_subcategoria) ?>" 
                                   class="btn-action btn-edit" title="Editar subcategoría">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/RMIE/app/controllers/SubcategoryController.php?accion=delete&id=<?= urlencode($sub['obj']->id_subcategoria) ?>" 
                                   class="btn-action btn-delete" 
                                   onclick="return confirm('¿Está seguro de eliminar la subcategoría &quot;<?= htmlspecialchars($sub['obj']->nombre) ?>&quot;?')"
                                   title="Eliminar subcategoría">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="no-data-message">
                                    <i class="fas fa-layer-group fa-3x mb-3 text-muted"></i>
                                    <h5>No hay subcategorías registradas</h5>
                                    <p>Comience agregando su primera subcategoría haciendo clic en el botón "Agregar Subcategoría"</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Información adicional -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Información:</strong> Las subcategorías permiten organizar mejor sus productos dentro de cada categoría principal. Cada subcategoría debe estar asociada a una categoría existente.
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
