<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
?>
<!-- Vista para listar subcategorías -->
<div class="container mt-4">
    <h2 class="categorias-title">Subcategorías</h2>
    <form method="get" class="row g-3 mb-3">
        <input type="hidden" name="action" value="listar_subcategorias">
        <div class="col-md-4">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>" placeholder="Buscar por nombre">
        </div>
        <div class="col-md-3">
            <label for="categoria" class="form-label">Categoría</label>
            <select name="categoria" id="categoria" class="form-control">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>><?= $cat['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="orden" class="form-label">Orden</label>
            <select name="orden" id="orden" class="form-control">
                <option value="asc" <?= (($_GET['orden'] ?? 'asc') === 'asc') ? 'selected' : '' ?>>A-Z</option>
                <option value="desc" <?= (($_GET['orden'] ?? '') === 'desc') ? 'selected' : '' ?>>Z-A</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            <a href="?action=listar_subcategorias" class="btn btn-secondary w-100">Limpiar</a>
        </div>
        <div class="col-12 d-flex align-items-end">
            <a href="?action=crear_subcategoria" class="btn btn-success w-100">Nueva Subcategoría</a>
        </div>
    </form>
    <div class="categorias-card">
    <table class="table table-categorias mb-0">
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
</div>
<a href="/RMIE/app/views/dashboard.php" class="btn btn-secondary mt-3">Volver al dashboard</a>