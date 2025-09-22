<!-- Vista para crear cliente -->
<div class="container mt-4">
    <h2>Nuevo Cliente</h2>
    <form method="post" action="?action=guardar">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Celular</label>
            <input type="text" name="cel_cliente" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Local</label>
            <select name="id_locales" class="form-control" required>
                <option value="">Seleccione un local</option>
                <?php foreach ($locales as $local): ?>
                    <option value="<?= $local['id_locales'] ?>"><?= $local['nombre_local'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="?action=listar" class="btn btn-secondary">Cancelar</a>
        <a href="/RMIE/app/views/dashboard.php" class="btn btn-primary">Regresar al Dashboard</a>
    </form>
</div>