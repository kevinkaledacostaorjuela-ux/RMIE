<!-- Vista para crear proveedor -->
<div class="container mt-4">
    <h2>Nuevo Proveedor</h2>
    <form method="post" action="?action=guardar_proveedor">
        <div class="mb-3">
            <label for="nombre_distribuidor" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre_distribuidor" name="nombre_distribuidor" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="cel_proveedor" class="form-label">Celular</label>
            <input type="text" class="form-control" id="cel_proveedor" name="cel_proveedor" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" required>
        </div>
        <div class="mb-3">
            <label for="productos" class="form-label">Productos</label>
            <select name="productos[]" id="productos" class="form-control" multiple required>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['id_productos'] ?>"><?= $prod['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Mantén presionada la tecla Ctrl (Windows) o Cmd (Mac) para seleccionar varios productos.</small>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="?action=listar_proveedores" class="btn btn-secondary">Cancelar</a>
    </form>
</div>