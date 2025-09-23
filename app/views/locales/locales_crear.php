<?php
// Vista para crear un local
?>
<h2>Crear Local</h2>
<form method="post" action="/RMIE/app/controllers/LocalController.php?action=guardar_local">
    <label>Nombre:</label>
    <input type="text" name="nombre_local" required><br>
    <label>Dirección:</label>
    <input type="text" name="direccion" required><br>
    <label>Teléfono:</label>
    <input type="text" name="cel_local" required><br>
    <label>Estado:</label>
    <select name="estado">
        <option value="activo">Activo</option>
        <option value="inactivo">Inactivo</option>
    </select><br>
    <label>Localidad:</label>
    <input type="text" name="localidad"><br>
    <label>Barrio:</label>
    <input type="text" name="barrio"><br>
    <button type="submit">Guardar</button>
</form>
<a href="/RMIE/app/controllers/LocalController.php?action=listar_locales">Volver a la lista</a>
<br><a href="/RMIE/app/views/dashboard.php" style="margin-top:10px;display:inline-block;">Regresar al Dashboard</a>
