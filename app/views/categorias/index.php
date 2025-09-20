<!DOCTYPE html>
<html>
<head>
    <title>Categorías</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Listado de Categorías</h1>
        <a href="/RMIE/app/views/dashboard.php" class="btn-categorias">Volver al menú principal</a>
        <a href="/RMIE/app/controllers/CategoryController.php?accion=create" class="btn-categorias">Agregar Categoría</a>
        <table class="table table-striped table-bordered table-categorias">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
            <?php if (isset($categorias) && is_array($categorias) && !empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat->id_categoria) ?></td>
                        <td><?= htmlspecialchars($cat->nombre) ?></td>
                        <td><?= htmlspecialchars($cat->descripcion) ?></td>
                        <td><?= htmlspecialchars($cat->fecha_creacion) ?></td>
                        <td>
                            <a href="/RMIE/app/controllers/CategoryController.php?accion=edit&id=<?= urlencode($cat->id_categoria) ?>">Editar</a> |
                            <a href="/RMIE/app/controllers/CategoryController.php?accion=delete&id=<?= urlencode($cat->id_categoria) ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; color: #888;">No hay categorías registradas.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
