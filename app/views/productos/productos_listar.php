<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
?>
<!-- Vista para listar productos -->
<div class="container mt-4">
    <h2 class="categorias-title">Productos</h2>
    <form method="get" class="row g-3 mb-3">
        <input type="hidden" name="action" value="listar_productos">
        <div class="col-md-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>" placeholder="Buscar por nombre">
        </div>
        <div class="col-md-2">
            <label for="categoria" class="form-label">Categoría</label>
            <select name="categoria" id="categoria" class="form-control">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>><?= $cat['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="subcategoria" class="form-label">Subcategoría</label>
            <select name="subcategoria" id="subcategoria" class="form-control">
                <option value="">Todas</option>
                <?php foreach ($subcategorias as $sub): ?>
                    <option value="<?= $sub['id_subcategoria'] ?>" <?= isset($_GET['subcategoria']) && $_GET['subcategoria'] == $sub['id_subcategoria'] ? 'selected' : '' ?>><?= $sub['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="orden" class="form-label">Orden</label>
            <select name="orden" id="orden" class="form-control">
                <option value="asc" <?= (($_GET['orden'] ?? 'asc') === 'asc') ? 'selected' : '' ?>>A-Z</option>
                <option value="desc" <?= (($_GET['orden'] ?? '') === 'desc') ? 'selected' : '' ?>>Z-A</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            <a href="?action=listar_productos" class="btn btn-secondary w-100">Limpiar</a>
            <a href="/RMIE/app/controllers/ProductoController.php?action=crear_producto" class="btn btn-success w-100">Nuevo Producto</a>
        </div>
    </form>
    <div class="categorias-card">
    <table class="table table-categorias mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha Entrada</th>
                <th>Fecha Fabricación</th>
                <th>Fecha Caducidad</th>
                <th>Subcategoría</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $prod): ?>
                    <tr>
                        <td><?= $prod['id_productos'] ?></td>
                        <td><?= $prod['nombre'] ?></td>
                        <td><?= $prod['descripcion'] ?></td>
                        <td><?= $prod['fecha_entrada'] ?></td>
                        <td><?= $prod['fecha_fabricacion'] ?></td>
                        <td><?= $prod['fecha_caducidad'] ?></td>
                        <td><?= $prod['subcategoria_nombre'] ?></td>
                        <td><?= $prod['categoria_nombre'] ?></td>
                        <td>
                            <a href="/RMIE/app/controllers/ProductoController.php?action=editar_producto&id=<?= $prod['id_productos'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="/RMIE/app/controllers/ProductoController.php?action=eliminar_producto&id=<?= $prod['id_productos'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">No hay productos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
<a href="/RMIE/app/views/dashboard.php" class="btn btn-secondary mt-3">Volver al dashboard</a>