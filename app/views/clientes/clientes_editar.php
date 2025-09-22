<!-- Vista para editar cliente -->
<div class="container mt-4">
    <h2>Editar Cliente</h2>
    <form method="post" action="?action=actualizar&id=<?= $cliente['id_clientes'] ?>">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= $cliente['nombre'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="<?= $cliente['descripcion'] ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Celular</label>
            <input type="text" name="cel_cliente" class="form-control" value="<?= $cliente['cel_cliente'] ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= $cliente['correo'] ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control" value="<?= $cliente['estado'] ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Local</label>
            <select name="id_locales" class="form-control" required>
                <?php foreach ($locales as $local): ?>
                    <option value="<?= $local['id_locales'] ?>" <?= $local['id_locales'] == $cliente['id_locales'] ? 'selected' : '' ?>><?= $local['nombre_local'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="?action=listar" class="btn btn-secondary">Cancelar</a>
        <a href="/RMIE/app/views/dashboard.php" class="btn btn-primary">Regresar al Dashboard</a>
    </form>
</div>