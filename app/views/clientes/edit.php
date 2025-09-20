<!DOCTYPE html>
<html>
<head>
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="/RMIE/public/css/styles.css">
</head>
<body>
    <div class="categorias-container">
        <h1>Editar Cliente</h1>
        <form method="POST" action="">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= $cliente->nombre ?>" required><br>
            <label>Descripción:</label>
            <input type="text" name="descripcion" value="<?= $cliente->descripcion ?>" required><br>
            <label>Celular:</label>
            <input type="text" name="cel_cliente" value="<?= $cliente->cel_cliente ?>" required><br>
            <label>Correo:</label>
            <input type="email" name="correo" value="<?= $cliente->correo ?>" required><br>
            <label>Estado:</label>
            <input type="text" name="estado" value="<?= $cliente->estado ?>" required><br>
            <label>Local:</label>
            <input type="text" name="id_locales" value="<?= $cliente->id_locales ?>" required><br>
            <button type="submit" class="btn-categorias">Actualizar</button>
        </form>
        <a href="index.php" class="btn-categorias">Volver al listado</a>
    </div>
</body>
</html>
