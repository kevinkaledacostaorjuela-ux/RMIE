<!-- Vista para listar proveedores con filtro por producto -->
<div class="container mt-4">
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-primary mb-3">Regresar al Dashboard</a>
    <h2>Proveedores</h2>
    <form method="get" class="row g-3 mb-3 align-items-end">
        <input type="hidden" name="action" value="listar_proveedores">
        <div class="col-md-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '' ?>">
        </div>
        <div class="col-md-2">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" name="estado" id="estado" class="form-control" value="<?= isset($_GET['estado']) ? htmlspecialchars($_GET['estado']) : '' ?>">
        </div>
        <div class="col-md-3">
            <label for="producto" class="form-label">Producto</label>
            <select name="producto" id="producto" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['id_productos'] ?>" <?= isset($_GET['producto']) && $_GET['producto'] == $prod['id_productos'] ? 'selected' : '' ?>><?= $prod['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-info w-100">Filtrar</button>
        </div>
        <div class="col-md-2">
            <a href="?action=listar_proveedores" class="btn btn-secondary w-100">Limpiar</a>
        </div>
        <div class="col-md-2">
            <a href="?action=crear_proveedor" class="btn btn-primary w-100">Nuevo Proveedor</a>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Celular</th>
                <th>Estado</th>
                <th>Productos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($proveedores)): ?>
                <?php foreach ($proveedores as $prov): ?>
                    <tr>
                        <td><?= $prov['id_proveedores'] ?></td>
                        <td><?= $prov['nombre_distribuidor'] ?></td>
                        <td><?= $prov['correo'] ?></td>
                        <td><?= $prov['cel_proveedor'] ?></td>
                        <td><?= $prov['estado'] ?></td>
                        <td>
                            <?php $prods = (new \Proveedor($pdo))->obtenerProductosPorProveedor($prov['id_proveedores']); ?>
                            <?php foreach ($prods as $p): ?>
                                <span class="badge bg-info text-dark"><?= $p['nombre'] ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <a href="?action=editar_proveedor&id=<?= $prov['id_proveedores'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?action=eliminar_proveedor&id=<?= $prov['id_proveedores'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No hay proveedores registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>