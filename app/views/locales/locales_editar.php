<?php
// Vista para editar un local
?>
<h2>Editar Local</h2>
<form method="post" action="/RMIE/app/controllers/LocalController.php?action=actualizar_local">
    <input type="hidden" name="id_locales" value="<?= htmlspecialchars($local['id_locales']) ?>">
    <label>Nombre:</label>
    <input type="text" name="nombre_local" value="<?= htmlspecialchars($local['nombre_local']) ?>" required><br>
    <label>Dirección:</label>
    <input type="text" name="direccion" value="<?= htmlspecialchars($local['direccion']) ?>" required><br>
    <label>Teléfono:</label>
    <input type="text" name="cel_local" value="<?= htmlspecialchars($local['cel_local']) ?>" required><br>
    <label>Estado:</label>
    <select name="estado">
        <option value="activo" <?= ($local['estado'] == 'activo') ? 'selected' : '' ?>>Activo</option>
        <option value="inactivo" <?= ($local['estado'] == 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
    </select><br>
    <label>Localidad:</label>
    <input type="text" name="localidad" value="<?= htmlspecialchars($local['localidad']) ?>"><br>
    <label>Barrio:</label>
    <input type="text" name="barrio" value="<?= htmlspecialchars($local['barrio']) ?>"><br>
    <button type="submit">Actualizar</button>
</form>
<a href="/RMIE/app/controllers/LocalController.php?action=listar_locales">Volver a la lista</a>
