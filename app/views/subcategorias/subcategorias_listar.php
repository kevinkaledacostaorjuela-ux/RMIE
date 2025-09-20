<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
?>
<!-- Vista para listar subcategorías -->
<div class="container mt-4">
    <h2>Subcategorías</h2>
    <form method="get" class="row g-3 mb-3">
        <input type="hidden" name="action" value="listar_subcategorias">
        <div class="col-md-6">
            <label for="categoria" class="form-label">Categoría</label>
            <select name="categoria" id="categoria" class="form-control" onchange="this.form.submit()">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>><?= $cat['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <a href="?action=crear_subcategoria" class="btn btn-primary w-100">Nueva Subcategoría</a>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subcategorias)): ?>
                <?php foreach ($subcategorias as $sub): ?>
                    <tr>
                        <td><?= $sub['id_subcategoria'] ?></td>
                        <td><?= $sub['nombre'] ?></td>
                        <td><?= $sub['descripcion'] ?></td>
                        <td><?= $sub['categoria_nombre'] ?></td>
                        <td>
                            <a href="?action=editar_subcategoria&id=<?= $sub['id_subcategoria'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?action=eliminar_subcategoria&id=<?= $sub['id_subcategoria'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay subcategorías registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<a href="/RMIE/app/views/dashboard.php" class="btn btn-secondary mt-3">Volver al dashboard</a>