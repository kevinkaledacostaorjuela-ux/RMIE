<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.php');
    exit();
}
?>
<!-- Vista para editar subcategoría -->
<div class="container mt-4">
    <h2>Editar Subcategoría</h2>
    <form method="post" action="?action=actualizar_subcategoria&id=<?= $subcategoria['id_subcategoria'] ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $subcategoria['nombre'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= $subcategoria['descripcion'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select class="form-control" id="id_categoria" name="id_categoria" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $subcategoria['id_categoria'] ? 'selected' : '' ?>><?= $cat['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="?action=listar_subcategorias" class="btn btn-secondary">Cancelar</a>
    </form>
    <a href="/RMIE/app/views/dashboard.php" class="btn btn-secondary mt-3">Volver al dashboard</a>
</div>