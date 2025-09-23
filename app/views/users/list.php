<?php
// Vista para listar usuarios con filtro por nombre y rol, y botón de regresar al dashboard
?>
<h2>Lista de Usuarios</h2>

<a href="/RMIE/app/controllers/UserController.php?action=crear" class="btn btn-primary mb-3">Nuevo Usuario</a>
<a href="/RMIE/app/views/dashboard.php" class="btn btn-primary mb-3">Regresar al Dashboard</a>

<form method="get" action="/RMIE/app/controllers/UserController.php" class="mb-3">
    <input type="hidden" name="action" value="filtrar">
    <label>Nombre:</label>
    <input type="text" name="nombres" value="<?= isset($_GET['nombres']) ? htmlspecialchars($_GET['nombres']) : '' ?>">
    <label>Rol:</label>
    <select name="rol">
        <option value="">Todos</option>
        <option value="admin" <?= (isset($_GET['rol']) && $_GET['rol'] == 'admin') ? 'selected' : '' ?>>Admin</option>
        <option value="coordinador" <?= (isset($_GET['rol']) && $_GET['rol'] == 'coordinador') ? 'selected' : '' ?>>Coordinador</option>
    </select>
    <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
    <a href="/RMIE/app/controllers/UserController.php?action=listar" class="btn btn-light btn-sm">Limpiar</a>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Tipo</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['num_doc']) ?></td>
                    <td><?= htmlspecialchars($user['tipo_doc']) ?></td>
                    <td><?= htmlspecialchars($user['nombres']) ?></td>
                    <td><?= htmlspecialchars($user['apellidos']) ?></td>
                    <td><?= htmlspecialchars($user['correo']) ?></td>
                    <td><?= htmlspecialchars($user['num_cel']) ?></td>
                    <td><?= htmlspecialchars($user['rol']) ?></td>
                    <td>
                        <a href="/RMIE/app/controllers/UserController.php?action=editar&num_doc=<?= $user['num_doc'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="/RMIE/app/controllers/UserController.php?action=eliminar&num_doc=<?= $user['num_doc'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">No hay usuarios registrados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
