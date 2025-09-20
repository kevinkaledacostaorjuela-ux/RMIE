<!-- Vista para crear alerta -->
<div class="container mt-4">
    <h2>Nueva Alerta</h2>
    <form method="post" action="?action=guardar_alerta">
        <div class="mb-3">
            <label for="cliente_no_disponible" class="form-label">Mensaje</label>
            <input type="text" class="form-control" id="cliente_no_disponible" name="cliente_no_disponible" required>
        </div>
        <div class="mb-3">
            <label for="id_clientes" class="form-label">Cliente</label>
            <select name="id_clientes" id="id_clientes" class="form-control" required>
                <option value="">Seleccione un cliente</option>
                <?php foreach ($clientes as $cli): ?>
                    <option value="<?= $cli['id_clientes'] ?>"><?= $cli['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_productos" class="form-label">Producto</label>
            <select name="id_productos" id="id_productos" class="form-control" required>
                <option value="">Seleccione un producto</option>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['id_productos'] ?>"><?= $prod['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="?action=listar_alertas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>