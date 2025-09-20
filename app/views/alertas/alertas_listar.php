<!-- Vista para listar alertas con filtro por producto -->
<div class="container mt-4">
    <h2>Alertas</h2>
    <form method="get" class="row g-3 mb-3">
        <input type="hidden" name="action" value="listar_alertas">
        <div class="col-md-6">
            <label for="producto" class="form-label">Producto</label>
            <select name="producto" id="producto" class="form-control" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['id_productos'] ?>" <?= isset($_GET['producto']) && $_GET['producto'] == $prod['id_productos'] ? 'selected' : '' ?>><?= $prod['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <a href="?action=crear_alerta" class="btn btn-primary w-100">Nueva Alerta</a>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Mensaje</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($alertas)): ?>
                <?php foreach ($alertas as $alerta): ?>
                    <tr>
                        <td><?= $alerta['id_alertas'] ?></td>
                        <td><?= $alerta['cliente_nombre'] ?></td>
                        <td><?= $alerta['producto_nombre'] ?></td>
                        <td><?= $alerta['cliente_no_disponible'] ?></td>
                        <td>
                            <a href="?action=editar_alerta&id=<?= $alerta['id_alertas'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?action=eliminar_alerta&id=<?= $alerta['id_alertas'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay alertas registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>