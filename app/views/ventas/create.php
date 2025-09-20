<!DOCTYPE html>
<html>
<head>
    <title>Crear Venta</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Crear Venta</h1>
    <form method="POST" action="../../controllers/SaleController.php?accion=create">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br>
            <label>Dirección:</label>
            <input type="text" name="direccion" required><br>
            <label>Cantidad:</label>
            <input type="number" name="cantidad" required><br>
            <label>Fecha Venta:</label>
            <input type="date" name="fecha_venta" required><br>
            <label>Cliente:</label>
            <input type="text" name="id_clientes" required><br>
            <label>Reporte:</label>
            <input type="text" name="id_reportes" required><br>
            <label>Ruta:</label>
            <input type="text" name="id_ruta" required><br>
            <label>Producto:</label>
            <input type="text" name="id_productos" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="../../controllers/SaleController.php?accion=index" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
