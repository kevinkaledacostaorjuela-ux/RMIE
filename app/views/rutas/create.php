<!DOCTYPE html>
<html>
<head>
    <title>Crear Ruta</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Crear Ruta</h1>
    <form method="POST" action="create.php">
            <label>Dirección:</label>
            <input type="text" name="direccion" required><br>
            <label>Local:</label>
            <input type="text" name="id_locales" required><br>
            <label>Cliente:</label>
            <input type="text" name="id_clientes" required><br>
            <label>Reporte:</label>
            <input type="text" name="id_reportes" required><br>
            <label>Venta:</label>
            <input type="text" name="id_ventas" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
