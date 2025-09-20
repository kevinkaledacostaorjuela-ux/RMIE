<!DOCTYPE html>
<html>
<head>
    <title>Editar Local</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Editar Local</h1>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $local->nombre ?>" required><br>
        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?= $local->direccion ?>" required><br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver al listado</a>
</body>
</html>
