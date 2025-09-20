<!DOCTYPE html>
<html>
<head>
    <title>Editar Venta</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Editar Venta</h1>
        <form method="POST" action="">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= $venta->nombre ?>" required><br>
            <label>Dirección:</label>
            <input type="text" name="direccion" value="<?= $venta->direccion ?>" required><br>
            <label>Cantidad:</label>
            <input type="number" name="cantidad" value="<?= $venta->cantidad ?>" required><br>
            <label>Fecha Venta:</label>
            <input type="date" name="fecha_venta" value="<?= $venta->fecha_venta ?>" required><br>
            <label>Cliente:</label>
            <input type="text" name="id_clientes" value="<?= $venta->id_clientes ?>" required><br>
            <label>Reporte:</label>
            <input type="text" name="id_reportes" value="<?= $venta->id_reportes ?>" required><br>
            <label>Ruta:</label>
            <input type="text" name="id_ruta" value="<?= $venta->id_ruta ?>" required><br>
            <label>Producto:</label>
            <input type="text" name="id_productos" value="<?= $venta->id_productos ?>" required><br>
            <button type="submit" class="btn-categorias">Actualizar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
