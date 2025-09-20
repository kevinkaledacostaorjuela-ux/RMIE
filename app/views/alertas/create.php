<!DOCTYPE html>
<html>
<head>
    <title>Crear Alerta</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Crear Alerta</h1>
    <form method="POST" action="create.php">
            <label>Producto:</label>
            <input type="text" name="id_productos" required><br>
            <label>Cliente No Disponible:</label>
            <input type="text" name="cliente_no_disponible" required><br>
            <label>ID Cliente:</label>
            <input type="text" name="id_clientes" required><br>
            <button type="submit" class="btn-categorias">Guardar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
