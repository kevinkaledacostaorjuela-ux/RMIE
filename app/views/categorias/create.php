 <!DOCTYPE html>
<html>
<head>
    <title>Agregar Categoría</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Agregar Categoría</h1>
    <form method="POST" action="/RMIE/app/controllers/CategoryController.php?accion=create">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br>
            <label>Descripción:</label>
            <input type="text" name="descripcion" required><br>
            <button type="submit" class="btn btn-success">Guardar</button>
        </form>
    <a href="/RMIE/app/controllers/CategoryController.php?accion=index" class="btn btn-primary" style="margin-top:15px;display:inline-block;">
        <i class="fas fa-arrow-left"></i> Volver al listado de categorías
    </a>
    </div>
</body>
</html>
