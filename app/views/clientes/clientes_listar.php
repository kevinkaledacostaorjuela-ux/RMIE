<!-- Vista para listar clientes -->
<div class="container mt-4">
    <h2>Clientes</h2>
    <a href="?action=crear" class="btn btn-primary mb-3">Nuevo Cliente</a>
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-primary mb-3">Regresar al Dashboard</a>
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
                        <td><?= $cli['id_clientes'] ?></td>
                        <td><?= $cli['nombre'] ?></td>
                        <td><?= $cli['descripcion'] ?></td>
                        <td><?= $cli['cel_cliente'] ?></td>
                        <td><?= $cli['correo'] ?></td>
                        <td><?= $cli['estado'] ?></td>
                        <td><?= $cli['nombre_local'] ?></td>
                        <td>
                            <a href="?action=editar&id=<?= $cli['id_clientes'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?action=eliminar&id=<?= $cli['id_clientes'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No hay clientes registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>