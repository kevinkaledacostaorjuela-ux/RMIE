<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ruta</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <h1>Crear Nueva Ruta</h1>
    <form action="" method="POST">
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" required>
        <br>
        <label for="nombre_local">Nombre Local:</label>
        <input type="text" name="nombre_local" id="nombre_local" required>
        <br>
        <label for="nombre_cliente">Nombre Cliente:</label>
        <input type="text" name="nombre_cliente" id="nombre_cliente" required>
        <br>
        <label for="id_clientes">ID Cliente:</label>
        <input type="number" name="id_clientes" id="id_clientes" required>
        <br>
        <label for="id_reportes">ID Reporte:</label>
        <input type="number" name="id_reportes" id="id_reportes" required>
        <br>
        <label for="id_ventas">ID Venta:</label>
        <input type="number" name="id_ventas" id="id_ventas" required>
        <br>
        <button type="submit">Crear</button>
    </form>
</body>
</html>
