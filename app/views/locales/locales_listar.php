<?php
// Vista para listar locales
?>
<h2>Lista de Locales</h2>
<a href="/RMIE/app/controllers/LocalController.php?action=crear_local">Crear nuevo local</a>
<br><a href="/RMIE/app/views/dashboard.php" style="margin-top:10px;display:inline-block;">Regresar al Dashboard</a>

<form method="get" action="/RMIE/app/controllers/LocalController.php" style="margin: 20px 0;">
    <input type="hidden" name="action" value="filtrar_locales">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '' ?>">
    <label>Estado:</label>
    <select name="estado">
        <option value="">Todos</option>
        <option value="activo" <?= (isset($_GET['estado']) && $_GET['estado'] == 'activo') ? 'selected' : '' ?>>Activo</option>
        <option value="inactivo" <?= (isset($_GET['estado']) && $_GET['estado'] == 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
    </select>
    <button type="submit">Filtrar</button>
    <a href="/RMIE/app/controllers/LocalController.php?action=listar_locales" style="margin-left:10px;">Limpiar</a>
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php if (!empty($locales)): ?>
        <?php foreach ($locales as $local): ?>
            <tr>
                <td><?= htmlspecialchars($local['id_locales']) ?></td>
                <td><?= htmlspecialchars($local['nombre_local']) ?></td>
                <td><?= htmlspecialchars($local['direccion']) ?></td>
                <td><?= htmlspecialchars($local['cel_local']) ?></td>
                <td><?= htmlspecialchars($local['estado']) ?></td>
                <td>
                    <a href="/RMIE/app/controllers/LocalController.php?action=editar_local&id=<?= $local['id_locales'] ?>">Editar</a> |
                    <a href="/RMIE/app/controllers/LocalController.php?action=eliminar_local&id=<?= $local['id_locales'] ?>" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">No hay locales registrados.</td></tr>
    <?php endif; ?>
</table>
