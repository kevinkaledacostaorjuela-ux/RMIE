<!DOCTYPE html>
<html>
<head>
    <title>Crear Reporte</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Crear Reporte</h1>
    <form method="POST" action="create.php">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br>
            <label>Descripción:</label>
            <input type="text" name="descripcion" required><br>
            <label>Producto:</label>
            <input type="text" name="id_productos" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
