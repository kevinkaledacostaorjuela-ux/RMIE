<!DOCTYPE html>
<html>
<head>
    <title>Editar Reporte</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Editar Reporte</h1>
        <form method="POST" action="">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= $reporte->nombre ?>" required><br>
            <label>Descripción:</label>
            <input type="text" name="descripcion" value="<?= $reporte->descripcion ?>" required><br>
            <label>Producto:</label>
            <input type="text" name="id_productos" value="<?= $reporte->id_productos ?>" required><br>
            <button type="submit" class="btn-categorias">Actualizar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
