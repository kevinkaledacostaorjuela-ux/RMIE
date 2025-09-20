<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
?>
<!-- Vista para crear subcategoría -->
<div class="container mt-4">
    <h2>Nueva Subcategoría</h2>
    <form method="post" action="?action=guardar_subcategoria">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select class="form-control" id="id_categoria" name="id_categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"><?= $cat['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="?action=listar_subcategorias" class="btn btn-secondary">Cancelar</a>
    </form>
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-secondary mt-3">Volver al dashboard</a>
</div>