<!-- Vista para crear categoría -->
<div class="container mt-4">
    <h2>Nueva Categoría</h2>
    <form method="post" action="?action=guardar_categoria">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="?action=listar_categorias" class="btn btn-secondary">Cancelar</a>
    </form>
</div>