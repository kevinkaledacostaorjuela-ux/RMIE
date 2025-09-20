<!DOCTYPE html>
<html>
<head>
    <title>Crear Local</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <h1>Crear Local</h1>
    <form method="POST" action="create.php">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label>Dirección:</label>
        <input type="text" name="direccion" required><br>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver al listado</a>
</body>
</html>
