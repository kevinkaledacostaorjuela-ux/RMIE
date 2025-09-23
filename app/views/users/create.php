<?php
// Vista para crear un nuevo usuario
?>
<h2>Crear Usuario</h2>
<a href="/RMIE/app/controllers/UserController.php?action=listar" class="btn btn-secondary mb-3">Volver a la lista</a>
<form method="post" action="/RMIE/app/controllers/UserController.php?action=guardar">
    <div class="mb-3">
        <label>Documento</label>
        <input type="text" name="num_doc" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tipo de Documento</label>
        <input type="text" name="tipo_doc" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nombres</label>
        <input type="text" name="nombres" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Apellidos</label>
        <input type="text" name="apellidos" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="contrasena" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Celular</label>
        <input type="text" name="num_cel" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Rol</label>
        <select name="rol" class="form-control" required>
            <option value="admin">Admin</option>
            <option value="coordinador">Coordinador</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
</form>
