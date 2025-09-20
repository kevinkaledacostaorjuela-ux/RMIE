<!DOCTYPE html>
<html>
<head>
    <title>Editar Ruta</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Editar Ruta</h1>
        <form method="POST" action="">
            <label>Dirección:</label>
            <input type="text" name="direccion" value="<?= $ruta->direccion ?>" required><br>
            <label>Local:</label>
            <input type="text" name="id_locales" value="<?= $ruta->id_locales ?>" required><br>
            <label>Cliente:</label>
            <input type="text" name="id_clientes" value="<?= $ruta->id_clientes ?>" required><br>
            <label>Reporte:</label>
            <input type="text" name="id_reportes" value="<?= $ruta->id_reportes ?>" required><br>
            <label>Venta:</label>
            <input type="text" name="id_ventas" value="<?= $ruta->id_ventas ?>" required><br>
            <button type="submit" class="btn-categorias">Actualizar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
