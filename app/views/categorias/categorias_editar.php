<!-- Vista para editar categoría -->
<div class="container mt-4">
    <h2>Editar Categoría</h2>
    <form method="post" action="?action=actualizar_categoria&id=<?= $categoria['id'] ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $categoria['nombre'] ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="?action=listar_categorias" class="btn btn-secondary">Cancelar</a>
    </form>
</div>