<!-- Vista para eliminar categoría (confirmación) -->
<div class="container mt-4">
    <h2>Eliminar Categoría</h2>
    <p>¿Estás seguro de que deseas eliminar la categoría <strong><?= $categoria['nombre'] ?></strong>?</p>
    <form method="post" action="?action=eliminar_categoria_confirmar&id=<?= $categoria['id'] ?>">
        <button type="submit" class="btn btn-danger">Eliminar</button>
        <a href="?action=listar_categorias" class="btn btn-secondary">Cancelar</a>
    </form>
</div>