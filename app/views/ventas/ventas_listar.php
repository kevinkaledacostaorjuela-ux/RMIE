<?php
// Vista para listar ventas con filtro por nombre y estado, y botón de regresar al dashboard
?>
<div class="container mt-4">
    <h2>Ventas</h2>
    <a href="/RMIE/app/controllers/VentaController.php?action=crear" class="btn btn-primary mb-3">Nueva Venta</a>
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-primary mb-3">Regresar al Dashboard</a>

    <form method="get" action="/RMIE/app/controllers/VentaController.php" class="mb-3">
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
        <a href="/RMIE/app/controllers/VentaController.php?action=listar" class="btn btn-light btn-sm">Limpiar</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Cantidad</th>
                <th>Fecha Venta</th>
                <th>Estado</th>
                <th>Cliente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ventas)): ?>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?= htmlspecialchars($venta['id_ventas']) ?></td>
                        <td><?= htmlspecialchars($venta['nombre']) ?></td>
                        <td><?= htmlspecialchars($venta['direccion']) ?></td>
                        <td><?= htmlspecialchars($venta['cantidad']) ?></td>
                        <td><?= htmlspecialchars($venta['fecha_venta']) ?></td>
                        <td><?= htmlspecialchars($venta['estado'] ?? '') ?></td>
                        <td><?= htmlspecialchars($venta['nombre_cliente'] ?? '') ?></td>
                        <td>
                            <a href="/RMIE/app/controllers/VentaController.php?action=editar&id=<?= $venta['id_ventas'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="/RMIE/app/controllers/VentaController.php?action=eliminar&id=<?= $venta['id_ventas'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No hay ventas registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
