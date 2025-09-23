<?php
// Vista para editar usuario
?>
<h2>Editar Usuario</h2>
<a href="/RMIE/app/controllers/UserController.php?action=listar" class="btn btn-secondary mb-3">Volver a la lista</a>
<form method="post" action="/RMIE/app/controllers/UserController.php?action=actualizar">
    <input type="hidden" name="num_doc" value="<?= htmlspecialchars($usuario['num_doc']) ?>">
    <div class="mb-3">
        <label>Tipo de Documento</label>
        <input type="text" name="tipo_doc" class="form-control" value="<?= htmlspecialchars($usuario['tipo_doc']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Nombres</label>
        <input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($usuario['nombres']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Apellidos</label>
        <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="contrasena" class="form-control" value="<?= htmlspecialchars($usuario['contrasena']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Celular</label>
        <input type="text" name="num_cel" class="form-control" value="<?= htmlspecialchars($usuario['num_cel']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Rol</label>
        <select name="rol" class="form-control" required>
            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="coordinador" <?= $usuario['rol'] == 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Actualizar</button>
</form>
