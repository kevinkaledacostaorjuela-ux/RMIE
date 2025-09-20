<!-- Vista para eliminar subcategoría (confirmación) -->
<div class="container mt-4">
    <h2>Eliminar Subcategoría</h2>
    <p>¿Estás seguro de que deseas eliminar la subcategoría <strong><?= $subcategoria['nombre'] ?></strong>?</p>
    <form method="post" action="?action=eliminar_subcategoria_confirmar&id=<?= $subcategoria['id_subcategoria'] ?>">
        <button type="submit" class="btn btn-danger">Eliminar</button>
        <a href="?action=listar_subcategorias" class="btn btn-secondary">Cancelar</a>
    </form>
</div>