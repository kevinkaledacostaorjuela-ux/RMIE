<!-- Vista para editar alerta -->
<div class="container mt-4">
    <h2>Editar Alerta</h2>
    <form method="post" action="?action=actualizar_alerta&id=<?= $alerta['id_alertas'] ?>">
        <div class="mb-3">
            <label for="cliente_no_disponible" class="form-label">Mensaje</label>
            <input type="text" class="form-control" id="cliente_no_disponible" name="cliente_no_disponible" value="<?= $alerta['cliente_no_disponible'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="id_clientes" class="form-label">Cliente</label>
            <select name="id_clientes" id="id_clientes" class="form-control" required>
                <?php foreach ($clientes as $cli): ?>
                    <option value="<?= $cli['id_clientes'] ?>" <?= $cli['id_clientes'] == $alerta['id_clientes'] ? 'selected' : '' ?>><?= $cli['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_productos" class="form-label">Producto</label>
            <select name="id_productos" id="id_productos" class="form-control" required>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['id_productos'] ?>" <?= $prod['id_productos'] == $alerta['id_productos'] ? 'selected' : '' ?>><?= $prod['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="?action=listar_alertas" class="btn btn-secondary">Cancelar</a>
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-primary">Regresar al Dashboard</a>
    </form>
</div>