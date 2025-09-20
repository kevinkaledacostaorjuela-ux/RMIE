<!-- Vista para editar proveedor -->
<div class="container mt-4">
    <h2>Editar Proveedor</h2>
    <form method="post" action="?action=actualizar_proveedor&id=<?= $proveedor['id_proveedores'] ?>">
        <div class="mb-3">
            <label for="nombre_distribuidor" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre_distribuidor" name="nombre_distribuidor" value="<?= $proveedor['nombre_distribuidor'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?= $proveedor['correo'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="cel_proveedor" class="form-label">Celular</label>
            <input type="text" class="form-control" id="cel_proveedor" name="cel_proveedor" value="<?= $proveedor['cel_proveedor'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" value="<?= $proveedor['estado'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="productos" class="form-label">Productos</label>
            <select name="productos[]" id="productos" class="form-control" multiple required>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['id_productos'] ?>" <?= in_array($prod['id_productos'], array_column($productosProveedor, 'id_productos')) ? 'selected' : '' ?>><?= $prod['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="?action=listar_proveedores" class="btn btn-secondary">Cancelar</a>
    </form>
</div>