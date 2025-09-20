<!-- Vista para eliminar proveedor (confirmación) -->
<div class="container mt-4">
    <h2>Eliminar Proveedor</h2>
    <p>¿Estás seguro de que deseas eliminar el proveedor <strong><?= $proveedor['nombre_distribuidor'] ?></strong>?</p>
    <form method="post" action="?action=eliminar_proveedor_confirmar&id=<?= $proveedor['id_proveedores'] ?>">
        <button type="submit" class="btn btn-danger">Eliminar</button>
        <a href="?action=listar_proveedores" class="btn btn-secondary">Cancelar</a>
    </form>
</div>