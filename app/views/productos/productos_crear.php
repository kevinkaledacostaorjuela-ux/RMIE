<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
?>
<!-- Vista para crear producto -->
<div class="container mt-4">
    <h2>Nuevo Producto</h2>
    <form method="post" action="?action=guardar_producto">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <div class="mb-3">
            <label for="fecha_entrada" class="form-label">Fecha Entrada</label>
            <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" required>
        </div>
        <div class="mb-3">
            <label for="fecha_fabricacion" class="form-label">Fecha Fabricación</label>
            <input type="date" class="form-control" id="fecha_fabricacion" name="fecha_fabricacion" required>
        </div>
        <div class="mb-3">
            <label for="fecha_caducidad" class="form-label">Fecha Caducidad</label>
            <input type="date" class="form-control" id="fecha_caducidad" name="fecha_caducidad" required>
        </div>
        <div class="mb-3">
            <label for="id_subcategoria" class="form-label">Subcategoría</label>
            <select class="form-control" id="id_subcategoria" name="id_subcategoria" required>
                <option value="">Seleccione una subcategoría</option>
                <?php foreach ($subcategorias as $sub): ?>
                    <option value="<?= $sub['id_subcategoria'] ?>">
                        <?= $sub['nombre'] ?> (<?= $sub['categoria_nombre'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    <a href="/RMIE/app/controllers/ProductoController.php?action=listar_productos" class="btn btn-secondary">Cancelar</a>
    </form>
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-secondary mt-3">Volver al dashboard</a>
</div>