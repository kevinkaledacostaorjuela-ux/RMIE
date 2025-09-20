<!-- Vista para eliminar alerta (confirmación) -->
<div class="container mt-4">
    <h2>Eliminar Alerta</h2>
    <p>¿Estás seguro de que deseas eliminar la alerta para el cliente <strong><?= $alerta['id_clientes'] ?></strong>?</p>
    <form method="post" action="?action=eliminar_alerta_confirmar&id=<?= $alerta['id_alertas'] ?>">
        <button type="submit" class="btn btn-danger">Eliminar</button>
        <a href="?action=listar_alertas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>