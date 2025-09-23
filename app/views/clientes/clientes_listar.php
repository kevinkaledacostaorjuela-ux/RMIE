<!-- Vista para listar clientes -->
<div class="container mt-4">
    <h2>Clientes</h2>
    <a href="/RMIE/app/controllers/ClienteController.php?action=crear" class="btn btn-primary mb-3">Nuevo Cliente</a>
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-primary mb-3">Regresar al Dashboard</a>

    <form method="get" action="/RMIE/app/controllers/ClienteController.php" class="mb-3">
        <input type="hidden" name="action" value="filtrar">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '' ?>">
        <label>Estado:</label>
        <select name="estado">
            <option value="">Todos</option>
            <option value="activo" <?= (isset($_GET['estado']) && $_GET['estado'] == 'activo') ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo" <?= (isset($_GET['estado']) && $_GET['estado'] == 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
        <a href="/RMIE/app/controllers/ClienteController.php?action=listar" class="btn btn-light btn-sm">Limpiar</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Celular</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Local</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $cli): ?>
                    <tr>
                        <td><?= htmlspecialchars($cli['id_clientes']) ?></td>
                        <td><?= htmlspecialchars($cli['nombre']) ?></td>
                        <td><?= htmlspecialchars($cli['descripcion']) ?></td>
                        <td><?= htmlspecialchars($cli['cel_cliente']) ?></td>
                        <td><?= htmlspecialchars($cli['correo']) ?></td>
                        <td><?= htmlspecialchars($cli['estado']) ?></td>
                        <td><?= htmlspecialchars($cli['nombre_local']) ?></td>
                        <td>
                            <a href="/RMIE/app/controllers/ClienteController.php?action=editar&id=<?= $cli['id_clientes'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="/RMIE/app/controllers/ClienteController.php?action=eliminar&id=<?= $cli['id_clientes'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No hay clientes registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>