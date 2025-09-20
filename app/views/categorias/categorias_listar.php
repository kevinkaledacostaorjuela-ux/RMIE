<!-- Vista para listar categorías -->
<div class="container mt-4">
    <h2>Categorías</h2>
    <a href="?action=crear_categoria" class="btn btn-primary mb-3">Nueva Categoría</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><?= $cat['nombre'] ?></td>
                        <td>
                            <a href="?action=editar_categoria&id=<?= $cat['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?action=eliminar_categoria&id=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No hay categorías registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
