<?php
// Vista para crear una venta
?>
<h2>Crear Venta</h2>
<form method="post" action="/RMIE/app/controllers/VentaController.php?action=guardar">
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br>
    <label>Dirección:</label>
    <input type="text" name="direccion" required><br>
    <label>Cantidad:</label>
    <input type="number" name="cantidad" required><br>
    <label>Fecha Venta:</label>
    <input type="date" name="fecha_venta" required><br>
    <label>Cliente:</label>
    <select name="id_clientes" required>
        <option value="">Seleccione un cliente</option>
        <?php if (isset($clientes) && is_array($clientes)): ?>
            <?php foreach ($clientes as $cli): ?>
                <option value="<?= htmlspecialchars($cli['id_clientes']) ?>">
                    <?= htmlspecialchars($cli['nombre']) ?> (<?= htmlspecialchars($cli['id_clientes']) ?>)
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select><br>
    <button type="submit">Guardar</button>
</form>
<a href="/RMIE/app/controllers/VentaController.php?action=listar" style="margin-top:10px;display:inline-block;">Volver a la lista</a>
<a href="/RMIE/app/views/dashboard.php" style="margin-top:10px;display:inline-block;">Regresar al Dashboard</a>
