<!-- Vista para eliminar producto (confirmación) -->
<div class="container mt-4">
    <h2>Eliminar Producto</h2>
    <p>¿Estás seguro de que deseas eliminar el producto <strong><?= $producto['nombre'] ?></strong>?</p>
    <form method="post" action="?action=eliminar_producto_confirmar&id=<?= $producto['id_productos'] ?>">
        <button type="submit" class="btn btn-danger">Eliminar</button>
        <a href="?action=listar_productos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>