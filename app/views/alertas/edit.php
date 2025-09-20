<!DOCTYPE html>
<html>
<head>
    <title>Editar Alerta</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Editar Alerta</h1>
        <form method="POST" action="">
            <label>Producto:</label>
            <input type="text" name="id_productos" value="<?= $alerta->id_productos ?>" required><br>
            <label>Cliente No Disponible:</label>
            <input type="text" name="cliente_no_disponible" value="<?= $alerta->cliente_no_disponible ?>" required><br>
            <label>ID Cliente:</label>
            <input type="text" name="id_clientes" value="<?= $alerta->id_clientes ?>" required><br>
            <button type="submit" class="btn-categorias">Actualizar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
